<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class MeghalaMiddleware
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
        if (auth('meghala')->check() && auth('meghala')->user()->status == '1') {
            return $next($request);
        }
        auth()->guard('meghala')->logout();
        return redirect()->route('meghala.auth.login');
    }
}
