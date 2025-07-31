<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class MemberMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    
    public function handle($request, Closure $next)
    {
        if (auth('member')->check() && auth('member')->user()->status == '1') {
            return $next($request);
        }
        auth()->guard('member')->logout();
        return redirect()->route('member.auth.login');
    }
}
