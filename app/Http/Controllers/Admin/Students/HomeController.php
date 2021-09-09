<?php

namespace App\Http\Controllers\Admin\Students;

use App\Helpers\Helper;
use App\Http\Controllers\TimezoneController;
use App\Repositories\Admin\PusherRepository;
use App\Repositories\Admin\students\StudentRepository;
use App\Repositories\Admin\teachers\TeacherRepository;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use MacsiDigital\Zoom\Facades\Zoom;
use Yajra\DataTables\Facades\DataTables;
use Timezone;

class HomeController extends Controller
{
    protected $studentRepository, $pusherRepository, $teacherRepository, $stripe, $timezone;

    /**
     * Create a new controller instance.
     *
     * @param StudentRepository $studentRepository
     * @param PusherRepository $pusherRepository
     * @param TeacherRepository $teacherRepository
     * @param TimezoneController $timezone
     */
    public function __construct(
        StudentRepository $studentRepository,
        PusherRepository $pusherRepository,
        TeacherRepository $teacherRepository,
        TimezoneController $timezone
    )
    {
        $this->studentRepository = $studentRepository;
        $this->pusherRepository = $pusherRepository;
        $this->teacherRepository = $teacherRepository;
        $this->stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
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
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index()
    {
        // Check cancelling premium for user
        $this->cancellingPremium();

        $date = [];
        $date_time = [];

        for ($i = 0; $i < 7; $i++) {
            $temp = [];
            $nextDate = Carbon::parse(Timezone::convertToLocal(Carbon::parse(Carbon::today()->addDays($i)), 'Y-m-d H:i:s'))->format('l,m,d,Y');
            $nextDate = explode(',', $nextDate);
            $temp['name'] = $this->getDayName($nextDate[0]);
            $temp['month'] = $nextDate[1];
            $temp['day'] = $nextDate[2];
            $temp['year'] = $nextDate[3];
            array_push($date, $temp);
        }
        $nationality = config('nation');

        //$teacher = $this->studentRepo->getRandomTeacher();

        // Get teacher random
        $teacher = $this->studentRepository->getRandomTeacherChoice();
        // Lesson last of student
//        $lesson_learn_last = $this->studentRepository->getLessonLearnLast();
        $lesson_learn_last = [];
        if(!is_null($teacher)) {
            $lesson_learn_last = $this->studentRepository->getLessonCourseLearnLast($teacher->teacher_id);
        }
//        dd($lesson_learn_last);
        // Get count lesson of user
        $countSuddenLesson = $this->studentRepository->getCountSuddenLesson();

        // Get member status of user
        $membership_status = $this->studentRepository->getMemberStatus();
        $courses = DB::table('course')->get();
        $teacher_lessons = $this->studentRepository->getTeacherLessons();
        $teacher_lessons = collect($teacher_lessons)->chunk(6);

        $teachers = $this->studentRepository->studentSearch([]);

        $count = $teachers[0];

        $teachers = $teachers[1];
        return view('admin.students.home', [
            'nationality'       => $nationality,
            'teacher'           => $teacher,
            'countSuddenLesson' => $countSuddenLesson,
            'membership_status' => $membership_status,
            'courses'           => $courses,
            'teachers'          => $teachers,
            'count'             => $count,
            'lesson_learn_last' => $lesson_learn_last,
            'date'              => $date,
            'date_time'         => $date_time,
            'teacher_lessons'   => $teacher_lessons,
        ]);
    }

    /**
     * student Validation.
     * @param Request $request
     * @return JsonResponse
     */
    public function studentValidation(Request $request)
    {
        $input = $request->all();
        $rules = [];
        ($input['time_from'] != '' && $input['time_to'] != '') ? $rules['time_to'] = 'after_or_equal:time_from' : $rules['time_to'] = '';

        $rules['coin_from'] = 'nullable|numeric';
        $rules['coin_to'] = 'nullable|numeric';

        if($input['coin_to'] != '' && $input['coin_from'] && $input['coin_from'] > $input['coin_to']){
            $rules['coin_to'] = 'nullable|numeric|gte:coin_from';
        }
        $attributes = [
            'time_from' => __('student_home.search_teacher.time_from'),
            'time_to'   => __('student_home.search_teacher.time_to'),
            'coin_from' => __('student_home.search_teacher.coin_from'),
            'coin_to'   => __('student_home.search_teacher.coin_to'),
        ];
        $message = [
            'time_to.after_or_equal'=> __('validation_custom.M013',['attribute'=>__('student_home.search_teacher.time_from'),'field'=> __('student_home.search_teacher.time_to')]),
            'coin_from.numeric' => __('validation_custom.M006',['attribute'=>__('student_home.search_teacher.coin')]),
            'coin_to.numeric' => __('validation_custom.M006',['attribute'=>__('student_home.search_teacher.coin')]),
            'coin_to.gte' => __('validation_custom.M013',['attribute'=>__('student_home.search_teacher.coin_from'),'field'=> __('student_home.search_teacher.coin_to')]),
        ];

        $validator = Validator::make($input, $rules, $message)->setAttributeNames($attributes);
        if ($validator->fails()) {
            return $this->responseError(null, $validator->errors());
        }else {
            return $this->responseSuccess();
        }
    }

    /**
     * Search home.
     * @param Request $request
     * @return Factory|View
     */
    public function studentSearch(Request $request)
    {
        $input = $request->all();
        $date_time = explode('|', $input['date_time']);
        $check_modal_teacher = $input['check_modal_teacher'];
        $date = [];
        for ($i = 0; $i < 7; $i++) {
            $temp = [];
            $nextDate = Carbon::today()->addDays($i)->format('l,m,d,Y');
            $nextDate = explode(',', $nextDate);
            $temp['name'] = $this->getDayName($nextDate[0]);
            $temp['month'] = $nextDate[1];
            $temp['day'] = $nextDate[2];
            $temp['year'] = $nextDate[3];
            array_push($date, $temp);
        }
        $nationality = config('nation');
        $courses = DB::table('course')->get();
        $teacher_lessons = $this->studentRepository->getTeacherLessons();
        $teachers = $this->studentRepository->studentSearch($input);
        $count = $teachers[0];
        $teachers = $teachers[1];
        $countSuddenLesson = $this->studentRepository->getCountSuddenLesson();
        return view('admin.students.home',[
            'nationality'           => $nationality,
            'courses'               => $courses,
            'date'                  => $date,
            'teachers'              => $teachers,
            'teacher_lessons'       => $teacher_lessons,
            'check_modal_teacher'   => $check_modal_teacher,
            'count'                 => $count,
            'input'                 => $input,
            'date_time'             => $date_time,
            'countSuddenLesson'     => $countSuddenLesson,
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function updateCourse(Request $request)
    {
        $course_id = (int)$request->getContent();
        try {
            $student_course = DB::table('student_courses')
                ->select('*')
                ->where('course_id', $course_id)
                ->first();
            if ($student_course == null) {
                $first_lesson = DB::table('lessons')
                    ->selectRaw('min(number) as first_lesson')
                    ->where('course_id', $course_id)
                    ->first();
                $student_next_lesson = DB::table('lessons')
                    ->join('course', 'lessons.course_id', '=', 'course.id')
                    ->select('lessons.name as lesson_name', 'course.name as course_name')
                    ->where([['lessons.course_id', $course_id],
                        ['lessons.number', $first_lesson->first_lesson]])
                    ->first();
            } else {
                $student_last_lesson = DB::table('lesson_histories')
                    ->join('lessons', 'lesson_histories.lesson_id', '=', 'lessons.id')
                    ->join('course', 'lessons.course_id', '=', 'course.id')
                    ->select('lessons.id as lesson_id', 'course.id as course_id'
                        , 'lesson_histories.created_at as created_at', 'lessons.number as lesson_number')
                    ->where([['lesson_histories.student_id', Auth::id()],
                        ['lessons.course_id', $course_id]])
                    ->orderBy('lesson_histories.created_at', 'DESC')
                    ->first();
                $student_next_lesson = DB::table('lessons')
                    ->join('course', 'course.id', '=', 'lessons.course_id')
                    ->select('course.name as course_name', 'lessons.name as lesson_name')
                    ->where([['course.id', $student_last_lesson->course_id], ['lessons.id', $student_last_lesson->lesson_id + 1]])
                    ->first();
                if ($student_next_lesson == null) {
                    $student_next_lesson['lesson_name'] = "You have done this course";
                }
            }
            return $this->responseSuccess('next_lesson', $student_next_lesson);
        } catch (\Exception $e) {
            return $this->responseError('error');
        }
    }

    /**
     * Show get book-lesson view
     *
     * @param $id
     * @param Request $request
     * @return  View
     * @throws \Throwable
     */
    public function bookLesson($id , Request $request)
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

        // Check cancelling premium for user
        $this->cancellingPremium();

        //get coin of teacher
        $coinPerStudy = $this->getCoinStartLessonNow($id);

        //check the teacher is free
        $schedule = $this->studentRepository->getStatusOfTeacherById($id);
        $schedule = $schedule ?? null;

        // Get count lesson of user
        $countSuddenLesson = $this->studentRepository->getCountSuddenLesson();

        //get total coin of student
        $totalCoinOfStudent = $this->studentRepository->getTotalCoinOfStudent();
        $totalCoinOfStudent = $totalCoinOfStudent->total_coin ?? 0; // return number of coin

        //get student membership status
        $student_membership_status = $this->studentRepository->getMemberStatus()->membership_status;

        //get teacher_information
        $teacher_information = $this->teacherRepository->getTeacherInformation($id);

        //get teacher_coin
        $teacher_coin = $this->teacherRepository->getTeacherCoin($id);

        //get courses
        $courses = $this->teacherRepository->getCourse();

        //get num of lessons
        $number_of_lessons = $this->teacherRepository->getNumberOfLesson($id);

        //get num of reservations
        $number_of_reservations = $this->teacherRepository->getNumberOfReservation($id);

        //get lesson histories
        $lesson_histories = $this->teacherRepository->getTeacherLessonHistories($id, Auth::id());

        $teacher_courses = $this->teacherRepository->getTeacherCourses($id);

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

        if ($checkTeacherCanTeach) {
            $lessons = DB::table('lessons')
                ->select('*')
                ->where('lessons.course_id','=', $student_last_lesson->course_id)
                ->get();
        }
        else {
            $lessons = [];
        }

//        dd($student_last_lesson, $latestLesson, $checkTeacherCanTeach);

        $teacher_information->number_of_lessons = $number_of_lessons;
        $teacher_information->number_of_reservations = $number_of_reservations;
        $teacher_information->nationName = isset($teacher_information->nationality) ? config('nation')[$teacher_information->nationality] : null;
        $teacher_information->courses = $courses;

        $teacherReview = $this->studentRepository->getTeacherReview($id);
        $getNumberStar = $this->studentRepository->getNumberStar($id);
        if($request->ajax())
        {
            return view('admin.students.review_teacher_detail', compact('teacherReview'))->render();
        }
        return view('admin.students.lessons.bookLesson', compact(
            'teacher_information',
            'lesson_histories',
            'coinPerStudy',
            'schedule',
            'totalCoinOfStudent',
            'countSuddenLesson',
            'teacher_coin',
            'student_membership_status',
            'teacher_courses',
            'student_last_lesson',
            'checkTeacherCanTeach',
            'lessons',
            'teacherReview',
            'getNumberStar',
            'latestLesson',
            'current_student_lesson'
        ));
    }

    public function get7daytrialStep1()
    {
        return view('admin.students.getTrial.step1');
    }

    /**
     * Show get Trial form step-2
     *
     * @return  Renderable
     */
    public function get7daytrialStep2()
    {
        return view('admin.students.getTrial.step2');
    }

    /**
     * Show get Premium form step-1
     *
     * @return  Renderable
     */
    public function getPremiumStep1()
    {
        return view('admin.students.getPremium.step1');
    }

    /**
     * Show get Premium form step-2
     *
     * @return  Renderable
     */
    public function getPremiumStep2()
    {
        return view('admin.students.getPremium.step2');
    }

    /**
     * booking list
     *
     * @return  view
     */
    public function bookingList()
    {
        // Check cancelling premium for user
        $this->cancellingPremium();

        // Lesson last of student
        $lesson_learn_last = $this->studentRepository->getLessonLearnLast();
        $data = [];
        $data['booking'] = $this->studentRepository->getHistoryBooking();

        //convert time to users' local time
        foreach ($data['booking'] as $record) {
            $timeAfterConvert = Timezone::convertToLocal(Carbon::parse($record->start_date . " " . $record->start_hour), 'Y-m-d H:i:s');
            $record->start_date = date('Y-m-d', strtotime($timeAfterConvert));
            $record->start_hour = date('H:i:s', strtotime($timeAfterConvert));
        }
        $data['start_date'] = [];
        foreach ($data['booking'] as $key => $item) {
            array_push($data['start_date'], $item->start_date);
        }
        $data['start_date'] = array_unique($data['start_date']);
        return view('admin.students.lessons.bookingList', compact('data','lesson_learn_last'));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getCourseCanTeachByTeacherId(Request $request) {
        $input = $request->only('teacher_id');
        $hasData = $this->studentRepository->getAllCourse($input);

        if(!empty($hasData)) {
            return $this->responseSuccess($hasData);
        }
        return $this->responseError();
    }

    public function updateLessonBooked(Request $request) {
        $input = $request->only('lesson_id', 'teacher_schedule_id');

        $data = $this->studentRepository->updateLesson($input);

        $channel = 'notification-user';
        $status = $this->pusherRepository->sendNotify($channel, $data[1]['user_to'], $data[1]);
        if(is_null($data[0]) && $status) {
            return $this->responseError();
        }
        return $this->responseSuccess($data[0], __('validation_custom.M027'));
    }


    /**
     * send the notification to teacher when confirm at step 2
     * @author ThachDang
     * @param Request $request
     * @param $id
     */
    public function pushNotificationToTeacherWhenFirmlyBooked(Request $request, $id)
    {
        $input = $request->all();
//        $linkZoom = $this->studentRepository->bookLesson($input, $id);
        $input['teacher_id'] = $id;
        $time = date('Y-m-d H:i:s', strtotime($this->timezone->convertFromLocal(Carbon::parse($input["start_date"] . " " . $input["start_hour"] )->format('Y-m-d H:i:s'))));
        $input['start_date'] = date('Y-m-d', strtotime($time));
        $input['start_hour'] = date('H:i:s', strtotime($time));
        $data = [
            'data' => $input,
            'type' => 2 //booked
        ];
        $channel = 'notification-open-lesson-teacher';
        $status = $this->pusherRepository->sendMessage($channel, $id, $data);
        return $status ? $this->responseSuccess($data) : $this->responseError();
    }

    public function paymentHistory()
    {
        // Check cancelling premium for user
        $this->cancellingPremium();

        return view('admin.students.paymentsHistory');
    }

    public function getPaymentHistories() {
        $data = [];
        $allPaymentHistories= [];

        $customer_id = $this->studentRepository->getCustomerId();
        if($customer_id) {
            $paymentHistories=$this->stripe->paymentIntents->all(
                [
                    'customer' => $customer_id->stripe_customer_id,
                    'limit' => 100
                ])->data;

            array_push($allPaymentHistories, $paymentHistories);

            if(count($paymentHistories) > 99) {
                $lastId = $paymentHistories["99"]["id"];
                $has_more = True;
                while($has_more){

                    $req = $this->stripe->paymentIntents->all(
                        [
                            'customer' => $customer_id->stripe_customer_id,
                            'limit' => 100,
                            "starting_after" => $lastId
                        ])->data;
                    array_push($allPaymentHistories, $req);
                    if(count($req) < 100) {
                        $has_more = False;
                    }
                    else {
                        $lastId = $req["99"]["id"];
                    }
                }
            }
        }
        for($i=0; $i < count($allPaymentHistories); ++$i) {
            for($j=0; $j < count($allPaymentHistories[$i]); ++$j) {
                if($allPaymentHistories[$i][$j]->status == "succeeded") {
                    array_push($data, [
                        'amount' => $allPaymentHistories[$i][$j]->amount,
                        'created' => Helper::formatDateHIS(Timezone::convertToLocal(Carbon::parse($allPaymentHistories[$i][$j]->created), 'Y-m-d H:i:s')),
                        'description' => $allPaymentHistories[$i][$j]->description,
                        'currency' => $allPaymentHistories[$i][$j]->currency,
                    ]);
                }
            }
        }
        return Datatables::of($data)
            ->make(true);
    }

    /**
     * send the notification to teacher when confirm at step 1
     * by ThachDang
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function pushNotificationToTeacherWhenBooked(Request $request, $id)
    {
        // Check cancelling premium for user
        $this->cancellingPremium();

        // Get data
        $student = DB::table('users')
            ->select(
                'user_information.membership_status'
            )
            ->leftJoin('user_information', 'user_information.user_id', '=', 'users.id')
            ->where('users.id', Auth::id())
            ->first();
        if ($student->membership_status == config('constants.membership.id.free')) {
            return $this->responseError('expired', __('validation_custom.M054'));
        }

        $input = $request->all();

        $status = $this->studentRepository->checkDisabledButtonWithTime($input, $id);
        if(!$status) {
            return $this->responseSuccess([
                'expired' => true
            ]);
        }

        $status_schedule = 2;
        $before_status = 3;
        $changeStatusInTeacherScheduleTable = $this->studentRepository->changeStatusScheduleOfTeacher($input, $id, $status_schedule, $before_status); //change status schedule table to 2 when the student starts
        if ($changeStatusInTeacherScheduleTable) {
            $data = [
                'data' => null,
                'type' => 1, //booking
            ];
            $channel = 'notification-open-lesson-teacher';
            $status = $this->pusherRepository->sendMessage($channel, $id, $data);
            if ($status) {
                return $this->responseSuccess($data);
            }
            return $this->responseError();
        }
        return $this->responseError();
    }

    public function pushNotificationToTeacherWhenStart(Request $request, $id)
    {
        // Check cancelling premium for user
        $this->cancellingPremium();
        $bookingId = $request->only('bookingId');
        if(!empty($bookingId)) {
            $status = $this->studentRepository->checkTime($bookingId['bookingId']);
            if(!$status) {
                return $this->responseSuccess([
                    'expired' => true
                ]);
            }
        }
        $data = [
            'data' => null,
            'type' => 1, //booking
        ];
        $channel = 'notification-open-lesson-teacher';
        $status = $this->pusherRepository->sendMessage($channel, $id, $data);
        if ($status) {
            return $this->responseSuccess($data);
        }
        return $this->responseError();
    }

    public function pushNotificationToTeacherWhenClosed($id)
    {
        $data = [
            'data' => null,
            'type' => 3, //cancel
        ];
        $channel = 'notification-open-lesson-teacher';
        $status = $this->pusherRepository->sendMessage($channel, $id, $data);
        if ($status) {
            return $this->responseSuccess();
        }
        return $this->responseError();
    }
    /**
     * @param $id
     * @return JsonResponse
     */
    public function pushNotificationToTeacherWhenCanceled(Request $request, $id)
    {
        $input = $request->all();
        $status_schedule = 3;
        $before_status = 2;
        $changeStatusInTeacherScheduleTable = $this->studentRepository->changeStatusScheduleOfTeacher($input, $id, $status_schedule, $before_status); //change status schedule table to 3 when the student cancel
        $data = [
            'data' => null,
            'type' => 3, //cancel
        ];
        $channel = 'notification-open-lesson-teacher';
        $status = $this->pusherRepository->sendMessage($channel, $id, $data);
        if ($status) {
            return $this->responseSuccess();
        }
    }

    /**
     * get coin to show in book lesson
     * by ThachDang
     * @return int
     */
    public function getCoinStartLessonNow($teacher_id)
    {

        // Get count lesson of user
        $numberOfTheSuddenLesson = $this->studentRepository->getCountSuddenLesson();

        // Get member status of user
        $membership_status = $this->studentRepository->getMemberStatus();
        $membership_status = $membership_status->membership_status ?? null;
        if (!($numberOfTheSuddenLesson < 2 && ($membership_status == 2 || $membership_status == 3))) {
            return $this->studentRepository->getCoin($teacher_id)->coin;
        }
        return 0;
    }


    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Pusher\PusherException
     */
    public function notifyToTeacherStartLesson(Request $request)
    {
        $input = $request->all();
        //get teacher coin
//        $coin = DB::table('teacher_coin')
//            ->where('teacher_id', (int)$input['teacher_id'])
//            ->first();

        $coin = DB::table('booking')
            ->where('teacher_schedule_id', '=', (int)$input['schedule_id'])
            ->where('student_id', '=', Auth::id())
            ->first();

        $teacher_coin =  $coin != null? $coin->coin : 0;
        //get last lesson_id
//        $last_lesson = DB::table('users')
//            ->join('lesson_histories','lesson_histories.student_id','=','users.id')
//            ->join('lessons','lesson_histories.lesson_id','=','lessons.id')
//            ->select('lesson_histories.*','lessons.*')
//            ->where('lesson_histories.lesson_id', DB::raw("(select max(`lesson_id`) from lesson_histories where student_id = ".Auth::id().")"))
//            ->first();

//        $lesson_id = null;
//        if($last_lesson == null) {
//            $last_lesson = DB::table('lessons')
//                ->join('course', 'lessons.course_id','=','course.id')
//                ->select('lessons.*')
//                ->where('course.id', DB::raw("(select min(`id`) from course)"))
//                ->where('lessons.number',1)
//                ->first();
//            $lesson_id = $last_lesson->id;
//        }
//        else {
//
//            //check last lesson
//            $lesson = DB::table('lessons')
//                ->select('*')
//                ->where('id', $last_lesson->lesson_id)
//                ->first();
//            $lesson_max_number = DB::table('lessons')
//                ->select('*')
//                ->where('course_id', $last_lesson->course_id)
//                ->where('lessons.number', DB::raw("(select max(`number`) from lessons where course_id=".$last_lesson->course_id.")"))
//                ->first();
//
//            $last_course = DB::table('course')
//                ->select('*')
//                ->where('course.id', DB::raw("(select max(`id`) from course)"))
//                ->first();
//
//            if($lesson->number == $lesson_max_number->number) {
//                if($lesson->course_id == $last_course->id){
//                    $lesson_id = $last_lesson->id;
//                }
//                else {
//                    $last_lesson = DB::table('lessons')
//                        ->join('course', 'lessons.course_id','=','course.id')
//                        ->select('lessons.*')
//                        ->where('course.id', $last_lesson->course_id + 1)
//                        ->where('lessons.number',1)
//                        ->first();
//                    $lesson_id = $last_lesson->id;
//                }
//            }
//            else {
//                $lesson_id = $last_lesson->lesson_id + 1;
//            }
//        }

//        $user = Zoom::user()->find('khoivinhphan@gmail.com');
//
//        $meeting = Zoom::meeting()->make([
//            'topic' => $last_lesson->name,
//            'start_time' => Carbon::now(),
//            'password' => 'scret123',
//            "duration" => 30,
//            'settings' => [
//                'join_before_host' => true,
//            ]
//        ]);
//
//        $user->meetings()->save($meeting);

        $lesson = DB::table('student_courses')
            ->join('lessons','student_courses.lesson_id','=','lessons.id')
            ->select('lessons.*')
            ->where('student_courses.teacher_schedule_id', '=', (int)$input['schedule_id'])
            ->where('student_courses.student_id','=', Auth::id())
            ->first();

        $zoom_link = DB::table('users')
            ->join('user_information','users.id','=','user_information.user_id')
            ->select('user_information.link_zoom')
            ->where('users.id', (int)$input['teacher_id'])
            ->first();

        $lesson_history = [
            'student_id'    =>  Auth::id(),
            'teacher_id'    =>  $input['teacher_id'],
            'coin'          =>  $teacher_coin,
            'lesson_id'     =>  $lesson->id,
            'date'          =>  Carbon::now()->format('Y-m-d'), //get VN timezone
            'time'          =>  $input['schedule_time'],
            'course_id'     =>  $lesson->course_id,
            'zoom_link'     =>  $zoom_link->link_zoom,
            'type'          =>  2,
            'created_at'    =>  Carbon::now(),
            'updated_at'    =>  Carbon::now(),
        ];


        DB::beginTransaction();
        try {
            //insert DB
            DB::table('lesson_histories')->insert($lesson_history);

            //update teacher_schedule
            DB::table('teacher_schedule')
                ->where([['start_date',Carbon::now()->format('Y-m-d')],['start_hour',$input['schedule_time']]])
                ->update(['status' => 1, 'updated_at' => Carbon::now(),
                ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }

        $data = [
            'data' => "Student start lesson",
            'type' => 1,
            'zoom_url' => $zoom_link->link_zoom,
        ];

        $channel = 'notification-student-notify-teacher';
        $status = $this->pusherRepository->sendMessage($channel, (int)$input['teacher_id'], $data);
        return $status ? $this->responseSuccess($data) : $this->responseError();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Pusher\PusherException
     */
    public function notifyToTeacherAfter5Minutes(Request $request): JsonResponse
    {
        $input = $request->all();
        $data = [
            'data' => "Student dont start lesson after 5 minutes",
            'type' => 2,
        ];
        $channel = 'notification-student-notify-teacher';
        $status = $this->pusherRepository->sendMessage($channel, (int)$input['teacher_id'], $data);
        return $status ? $this->responseSuccess($data) : $this->responseError();
    }

    /**
     * get notification
     * by Bao
     * @return view
     */
    public function notificationAll(): View
    {
        return view('admin.students.notificationAll');
    }

    /**
     * get student curriculum
     * @return View
     */
    public function indexCourse(): View
    {
        $courses = $this->studentRepository->getCourses();
        return view('admin.students.courses.index', compact('courses'));
    }

    /**
     * get course detail
     * @param $id
     * @return View
     */
    public function detailCourse($id): View
    {
        $course = $this->studentRepository->getCourseById($id);
        if($course == null) {
            abort(404);
        }
        $lessons = $this->studentRepository->getCourseInfoById($id);
        return view('admin.students.courses.detail', compact('course', 'lessons'));
    }

    public function pushRequestCancel(Request $request): JsonResponse
    {
        $input = $request->all();
        $status_schedule = 3;
        $before_status = 2;
        $status = $this->studentRepository->changeStatusScheduleOfTeacher($input, $input['teacher_id'], $status_schedule, $before_status); //change status schedule table to 3 when the student cancel
        if ($status) {
            return $this->responseSuccess();
        }
        return $this->responseError();
    }
}
