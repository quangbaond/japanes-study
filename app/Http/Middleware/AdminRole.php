<?php

namespace App\Http\Middleware;

use Closure;
use Request;
use Illuminate\Support\Facades\Auth;

class AdminRole
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
        if (
            Auth::check() && Auth::user()->role == config('constants.role.admin')
            || (Auth::check() && Auth::user()->role == config('constants.role.child-admin')
                && (!(Request::is('admin/admin-list*')) || Request::is('admin/admin-list/detail/' . Auth::id()) || Request::is('admin/admin-list/change-password') || Request::is('admin/admin-list/update-profile'))  )
        ) {
            return $next($request);
        }
        abort(403);
    }
}
