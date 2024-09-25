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
    protected $redirectTo = '/masters/index';

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
            return redirect()->intended(route('masters.index'));
        }

        return redirect()->back()->withInput($request->only('email', 'remember'));
    }

    public function logout()
    {
        Auth::guard('master')->logout();
        return redirect('/');
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
