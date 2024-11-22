<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class MasterLoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */



    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected function redirectTo()
    {
        // 檢查是否為師傅
        if (Auth::guard('master')->check()) {
            return route('masters.index');
        }

        return '/home';
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function showLoginForm()
    {
        return view('auth.masters_login');
    }
    public function __construct()
    {
        $this->middleware('guest:master')->except('logout');
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'phone' => 'required|string',
        ]);

        // 將電話號碼作為密碼來驗證
        if (Auth::guard('master')->attempt(['email' => $request->email, 'password' => $request->phone])) {
            return redirect()->route('masters.index');
        }

        return redirect()->back()->withInput($request->only('email', 'remember'));
    }

    public function logout(Request $request)
    {
        Auth::guard('master')->logout(); // 使用對應的 guard 進行登出

        $request->session()->invalidate(); // 清除 session 資料
        $request->session()->regenerateToken(); // 重新生成 CSRF token

        return redirect()->route('masters_login'); // 重定向到登入頁面
    }
//    public function login(Request $request)
//    {
//        $this->validate($request, [
//            'email' => 'required|string|email',
//            'password' => 'required|string',
//        ]);
//
//        if ($this->attemptLogin($request)) {
//            return $this->sendLoginResponse($request);
//        }
//
//        return $this->sendFailedLoginResponse($request);
//    }

}
