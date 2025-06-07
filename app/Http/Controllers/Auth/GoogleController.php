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
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            // 確保 Google 回傳 email
            if (!$googleUser->email) {
                return redirect()->route('login')->withErrors('Google 帳戶沒有提供 Email');
            }

            // 查找或創建用戶
            $user = User::updateOrCreate(
                ['email' => $googleUser->email],
                [
                    'name' => $googleUser->name,
                    'google_id' => $googleUser->id,
                    'password' => bcrypt(uniqid()), // 隨機密碼
                    'avatar' => $googleUser->avatar,
                ]
            );
            // 直接標記為已驗證
            if (! $user->hasVerifiedEmail()) {
                $user->markEmailAsVerified();
            }
            // 登入用戶
            Auth::login($user);

            return redirect()->route('users.index');
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Google 登入失敗，請重試');

        }
    }
}
