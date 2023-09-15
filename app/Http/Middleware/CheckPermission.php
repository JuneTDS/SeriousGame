<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // return $next($request);
    }

    public function isSuperAdmin()
    {
        if (Auth::user()->role == 'super_admin') {
            return true;
        }
        return false;
    }

    public function isAdmin()
    {
        if (Auth::user()->role == 'admin') {
            return true;
        }
        return false;
    }

    public function isStudent()
    {
        if (Auth::user()->role == 'Student') {
            return true;
        }
        return false;
    }
}
