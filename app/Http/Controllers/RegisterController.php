<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Models\Client;
use App\Models\Influencer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Http\MyTrait\ResTrait;

class RegisterController extends Controller
{
    use ResTrait;

    public function register(RegisterRequest $request)
    {
        $typeId = (int) $request->type;

        DB::beginTransaction();
        try {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'type_id'  => $typeId,
            ]);

            if ($typeId === 1) {
                // create minimal client row (common client metadata)
                Client::create([
                    'id' => $user->id,
                    'is_have_cr' => $request->is_have_cr ?? 'no',
                ]);
            } else { // influencer
                Influencer::create([
                    'id' => $user->id,
                    'type_id' => 1, // default influencer type if you want; adjust if needed
                    // other influencer fields left nullable to be filled in profile completion
                ]);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            DB::commit();

            return $this->success([
                'message' => 'User registered successfully',
                'token'   => $token,
                'needs_profile_completion' => true,
                'user' => [
                    'id' => $user->id,
                    'type_id' => $user->type_id,
                ],
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->fail($e->getMessage());
        }
    }
}
