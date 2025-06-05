<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class MasterVerificationController extends Controller
{
    protected $redirectTo = '/masters/index';
    public function notice()
    {
        return view('auth.verify_masters');
    }

    public function verify(Request $request)
    {
        $user = \App\Models\Master::findOrFail($request->route('id'));

        if (! hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            abort(403, '驗證失敗，簽章不符。');
        }

        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }
        // 自動登入該使用者
        auth()->guard('master')->login($user);
        return redirect()->route('masters.index'); // 驗證成功後的頁面
    }


    public function resend(Request $request)
    {
        if ($request->user('master')->hasVerifiedEmail()) {
            return redirect('/masters/index');
        }

        $request->user('master')->sendEmailVerificationNotification();

        return back()->with('message', '驗證信已重新寄出！');
    }

}

