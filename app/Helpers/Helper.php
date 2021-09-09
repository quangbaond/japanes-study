<?php

namespace App\Helpers;
use App\Models\Notifications;
use App\Models\Receiver;
use Illuminate\Support\Str;
use Timezone;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use DB;
class Helper
{
    /**
     * Get day by format define
     * @param $date
     * @return string
     */
    public static function formatDate($date)
    {
        return date('Y/m/d', strtotime($date));
    }

    /**
     * Get day by format define HIS
     * @param $date
     * @return string
     */
    public static function formatDateHIS($date)
    {
        return date('Y/m/d H:i:s', strtotime($date));
    }

    public static function checkDate($year, $month, $day)
    {
        return checkdate($month, $day, $year);
    }
    public static function getDate($startDate)
    {
        $data = [];
        $class = "";
        $day = \Carbon\Carbon::parse($startDate)->day;
        $month = \Carbon\Carbon::parse($startDate)->month;
        $week = \Carbon\Carbon::parse($startDate)->format('l');
        $year = \Carbon\Carbon::parse($startDate)->year;
        switch ($week) {
            case "Monday" :
                $week = "Mon";
            break;
            case "Tuesday" :
                $week = "Tue";
            break;
            case "Wednesday" :
                $week = "Wed";
            break;
            case "Thursday" :
                $week = "Thu";
            break;
            case "Friday" :
                $week = "Fri";
            break;
            case "Saturday" :
                $class = "text-primary";
                $week = "Sat";
            break;
            case "Sunday" :
                $class = "text-danger";
                $week = "Sun";
            break;
        }
        $data['day'] = $day; $data['month'] = $month;  $data['week'] = $week; $data['year'] = $year; $data['class'] = $class;
        return $data;
    }
    public static function osort(&$array, $properties)
    {
        if (is_string($properties)) {
            $properties = array($properties => SORT_ASC);
        }
        uasort($array, function($a, $b) use ($properties) {
            foreach($properties as $k => $v) {
                if (is_int($k)) {
                    $k = $v;
                    $v = SORT_ASC;
                }
                $collapse = function($node, $props) {
                    if (is_array($props)) {
                        foreach ($props as $prop) {
                            $node = (!isset($node->$prop)) ? null : $node->$prop;
                        }
                        return $node;
                    } else {
                        return (!isset($node->$props)) ? null : $node->$props;
                    }
                };
                $aProp = $collapse($a, $k);
                $bProp = $collapse($b, $k);
                if ($aProp != $bProp) {
                    return ($v == SORT_ASC)
                        ? strnatcasecmp($aProp, $bProp)
                        : strnatcasecmp($bProp, $aProp);
                }
            }
            return 0;
        });
    }
    public static function starTeacher($id)
    {
        $star = 0;
        $result = 0;
        $teacher_star = DB::table('teacher_review')->where('teacher_id' , '=' , $id)->get();
        if( $id == null || $teacher_star->count() == 0) {
            return $result = 0;
        }
        foreach ($teacher_star as $item)
        {
            $star += $item->star;
        }
        $count  = $teacher_star->count();
        $result = $count  == 0 ? 0 : ($star / $teacher_star->count());
        if($result > 5){
            $result = 5;
        }
        return number_format($result, 2);
    }

    /**
     *
     * @param $date , $time
     * @return array
     */
    public static function getTime($date , $time)
    {

        $data = [];
        $startTime = $date . " " . $time;
        $now = date("Y-m-d H:i:s", strtotime(Carbon::now()));
        $diff = abs(strtotime($now) - strtotime($startTime));
        $years = floor($diff / (365 * 60 * 60 * 24));
        $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
        $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
        $hours = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24) / (60 * 60));
        $minutes = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
        $seconds = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60 - $minutes * 60));
        $data['years'] = $years; $data['months'] = $months ; $data['days'] = $days; $data['hours'] = $hours; $data['seconds'] = $seconds;
        return $data;
    }

}
