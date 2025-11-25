<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\MyTrait\ResTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\UserInfo;

class UserInfoController extends Controller
{
    use ResTrait;

    public function getUserInfo()
    {
        $userInfo = UserInfo::where('user_id', Auth::id())->first();
        return $this->success($userInfo);
    }

    public function updatePhoneNumber(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string|max:15'
        ]);

        $userInfo = UserInfo::updateOrCreate(
            ['user_id' => Auth::id()],
            ['phone_number' => $request->phone_number]
        );

        return $this->success($userInfo);
    }

    public function updateIdentityNumber(Request $request)
    {
        $request->validate([
            'identity_number' => 'required|string|max:20'
        ]);

        $userInfo = UserInfo::updateOrCreate(
            ['user_id' => Auth::id()],
            ['identity_number' => $request->identity_number]
        );

        return $this->success($userInfo);
    }

    public function updateProfileImage(Request $request)
    {
        $request->validate([
            'profile_image' => 'required|file|mimes:jpg,jpeg,png|max:2048'
        ]);

        $path = $request->file('profile_image')->store('private/profile_images');

        $userInfo = UserInfo::updateOrCreate(
            ['user_id' => Auth::id()],
            ['profile_image' => $path]
        );

        return $this->success($userInfo);
    }
}
