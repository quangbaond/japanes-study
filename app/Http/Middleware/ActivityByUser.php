<?php

namespace App\Http\Middleware;

use App\Models\User;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class ActivityByUser
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     * @throws \Exception
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            // Get ip
            $ip = geoip()->getLocation(geoip()->getClientIP());

            // Show message when change timezone
            if (Auth::user()->timezone != $ip['timezone']) {
                $lang = Session::get('language');
                if ($lang == 'ja') {
                    request()->session()->flash('success', __('validation_custom.M072_01') . $ip['timezone'] . __('validation_custom.M072_02'));
                } else {
                    request()->session()->flash('success', __('validation_custom.M072') . $ip['timezone']);
                }
            }

            //$ip = geoip()->getLocation('103.9.78.84');

            $expiresAt = Carbon::now()->addMinutes(env('SESSION_LIFETIME')); // keep online for 1 min
            Cache::put('user-is-online-' . Auth::id(), true, $expiresAt);
            // last seen and change timezone for user
            User::where('id', Auth::user()->id)
                ->update([
                    'last_seen' => (new \DateTime())->format("Y-m-d H:i:s"),
                    'timezone' => $ip['timezone']
                ]);
        }
        return $next($request);
    }
}
