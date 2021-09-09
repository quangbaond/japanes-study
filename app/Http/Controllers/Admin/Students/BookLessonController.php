<?php


namespace App\Http\Controllers\Admin\Students;


use App\Http\Controllers\Controller;
use App\Http\Controllers\TimezoneController;
use App\Repositories\Admin\Managers\CalendarRepository;
use App\Repositories\Admin\PusherRepository;
use App\Repositories\Admin\students\StudentRepository;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use phpDocumentor\Reflection\Types\False_;
use Yajra\DataTables\DataTables;
use JamesMills\LaravelTimezone\Facades\Timezone;

class BookLessonController extends Controller
{
    protected $teacher_schedule;
    protected $studentRepository;
    protected $pusherRepository;
    protected $calendarRepository;
    protected $timezone;
    public function __construct(StudentRepository $studentRepository, PusherRepository $pusherRepository, CalendarRepository $calendarRepository, TimezoneController $timezone)
    {
        $this->studentRepository = $studentRepository;
        $this->pusherRepository = $pusherRepository;
        $this->calendarRepository = $calendarRepository;
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

    public function getTeacherSchedule($id)
    {
        if ($this->checkMemberShipStatus() == false) {
            return $this->responseError('error_premium_is_expired',__('validation_custom.M054'));
        }
        //get 7 days next, day name, and teacher schedule
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
        foreach ($schedules as $value) {
            $schedule_time['' . $value->id] = $value->start_hour;
        }

        $data = [
            'content' => View::make('admin.students.lessons.append.tableSchedule', compact('schedules', 'date'))->render(),
        ];

//        dd($schedule_time);
        Session::put('schedule_time', $schedule_time);
        return $this->responseSuccess($data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLessons(Request $request) {
        $input = $request->all();
        $course_id = $input['get_lesson_by_course'];
        $lessons = $this->studentRepository->getCourseInfoById($course_id);
        return $this->responseSuccess('lessons', $lessons);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStudentLesson(Request $request, $id) {
        $input = $request->all();

        $student_last_lesson = $this->studentRepository->getLessonCourseLearnLast($id);

        $latestLesson = $this->studentRepository->getLatestLesson();

        $current_student_lesson = $this->studentRepository->getCurrentLesson();
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

//    public function validateLesson(Request $request) {
//        $input = $request->all();
//
//        // Rule validation
//        $rules['course'] = 'required';
//        $rules['lesson'] = 'required';
////        dd($input['course']);
//        $attributes = [
//            'course' => __('student_book_lesson.course'),
//            'lesson' => __('student_book_lesson.lesson'),
//        ];
//
//        // Message validation
//        $message = [
//            'course.required'              => __('validation_custom.M001',['attribute'=>':attribute']),
//            'lesson.required'              => __('validation_custom.M001',['attribute'=>':attribute']),
//        ];
//
//        $validator = Validator::make($input, $rules, $message)->setAttributeNames($attributes);;
//
//        if ($validator->fails()) {
//            return $this->responseError('error', $validator->errors());
//        } else {
//            return $this->responseSuccess();
//        }
//    }

    /**
     * @param $input_schedules
     * @return array
     */
    public function getBookedScheduleOnScreen($input_schedules) {
        $schedules_id = [];
        //get data of schedule on booking screen
        foreach ($input_schedules as $key => $value) {
            if (!in_array($key, ['teacher_coin', 'teacher_id', 'student_total_coin', 'numOfSchedule', '_token', 'schedule_lesson'])) {
                foreach ($value as $schedule_id) {
                    array_push($schedules_id, (int)$schedule_id);
                }
            }
        }
        return $schedules_id;
    }

    /**
     * @return bool
     */
    public function checkMemberShipStatus() {
        $this->cancellingPremium();
        $student_membership_status = $this->studentRepository->getMemberStatus();
        if ($student_membership_status->membership_status == 1) {
            return false;
        }
        return true;
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
     * @param $id
     * @param Request $request
     */
    function validateBookLesson($id, Request $request)
    {
        if ($this->checkMemberShipStatus() == false) {
            return $this->responseError('error_premium_is_expired', __('validation_custom.M054'));
        }

        $input_schedules = $request->all();

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
                if($value->start_date == Carbon::parse($this->timezone->convertToLocal(Carbon::now() , "Y-m-d"))->addDay(7)->format('Y-m-d') || $value->start_date == Carbon::parse($this->timezone->convertToLocal(Carbon::now() , "Y-m-d"))->subDay(1)->format('Y-m-d'))
                    return null;
                return $value;
            });

        $database_booked_schedule_id = DB::table('booking')
            ->join('teacher_schedule','booking.teacher_schedule_id','=','teacher_schedule.id')
            ->select('teacher_schedule.*')
            ->where([['teacher_schedule.start_date','>=',Carbon::now()->format('Y:m:d')],
                ['teacher_schedule.start_date','<=', Carbon::now()->addDay(6)->format('Y:m:d')],
                ['teacher_schedule.status', '=', 2],
                ['booking.student_id', Auth::id()]])
            ->orderBy('teacher_schedule.start_date','ASC')
            ->orderBy('teacher_schedule.start_hour','ASC')
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
            if (!in_array( $value, $db_schedule_id)) {
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
                if ($key != '_token' && $key != 'numOfSchedule' && $key!= 'schedule_lesson' && in_array($schedule->id, $value) && !Carbon::parse($schedule->start_hour)->eq(Carbon::parse($schedule_time[$schedule->id]))) {
                    $row = array_search($schedule->start_date, $this->getNumberOfDay());
                    if($row != false) {
                        $updated_schedule_id['row'.$row.':'.$schedule->id. ':'. $schedule->start_date] = $schedule->start_hour;
                    }
                    $schedule_time[$schedule->id] = $schedule->start_hour;
                }
            }
        }

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
            if (!in_array($key, ['teacher_coin', 'teacher_id', 'student_total_coin', 'numOfSchedule', '_token', 'schedule_lesson'])) {
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
                if ($key != '_token' && $key != 'numOfSchedule' && $key!= 'schedule_lesson' && in_array($schedule->id, $value) && $schedule->status != 3) {
                    array_push($booked_schedule_id, $schedule->id);
                }
            }
        }

        if ($booked_schedule_id != []) {
            $booked_schedule_id['message'] = __('validation_custom.M041');
            return $this->responseError('error', $booked_schedule_id);
        }

        //compare time and now + 30'
        $today = $this->timezone->convertToLocal(Carbon::now(),'Y-m-d');
        foreach ($schedules as $schedule) {
            foreach ($input_schedules as $key => $value) {
                if ($key != '_token' && $key != 'numOfSchedule' && $key!= 'schedule_lesson' && in_array($schedule->id, $value) &&
                    $schedule->start_date == $today && !Carbon::parse($schedule->start_date.' '.$schedule->start_hour)->gte(Carbon::parse($this->timezone->convertToLocal(Carbon::now()->addMinute(30), 'Y-m-d H:i:00')))) {
                    array_push($booked_schedule_id, $schedule->id);
                }
            }
        }

        if ($booked_schedule_id != []) {
            $booked_schedule_id['message'] = __('validation_custom.M042');
            return $this->responseError('error', $booked_schedule_id);
        }

        //check membership_status = 6
        $student_membership_status = $this->studentRepository->getMemberStatus();
        if($student_membership_status->membership_status == 6) {
            $date_expire_premium = $this->studentRepository->getDateExpirePremium();
            $date_expire_premium = Carbon::parse($this->timezone->convertToLocal(Carbon::parse($date_expire_premium->premium_end_date)->subMinute(30),'Y-m-d H:i:s'));
            foreach ($schedules as $schedule) {
                foreach ($input_schedules as $key => $value) {
                    if ($key != '_token' && $key != 'numOfSchedule' && $key!= 'schedule_lesson' && in_array($schedule->id, $value) &&
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
     * @param $id
     * @param Request $request
     * @return
     */
    function validateCoinEnough($id, Request $request)
    {
        $input_schedules = $request->all();
        //get student total coins
        $student_coin = DB::table('student_total_coins')
            ->where('student_id', Auth::id())
            ->select('total_coin')
            ->first();
        if ($student_coin != null) {
            //get teacher_coin
            $coin = DB::table('teacher_coin')
                ->where('teacher_id', $id)
                ->first();
            $coin = $coin != null ? $coin->coin : 0;

            $input_schedules['numOfSchedule'] = (int)$input_schedules['numOfSchedule'];

            //check enough student_coin
            if ($student_coin->total_coin < $coin * $input_schedules['numOfSchedule']) {
                $message = __('validation_custom.M038');
                return $this->responseError('error', $message);
            }
            $input_schedules['teacher_coin'] = $coin;
            $input_schedules['teacher_id'] = (int)$id;
            $input_schedules['student_total_coin'] = (int)$student_coin->total_coin;

//            if($this->checkMemberShipStatus() == false) {
//                return $this->responseError('error_premium_is_expired', __('validation_custom.M054'));
//            }
            if ($this->insertBookingData($input_schedules)) {
                //get teacher zoom url
                $teacher_zoom_url = DB::table('users')
                    ->join('user_information', 'users.id','=', 'user_information.user_id')
                    ->select('user_information.link_zoom as link_zoom')
                    ->where('users.id', (int)$input_schedules['teacher_id'])
                    ->first()->link_zoom;
                $schedules_id = $this->getBookedScheduleOnScreen($input_schedules);

                $schedule_data = DB::table('teacher_schedule')
                    ->select('*')
                    ->whereIn('id',$schedules_id)
                    ->get();

                $users = DB::table('users')
                    ->select('users.email', 'users.id')
                    ->whereIn('id',[(int)$input_schedules['teacher_id'], Auth::id()])
                    ->get();

                $attendees = [];
                foreach ($users as $user) {
                    array_push($attendees, ['email' => $user->email, 'id'=> $user->id]);
                }

                $this->calendarRepository->createEvent($schedule_data, $teacher_zoom_url, $attendees);

//                $data = [
//                    'data' => __('validation_custom.M040',[],'ja'),
//                    'type' => 4, // Student book teacher lesson
//                ];
//                $channel = 'notification-student-notify-teacher';
//                $status = $this->pusherRepository->sendMessage($channel, (int)$id, $data);
//                return $status ? $this->responseSuccess($data) : $this->responseError();
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
                    'user_id' => Auth::id(),
                ];

                DB::table('receiver')->insert($data_receiver);

                $pusherBody['id'] = $notification_id_of_student;
                $pusherBody['title'] = $title_of_student;
                $pusherBody['content'] = $content_of_student;
                $pusherBody['created_at'] =  CarBon::now();
                $pusherBody['user_to'] = Auth::id();

                $channel = 'notification-user';
                $this->pusherRepository->sendNotify($channel, Auth::id(), $pusherBody);



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
                    'user_id' => $id,
                ];

                DB::table('receiver')->insert($data_receiver);
                $pusherBody['id'] = $notification_id_of_teacher;
                $pusherBody['title'] = $title_of_teacher;
                $pusherBody['content'] = $content_of_teacher;
                $pusherBody['user_to'] = $id;
                $this->pusherRepository->sendNotify($channel, $id, $pusherBody);

                return $this->responseSuccess();
            } else {
                return $this->responseError('error', "Connection Error");
            }
        } else {
            $message = __('validation_custom.M038');
            return $this->responseError('error', $message);
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
     */
    public function insertBookingData($input_schedules)
    {
        DB::beginTransaction();
        try {
            $schedules_id = $this->getBookedScheduleOnScreen($input_schedules);
            $schedules_lesson = $this->getScheduleLesson($input_schedules);

            foreach ($schedules_id as $value) {
                $data[] = [
                    'student_id' => Auth::id(),
                    'teacher_schedule_id' => $value,
                    'coin' => $input_schedules['teacher_coin'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $data_schedule_lesson [] =[
                    'student_id' => Auth::id(),
                    'teacher_schedule_id' => $value,
                    'lesson_id' => $schedules_lesson[$value],
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
//            dd($data , $schedules_lesson, $data_schedule_lesson);
            DB::table('booking')->insert($data);
            DB::table('student_courses')->insert($data_schedule_lesson);
            DB::table('teacher_schedule')
                ->whereIn('id', $schedules_id)
                ->update(['status' => 2, 'updated_at' => now()]);
            $count = sizeof($schedules_id);

            $used_coin = $input_schedules['teacher_coin'] * $count;
            DB::table('history_student_use_coin')
                ->insert([
                    'student_id' => Auth::id(),
                    'coin' => $used_coin,
                    'teacher_id' => (int)$input_schedules['teacher_id'],
                    'status' => 2,
                    'created_at' => now(),
                ]);
            DB::table('student_total_coins')
                ->where('student_id', Auth::id())
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

    public function toBookingList()
    {
        try {
            Session::forget('schedule_time');
            $success = __('validation_custom.M039');
            return \redirect()->route('student.lesson.list')->with('success', $success);
        } catch (\Exception $e) {
            dd(123);
        }
    }


    public function checkTimeRemove(Request $request) {
        $bookingId = $request->only('bookingId');
        if(!empty($bookingId)) {
            $status = $this->studentRepository->checkTimeRemove($bookingId['bookingId']);
            if(!$status) {
                return $this->responseSuccess([
                    'expired' => true
                ]);
            }
        }
        return $this->responseSuccess(
            [
                'expired' => false
            ]
        );
    }
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeBookingList(Request $request)
    {
        // Check cancelling premium for user
        $this->cancellingPremium();

        $input = $request->idBooking;
        // get teacher schedule
        $status = $this->studentRepository->removeBookingListById($input);
        return $status ? $this->responseSuccess( __('validation_custom.M044')) : $this->responseError();
    }

    /**
     * lesson history
     *
     * @return \Illuminate\View\View|Factory|View
     */
    public function lessonHistory()
    {
        // Check cancelling premium for user
        $this->cancellingPremium();

        $counter = $this->studentRepository->getLessonHistoryCounter();
        $student_email = DB::table('users')
            ->select('users.email as student_email')
            ->where('users.id', Auth::id())
            ->first();
        return view('admin.students.lessons.history', compact('counter','student_email'));
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getListHistoryDataTable()
    {
        $histories = $this->studentRepository->listHistory(Auth::id());
        //dd($histories);
        return Datatables::of($histories)
            ->addColumn('date_name', function ($history) {
                return Carbon::parse($history->date)->format('Y/m/d');
            })
            ->addColumn('btn_claim', function ($history) {
                if (!(isset($history->status) && $history->status == 2)) {
                    return '<button class="btn btn-danger btn-flat btn-sm" id="claim"> '. __('student.claim') .'</button>';
                }
            })
            ->addColumn('btn_review', function ($history) {
                if (!(isset($history->status) && $history->status == 2)) {
                    if($history->star == null){
                        $review = __('student.review') ;
                    }
                    else {
                        $review = __('student.see_review') ;
                    }
                    return '<button class="btn btn-warning btn-flat btn-sm mr-3" id="review">'.$review.'</button>';
                }
            })
            ->addColumn('history_status', function ($history) {
                if(isset($history->status) && $history->status == 2)
                    return '<span class="text-danger">'. __('student_lesson_history.not_attended') .'</span>';
                else {
                    return '<span class="">'. __('student_lesson_history.attended') .'</span>';
                }
            })
            ->rawColumns(['date_name','btn_claim', 'btn_review', 'history_status'])
            ->make(true);
    }
    public function checkHistories(Request $request)
    {
        $lesson = DB::table('lesson_histories')
            ->select('teacher_review.*' , 'lesson_histories.*')
            ->where('lesson_histories.student_id' , Auth::id())
            ->leftJoin('teacher_review' , 'teacher_review.lesson_histories_id' , '=' , 'lesson_histories.id')
            ->orderBy('lesson_histories.created_at' , 'desc')
            ->first();
        if(!empty($lesson)){
            $start_lesson = Timezone::convertToLocal(Carbon::parse($lesson->created_at)->addMinute(25) , 'Y-m-d H:i:s');
            if($lesson->star == null && $start_lesson <= Timezone::convertToLocal(Carbon::now() ,"Y-m-d H:i:s") && $request->id_lesson != $lesson->id){
                return $this->responseSuccess(($lesson->id));
            }
            else {
                return $this->responseError();
            }
        }
    }
    public function  reviewLesson(Request $request)
    {
        $input = $request->all();
        $rules = [];
        ($input['star'] != '' && $input['comment'] != '') ? $rules['star'] = 'min:1,max:5' : $rules['comment'] = 'max:500';
        $message = [
            'star.min'  => __('validation_custom.M010'),
            'star.max'  => __('validation_custom.M010'),
            'comment.max' =>  __('validation_custom.M010')
        ];
        $validator = Validator::make($input, $rules, $message);
        if ($validator->fails()) {
            return back()->withError(__('validation_custom.M001'));
        }
        $lesson = DB::table('lesson_histories')
            ->select('teacher_review.comment' ,'teacher_review.star' , 'teacher_review.id as id_teacher_review' , 'lesson_histories.*')
            ->where('lesson_histories.student_id' , Auth::id())
            ->where('lesson_histories.id' , $request->id_lesson)
            ->leftJoin('teacher_review' , 'teacher_review.lesson_histories_id' , '=' , 'lesson_histories.id')
            ->orderBy('lesson_histories.created_at' , 'desc')
            ->first();
        $lesson->created_at = Timezone::convertToLocal(Carbon::parse($lesson->created_at)->addMinute(25) , 'Y-m-d H:i:s');
        if($lesson->star == null && $lesson->created_at <= Timezone::convertToLocal(Carbon::now() ,"Y-m-d H:i:s")){
            DB::table('teacher_review')
                ->insert([
                    'teacher_id' => $lesson->teacher_id,
                    'student_id' => Auth::id(),
                    'lesson_histories_id' => $lesson->id,
                    'star' => $request->star,
                    'comment' => $request->comment,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ]);
        }
        else {
            if($lesson->id_teacher_review !== null){
                DB::table('teacher_review')
                    ->where('id' , $lesson->id_teacher_review)
                    ->update([
                        'star' => $request->star,
                        'comment' => $request->comment,
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    ]);
            }
        }
        return back()->withSuccess(__('validation_custom.M069'));
    }

}
