<?php

namespace App\Http\Controllers;
use App\Http\Requests\Auth\LoginRequest;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
 use App\Helpers\ArrayHelper;

class LoginController extends Controller
{


public function login(LoginRequest $request)
{
    if (!Auth::attempt($request->only('email', 'password'))) {
        return $this->fail("Invalid credentials");
    }

    /** @var \App\Models\User $user */
    $user = Auth::user();

    if (! $user instanceof User) {
        return $this->fail('Authenticated user is not a valid App\Models\User instance');
    }

    $token = $user->createToken('auth_token')->plainTextToken;

    // Load all related data
    $user->load([
        'client',
        'client.clientWithCr',
        'client.clientWithoutCr',
        'userInfo',
        'influencer'
    ]);

    // Convert and clean response
    $cleanUser = ArrayHelper::clean($user);

    return $this->success([
        'user' => $cleanUser,
        'token' => $token
    ]);
}

}
