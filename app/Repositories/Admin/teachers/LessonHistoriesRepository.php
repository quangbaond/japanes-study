<?php
namespace App\Repositories\Admin\teachers;

use App\Http\Controllers\TimezoneController;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LessonHistoriesRepository {

    private $timezone;
    public function __construct(TimezoneController $timezone)
    {
        $this->timezone = $timezone;
    }

    public function getListLessonHistories($data) {
        $lessonHistories = DB::table('users')
            ->select(
                'lesson_histories.id as lesson_histories_id',
                'users.email as student_email',
                'users.id as student_id',
                'users.nickname as student_nickname',
                'lesson_histories.date as lesson_histories_date',
                'lesson_histories.time as lesson_histories_time',
                'lesson_histories.coin as lesson_histories_coin',
                'course.name as course_name',
                'lessons.name as lesson_content',
                'lesson_histories.student_id as student_id',
            )
            ->join('lesson_histories','users.id','=', 'lesson_histories.student_id')
            ->join('lessons','lesson_histories.lesson_id','=','lessons.id')
            ->join('course','course.id','=','lessons.course_id')
            ->where('users.role','=', config('constants.role.student'))
            ->where('users.auth', '=',1)
            ->where('lesson_histories.teacher_id', Auth::id())
            ->orderBy('lesson_histories.date','DESC')
            ->orderBy('lesson_histories.time','DESC');

        //get teacher schedule booking
        $data_booking_histories = DB::table('users')
            ->select('users.email as student_email',
                'users.nickname as student_nickname',
                'teacher_schedule.start_date as lesson_histories_date',
                'teacher_schedule.start_hour as lesson_histories_time',
                'booking.coin as lesson_histories_coin',
                'teacher_schedule.status as teacher_schedule_status',
                'booking.student_id as student_id'
            )
            ->join('booking','users.id','=','booking.student_id')
            ->join('teacher_schedule','booking.teacher_schedule_id','=','teacher_schedule.id')
            ->where('users.role','=', config('constants.role.student'))
            ->where('users.auth', '=', 1)
            ->where('teacher_schedule.teacher_id', '=', Auth::id())
            ->where('teacher_schedule.status', '=', 2)
            ->whereRaw("CONCAT(teacher_schedule.start_date, ' ',teacher_schedule.start_hour) < ?", [ Carbon::now()->subMinute(1)->format('Y-m-d H:i:s') ]);
//        dd(Carbon::now()->subMinute(30)->format('Y-m-d H:i:s'));
        if( !empty($data['email']) || !empty($data['nickname'])) {
            if( !empty($data['email']) ) {
                $lessonHistories->whereIn('users.email', $data['email']);
                $data_booking_histories->whereIn('users.email', $data['email']);
            }
            if( !empty($data['nickname']) ) {
                $lessonHistories->whereIn('users.id', $data['nickname']);
                $data_booking_histories->whereIn('users.id', $data['nickname']);
            }
        }
        if( !empty($data['date_to']) || !empty($data['date_from'])) {
            if(!empty($data['date_from'])) {
                $lessonHistories->whereRaw('CONCAT(lesson_histories.date, " ", lesson_histories.time) >= ?', [date('Y-m-d H:i:s', strtotime($this->timezone->convertFromLocal(Carbon::parse($data["date_from"] . " " . "00:00:00")->format('Y-m-d H:i:s'))))] );
                $data_booking_histories->whereRaw('CONCAT(teacher_schedule.start_date, " ", teacher_schedule.start_hour) >= ?',  [date('Y-m-d H:i:s', strtotime($this->timezone->convertFromLocal(Carbon::parse($data["date_from"] . " " . "00:00:00")->format('Y-m-d H:i:s'))))] );
            }
            if(!empty($data['date_to'])) {
                $lessonHistories->whereRaw('CONCAT(lesson_histories.date, " ", lesson_histories.time) <= ?', [date('Y-m-d H:i:s', strtotime($this->timezone->convertFromLocal(Carbon::parse($data["date_to"] . " " . "23:59:59")->format('Y-m-d H:i:s'))))] );
                $data_booking_histories->whereRaw('CONCAT(teacher_schedule.start_date, " ", teacher_schedule.start_hour) <= ?', [date('Y-m-d H:i:s', strtotime($this->timezone->convertFromLocal(Carbon::parse($data["date_to"] . " " . "23:59:59")->format('Y-m-d H:i:s'))))] );
            }
        }
        $data_lesson_histories = $lessonHistories->get()->toArray();

        $data_booking_histories = $data_booking_histories->get()->toArray();


        $data_lesson_histories = array_merge($data_lesson_histories, $data_booking_histories);
        return $data_lesson_histories;
    }
}
