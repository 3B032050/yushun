<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
class GoogleController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->user();

        // 查找或創建用戶
        $user = User::updateOrCreate(
            ['email' => $googleUser->email],
            [
                'name' => $googleUser->name,
                'google_id' => $googleUser->id,
                'phone'=>$googleUser->phone,
                'password'=>$googleUser->phone,
                'address'=>$googleUser->address,
                'avatar' => $googleUser->avatar,
            ]
        );

        // 登入該用戶
        Auth::login($user);

        // 重定向至首頁或其他頁面
        return redirect()->route('users.index');
    }
}
