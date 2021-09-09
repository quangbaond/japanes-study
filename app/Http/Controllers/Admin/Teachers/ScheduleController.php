<?php

namespace App\Http\Controllers\Admin\Teachers;

use App\Helpers\Helper;
use App\Http\Controllers\TimezoneController;
use App\Repositories\Admin\Managers\ProfileRepository;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Repositories\Admin\teachers\ScheduleRepository;
use App\Models\TeacherSchedule;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

use App\Repositories\Admin\Managers\TeacherRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use function GuzzleHttp\Psr7\str;

class ScheduleController extends Controller
{
    protected $scheduleRepository;
    protected $teacherRepository;
    protected $timezone;

    /**
     * Display a listing of the resource.
     *
     * @param ScheduleRepository $scheduleRepository
     * @param TeacherRepository $teacherRepository
     * @param TimezoneController $timezone
     */
    function __construct(ScheduleRepository $scheduleRepository, TeacherRepository $teacherRepository, TimezoneController $timezone)
    {
        $this->scheduleRepository = $scheduleRepository;
        $this->teacherRepository = $teacherRepository;
        $this->timezone = $timezone;
    }

    /**
     * Show the application dashboard.
     *
     * @param
     * @return View
     */
    public function addSchedule()
    {
        $dateScheduled = DB::table('teacher_schedule')
            ->select('teacher_schedule.*')
            ->where('teacher_id', '=', Auth::id())
            ->orderBy('start_date')
            ->get()
            ->filter(function ($value) {
                $date = Carbon::parse($value->start_date.' '.$value->start_hour);
                $value->start_hour = $this->timezone->convertToLocal($date, "H:i");
                $value->start_date = $this->timezone->convertToLocal($date , "Y-m-d");
                return $value;
            });

        $datetemp = [];
        foreach ($dateScheduled as $schedule) {
            array_push($datetemp, $schedule->start_date);
        }
        $date = [];
        $dem = 0;
        for ($i = 0; $i < 7; $i++) {
            $temp = [];
            $nextDate = $this->timezone->convertToLocal(Carbon::now()->addDays($i), 'Y-m-d');
            if (!in_array($nextDate, $datetemp)) {
                $nextDate = $this->timezone->convertToLocal(Carbon::now()->addDays($i), 'l,m,d');
                $nextDate = explode(',', $nextDate);
                $temp['name'] = $this->getDayName($nextDate[0]);
                $temp['month'] = $nextDate[1];
                $temp['day'] = $nextDate[2];
                array_push($date, $temp);
            } else {
                array_push($date, []);
                $dem++;
            }
        }
        if ($dem == 7) {
            $notification = '次の7日間のスケジュールが追加されました。
                             変更したい場合は、スケジュール一覧画面で変更してください。';
            return view('admin.teachers.addSchedule')->with('notification', $notification);
        }
        $teacher_zoom = $this->teacherRepository->getTeacherZoomLink();
//        dd($this->timezone->convertToLocal(Carbon::now()));
        return view('admin.teachers.addSchedule')->with('date', $date)->with('teacher_zoom', $teacher_zoom);
    }


    /**
     * @param $date
     * @return mixed
     */
    public function getDayName($date)
    {
        $dateArray = __('date');
        switch ($date) {
            case "Monday":
                $date = $dateArray[0];
                return $date;
            case "Tuesday":
                $date = $dateArray[1];
                return $date;
            case "Wednesday":
                $date = $dateArray[2];
                return $date;
            case "Thursday":
                $date = $dateArray[3];
                return $date;
            case "Friday":
                $date = $dateArray[4];
                return $date;
            case "Saturday":
                $date = $dateArray[5];
                return $date;
            case "Sunday":
                $date = $dateArray[6];
                return $date;
        }
    }

    /**
     * get minimum schedule time on day
     * by sitranv
     * @param $arrayValue
     * @return int
     */
    public function maxInDay($arrayValue) {
        $max = '00:00';
        $index = -1;
        $arrayValue = (array) $arrayValue;
        if(!sizeof($arrayValue) > 0) return $index;
        for ($i = 0; $i < sizeof($arrayValue); $i++) {
            if($arrayValue[$i] != "" && strtotime($max) < strtotime($arrayValue[$i])) {
                $max = $arrayValue[$i];
                $index = $i;
            }
        }
        return $index;
    }

    /**
     * get maximum schedule time on day
     * by sitranv
     * @param $arrayValue
     * @return int
     */
    public function minInDay($arrayValue) {
        $max = '24:00';
        $index = -1;
        $arrayValue = (array) $arrayValue;
        if(!sizeof($arrayValue) > 0) return $index;
        for ($i = 0; $i < sizeof($arrayValue); $i++) {
            if($arrayValue[$i] != "" && strtotime($max) > strtotime($arrayValue[$i])) {
                $max = $arrayValue[$i];
                $index = $i;
            }
        }
        return $index;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function validateSchedule(Request $request)
    {
        $teacher_zoom = $this->teacherRepository->getTeacherZoomLink();
        if (is_null($teacher_zoom->link_zoom) || $teacher_zoom->link_zoom === '') {
            return $this->responseError('error', "Create your zoom link before change anything");
        } else {
            $distance_time = config('constants.distance_time');

            $data = json_decode($request->getContent());

            $array = [];
            foreach ($data as $key => $value) {
                array_push($array, $value);
            }

            //check all empty input
            $scheduleFailed = $this->checkEmptyAllInput($array);
            if ($scheduleFailed != []) {
                return $this->responseError('scheduleFailed', $scheduleFailed);
            }

            //check current date and time
            $scheduleFailed = $this->compareTimeAndCurrentTime($array, $distance_time);
            if ($scheduleFailed != []) {
                return $this->responseError('scheduleFailed', $scheduleFailed);
            }

            //validate input
            $scheduleFailed = $this->validateAllTime($array, $distance_time);

            if ($scheduleFailed != []) {
                return $this->responseError('scheduleFailed', $scheduleFailed);
            }

            //validate database
            $scheduleFailed = $this->validateTimeDatabase($array, $distance_time);
            if ($scheduleFailed != []) {
                return $this->responseError('scheduleFailed', $scheduleFailed);
            }

            //insert DB
            if ($this->insertSchedule($array)) {
                return $this->responseSuccess();
            } else {
                $error = __('validation_custom.M007');
                return $this->responseError('error', $error);
            }
        }
    }

    /**
     * @param $array
     * @return bool
     */
    public function insertSchedule($array)
    {
        DB::beginTransaction();
        try {
            $temp = [];
            $value = [];
            for ($i = 0; $i < sizeof($array); $i++) {
                $arrayKey = array_keys((array)$array[$i]);
                if ($arrayKey) {
                    array_push($temp, (int)explode('-', $arrayKey[0])[0]);
                    array_push($value, array_values((array)$array[$i]));
                }
            }
            $i = 0;
            foreach ($value as $time) {
//                $date = $this->getDate($temp[$i] - 1);
                $date = $this->timezone->convertToLocal(Carbon::now()->addDays($temp[$i] - 1), 'Y-m-d');
                //test
                foreach ($time as $v) {
                    if ($v != "") {
                        $convertFromLocal = $this->timezone->convertFromLocal($date. ' '. $v);
                        $data[] = [
                            'teacher_id' => Auth::id(),
                            'start_date' => $convertFromLocal->format('Y-m-d'),
                            'start_hour' => $convertFromLocal->format('H:i'),
                            'status' => 3,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
//                foreach ($time as $v) {
//                    if ($v != "") {
//                        $data[] = [
//                            'teacher_id' => Auth::id(),
//                            'start_date' => $date,
//                            'start_hour' => $v,
//                            'status' => 3,
//                            'created_at' => now(),
//                            'updated_at' => now(),
//                        ];
//                    }
//                }
                $i++;
            }
            DB::table('teacher_schedule')->insert($data);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    /**
     * @param $array
     * @param $distance_time
     * @return array
     */
    public function validateTimeDatabase($array, $distance_time)
    {
        $schedules = DB::table('teacher_schedule')
            ->select('*')
            ->where([['teacher_id', Auth::id()],
                ['start_date', '>=', Carbon::now()->subDay(1)->format('Y-m-d')],
                ['start_date', '<=', Carbon::now()->addDays(7)->format('Y-m-d')]])
            ->orderBy('start_date')
            ->get()
            ->filter(function ($value) {
                $date = Carbon::parse($value->start_date.' '.$value->start_hour);
                $value->start_hour = $this->timezone->convertToLocal($date, "H:i");
                $value->start_date = $this->timezone->convertToLocal($date , "Y-m-d");
                return $value;
            })
            ->toArray();

        $scheduleFailed = [];
        for ($i = 0; $i < sizeof($array); $i++) {
            $arrayKey = array_keys((array)$array[$i]);
            $arrayValue = array_values((array)$array[$i]);
            $nextDate = Carbon::today()->addDays($i)->format('Y-m-d');
            for ($j = 0; $j < sizeof($arrayValue); $j++) {
                if ($arrayValue[$j] == "") continue;
                foreach ($schedules as $schedule) {
                    if ($schedule->start_date == $nextDate) {
                        //test timezone
//                        $convertFromLocal = $this->timezone->convertFromLocal($nextDate .' '.$arrayValue[$j]);
//                        $startTime = strtotime($convertFromLocal->format('H:i'));
                        $startTime = strtotime($arrayValue[$j]);
                        $endTime = strtotime($schedule->start_hour);
                        if ($endTime > $startTime) {
                            $diff = gmdate('H:i', $endTime - $startTime);
                            if (strtotime($diff) < strtotime($distance_time)) {
                                if (!in_array($arrayKey[$j], $scheduleFailed, true)) {
                                    array_push($scheduleFailed, $arrayKey[$j]);
                                }
                            }
                        } elseif ($endTime <= $startTime) {
                            $diff = gmdate('H:i', $startTime - $endTime);
                            if (strtotime($diff) < strtotime($distance_time)) {
                                if (!in_array($arrayKey[$j], $scheduleFailed, true)) {
                                    array_push($scheduleFailed, $arrayKey[$j]);
                                }
                            }
                        }
                    }
                }
            }
        }

        if (sizeof($scheduleFailed) > 0) {
            $scheduleFailed['msgErr'] = __('validation_custom.M029');
            return $scheduleFailed;
        } else {
            return [];
        }
    }

    /**
     *
     * Validation time list schedule
     * @param $array
     * @return bool
     */
    public function validationTime(Request $request)
    {
        $input = json_decode($request->getContent(), true);
//        dd($input);
        $teacher_zoom = $this->teacherRepository->getTeacherZoomLink();
        if (is_null($teacher_zoom->link_zoom) || $teacher_zoom->link_zoom === '') {
            return $this->responseError('error', "Create your zoom link before change anything");
        } else {
            //$data = $request->data;
            $data = $input['data'];
            $remove = $input['remove'];
            $exits = $input['exits'];
            $errors = array();
            $keys = array_keys($data);
            $distance_time = config('constants.distance_time');
            $distance_time_s = $this->processTime($distance_time);
            $list_tmp = array();
            $list_check_db = array();
            if (empty($remove) && empty($exits) && empty($data)) {
                return $this->responseError('error', __('validation_custom.M030'));
            }
            $check = true;
            foreach ($data as $ss_check) {
                if (!empty($ss_check)) {
                    $check = false;
                }
            }

            //dd($check);
            if ($check == true && empty($exits) && empty($remove)) {
                return $this->responseError('error', __('validation_custom.M030'));
            }
            if (count($keys) >= 1) {
                $listSchedule = $this->teacherRepository->getScheduleData($keys[count($keys) - 1]. '00:00:00', $keys[0].' 24:00:00');

                foreach ($listSchedule as $ss) {
                    if (!in_array($ss->id, $remove)) {
                        $list_tmp[$ss->id] = $ss->start_hour;
                        $list_check_db[$ss->start_date][] = $this->processTime($ss->start_hour);
                    }
                }
            }
//            dd($list_tmp, $list_check_db);

            //check date before and after
            $scheduleFailed = [];
            $arrayKey = array_keys((array)$data);
            $arrayValue = array_values((array)$data);
            for ($i = 0; $i < sizeof($data) - 1; $i++) {
                $arrayKeyBefore = array_keys($arrayValue[$i]);
                $arrayValueBefore = array_values($arrayValue[$i]);
                $arrayKeyAfter = array_keys($arrayValue[$i + 1]);
                $arrayValueAfter = array_values($arrayValue[$i + 1]);
                $beforeIndex = $this->minInDay($arrayValueBefore);
                $afterIndex = $this->maxInDay($arrayValueAfter);
                if ($beforeIndex == -1 || $afterIndex == -1) {
                    continue;
                }

                $dateBefore = Carbon::parse($arrayKey[$i] .' '
                    . $arrayValueBefore[$beforeIndex]);
                $dateAfter = Carbon::parse($arrayKey[$i + 1] .' '
                    . $arrayValueAfter[$afterIndex]);
                if ($dateBefore->lt($dateAfter->addMinutes(config('constants.distance_time_minute')))) {
                    $scheduleFailed[$arrayKeyAfter[$afterIndex]] = __('validation_custom.M029');
                    $scheduleFailed[$arrayKeyBefore[$beforeIndex]] = __('validation_custom.M029');
                }
            }
            if (sizeof($scheduleFailed) > 0) {
                return $this->responseError('error', $scheduleFailed);
            }

            $list_exits = array();
            $tmp = array();
            $list_exits_time = array();
            foreach ($exits as $p) {
                $list_exits[$p['id_div']] = $p['id'];
                if (isset($list_exits_time[$p['time']])) {
                    $list_exits_time[$p['time']]++;
                } else {
                    $list_exits_time[$p['time']] = 1;
                }
            }
            //print_r($list_exits);
            foreach ($data as $key => $value) {
                foreach ($value as $kk => $item) {
                    if (!empty($item)) {
                        $tmp[$key][$kk] = $this->processTime($item);
                    } else {
                        $tmp[$key][$kk] = 0;
                        $errors[$kk] = __('validation_custom.M030');
                    }
                }

            }
            if (!empty($tmp)) {
                foreach ($tmp as $key => &$p) {
                    asort($p);
                }
                foreach ($tmp as $pp => $k) {
                    $tmp_key = array_keys($k);
                    if (count($tmp_key) > 24) {
                        $errors[$tmp_key[0]] = __('validation_custom.M029');
                        continue;
                    }

                    // echo count($k);
                    // echo $list_exits_time[$pp];
                    // die;
                    // if($list_exits_time[$pp] != count($k)){
                    //     return $this->responseError('error', __('validation_custom.M028'));
                    // }
                    if (count($tmp_key) >= 1) {
                        $tmp_check = array();
                        $check_val = false;

                        for ($i = 0; $i < count($k); $i++) {
                            if (isset($list_exits[$tmp_key[$i]])) {
                                $id = $list_exits[$tmp_key[$i]];
//                                dd($list_tmp);
                                $tmp_time = $this->processTime($list_tmp[$id]);
                            } else {
                                $tmp_time = 0;
                            }
//                            echo '<br>' .$tmp_time;
                            //echo $tmp_key[$i].'-'.$tmp_time.PHP_EOL;
                            //echo $k[$tmp_key[$i]].PHP_EOL;
                            //print_r($k);
                            $tmp_check[] = $k[$tmp_key[$i]];
                            if ($tmp_time != $k[$tmp_key[$i]]) {

                                if (!isset($errors[$tmp_key[$i]])) {
                                    $t2 = $this->timezone->convertToLocal(Carbon::now(), 'Y/m/d');
                                    $t1 = $pp;
                                    $diff = date_diff(date_create($t1), date_create($t2));
                                    if ($diff->days == 0) {
                                        $time = $this->timezone->convertToLocal(Carbon::now(), 'H:i');
                                        $time_s = $this->processTime($time);

                                        if (($k[$tmp_key[$i]] - $time_s) < $distance_time_s) {
                                            $errors[$tmp_key[$i]] = __('validation_custom.M031');
                                        }
                                    }
                                    if ($i < count($k) - 1) {
                                        if (($k[$tmp_key[$i + 1]] - $k[$tmp_key[$i]]) < $distance_time_s) {
                                            $errors[$tmp_key[$i]] = __('validation_custom.M029');
                                        }

                                    }
                                    if ($i > 0) {
                                        //echo ($k[$tmp_key[$i]] - $k[$tmp_key[$i-1]]);
                                        if (($k[$tmp_key[$i]] - $k[$tmp_key[$i - 1]]) < $distance_time_s) {
                                            $errors[$tmp_key[$i]] = __('validation_custom.M029');
                                        }
                                    }
                                    $check_val = true;
                                }
                            }
                        }

                        if (!empty($list_check_db) && empty($errors)) {
                            if (isset($list_check_db[$pp])) {
                                $list = $list_check_db[$pp];
                                if (count($list) != count($k)) {
                                    foreach ($list as $ss => $sss) {
                                        if (!in_array($sss, $tmp_check)) {
                                            $tmp_check[] = $sss;
                                        }
                                    }
                                    sort($tmp_check);
                                    //print_r($tmp_check);
                                    for ($l = 0; $l < count($tmp_check); $l++) {
                                        if ($l < count($tmp_check) - 1) {
                                            if (($tmp_check[$l + 1] - $tmp_check[$l]) < $distance_time_s) {
//                                                dd(1);
                                                return $this->responseError('error', __('validation_custom.M028'));
                                            }
                                        }
                                        if ($l > 0) {
                                            //echo ($k[$tmp_key[$i]] - $k[$tmp_key[$i-1]]);
                                            if (($tmp_check[$l] - $tmp_check[$l - 1]) < $distance_time_s) {
//                                                dd(2);
                                                return $this->responseError('error', __('validation_custom.M028'));
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        $t1 = $pp;
                        $t2 = $this->timezone->convertToLocal(Carbon::now(), 'Y/m/d');
                        $diff = date_diff(date_create($t1), date_create($t2));

                        if ($diff->days <= 0) {
                            $time = $this->timezone->convertToLocal(Carbon::now(), 'H:i');

                            $time_s = $this->processTime($time);

                            if (($k[$tmp_key[0]] - $time_s) < $distance_time_s) {
                                $errors[$tmp_key[0]] = __('validation_custom.M031');
                            }
                        }
                    }
                }
                if (!empty($errors)) {
                    return $this->responseError('error', $errors);
                } else {
//                    dd($data, $list_exits, $remove);
                    $check = $this->teacherRepository->addSchedule($data, $list_exits, $remove);

                    if ($check == true) {
                        Session::flash('success_schedule', __('validation_custom.M027'));
                        return $this->responseSuccess(null, __('validation_custom.M027'));
                    } else {
//                        dd(3);
                        return $this->responseError('error', __('validation_custom.M028'));
                    }

                }
            }
            if (!empty($remove)) {
                $check = $this->teacherRepository->removeSchedule($remove);

                if ($check == true) {
                    Session::flash('success_schedule', __('validation_custom.M027'));

                    return $this->responseSuccess(null, __('validation_custom.M027'));
                }
            }
            return $this->responseError('error', __('validation_custom.M028'));
        }
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toListSchedule()
    {
        try {
            $success = __('validation_custom.M008');
            return \redirect()->route('teacher.listSchedule')->with('success', $success);
        } catch (\Exception $e) {
            dd(123);
        }
    }

    /**
     * @param $array
     * @return array
     */
    public function checkEmptyAllInput($array)
    {
        $scheduleFailed = [];
        $check = 0;
        for ($i = 0; $i < sizeof($array); $i++) {
            $arrayValue = array_values((array)$array[$i]);
            $arrayKey = array_keys((array)$array[$i]);
            for ($j = 0; $j < sizeof($arrayValue); $j++) {
                if ($arrayValue[$j] != "") {
                    $check = 1;
                    break;
                } else {
                    if (!in_array($arrayKey[$j], $scheduleFailed, true)) {
                        array_push($scheduleFailed, $arrayKey[$j]);
                    }
                }
            }
        }
        if ($check == 0) {
            $scheduleFailed['msgErr'] = __('validation_custom.M030');
            return $scheduleFailed;
        } else {
            return [];
        }
    }

    /**
     * @param $array
     * @param $distance_time
     * @return array
     */
    public function compareTimeAndCurrentTime($array, $distance_time)
    {
        $temp = [];
        $temp1 = [];
        $value = [];
        $scheduleFailed = [];

        for ($i = 0; $i < sizeof($array); $i++) {
            $arrayKey = array_keys((array)$array[$i]);
            if ($arrayKey) {
                array_push($temp1, $arrayKey);
                array_push($temp, (int)explode('-', $arrayKey[0])[0]);
                array_push($value, array_values((array)$array[$i]));
            }
        }
        $currentDate = $this->timezone->convertToLocal(Carbon::now(), 'Y-m-d');
//        $date = $this->getDate($temp[0] - 1);
        $date = $this->timezone->convertToLocal(Carbon::now()->addDays($temp[0] - 1), 'Y-m-d');
        if ($date == $currentDate) {
            for ($i = 0; $i < sizeof($value[0]); $i++) {
                if ($value[0][$i] == "") {
                    continue;
                } else {
                    $currentTime = $this->timezone->convertToLocal(Carbon::now(),'H:i');
                    $currentTime = strtotime($currentTime);
                    $time = strtotime($value[0][$i]);
                    if ($time > $currentTime) {
                        $diff = gmdate('H:i', $time - $currentTime);
                        if (strtotime($diff) < strtotime($distance_time)) {
                            array_push($scheduleFailed, $temp1[0][$i]);
                        }
                    } else {
                        array_push($scheduleFailed, $temp1[0][$i]);
                    }
                }
            }
        }
        if (sizeof($scheduleFailed) > 0) {
            $scheduleFailed['msgErr'] = __('validation_custom.M031');
            return $scheduleFailed;
        } else {
            return [];
        }
    }


    /**
     * @param $array
     * @param $distance_time
     * @return array
     */
    public function validateAllTime($array, $distance_time)
    {
        $scheduleFailed = [];
        for ($i = 0; $i < sizeof($array); $i++) {
            $arrayKey = array_keys((array)$array[$i]);
            $arrayValue = array_values((array)$array[$i]);
            for ($j = 0; $j < sizeof($arrayValue) - 1; $j++) {
                $startTime = strtotime($arrayValue[$j]);
                for ($k = $j + 1; $k < sizeof($arrayValue); $k++) {
                    $endTime = strtotime($arrayValue[$k]);
                    if ($arrayValue[$j] == "" || $arrayValue[$k] == "") {
                        continue;
                    } else {
                        if ($endTime > $startTime) {
                            $diff = gmdate('H:i', $endTime - $startTime);
                            if (strtotime($diff) < strtotime($distance_time)) {
                                if (!in_array($arrayKey[$j], $scheduleFailed, true)) {
                                    array_push($scheduleFailed, $arrayKey[$j]);
                                }
                                if (!in_array($arrayKey[$k], $scheduleFailed, true)) {
                                    array_push($scheduleFailed, $arrayKey[$k]);
                                }
                            }
                        } elseif ($endTime <= $startTime) {
                            $diff = gmdate('H:i', $startTime - $endTime);
                            if (strtotime($diff) < strtotime($distance_time)) {
                                if (!in_array($arrayKey[$j], $scheduleFailed, true)) {
                                    array_push($scheduleFailed, $arrayKey[$j]);
                                }
                                if (!in_array($arrayKey[$k], $scheduleFailed, true)) {
                                    array_push($scheduleFailed, $arrayKey[$k]);
                                }
                            }
                        }
                    }
                }
            }
        }

        for ($i = 0; $i < sizeof($array) - 1; $i++) {
            $arrayKeyBefore = array_keys((array)$array[$i]);
            $arrayValueBefore = array_values((array)$array[$i]);
            $beforeIndex = $this->maxInDay($arrayValueBefore);
            $arrayKeyAfter = array_keys((array)$array[$i + 1]);
            $arrayValueAfter = array_values((array)$array[$i + 1]);
            $afterIndex = $this->minInDay($arrayValueAfter);
            if ($beforeIndex == -1 || $afterIndex == -1) {
                continue;
            }
            $dateBefore = Carbon::parse(Carbon::now()->addDays($i)->format('Y-m-d') .' '
                . $arrayValueBefore[$beforeIndex]);
            $dateAfter = Carbon::parse(Carbon::now()->addDays($i + 1)->format('Y-m-d') .' '
                . $arrayValueAfter[$afterIndex]);
            if ($dateBefore->gt($dateAfter->subMinute(config('constants.distance_time_minute')))) {
                if (!in_array($arrayKeyBefore[$beforeIndex], $scheduleFailed, true)) {
                    array_push($scheduleFailed, $arrayKeyBefore[$beforeIndex]);
                }
                if (!in_array($arrayKeyAfter[$afterIndex], $scheduleFailed, true)) {
                    array_push($scheduleFailed, $arrayKeyAfter[$afterIndex]);
                }
            }
        }

        if (sizeof($scheduleFailed) > 0) {
            $scheduleFailed['msgErr'] = __('validation_custom.M029');
            return $scheduleFailed;
        } else {
            return [];
        }
    }

    /**
     * @param $next
     * @return string
     */
    public function getDate($next)
    {
        return Carbon::today()->addDays($next)->format('Y-m-d');
    }

    /**
     * Display list schedule.
     *
     * @return \Illuminate\Contracts\View\Factory|View
     */
    public function listSchedule(Request $request)
    {
        $input = $request->all();
        $check = false;
        $numOfSchedule = true;
        $rules = [];
        $diff = 7;
        $date_start = Carbon::parse($this->timezone->convertToLocal(Carbon::now(), 'Y-m-d H:i'));
        $time = $date_start->format('Y/m/d');
        $time_next = $this->timezone->convertToLocal(Carbon::now()->addDays(6), 'Y/m/d');
        if (!empty($input)) {
            $check = true;
            //$rules['from_date'] = 'date';
            //$rules['to_date'] = 'date';
            $start_time = 0;
            $end_time = 0;
            $rules['to_date'] = '';
            ($input['from_date'] != '' && $input['to_date'] != '') ? $rules['to_date'] = 'after_or_equal:from_date' : $rules['to_date'] = '';
            ($input['from_time'] != '' && $input['to_time'] != '') ? $rules['to_time'] = 'after_or_equal:to_time' : $rules['to_time'] = '';
            if ($input['from_time'] != '') {
                $start_time = $this->processTime($input['from_time']);
            }
            if ($input['to_time'] != '') {
                $end_time = $this->processTime($input['to_time']);
            }
            ($start_time > 0 && $end_time > 0 && ($end_time < $start_time)) ? $rules['to_time'] = 'min:' . $start_time : $rules['to_time'] = '';
            $message = [
                'to_date.after_or_equal' => __('validation_custom.M010'),
                'to_time.min' => __('validation_custom.date'),
                'from_date.required' => __('validation_custom.M001'),
                'to_time.required' => __('validation_custom.M001'),
                'from_time.required' => __('validation_custom.M001'),
                'to_time.after_or_equal' => __('validation_custom.M010'),
            ];

            $validator = Validator::make($input, $rules, $message, [
                'from_date' => '日付',
                'to_date' => '日付',
                'from_time' => '時間帯',
                'to_time' => '時間帯'
            ]);

            if ($validator->fails()) {
                return redirect(route('teacher.listSchedule'))->withErrors($validator)->withInput();
            }
            if ($input['from_date'] != '' && $input['to_date'] == '') {
                $date_start = Carbon::createFromFormat('Y/m/d', $input['from_date']);
                $maxOfDate = $this->teacherRepository->getBiggestDateOfSchedule();
                $maxOfDate = Carbon::parse($maxOfDate[0]->max_date);
                if ($date_start->gt($maxOfDate)) {
                    $numOfSchedule = false;
                }
                $diff = $maxOfDate->diffInDays($date_start) + 2;
            }
            if ($input['from_date'] != '' && $input['to_date'] != '') {
                $diff = date_diff(date_create($input['from_date']), date_create($input['to_date']));
                $diff = $diff->days;
                $diff = $diff + 1;
                $date_start = Carbon::createFromFormat('Y/m/d', $input['from_date']);
            }

            if ($input['to_date'] != '' && $input['from_date'] == '') {
                $date_end = Carbon::createFromFormat('Y/m/d', $input['to_date']);
                $minOfDate = $this->teacherRepository->getSmallestDateOfSchedule();
                $date_start = Carbon::parse($minOfDate[0]->min_date)->format('Y/m/d');
                $date_start = Carbon::createFromFormat('Y/m/d', $date_start);
                if ($date_end->lt($date_start)) {
                    $numOfSchedule = false;
                }
                $diff = $date_end->diffInDays($minOfDate[0]->min_date) + 1;
            }
        }
        $date = [];
        $schedules = [];
        $count = 0;
        for ($i = 0; $i < $diff; $i++) {
            $temp = [];
            if ($i != 0) {
                $date_start = $date_start->addDays(1);
            }
            $nextDate = $date_start->format('l,m,d,Y');
            $nextDate = explode(',', $nextDate);
            $temp['name'] = $this->getDayName($nextDate[0]);
            $temp['month'] = $nextDate[1];
            $temp['day'] = $nextDate[2];
            $temp['year'] = $nextDate[3];
            $temp['time'] = $date_start->format('Y-m-d');
            $searchDate = $date_start->format('Y-m-d');
            $strDate = $this->getStringDate($searchDate);
            $schedule = $this->teacherRepository->getScheduleData($strDate[0], $strDate[1]);
            if($check == true && empty($schedule)) {
                $temp['check_isset'] = false;
                $count ++;
            }
            else {
                $temp['check_isset'] = true;
            }
            array_push($date, $temp);
            array_push($schedules, $schedule);
        }
        if ($count == $diff) {
            $numOfSchedule = false;
        }
        $constant_time = config('constants.distance_time');
        $constant_time = $this->processTime($constant_time);
//        dd($schedules, $date, $time, $time_next, $constant_time);
        $date1=date_create($date[0]['time']);
        $date2=date_create($date[sizeof($date) - 1]['time']);
        $distance = date_diff($date1, $date2)->days + 1;

        $teacher_zoom = $this->teacherRepository->getTeacherZoomLink();
        return view('admin.teachers.listSchedule', compact('schedules', 'date', 'diff', 'time', 'time_next', 'constant_time', 'teacher_zoom', 'distance','numOfSchedule'));
    }

    public function getStringDate($date)
    {
        $first_date = $date . ' 00:00:00';
        $end_date = $date . ' 23:59:59';
        return array($first_date, $end_date);
    }

    public function processTime($string)
    {
        $tmp = explode(':', $string);
        return (int)($tmp[0] * 3600 + $tmp[1] * 60);

    }
}

