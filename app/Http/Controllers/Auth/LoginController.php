<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
class LoginController extends Controller
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

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */

    protected function authenticated($request, $user)
    {
        return redirect('/users/index');
    }
    protected function sendFailedLoginResponse(Request $request)
    {
        $email = $request->input($this->username());  // 預設是 email

        // 檢查該 email 是否有對應使用者
        $userExists = User::where('email', $email)->exists();

        if (!$userExists) {
            // 信箱不存在
            $errors = [$this->username() => '該電子郵件地址尚未註冊'];
        } else {
            // 信箱存在但密碼錯誤
            $errors = ['password' => '密碼輸入錯誤'];
        }

        if ($request->expectsJson()) {
            return response()->json($errors, 422);
        }

        return redirect()->back()
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors($errors);
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

}
