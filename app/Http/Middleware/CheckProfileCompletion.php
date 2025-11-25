<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Client;
use App\Models\ClientsWithCr;
use App\Models\ClientsWithoutCr;
use App\Models\ClientWithCR;
use App\Models\ClientWithoutCR;
use App\Models\Influencer;
use App\Models\UserInfo;

class CheckProfileCompletion
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user->type_id === 1) {
            $client = Client::find($user->id);

            if (is_null($client->is_have_cr)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Profile not completed',
                    'need_complete_profile' => true
                ], 200);
            }

            if ($client->is_have_cr === 'yes') {
                $cr = ClientWithCR::where('client_id', $user->id)->first();
                $userInfo = UserInfo::where('user_id', $user->id)->first();

                if (!$cr || !$userInfo || !$userInfo->identity_number) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Profile not completed',
                        'need_complete_profile' => true
                    ], 200);
                }
            }

            if ($client->is_have_cr === 'no') {
                $cr = ClientWithoutCR::where('client_id', $user->id)->first();
                if (!$cr) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Profile not completed',
                        'need_complete_profile' => true
                    ], 200);
                }
            }

        } elseif ($user->type_id === 2) {
            $influencer = Influencer::find($user->id);

            if (!$influencer || !$influencer->gender || !$influencer->type_id) {
                return response()->json([
                    'status' => false,
                    'message' => 'Profile not completed',
                    'need_complete_profile' => true
                ], 200);
            }
        }

        return $next($request);
    }
}
