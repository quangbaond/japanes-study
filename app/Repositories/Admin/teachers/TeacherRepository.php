<?php


namespace App\Repositories\Admin\teachers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Notifications;
use Illuminate\Support\Facades\Auth;
use Timezone;
class TeacherRepository
{
    /**
     * @param $id
     * @return object|null
     */
    public function getTeacherInformation($id)
    {
        return DB::table('user_information')
            ->join('users', 'user_information.user_id', '=', 'users.id')
            ->select('users.email', 'users.nickname', 'user_information.*', 'user_information.self-introduction as self_introduction')
            ->where('user_information.user_id', '=', $id)
            ->first();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getTeacherCoin($id)
    {
        $coin = DB::table('teacher_coin')
            ->where('teacher_id', $id)
            ->first();
        return $coin != null? $coin->coin : 0;
    }

    /**
     * @param $id
     * @return int
     */
    public function getNumberOfLesson($id)
    {
        $theNumberOfLessonHistories = DB::table('lesson_histories')
            ->select('id')
            ->where('teacher_id', '=', $id)
            ->groupBy('teacher_id')
            ->count('id');
        $theNumberOfBookingHistories = DB::table('booking')
            ->select('booking.id')
            ->join('teacher_schedule', 'teacher_schedule.id' , '=', 'booking.teacher_schedule_id')
            ->where('teacher_schedule.teacher_id', $id)
            ->where('teacher_schedule.status', config('constants.teacher_schedule.booking'))
            ->where(function($query) {
                $query->whereDate('teacher_schedule.start_date', '<', date('Y-m-d', strtotime(now())));
                $query->orWhere(function($subQuery) {
                   $subQuery->whereDate('teacher_schedule.start_date', '=', date('Y-m-d', strtotime(now())));
                   $subQuery->whereTime('teacher_schedule.start_hour', '<', date('H:i:s', strtotime(now())));
                });
            })
            ->groupBy('teacher_schedule.teacher_id')
            ->count('booking.id');
        return $theNumberOfBookingHistories + $theNumberOfLessonHistories;
    }

    /**
     * @param $id
     * @return int
     */
    public function getNumberOfReservation($id)
    {
        return DB::table('teacher_schedule')
            ->where([['teacher_schedule.teacher_id', '=', $id], ['teacher_schedule.status', '=', 2]])
            ->where(function($query) {
                $query->whereDate('teacher_schedule.start_date', '>', date('Y-m-d', strtotime(now())));
                $query->orWhere(function($subQuery) {
                    $subQuery->whereDate('teacher_schedule.start_date', '=', date('Y-m-d', strtotime(now())));
                    $subQuery->whereTime('teacher_schedule.start_hour', '>', date('H:i:s', strtotime(now())));
                });
            })
            ->count('teacher_schedule.id');
    }

    /**
     * @param $teacher_id
     * @param $student_id
     * @return \Illuminate\Support\Collection
     */
    public function getTeacherLessonHistories($teacher_id, $student_id)
    {
        return DB::table('lesson_histories')
            ->select('lesson_histories.*', 'course.name as course_name', 'lessons.name as lesson_name')
            ->join('lessons', 'lesson_histories.lesson_id', '=', 'lessons.id')
            ->join('course', 'lessons.course_id', '=', 'course.id')
            ->where('lesson_histories.teacher_id', '=', $teacher_id)
            ->where('lesson_histories.student_id', '=', $student_id)
            ->orderBy('lesson_histories.date', 'DESC')
            ->orderBy('lesson_histories.time', 'DESC')
            ->get()
            ->filter(function ($value) {
                $date = Carbon::parse($value->date .' '.$value->time);
                $value->date = Timezone::convertToLocal($date, 'Y-m-d');
                $value->time = Timezone::convertToLocal($date, 'H:i');
                return $value;
            });
    }

    /**
     * @param $id
     * @return \Illuminate\Support\Collection
     */
    public function getCourse()
    {
        return DB::table('course')
            ->select('course.name as course_name')
            ->orderBy('id')
            ->get();
    }
     /**
     * @return \Illuminate\Support\Collection
     */
    public function notificationsDataTable() {
        $user = Auth::user()->id;
        $query= Notifications::leftJoin('receiver' , 'notifications.id' , '=' , 'receiver.notification_id')
        ->orwhere('receiver.user_id' , $user)
        ->join('users', 'notifications.created_by', '=', 'users.id')
        ->select('users.email','notifications.*', 'receiver.read_at')
        ->orderBy('created_at' , 'desc');
        // Search title
        if (!empty($_GET["title"])) {
            $query->where(function ($query) {
                $query->where('notifications.title','LIKE','%'.$_GET["title"].'%')
                ->orWhere('notifications.content','LIKE','%'.$_GET["title"].'%');
            });
        }
        // Search created_at
        if (!empty($_GET["created_at_from"]) && empty($_GET["created_at_to"])) {
            $query->whereDate('notifications.created_at','>=',  date('Y-m-d', strtotime($_GET["created_at_from"])));
        }
        if (empty($_GET["created_at_from"]) && !empty($_GET["created_at_to"])) {
            $query->whereDate('notifications.created_at','<=',  date('Y-m-d', strtotime($_GET["created_at_to"])));
        }
        if (!empty($_GET["created_at_from"]) && !empty($_GET["created_at_to"])) {
            $query->whereDate('notifications.created_at','>=',  date('Y-m-d', strtotime($_GET["created_at_from"])));
            $query->whereDate('notifications.created_at','<=',  date('Y-m-d', strtotime($_GET["created_at_to"])));
        }
        return $query->get();
    }

    public function getTeacherCourses($teacher_id) {
        return DB::table('course')
            ->join('course_can_teach','course.id','=','course_can_teach.course_id')
            ->select('course.id as course_id', 'course.name as course_name', 'course.level_id')
            ->where('course_can_teach.teacher_id', '=', $teacher_id)
            ->orderBy('course.level_id', 'DESC')
            ->get();
    }
}
