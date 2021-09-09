<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use JamesMills\LaravelTimezone\Timezone;
use Carbon\Carbon;
class TimezoneController extends Timezone
{
    //
    public function convertToLocal(?Carbon $date, $format = null, $format_timezone = false) : string
    {
        if (is_null($date)) {
            return 'Empty';
        }

        $timezone = (auth()->user()->timezone) ?? config('app.timezone');

        $date->setTimezone($timezone);

        if (is_null($format)) {
            return $date->format(config('timezone.format'));
        }

        $formatted_date_time = $date->format($format);

        if ($format_timezone) {
            return $formatted_date_time . ' ' . $this->formatTimezone($date);
        }

        return $formatted_date_time;
    }
    public function convertFromLocal($date) : Carbon
    {
        return Carbon::parse($date, auth()->user()->timezone)->setTimezone('Asia/Ho_Chi_Minh');
    }

    public function convertFromLocalNotLogin($date) : Carbon
    {
        $ip = geoip()->getLocation(geoip()->getClientIP());
        $timezone = ($ip['timezone']) ?? config('app.timezone');
        return Carbon::parse($date, $timezone)->setTimezone('Asia/Ho_Chi_Minh');
    }

    public function convertToUserLocalWithId(?Carbon $date, $user_id, $format = null, $format_timezone = false )
    {
        if(is_null($date)) {
            return 'Empty';
        }
        $timezone = DB::table('users')->select('timezone')->where('users.id','=',$user_id)->first();
        $timezone = ($timezone->timezone) ?? config('app.timezone');
        $date->setTimezone($timezone);

        if (is_null($format)) {
            return $date->format(config('timezone.format'));
        }

        $formatted_date_time = $date->format($format);

        if ($format_timezone) {
            return $formatted_date_time . ' ' . $this->formatTimezone($date);
        }

        return $formatted_date_time;
    }

    public function convertToLocalWhenNotLogin(?Carbon $date, $format = null, $format_timezone = false )
    {
        if(is_null($date)) {
            return 'Empty';
        }
        $ip = geoip()->getLocation(geoip()->getClientIP());
        $timezone = ($ip['timezone']) ?? config('app.timezone');
        $date->setTimezone($timezone);

        if (is_null($format)) {
            return $date->format(config('timezone.format'));
        }

        $formatted_date_time = $date->format($format);

        if ($format_timezone) {
            return $formatted_date_time . ' ' . $this->formatTimezone($date);
        }

        return $formatted_date_time;
    }

    /**
     * @param  Carbon  $date
     * @return string
     */
    protected function formatTimezone(Carbon $date) : string
    {
        $timezone = $date->format('e');
        $parts = explode('/', $timezone);

        if (count($parts) > 1) {
            return str_replace('_', ' ', $parts[1]) . ', ' . $parts[0];
        }

        return str_replace('_', ' ', $parts[0]);
    }
}
