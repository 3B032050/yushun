<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class MasterLoginController extends Controller
{
    use AuthenticatesUsers;

    // 指定要用的 guard
    protected function guard()
    {
        return Auth::guard('master');
    }

    // 登入成功後導向路徑
    protected $redirectTo = '/masters/index';

    // 登入成功後的額外動作（你可以在這裡加更多邏輯）
    protected function authenticated(Request $request, $user)
    {
        return redirect()->route('masters.index');
    }

    public function showLoginForm()
    {
        return view('auth.masters_login');
    }

    public function __construct()
    {
        $this->middleware('guest:master')->except('logout');
    }

    // 這邊改成使用 trait 的 login 流程，改寫 validate 驗證規則
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'phone' => 'required|string',
        ]);

        $email = $request->input('email');
        $password = $request->input('phone');

        // 檢查 email 是否存在
        $userExists = \App\Models\Master::where('email', $email)->exists();

        if (!$userExists) {
            // 信箱不存在
            $errors = ['email' => '該電子郵件地址尚未註冊'];

            return redirect()->back()
                ->withInput($request->only('email'))
                ->withErrors($errors);
        }

        // 用 phone 當密碼嘗試登入
        if ($this->guard()->attempt(['email' => $email, 'password' => $password], $request->filled('remember'))) {
            // 登入成功，呼叫 trait 的 sendLoginResponse()
            return $this->sendLoginResponse($request);
        }

        // 密碼錯誤
        $errors = ['password' => '密碼輸入錯誤'];

        return redirect()->back()
            ->withInput($request->only('email'))
            ->withErrors($errors);
    }

    public function logout(Request $request)
    {
        Auth::guard('master')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        Cookie::queue(Cookie::forget('remember_web'));

        return redirect()->route('masters_login');
    }
}
