<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use App;

class Localization
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
        if ( Session::has('language') )
        {
            App::setLocale( Session::get('language') );
        }
        return $next($request);
    }
}
