<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class UnitMiddleware
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
        if (auth('unit')->check() && auth('unit')->user()->status == '1') {
            return $next($request);
        }
        auth()->guard('unit')->logout();
        return redirect()->route('unit.auth.login');
    }
}
