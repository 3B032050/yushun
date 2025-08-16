<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureMasterEmailIsVerified
{
    public function handle(Request $request, Closure $next)
    {
        $master = $request->user('master');

        // 未登入或未驗證
        if (!$master) {
            return redirect()->route('masters_login');
        }

        if (!$master->hasVerifiedEmail()) {
            return redirect()->route('masters.verification.notice');
        }

        return $next($request);
    }
}
