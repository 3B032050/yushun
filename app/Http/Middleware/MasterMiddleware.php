<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MasterMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    public function handle(Request $request, Closure $next): Response
    {
        $master = $request->user('master');

        // 只有非登入使用者才導向登入頁
        if (!$master || !$master->isMaster()) {
            return redirect()->route('masters_login');
        }

        return $next($request);
    }
}
