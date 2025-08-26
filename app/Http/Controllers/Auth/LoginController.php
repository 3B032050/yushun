<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Throwable;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     */
    protected function authenticated($request, $user)
    {
        try {
            return redirect('/users/index');
        } catch (Throwable $e) {
            // 發生例外 → 導回登入頁並提示
            return redirect()->route('login')->with('error', '登入後導向發生錯誤，請稍後再試');
        }
    }

    /**
     * 登入失敗回應
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        try {
            $email = $request->input($this->username());  // 預設是 email
            $userExists = User::where('email', $email)->exists();

            if (!$userExists) {
                $errors = [$this->username() => '該電子郵件地址尚未註冊'];
            } else {
                $errors = ['password' => '密碼輸入錯誤'];
            }

            if ($request->expectsJson()) {
                return response()->json($errors, 422);
            }

            return redirect()->back()
                ->withInput($request->only($this->username(), 'remember'))
                ->withErrors($errors);

        } catch (Throwable $e) {
            // 捕捉例外 → 一律回登入頁
            return redirect()->route('login')->with('error', '登入驗證發生錯誤，請稍後再試');
        }
    }

    /**
     * 建構子
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
}
