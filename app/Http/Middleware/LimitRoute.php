<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LimitRoute
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
//        $path = explode("/",$request->fullUrl());
//        $id = $path[count($path)-1];

        $limit_route = [];
        if (in_array($request->fullUrl(), $limit_route)) {
            $user = DB::table('users')
                ->select('user_information.membership_status')
                ->leftJoin('user_information','users.id','=','user_information.user_id')
                ->where('users.id',Auth::user()->id)
                ->first();
            if(in_array($user->membership_status,[config('constants.membership.id.premium_trial'),config('constants.membership.id.premium')])) {
                return $next($request);
            }
            else {
                abort(403);
            }
        }
        return $next($request);
    }
}
