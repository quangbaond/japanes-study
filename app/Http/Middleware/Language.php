<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;

class Language
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $prefix = explode('/', Request::path())[0];
        if ($prefix == "teacher" || $prefix == "admin") {
            $locale = 'ja';
            Session::put('language', $locale);
            app()->setLocale(Session::get('language'));
        } else {
            $lang = Session::get('language');
            if ($lang != "en") {
                $locale = "vi";
                Session::put('language', $locale);
                app()->setLocale(Session::get('language'));
            }
        }
        return $next($request);
    }
}
