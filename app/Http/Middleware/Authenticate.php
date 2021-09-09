<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            if (request()->is('admin/*')) {
                return route('login');
            } elseif (request()->is('teacher/*')) {
                return route('login.teacher');
            } else {
                return route('login.student');
            }
        }
    }
}
