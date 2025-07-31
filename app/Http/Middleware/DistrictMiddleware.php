<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class DistrictMiddleware
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
        if (auth('district')->check() && auth('district')->user()->status == '1') {
            return $next($request);
        }
        auth()->guard('district')->logout();
        return redirect()->route('district.auth.login');
    }
}
