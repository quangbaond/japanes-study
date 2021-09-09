<?php

namespace App\Http\Controllers\Admin\Teachers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TimezoneController;
use App\Repositories\Admin\Managers\TeacherRepository;
use App\Repositories\Admin\PusherRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use JamesMills\LaravelTimezone\Facades\Timezone;
use Yajra\DataTables\DataTables;

class MyPageController extends Controller
{
    protected $teacherRepository, $pusherRepository, $timezone;

    public function __construct(TeacherRepository $teacherRepository, PusherRepository $pusherRepository, TimezoneController $timezone)
    {
        $this->teacherRepository = $teacherRepository;
        $this->pusherRepository = $pusherRepository;
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
     * @return View
     */
    public function myPage()
    {
        //get 7 days next, day name, and teacher schedule (Asia/Ho_Chi_Minh) : Database
        $date = [];
        $date_temp = [];
        for ($i = 0; $i < 7; $i++) {
            $temp = [];
            $nextDate = $this->timezone->convertToLocal(Carbon::now()->addDays($i),'l,m,d,Y');
//            $nextDate = Carbon::today()->addDays($i)->format('l,m,d,Y');
            $nextDate = explode(',', $nextDate);
            $temp['name'] = $this->getDayName($nextDate[0]);
            $temp['month'] = $nextDate[1];
            $temp['day'] = $nextDate[2];
            $temp['year'] = $nextDate[3];
            $temp['full'] = $nextDate[3] . '-' . $nextDate[1] . '-' . $nextDate[2];
            array_push($date, $temp);
            $nextDate = $this->timezone->convertToLocal(Carbon::now()->addDays($i),'Y-m-d');
//            $nextDate = Carbon::today()->addDays($i)->format('Y-m-d');
            array_push($date_temp, $nextDate);
        }

        //get booked schedule in week
        $schedules = DB::table('teacher_schedule')
            ->where('teacher_schedule.teacher_id', Auth::id())
            ->whereRaw("CONCAT(teacher_schedule.start_date, ' ',teacher_schedule.start_hour) >= ?", [ Carbon::now()->subMinutes(30)->format('Y-m-d H:i:s') ])
//            ->whereRaw("CONCAT(teacher_schedule.start_date, ' ',teacher_schedule.start_hour) >= ?", [ Carbon::parse('2021-3-22 22:00:00')->format('Y-m-d H:i:s') ])
            ->whereRaw("CONCAT(teacher_schedule.start_date, ' ',teacher_schedule.start_hour) <= ?", [ Carbon::now()->addDay(6)->format('Y-m-d').' 23:59:00' ])
            ->where('teacher_schedule.status', '=', 2)
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
        //get num of teacher_schedule
        $num_booking = sizeof($schedules);

        //get count todaySchedule
        $today = Carbon::now()->format('Y-m-d');
        $countTodaySchedule = DB::table('booking')
            ->join('teacher_schedule', 'booking.teacher_schedule_id', '=', 'teacher_schedule.id')
            ->join('users', 'booking.student_id', '=', 'users.id')
            ->where([['teacher_schedule.start_date', $today],
                ['teacher_schedule.teacher_id', Auth::id()],
                ['teacher_schedule.start_hour','>=', Carbon::now()->subMinutes(30)->format('H:i:00')]])
            ->whereIn('teacher_schedule.status', [2, 3])
            ->groupBy('teacher_schedule.start_date')
            ->count();
        //get lesson counter
        $counter = $this->teacherRepository->getLessonCounter(Auth::id());
        return view('admin.teachers.MyPage', compact('schedules', 'date', 'num_booking', 'countTodaySchedule', 'counter'));
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getTodayScheduleDataTable()
    {
        $todaySchedules = $this->teacherRepository->todaySchedule(Auth::id());
        return Datatables::of($todaySchedules)
            ->addColumn('actions', function ($todaySchedule) {
                $videoAndText = '<a class="btn py-1 mb-0  pt-2" href="' . $todaySchedule->text_link . '" target="_blank" style="background-color: #F6B352; padding: 1px 10px">テキスト</a>
                                    <a class="btn btnShowVideo py-1 mb-0  pt-2 mr-1" href="javascript:;" data-video_link="' . $todaySchedule->video_link . '" style="background-color: #F68657; padding: 1px 10px"> ビデオ </a>';
                $min = Carbon::parse($todaySchedule->start_hour)->subMinutes(5);
                $max = Carbon::parse($todaySchedule->start_hour)->addMinutes(10);
                $now = Carbon::parse($this->timezone->convertToLocal(Carbon::now(),'H:i:00'));

//                return '<button value="' . $todaySchedule->start_hour . '" class="btn btn-success btn-start-lesson" name="' . $todaySchedule->student_id . '">予約レッスンへ進む</button>';
                if ($now->gte($min) && $now->lte($max))
                    $videoAndText = $videoAndText . '<button value="' . $this->timezone->convertFromLocal($todaySchedule->start_date . ' ' . $todaySchedule->start_hour)->format('H:i') . '" class="btn btn-success btn-start-lesson" name="' . $todaySchedule->student_id . '" content="' . $todaySchedule->schedule_id . '">予約レッスンへ進む</button>';
                else
                    $videoAndText = $videoAndText . '<button class="btn btn-secondary"  disabled>予約レッスンへ進む</button>';
                return '<div class="mw-100">' . $videoAndText . '</div>';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Pusher\PusherException
     */
    public function notifyToStudentWhenClickButtonStart(Request $request)
    {
        $input = $request->all();
        $data = [
            'data' => "Teacher invite you to join the lesson.",
            'type' => 1,
            'teacher_id' => (int)$input['teacher_id']
        ];
        $channel = 'notification-student-teacher-my-page';
        $status = $this->pusherRepository->sendMessage($channel, (int)$input['student_id'], $data);
        return $status ? $this->responseSuccess($data) : $this->responseError();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Pusher\PusherException
     */
    public function notifyToStudentTeacherCancelLesson(Request $request)
    {
        $input = $request->all();
        $data = [
            'data' => "Teacher cancel lesson",
            'type' => 2,
            'teacher_id' => (int)$input['teacher_id']
        ];
        $channel = 'notification-student-teacher-my-page';
        $status = $this->pusherRepository->sendMessage($channel, (int)$input['student_id'], $data);
        return $status ? $this->responseSuccess($data) : $this->responseError();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Pusher\PusherException
     */
    public function notifyToStudentTeacherStartLesson(Request $request)
    {
        $input = $request->all();
        $data = [
            'data' => "Teacher want to start lesson",
            'type' => 3,
            'teacher_id' => (int)$input['teacher_id'],
            'schedule_time'=> $input['schedule_time'],
            'schedule_id'=> $input['schedule_id']
        ];
        $channel = 'notification-student-teacher-my-page';
        $status = $this->pusherRepository->sendMessage($channel, (int)$input['student_id'], $data);
        return $status ? $this->responseSuccess($data) : $this->responseError();
    }
}

