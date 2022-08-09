<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role != '1' && Auth::user()->role != '2' && Auth::user()->role != '3' && Auth::user()->role != '6') {
            return redirect(route('enduser.dashboard'));
        }
        return $next($request);
    }
}
