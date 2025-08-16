<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\Master;

class MasterLoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/masters/index';

    public function __construct()
    {
        $this->middleware('guest:master')->except('logout');
    }

    protected function guard()
    {
        return Auth::guard('master');
    }

    public function showLoginForm()
    {
        return view('auth.masters_login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'phone' => 'required|string',
        ]);

        $credentials = [
            'email' => $request->email,
            'password' => $request->phone,
        ];

        // 先抓使用者
        $master = Master::where('email', $request->email)->first();

        if (!$master) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => '該電子郵件地址尚未註冊']);
        }

        // 嘗試登入
        if ($this->guard()->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('masters.index'));
        }

        // 密碼錯誤
        return back()->withInput($request->only('email'))
            ->withErrors(['password' => '密碼輸入錯誤']);
    }


    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        Cookie::queue(Cookie::forget('remember_web'));

        return redirect()->route('masters_login');
    }
}
