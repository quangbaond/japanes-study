<?php


namespace App\Http\Controllers\Admin\Managers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TimezoneController;
use App\Repositories\Admin\Managers\CalendarRepository;
use App\Repositories\Admin\Managers\StudentRepository;
use App\Repositories\Admin\Managers\TeacherRepository;
use App\Repositories\Admin\PusherRepository;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class TeacherBookingSubstituteController extends Controller
{
    protected $teacher_schedule;
    protected $studentRepository;
    protected $pusherRepository;
    protected $teacherRepository;
    protected $calendarRepository;
    protected $arrayKey;
    protected $timezone;
    public function __construct(StudentRepository $studentRepository, PusherRepository $pusherRepository, TeacherRepository $teacherRepository, CalendarRepository $calendarRepository, TimezoneController $timezone)
    {
        $this->studentRepository = $studentRepository;
        $this->pusherRepository = $pusherRepository;
        $this->teacherRepository = $teacherRepository;
        $this->calendarRepository = $calendarRepository;
        $this->arrayKey = ['teacher_coin', 'teacher_id', 'student_total_coin', '_token', 'student_id', 'student_membership','schedule_lesson'];
        $this->timezone = $timezone;
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
     * Get student last lesson
     *
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function getStudentLesson(Request $request, $id) {
        $input = $request->all();

        $latestLesson = $this->studentRepository->getLatestLesson();

        $current_student_lesson = $this->studentRepository->getCurrentLesson($input['student_id']);

        $student_last_lesson = $this->studentRepository->getLessonCourseLearnLast($input['student_id'], $id);
        if ($student_last_lesson == null) {
            $checkTeacherCanTeach = false;
        }
        else {
            $checkTeacherCanTeach = DB::table('course_can_teach')
                ->select('course_can_teach.course_id')
                ->where('course_can_teach.course_id' , '=', $student_last_lesson->course_id)
                ->where('course_can_teach.teacher_id','=',$id)
                ->first();
            $checkTeacherCanTeach = !empty($checkTeacherCanTeach);
            if (!empty($current_student_lesson) && $current_student_lesson->lesson_id == $latestLesson->id) {
                $checkTeacherCanTeach = false;
            }
        }

        if($checkTeacherCanTeach) {
            $lessons = DB::table('lessons')
                ->select('*')
                ->where('lessons.course_id','=', $student_last_lesson->course_id)
                ->get();
        }
        else {
            $lessons = [];
        }
        $student_lesson_info['last_lesson'] = $student_last_lesson;
        $student_lesson_info['check_teacher_can_teach'] = $checkTeacherCanTeach;
        $student_lesson_info['lessons'] = $lessons;
        $student_lesson_info['check_latest_lesson'] = !empty($current_student_lesson) && $current_student_lesson->lesson_id == $latestLesson->id;
        return $this->responseSuccess('last_lesson', $student_lesson_info);
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    public function bookingSubstitute($id)
    {
        $check = DB::table('users')
            ->select('users.id')
            ->where('users.id','=', $id)
            ->where('users.auth','=', 1)
            ->where('users.role','=', config('constants.role.teacher'))
            ->first();

        if (empty($check)) {
            abort(404);
        }

        $date = [];
        $date_temp = [];
        $schedule_time = [];
        for ($i = 0; $i < 7; $i++) {
            $temp = [];
            $nextDate = $this->timezone->convertToLocal(Carbon::now()->addDays($i),'l,m,d,Y');
            $nextDate = explode(',', $nextDate);
            $temp['name'] = $this->getDayName($nextDate[0]);
            $temp['month'] = $nextDate[1];
            $temp['day'] = $nextDate[2];
            $temp['year'] = $nextDate[3];
            $temp['full'] = $nextDate[3] . '-' . $nextDate[1] . '-' . $nextDate[2];
            array_push($date, $temp);
            $nextDate = $this->timezone->convertToLocal(Carbon::now()->addDays($i),'Y-m-d');
            array_push($date_temp, $nextDate);
        }

        $schedules = DB::table('teacher_schedule')
            ->where('teacher_id', $id)
            ->whereRaw("CONCAT(teacher_schedule.start_date, ' ',teacher_schedule.start_hour) >= ?", [ Carbon::now()->subDay(1)->format('Y-m-d').' 00:00:00' ])
            ->whereRaw("CONCAT(teacher_schedule.start_date, ' ',teacher_schedule.start_hour) <= ?", [ Carbon::now()->addDay(7)->format('Y-m-d').' 23:59:00' ])
            ->select('teacher_schedule.*')
            ->orderBy('start_date', 'ASC')
            ->orderBy('start_hour', 'ASC')
            ->get()
            ->filter(function ($value) {
                $date = Carbon::parse($value->start_date.' '.$value->start_hour);
                $value->start_hour = $this->timezone->convertToLocal($date, "H:i");
                $value->start_date = $this->timezone->convertToLocal($date , "Y-m-d");
                if($value->start_date == Carbon::parse($this->timezone->convertToLocal(Carbon::now() , "Y-m-d"))->addDay(7)->format('Y-m-d') || $value->start_date == Carbon::parse($this->timezone->convertToLocal(Carbon::now() , "Y-m-d"))->subDay(1)->format('Y-m-d'))
                    return null;
                return $value;
            })
            ->toArray();

        //check day is have schedule
        $schedule_date = [];
        foreach ($schedules as $value) {
            $schedule_time['' . $value->id] = $value->start_hour;
            if (!in_array($value->start_date, $schedule_date)) {
                array_push($schedule_date, $value->start_date);
            }
        }

        for ($i = 0; $i < 7; $i++) {
            if (in_array($date[$i]['full'], $schedule_date)) {
                $date[$i]['check_exist_schedule'] = true;
            } else {
                $date[$i]['check_exist_schedule'] = false;
            }
        }
//        dd($schedules);
        $teacher_courses = $this->teacherRepository->getTeacherCourses($id);
        $companies = DB::table('company')->get();
        Session::put('schedule_time', $schedule_time);
        $teacher = $this->teacherRepository->teacherDetail($id);
        return view('admin.managers.teachers.bookingSubstitute', compact('schedules', 'date', 'teacher', 'companies', 'teacher_courses'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function getLessons(Request $request, $id) {
        $input = $request->all();
        $course_id = $input['get_lesson_by_course'];
        $lessons = $this->teacherRepository->getCourseInfoById($course_id, $id);
        return $this->responseSuccess('lessons', $lessons);
    }

    public function studentDataTable() {
        $students = $this->studentRepository->studentBookingSubstituteDataTable();
//        dd($students);
        return DataTables::of($students)
            ->addColumn('checkbox', function ($student) {
                return '<input type="radio" class="chk_item" id="student_id-' . $student->id . '" name="user_id" value="' . $student->membership_status . '"/>';
            })
            ->rawColumns(['checkbox'])
            ->make(true);
    }

    /**
     * Validation students.
     * @param Request $request
     * @return JsonResponse
     */
    public function studentValidation(Request $request)
    {
        $input = $request->all();

        //Rule validation
        $rules = [];
        $input['student_id'] != '' ? $rules['student_id'] = 'integer' : $rules['student_id'] = '';

        // Set name for field
        $attributes = array(
            'student_id' => 'ID',
        );

        //Message validation
        $message = [
            'student_id.integer' => ':attribute' . config('validation.integer'),
        ];

        $validator = Validator::make($input, $rules, $message)->setAttributeNames($attributes);

        if ($validator->fails()) {
            return $this->responseError(null, $validator->errors());
        } else {
            return $this->responseSuccess();
        }
    }

    /**
     * @param $id
     * @return bool
     */
    public function checkMemberShipStatus($id) {
        $this->cancellingPremiumWithStudentId($id);
        $student_membership_status = $this->studentRepository->getMemberStatus($id);
        if ($student_membership_status->membership_status == 1) {
            return false;
        }
        return true;
    }

    /**
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
    public function validateBookLesson($id, Request $request)
    {
        $input_schedules = $request->all();

        if ($this->checkMemberShipStatus((int)$input_schedules['student_id']) == false) {
            return $this->responseError('error_premium_is_expired', __('validation_custom.M054'));
        }

        $teacher_id = $id;
        $schedules = DB::table('teacher_schedule')
            ->select('*')
            ->where([['teacher_id', $teacher_id],
                ['start_date', '>=', Carbon::now()->subDay(1)->format('Y-m-d')],
                ['start_date', '<=', Carbon::now()->addDay(7)->format('Y-m-d')]])
            ->orderBy('start_date')
            ->get()
            ->filter(function ($value) {
                $date = Carbon::parse($value->start_date.' '.$value->start_hour);
                $value->start_hour = $this->timezone->convertToLocal($date, "H:i");
                $value->start_date = $this->timezone->convertToLocal($date , "Y-m-d");
                if($value->start_date == Carbon::parse($this->timezone->convertToLocal(Carbon::now() , 'Y-m-d'))->addDay(7)->format('Y-m-d') || $value->start_date == Carbon::parse($this->timezone->convertToLocal(Carbon::now() , "Y-m-d"))->subDay(1)->format('Y-m-d'))
                    return null;
                return $value;
            });
        $database_booked_schedule_id = DB::table('booking')
            ->join('teacher_schedule', 'booking.teacher_schedule_id', '=', 'teacher_schedule.id')
            ->select('teacher_schedule.*')
            ->where([['teacher_schedule.start_date','>=',Carbon::now()->format('Y-m-d')],
                ['teacher_schedule.start_date', '<=', Carbon::today()->addDay(6)->format('Y-m-d')],
                ['teacher_schedule.status','=', 2],
                ['booking.student_id', (int)$input_schedules['student_id']]])
            ->orderBy('teacher_schedule.start_date', 'ASC')
            ->orderBy('teacher_schedule.start_hour', 'ASC')
            ->get()
            ->filter(function ($value) {
                $date = Carbon::parse($value->start_date.' '.$value->start_hour);
                $value->start_hour = $this->timezone->convertToLocal($date, "H:i");
                $value->start_date = $this->timezone->convertToLocal($date , "Y-m-d");
                if($value->start_date == Carbon::parse($this->timezone->convertToLocal(Carbon::now() , "Y-m-d"))->addDay(7)->format('Y-m-d') || $value->start_date == Carbon::parse($this->timezone->convertToLocal(Carbon::now() , "Y-m-d"))->subDay(1)->format('Y-m-d'))
                    return null;
                return $value;
            });

        //check with database (schedule delete)
        $db_schedule_id = [];
        foreach ($schedules as $schedule) {
            array_push($db_schedule_id, $schedule->id);
        }

        $schedules_id = $this->getBookedScheduleOnScreen($input_schedules);
        //check exist in array
        $booked_schedule_id = [];
        foreach ($schedules_id as $value) {
            if (!in_array($value, $db_schedule_id)) {
                array_push($booked_schedule_id, $value);
            }
        }

        if ($booked_schedule_id != []) {
            $booked_schedule_id['message'] = __('validation_custom.M045');
            return $this->responseError('error', $booked_schedule_id);
        }

        //check with database (schedule update start_hour)
        $schedule_time = Session::get('schedule_time');
        $updated_schedule_id = [];
        foreach ($schedules as $schedule) {
            foreach ($input_schedules as $key => $value) {
                if (!in_array($key, $this->arrayKey) && in_array($schedule->id, $value) && !Carbon::parse($schedule->start_hour)->eq(Carbon::parse($schedule_time[$schedule->id]))) {
                    $row = array_search($schedule->start_date, $this->getNumberOfDay());
                    if($row != false) {
                        $updated_schedule_id['row'.$row.':'.$schedule->id. ':'. $schedule->start_date] = $schedule->start_hour;
                    }
                    $schedule_time[$schedule->id] = $schedule->start_hour;
                }
            }
        }

//        dd($updated_schedule_id);
        Session::forget('schedule_time');
        Session::put('schedule_time', $schedule_time);

        if ($updated_schedule_id != []) {
            $updated_schedule_id['message'] = __('validation_custom.M045');
            return $this->responseError('updated', $updated_schedule_id);
        }

        //check same schedule with another teacher
        $schedules_id = [];
        //get data of schedule on booking screen
        foreach ($input_schedules as $key => $value) {
            if (!in_array($key, $this->arrayKey)) {
                foreach ($value as $schedule_id) {
                    array_push($schedules_id, (int)$schedule_id);
                }
            }
        }
        $data = DB::table('teacher_schedule')
            ->select('*')
            ->whereIn('id', $schedules_id)
            ->get()
            ->filter(function ($value) {
                $date = Carbon::parse($value->start_date.' '.$value->start_hour);
                $value->start_hour = $this->timezone->convertToLocal($date, "H:i");
                $value->start_date = $this->timezone->convertToLocal($date , "Y-m-d");
                if($value->start_date == Carbon::parse($this->timezone->convertToLocal(Carbon::now() , "Y-m-d"))->addDay(7)->format('Y-m-d') || $value->start_date == Carbon::parse($this->timezone->convertToLocal(Carbon::now() , "Y-m-d"))->subDay(1)->format('Y-m-d'))
                    return null;
                return $value;
            });

        $booked_schedule_id = [];
        foreach ($data as $schedule) {
            $booking = Carbon::parse($schedule->start_date .' '. $schedule->start_hour);
            foreach ($database_booked_schedule_id as $database_schedule) {
                $booked = Carbon::parse($database_schedule->start_date . ' '. $database_schedule->start_hour);
                $checkGreaterThan = $booking->gte($booked) && $booking->gte($booked->addMinutes(config('constants.distance_time_minute')));
                $checkLessThan = $booking->lte($booked) && $booking->lte($booked->subMinutes(config('constants.distance_time_minute')));
                if (!($checkGreaterThan || $checkLessThan)) {
                    if (!in_array($schedule->id, $booked_schedule_id, true))
                        array_push($booked_schedule_id, $schedule->id);
                }
            }
        }

        if ($booked_schedule_id != []) {
            $booked_schedule_id['message'] = __('validation_custom.M029');
            return $this->responseError('error', $booked_schedule_id);
        }

        //check status
        foreach ($schedules as $schedule) {
            foreach ($input_schedules as $key => $value) {
                if (!in_array($key, $this->arrayKey) && in_array($schedule->id, $value) && $schedule->status != 3) {
                    array_push($booked_schedule_id, $schedule->id);
                }
            }
        }

        if ($booked_schedule_id != []) {
            $booked_schedule_id['message'] = __('validation_custom.M041');
            return $this->responseError('error', $booked_schedule_id);
        }

        //compare time and now + 30'
        $today = Carbon::parse($this->timezone->convertToLocal(Carbon::now(), 'Y-m-d H:i:00'))->format('Y-m-d');
        foreach ($schedules as $schedule) {
            foreach ($input_schedules as $key => $value) {
//                dd($schedules);
//                dd(Carbon::parse($schedule->start_date .' ' . $schedule->start_hour)->gte(Carbon::parse($this->timezone->convertToLocal(Carbon::now()->addMinute(30), 'Y-m-d H:i:00'))));
//                && $schedule->start_date == $today
                if (!in_array($key, $this->arrayKey) && in_array($schedule->id, $value) && $schedule->start_date == $today && !Carbon::parse($schedule->start_hour)->gte(Carbon::parse($this->timezone->convertToLocal(Carbon::now()->addMinute(30), 'Y-m-d H:i:00')))) {
                    array_push($booked_schedule_id, $schedule->id);
                }
            }
        }

        if ($booked_schedule_id != []) {
            $booked_schedule_id['message'] = __('validation_custom.M042');
            return $this->responseError('error', $booked_schedule_id);
        }

        //check membership_status = 6
        $student_membership_status = $this->studentRepository->getMemberStatus((int)$input_schedules['student_id']);
        if($student_membership_status->membership_status == 6) {
            $date_expire_premium = $this->studentRepository->getDateExpirePremium((int)$input_schedules['student_id']);
            $date_expire_premium = Carbon::parse($this->timezone->convertToLocal(Carbon::parse($date_expire_premium->premium_end_date)->subMinute(30),'Y-m-d H:i:s'));
            foreach ($schedules as $schedule) {
                foreach ($input_schedules as $key => $value) {
                    if (!in_array($key, $this->arrayKey) && in_array($schedule->id, $value) &&
                        Carbon::parse($schedule->start_date.' '.$schedule->start_hour)->gte($date_expire_premium)) {
                        array_push($booked_schedule_id, $schedule->id);
                    }
                }
            }

            if ($booked_schedule_id != []) {
                $booked_schedule_id['message'] = __('validation_custom.M062');
                return $this->responseError('error', $booked_schedule_id);
            }
        }
        return $this->responseSuccess();
    }

    /**
     * @return array
     */
    public function getNumberOfDay() {
        $date = [];
        for ($i = 0; $i < 7; $i++) {
            $nextDate = $this->timezone->convertToLocal(Carbon::now()->addDays($i),'Y-m-d');
            array_push($date, $nextDate);
        }
        return $date;
    }

    /**
     * @param $input_schedules
     * @return array
     */
    public function getBookedScheduleOnScreen($input_schedules)
    {
        $schedules_id = [];
        //get data of schedule on booking screen
        foreach ($input_schedules as $key => $value) {
            if (!in_array($key, $this->arrayKey)) {
                foreach ($value as $schedule_id) {
                    array_push($schedules_id, (int)$schedule_id);
                }
            }
        }
        return $schedules_id;
    }

    /**
     * @param $id
     * @param Request $request
     * @return JsonResponse
     * @throws \Pusher\PusherException
     */
    function validateCoinEnough($id, Request $request)
    {
        $input_schedules = $request->all();
        $student_membership = DB::table('users')
            ->join('user_information', 'users.id', '=', 'user_information.user_id')
            ->select('user_information.membership_status')
            ->where('users.id', $input_schedules['student_id'])
            ->first();
        if (in_array((int)$student_membership->membership_status, [2, 3, 6])) {
            $student_coin = DB::table('student_total_coins')
                ->where('student_id', (int)$input_schedules['student_id'])
                ->select('total_coin')
                ->first();

            if ($student_coin != null) {
                //get teacher_coin
                $coin = DB::table('teacher_coin')
                    ->where('teacher_id', $id)
                    ->first();
                $coin = $coin != null ? $coin->coin : 0;
                $schedules_id = $this->getBookedScheduleOnScreen($input_schedules);
                if($schedules_id == []) {
                    return $this->responseError('empty');
                }
                $count = sizeof($schedules_id);
                //check enough student_coin
                if ($student_coin->total_coin < $coin * $count) {
                    $message = __('validation_custom.M038');
                    return $this->responseError('lackOfCoin', $message);
                }
                $input_schedules['teacher_coin'] = $coin;
                $input_schedules['teacher_id'] = (int)$id;
                $input_schedules['student_total_coin'] = (int)$student_coin->total_coin;

//                if ($this->checkMemberShipStatus((int)$input_schedules['student_id']) == false) {
//                    return $this->responseError('error_premium_is_expired', __('validation_custom.M054'));
//                }

                if ($this->insertBookingDataWithCoin($input_schedules)) {
                    //get teacher zoom url
                    $teacher_zoom_url = DB::table('users')
                        ->join('user_information', 'users.id', '=', 'user_information.user_id')
                        ->select('user_information.link_zoom as link_zoom')
                        ->where('users.id', (int)$input_schedules['teacher_id'])
                        ->first()->link_zoom;
                    $schedules_id = $this->getBookedScheduleOnScreen($input_schedules);

                    $schedule_data = DB::table('teacher_schedule')
                        ->select('*')
                        ->whereIn('id', $schedules_id)
                        ->get();

                    $users = DB::table('users')
                        ->select('users.email', 'users.id')
                        ->whereIn('id', [(int)$input_schedules['teacher_id'], (int)$input_schedules['student_id']])
                        ->get();

                    $attendees = [];
                    foreach ($users as $user) {
                        array_push($attendees, ['email' => $user->email, 'id'=> $user->id]);
                    }

                    $this->calendarRepository->createEvent($schedule_data, $teacher_zoom_url, $attendees);
                    $status = $this->insertNotifications($input_schedules);

//                    $data = [
//                        'type' => 1, // admin notify student
//                    ];
//                    $channel = 'notification-admin-notify';
//                    $status = $this->pusherRepository->sendMessage($channel, (int)$input_schedules['student_id'], $data);
//                    if ($status == false) {
//                        return $this->responseError();
//                    }
//                    $data = [
//                        'type' => 2, // admin notify teacher
//                    ];
//                    $channel = 'notification-admin-notify';
//                    $status = $this->pusherRepository->sendMessage($channel, (int)$id, $data);
//                    return $status ? $this->responseSuccess($data) : $this->responseError();
                    return $this->responseSuccess();
                }
            }
        } else {
            $input_schedules['teacher_id'] = (int)$id;

//            if ($this->checkMemberShipStatus((int)$input_schedules['student_id']) == false) {
//                return $this->responseError('error_premium_is_expired', __('validation_custom.M054'));
//            }

            if ($this->insertBookingDataWithoutCoin($input_schedules)) {
                //get teacher zoom url
                $teacher_zoom_url = DB::table('users')
                    ->join('user_information', 'users.id', '=', 'user_information.user_id')
                    ->select('user_information.link_zoom as link_zoom')
                    ->where('users.id', (int)$id)
                    ->first()->link_zoom;
                $schedules_id = $this->getBookedScheduleOnScreen($input_schedules);
                if($schedules_id == []) {
                    return $this->responseError('empty');
                }
                $schedule_data = DB::table('teacher_schedule')
                    ->select('*')
                    ->whereIn('id', $schedules_id)
                    ->get();

                $users = DB::table('users')
                    ->select('users.email', 'users.id')
                    ->whereIn('id', [(int)$id, (int)$input_schedules['student_id']])
                    ->get();

                $attendees = [];
                foreach ($users as $user) {
                    array_push($attendees, ['email' => $user->email, 'id'=> $user->id]);
                }

                $status = $this->insertNotifications($input_schedules);
                $this->calendarRepository->createEvent($schedule_data, $teacher_zoom_url, $attendees);

//                $data = [
//                    'type' => 1, // admin notify student
//                ];
//                $channel = 'notification-admin-notify';
//                $status = $this->pusherRepository->sendMessage($channel, (int)$input_schedules['student_id'], $data);
//                if ($status == false) {
//                    return $this->responseError();
//                }
//                $data = [
//                    'type' => 2, // admin notify teacher
//                ];
//                $channel = 'notification-admin-notify';
//                $status = $this->pusherRepository->sendMessage($channel, (int)$id, $data);
//                return $status ? $this->responseSuccess($data) : $this->responseError();
                return $this->responseSuccess();
            }
        }
        $message = __('validation_custom.M038');
        return $this->responseError('lackOfCoin', $message);
    }


    /**
     * @param $input_schedules
     * @return bool
     */
    public function insertNotifications($input_schedules)
    {
        DB::beginTransaction();
        try {
//            $language = config('language');
//            $language = array_keys($language);
//            $title = '';
//            $content = '';
//            foreach ($language as $value) {
//                switch ($value) {
//                    case 'JA':
//                        $title .= 'Japanese: ';
//                        $content .= 'Japanese: ';
//                        break;
//                    case 'VI':
//                        $title .= 'Vietnamese: ';
//                        $content .= 'Vietnamese: ';
//                        break;
//                    case 'EN':
//                        $title .= 'English: ';
//                        $content .= 'English: ';
//                        break;
//                }
//                $title .= __('validation_custom.M040.title', [], strtolower($value)) . '&#13;&#10;';
//                $content .= __('validation_custom.M040.content', [], strtolower($value)) . '&#13;&#10;';
//            }

            //for student
            //insert to notification table
            $title_of_student = trans('validation_custom.M040.title', [], 'vi') . '/ ' . trans('validation_custom.M040.title', [], 'en');
            $content_of_student = trans('validation_custom.M040.content', ['attribute' => route('student.lesson.list')], 'vi') . '<br><br>' . trans('validation_custom.M040.content', ['attribute' => route('student.lesson.list')], 'en');
            $data_of_student = [
                'title' => $title_of_student,
                'content' => $content_of_student,
                'receiver_class' => 4,
                'created_by' => Auth::id(),
                'created_at' => now(),
                'updated_at' => now()
            ];

            $notification_id_of_student = DB::table('notifications')->insertGetId($data_of_student);

            //insert receiver table
            $data_receiver[] = [
                'notification_id' => $notification_id_of_student,
                'user_id' => (int)$input_schedules['student_id'],
            ];

            DB::table('receiver')->insert($data_receiver);

            $pusherBody['id'] = $notification_id_of_student;
            $pusherBody['title'] = $title_of_student;
            $pusherBody['content'] = $content_of_student;
            $pusherBody['created_at'] =  CarBon::now();
            $pusherBody['user_to'] = (int)$input_schedules['student_id'];

            $channel = 'notification-user';
            $this->pusherRepository->sendNotify($channel, $pusherBody['user_to'], $pusherBody);



            //for teacher
            //insert to notification table
            $title_of_teacher = trans('validation_custom.M040.title', [], 'ja');
            $content_of_teacher = trans('validation_custom.M040.content', ['attribute' => route('teacher.my-page')], 'ja');
            $data_of_teacher = [
                'title' => $title_of_teacher,
                'content' => $content_of_teacher,
                'receiver_class' => 4,
                'created_by' => Auth::id(),
                'created_at' => now(),
                'updated_at' => now()
            ];

            $notification_id_of_teacher = DB::table('notifications')->insertGetId($data_of_teacher);

            //insert receiver table

            $data_receiver[] = [
                'notification_id' => $notification_id_of_teacher,
                'user_id' => (int)$input_schedules['teacher_id'],
            ];

            DB::table('receiver')->insert($data_receiver);
            $pusherBody['id'] = $notification_id_of_teacher;
            $pusherBody['title'] = $title_of_teacher;
            $pusherBody['content'] = $content_of_teacher;
            $pusherBody['user_to'] = (int)$input_schedules['teacher_id'];
            $this->pusherRepository->sendNotify($channel, $pusherBody['user_to'], $pusherBody);

            DB::commit();
            return true;
        } catch (\Exception $e) {
//            dd(123);
            DB::rollback();
            return false;
        }
    }

    public function getScheduleLesson($input_schedules) {
//        dd($input_schedules);
        $schedules_id = [];
        //get data of schedule on booking screen
        foreach ($input_schedules['schedule_lesson'] as $key => $value) {
            $array = explode(':', $value);
            $schedules_id[$array[0]] = (int)$array[1];
        }
        return $schedules_id;
    }
    /**
     * @param $input_schedules
     * @return bool
     */
    public function insertBookingDataWithCoin($input_schedules)
    {

        DB::beginTransaction();
        try {
            $schedules_id = $this->getBookedScheduleOnScreen($input_schedules);
            $schedules_lesson = $this->getScheduleLesson($input_schedules);
//            dd($schedules_lesson);
            foreach ($schedules_id as $value) {
                $data[] = [
                    'student_id' => (int)$input_schedules['student_id'],
                    'teacher_schedule_id' => $value,
                    'coin' => $input_schedules['teacher_coin'],
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                $data_schedule_lesson [] =[
                    'student_id' => (int)$input_schedules['student_id'],
                    'teacher_schedule_id' => $value,
                    'lesson_id' => $schedules_lesson[$value],
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            DB::table('booking')->insert($data);
            DB::table('student_courses')->insert($data_schedule_lesson);
            DB::table('teacher_schedule')
                ->whereIn('id', $schedules_id)
                ->update(['status' => 2, 'updated_at' => now()]);
            $count = sizeof($schedules_id);

            $used_coin = $input_schedules['teacher_coin'] * $count;
            DB::table('history_student_use_coin')
                ->insert([
                    'student_id' => (int)$input_schedules['student_id'],
                    'coin' => $used_coin,
                    'teacher_id' => (int)$input_schedules['teacher_id'],
                    'status' => 2,
                    'created_at' => now(),
                ]);
            DB::table('student_total_coins')
                ->where('student_id', (int)$input_schedules['student_id'])
                ->update([
                    'total_coin' => ($input_schedules['student_total_coin'] - $used_coin),
                    'updated_at' => now()
                ]);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    /**
     * @param $input_schedules
     * @return bool
     */
    public function insertBookingDataWithoutCoin($input_schedules)
    {
        DB::beginTransaction();
        try {
            $schedules_id = $this->getBookedScheduleOnScreen($input_schedules);
            $schedules_lesson = $this->getScheduleLesson($input_schedules);

            foreach ($schedules_id as $value) {
                $data[] = [
                    'student_id' => (int)$input_schedules['student_id'],
                    'teacher_schedule_id' => $value,
                    'coin' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $data_schedule_lesson [] =[
                    'student_id' => (int)$input_schedules['student_id'],
                    'teacher_schedule_id' => $value,
                    'lesson_id' => $schedules_lesson[$value],
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            DB::table('booking')->insert($data);
            DB::table('student_courses')->insert($data_schedule_lesson);
            DB::table('teacher_schedule')
                ->whereIn('id', $schedules_id)
                ->update(['status' => 2, 'updated_at' => now()]);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    public function toAdminBookingList()
    {
        try {
            Session::forget('schedule_time');
            $success = __('validation_custom.M039');
            return \redirect()->route('admin.booking-list')->with('success', $success);
        } catch (\Exception $e) {
            dd(123);
        }
    }
}
