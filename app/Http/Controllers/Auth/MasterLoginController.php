<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\Master;
use Illuminate\Validation\ValidationException;
use Throwable;

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
        try {
            $request->validate([
                'email' => 'required|email',
                'phone' => 'required|string',
            ]);

            $credentials = [
                'email' => $request->email,
                'password' => $request->phone,
            ];

            // 檢查帳號是否存在
            $master = Master::where('email', $request->email)->first();

            if (!$master) {
                return back()->withInput($request->only('email'))
                    ->with('validation_errors', ['該電子郵件地址尚未註冊']);
            }

            // 嘗試登入
            if ($this->guard()->attempt($credentials, $request->filled('remember'))) {
                $request->session()->regenerate();
                return redirect()->intended(route('masters.index'))
                    ->with('success', '登入成功！');
            }

            // 密碼錯誤
            return back()->withInput($request->only('email'))
                ->with('validation_errors', ['密碼輸入錯誤']);

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withInput()
                ->with('validation_errors', $e->validator->errors()->all());
        } catch (Throwable $e) {
            // 其他異常
            return back()
                ->withInput()
                ->with('error', '系統發生錯誤，請稍後再試');
        }
    }


    public function logout(Request $request)
    {
        try {
            $this->guard()->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();
            Cookie::queue(Cookie::forget('remember_web'));

            return redirect()->route('masters_login');
        } catch (Throwable $e) {
            return redirect()->route('masters_login')
                ->with('error', '登出過程發生錯誤，請稍後再試');
        }
    }
}
