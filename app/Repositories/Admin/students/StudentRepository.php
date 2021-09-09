<?php


namespace App\Repositories\Admin\students;

use App\Helpers\Helper;
use App\Http\Controllers\Admin\Students\BookLessonController;
use App\Http\Controllers\TimezoneController;
use App\Models\Course;
use App\Models\Notifications;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use MacsiDigital\Zoom\Facades\Zoom;
use Illuminate\Support\Arr;
use PhpParser\Node\Expr\Cast\Object_;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Ramsey\Uuid\Type\Time;
use Timezone;

class StudentRepository
{
    protected $timezone;

    public function __construct(TimezoneController $timezone)
    {
        $this->timezone = $timezone;
    }

    /**
     * @return Collection
     */
    public function getRandomTeacher()
    {
        $infoStudent = DB::table('lesson_histories')->select('lesson_id', 'course_id')->where('student_id',
            Auth::id())->first();
        if (!is_null($infoStudent)) {
            $data = DB::select(DB::raw("SELECT teacher_schedule.*,users.nickname FROM `teacher_schedule` INNER JOIN users on users.id = teacher_schedule.teacher_id INNER JOIN course_can_teach on course_can_teach.teacher_id = teacher_schedule.teacher_id WHERE DATE(start_date) like Date(now()) and ( DATE_FORMAT(start_hour, '%H:%i') BETWEEN SUBTIME(TIME_FORMAT(CURRENT_TIME(), '%H:%i'), '00:05') and ADDTIME(TIME_FORMAT(CURRENT_TIME(), '%H:%i'), '00:05') ) and teacher_schedule.status = 3 and course_can_teach.course_id = $infoStudent->course_id ORDER BY start_hour ASC LIMIT 1"));

            if (!empty($data)) {
                $data = (array)$data[0];
                $data['lesson_id'] = $infoStudent->lesson_id;
                $data['course_id'] = $infoStudent->course_id;
            }
        } else {
            $course_id = DB::table('course')->select('id')->first();
            $data = (array)DB::select(DB::raw("SELECT teacher_schedule.*,users.nickname FROM `teacher_schedule` INNER JOIN users on users.id = teacher_schedule.teacher_id INNER JOIN course_can_teach on course_can_teach.teacher_id = teacher_schedule.teacher_id WHERE DATE(start_date) like Date(now()) and ( DATE_FORMAT(start_hour, '%H:%i') BETWEEN SUBTIME(TIME_FORMAT(CURRENT_TIME(), '%H:%i'), '00:05') and ADDTIME(TIME_FORMAT(CURRENT_TIME(), '%H:%i'), '00:05') ) and teacher_schedule.status = 3 and course_can_teach.course_id = $course_id->id ORDER BY start_hour ASC LIMIT 1")); //CURRENT_TIME()

            if (!empty($data)) {
                $data = (array)$data[0];
                $data['lesson_id'] = 0;
                $data['course_id'] = $course_id->id;
            }
        }

        return $data;
    }

    /**
     * Get random teacher
     * by vinhppvk
     *
     * @return array
     */
    public function getRandomTeacherChoice()
    {
        $array_teacher = DB::table('users')
            ->select(
                'users.id as teacher_id',
                'users.role',
                'users.nickname',
                'user_information.image_photo',
                'teacher_schedule.start_date',
                'teacher_schedule.start_hour'
            )
            ->leftJoin('user_information', 'user_information.user_id', '=', 'users.id')
            ->join('teacher_schedule', 'teacher_schedule.teacher_id', '=', 'users.id')
            ->where('teacher_schedule.start_date', Carbon::now()->format('Y-m-d')) // Start_date is today
            ->where('teacher_schedule.start_hour', '<=',
                Carbon::now()->addMinute(5)->format('H:i:00')) // start_hour <= time()->addMinute(5)
            ->where('teacher_schedule.start_hour', '>=',
                Carbon::now()->subMinute(5)->format('H:i:00')) // start_hour >= time()->subMinute(5)
            ->where('teacher_schedule.status', 3) // The teacher has not been scheduled
            ->where('users.auth', 1) // Teacher is authentication
            ->where('users.last_seen', '>=',
                Carbon::now()->subMinute(1)->format('Y-m-d H:i:s')) // Teacher is online (1 minute)
            ->get()
            ->toArray();
        if (empty($array_teacher)) {
            return null;
        }
        $teacher = Arr::random($array_teacher);
        $time = date('Y-m-d H:i:s',
            strtotime(Timezone::convertToLocal(Carbon::parse($teacher->start_date . " " . $teacher->start_hour),
                'Y-m-d H:i:s')));
        $teacher->start_date = date('Y-m-d', strtotime($time));
        $teacher->start_hour = date('H:i:s', strtotime($time));
        return $teacher;
    }

    public function bookLesson($data, $teacher_id)
    {

    }


    /**
     * change status schedule of teacher when have student start
     * @param $data
     * @param $teacher_id
     * @return bool
     */
    public function changeStatusScheduleOfTeacher($data, $teacher_id, $status_schedule, $before_status)
    {
        $time = date('Y-m-d H:i:s',
            strtotime($this->timezone->convertFromLocal(Carbon::parse($data['start_date'] . " " . $data['start_hour'])->format('Y-m-d H:i:s'))));
        $data['start_date'] = date('Y-m-d', strtotime($time));
        $data['start_hour'] = date('H:i:s', strtotime($time));
        DB::beginTransaction();
        try {
            $exist = DB::table('teacher_schedule')
                ->where('teacher_id', $teacher_id)
                ->where('start_hour', 'like', $data['start_hour'])
                ->where('start_date', 'like', $data['start_date'])
                ->where('status', $before_status)->first();
            if (!is_null($exist)) {
                $exist = DB::table('teacher_schedule')
                    ->where('teacher_id', $teacher_id)
                    ->where('start_hour', 'like', $data['start_hour'])
                    ->where('start_date', 'like', $data['start_date'])
                    ->where('status', $before_status)
                    ->update([
                        'status' => $status_schedule,
                        'updated_at' => now(),
                    ]);
                DB::commit();
                return true;
            } else {
                return false;
            }
        } catch (\Throwable $th) {
            DB::rollback();
            return false;
        }
    }


    /**
     *
     * get History Booking by Bao
     *
     * @return array
     */
    public function getHistoryBooking()
    {
        return DB::table('booking')
            ->select(
                'booking.id as id_booking',
                'booking.*',
                'booking.coin as coin_teacher',
                'teacher_schedule.start_date',
                'teacher_schedule.start_hour',
                'teacher_schedule.teacher_id as teacher_id',
                'teacher_schedule.status as teacher_schedule_status',
                'users.nickname',
                'users.email',
                'user_information.image_photo as image_photo',
                'teacher_schedule.id as teacher_schedule_id',
                'lessons.name as lesson_name',
                'lessons.id as lesson_id',
                'lessons.text_link',
                'lessons.video_link',
                'course.name as course_name',
                'course.id as course_id',
            )
            ->leftJoin('teacher_schedule', 'teacher_schedule.id', '=', 'booking.teacher_schedule_id')
            ->leftJoin('users' , 'users.id' , '=' , 'teacher_schedule.teacher_id')
            ->leftJoin('user_information','users.id', '=', 'user_information.user_id')
            ->leftJoin('teacher_coin' , 'teacher_coin.teacher_id' , '=' , 'users.id')
//            ->join('student_courses', 'booking.teacher_schedule_id', '=', 'student_courses.teacher_schedule_id')
            ->join(DB::raw('(SELECT * FROM ((SELECT * FROM student_courses ORDER BY created_at desc) as student_courses) WHERE student_courses.id IN (SELECT MAX(id) FROM student_courses GROUP BY teacher_schedule_id) GROUP BY student_courses.teacher_schedule_id) as student_courses') ,
                    function($join)
                    {
                        $join->on('booking.teacher_schedule_id', '=', 'student_courses.teacher_schedule_id');
                    }
            )
            ->join('lessons', 'lessons.id', '=', 'student_courses.lesson_id')
            ->join('course', 'course.id', '=', 'lessons.course_id')
            ->where('booking.student_id', Auth::id())
            ->where('teacher_schedule.status', 2)
            ->where(function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->where('teacher_schedule.start_date', '=', Carbon::now()->format('Y-m-d'));
                    $subQuery->where('teacher_schedule.start_hour', '>=',
                        Carbon::now()->subMinute(11)->format('H:i:s'));
                });
                $query->orWhere('teacher_schedule.start_date', '>', Carbon::now()->format('Y-m-d'));
            })
            ->get()
            ->toArray();
    }

    /**
     * Get count sudden lesson
     * by Thach
     *
     * @return int
     */
    public function getCountSuddenLesson()
    {
        return DB::table('lesson_histories')
            ->select('id')
            ->where('student_id', Auth::id())
            ->where('type', '1')
            ->where('date', Carbon::now()->format('Y-m-d'))
            ->count();
    }

    /**
     * Get lesson last of student
     * by vinhppvk
     *
     * @return object
     */
    public function getLessonLearnLast()
    {
        $lesson_learn_last = DB::table('lesson_histories')
            ->select('lesson_histories.lesson_id', 'lessons.number', 'lessons.course_id')
            ->join('lessons', 'lessons.id', '=', 'lesson_histories.lesson_id')
            ->where('lesson_histories.student_id', Auth::id())->orderByDesc('lesson_histories.created_at')
            ->first();
        if (empty($lesson_learn_last)) {
            return DB::table('course')
                ->select('course.id as course_id', 'course.name as course_name', 'lessons.id as lesson_id',
                    'lessons.name as lesson_name', 'lessons.number', 'lessons.description')
                ->join('lessons', 'lessons.course_id', '=', 'course.id')
                ->where('course.id', 1)
                ->first();
        } else {
            // Get number of lessons
            $number_lesson = DB::table('lessons')->select('number')->where('id',
                $lesson_learn_last->lesson_id)->first();
            // Get number max of course
            $max_number_lesson = DB::table('lessons')->where('course_id', $lesson_learn_last->course_id)->max('number');

            if (($number_lesson->number + 1) > $max_number_lesson) { // Case: course next
                return DB::table('course')
                    ->select('course.id as course_id', 'course.name as course_name', 'lessons.id as lesson_id',
                        'lessons.name as lesson_name', 'lessons.number', 'lessons.description')
                    ->join('lessons', 'lessons.course_id', '=', 'course.id')
                    ->where('course.id', $lesson_learn_last->course_id + 1)
                    ->first();
            } else { // lesson next of course
                return DB::table('course')
                    ->select('course.id as course_id', 'course.name as course_name', 'lessons.id as lesson_id',
                        'lessons.name as lesson_name', 'lessons.number', 'lessons.description')
                    ->join('lessons', 'lessons.course_id', '=', 'course.id')
                    ->where('lessons.number', $lesson_learn_last->number + 1)
                    ->first();
            }
        }
    }

    public function getLessonCourseLearnLast($id)
    {
        $lesson_learn_last = DB::table('student_courses')
            ->join('lessons', 'student_courses.lesson_id', '=', 'lessons.id')
            ->select('student_courses.lesson_id', 'lessons.number', 'lessons.course_id')
            ->where('student_courses.student_id', Auth::id())
            ->orderByDesc('student_courses.updated_at')
            ->orderByDesc('student_courses.lesson_id')
            ->first();
        if (empty($lesson_learn_last)) {  // case: student doesnt have any lesson before -> get first teacher course
            return DB::table('course')
                ->select('course.id as course_id', 'course.name as course_name', 'lessons.id as lesson_id',
                    'lessons.name as lesson_name', 'lessons.number as lesson_number', 'lessons.number')
                ->join('lessons', 'lessons.course_id', '=', 'course.id')
                ->orWhereRaw('course.id = (SELECT MIN(course_id) FROM course_can_teach WHERE teacher_id = ' . $id . ')')
                ->orWhere('lessons.number', '=', 1)
                ->first();
        } else {  //case : student has lesson
            // Get number of lessons
            $number_lesson = DB::table('lessons')->select('number')->where('id',
                $lesson_learn_last->lesson_id)->first();
            // Get number max of course
            $max_number_lesson = DB::table('lessons')->where('course_id', $lesson_learn_last->course_id)->max('number');

            //Get max lesson id
            $max_lesson = DB::table('lessons')->whereRaw('lessons.id = (SELECT MAX(lessons.id) FROM lessons)')->first();

            if ($lesson_learn_last->lesson_id == $max_lesson->id) { //case max course
                return DB::table('course')
                    ->select('course.id as course_id', 'course.name as course_name', 'lessons.id as lesson_id',
                        'lessons.name as lesson_name', 'lessons.number', 'lessons.description')
                    ->join('lessons', 'lessons.course_id', '=', 'course.id')
                    ->where('lessons.id', $lesson_learn_last->lesson_id)
                    ->first();
            }

            if (($number_lesson->number + 1) > $max_number_lesson) { // Case: course next
                return DB::table('course')
                    ->select('course.id as course_id', 'course.name as course_name', 'lessons.id as lesson_id',
                        'lessons.name as lesson_name', 'lessons.number', 'lessons.description')
                    ->join('lessons', 'lessons.course_id', '=', 'course.id')
                    ->where('course.id', $lesson_learn_last->course_id + 1)
                    ->first();
            } else { // lesson next of course
                return DB::table('course')
                    ->select('course.id as course_id', 'course.name as course_name', 'lessons.id as lesson_id',
                        'lessons.name as lesson_name', 'lessons.number', 'lessons.description')
                    ->join('lessons', 'lessons.course_id', '=', 'course.id')
                    ->where('lessons.number', $lesson_learn_last->number + 1)
                    ->where('course.id', '=', $lesson_learn_last->course_id)
                    ->first();
            }
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model|Builder|object|null
     */
    public function getMemberStatus()
    {
        return DB::table('users')
            ->select('user_information.membership_status')
            ->leftJoin('user_information', 'users.id', '=', 'user_information.user_id')
            ->where('users.id', Auth::id())->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model|Builder|object|null
     */
    public function getDateExpirePremium()
    {
        return DB::table('users')
            ->leftJoin('user_information', 'users.id', '=', 'user_information.user_id')
            ->join('user_payment_info', 'users.id', '=', 'user_payment_info.user_id')
            ->where('user_information.membership_status', 6)
            ->where('users.id', Auth::id())
            ->select('user_payment_info.premium_end_date')
            ->first();
    }

    /**
     * search student.
     *
     * @param $input
     * @return LengthAwarePaginator
     */
    public function studentSearch($input)
    {
        $data = DB::table('users')
            ->select(
                'users.id as teacher_id',
                'users.nickname as teacher_name',
                'users.email as teacher_email',
                'user_information.image_photo as teacher_image',
                'user_information.self-introduction as teacher_self_introduction',
                'user_information.introduction_from_admin as teacher_introduction_from_admin',
                'user_information.nationality as teacher_nationality',
                DB::raw("GROUP_CONCAT(DISTINCT teacher_schedule.start_date SEPARATOR ',') as start_date"),
                DB::raw("GROUP_CONCAT(DISTINCT teacher_schedule.start_hour SEPARATOR ',') as start_hour"),
                DB::raw("GROUP_CONCAT(DISTINCT course_can_teach.course_id SEPARATOR ',') as course_id"),
                DB::raw('(SELECT
                                    (SELECT COUNT(lesson_histories.id) FROM lesson_histories WHERE lesson_histories.teacher_id = users.id) +
                                    (SELECT COUNT(booking.id) from booking INNER JOIN teacher_schedule on teacher_schedule.id = booking.teacher_schedule_id
                                        WHERE teacher_schedule.teacher_id = users.id AND concat_ws(" ",teacher_schedule.start_date, teacher_schedule.start_hour)  < NOW()
                                         AND teacher_schedule.`status` = 2)) AS number_count_lesson'),
                'teacher_coin.coin as teacher_coin'
            )
            ->leftJoin('user_information', 'users.id', '=', 'user_information.user_id')
            ->Join('teacher_schedule', 'users.id', '=', 'teacher_schedule.teacher_id')
            ->leftJoin('course_can_teach', 'users.id', '=', 'course_can_teach.teacher_id')
            ->leftJoin('teacher_coin', 'teacher_coin.teacher_id', '=', 'users.id')
            ->leftJoin('lesson_histories', 'lesson_histories.teacher_id', '=', 'users.id')
            ->where('users.role', '=', 2)
            ->where('users.auth', 1)
            ->groupBy(
                'users.id',
                'users.nickname',
                'users.email',
                'user_information.image_photo',
                'user_information.self-introduction',
                'user_information.introduction_from_admin',
                'user_information.nationality',
                'teacher_coin.coin'
            );

        // nationality
        if (!empty($input['nationality'])) {
            $data->where('user_information.nationality', '=', $input['nationality']);
        }
        // nickname
        if (!empty($input["nickname"])) {
            $data->where('users.nickname', 'LIKE', '%' . $input["nickname"] . '%');
        }
        //freeWord
        if (!empty($input["free_word"])) {
            $data->where(function ($query) use ($input) {
                $query->where('user_information.self-introduction', 'LIKE', '%' . $input["free_word"] . '%');
                $query->orWhere('user_information.introduction_from_admin', 'LIKE', '%' . $input["free_word"] . '%');
            });
        }

        // coin
        if (!empty($input['coin_from']) && empty($input['coin_to'])) {
            $data->where('teacher_coin.coin', '>=', $input['coin_from']);
        }
        if (empty($input['coin_from']) && !empty($input['coin_to'])) {
            $data->where('teacher_coin.coin', '<=', $input['coin_to']);
        }
        if (!empty($input['coin_from']) && !empty($input['coin_to'])) {
            $data->where('teacher_coin.coin', '>=', $input['coin_from']);
            $data->where('teacher_coin.coin', '<=', $input['coin_to']);
        }

        //courses
        if (!empty($input['courses'])) {
            $data->where('course_can_teach.course_id', '=', $input['courses']);
        }

        //Radio Lessons available now
        if (isset($input['btnRadioStatus']) && $input['btnRadioStatus'] == 2) {
            $data->where(function ($query) {
                $query->where('teacher_schedule.start_date', Carbon::now()->format('Y-m-d'))
                    ->where('teacher_schedule.start_hour', '<=', Carbon::now()->addMinute(5)->format('H:i:00'))
                    ->where('teacher_schedule.start_hour', '>=', Carbon::now()->subMinute(5)->format('H:i:00'))
                    ->where('teacher_schedule.status', 3);
            });
        }

        //radio Date
        if (isset($input['btnRadioStatus']) && $input['btnRadioStatus'] == 3) {
//            $localDateOfUser = Helper::formatDate(Timezone::convertToLocal(Carbon::now()->format('Y-m-d H:i:s')));
//            // start_hour
//            if (!empty($input['time_from']) && empty($input['time_to'])) {
//                $data->whereTime('teacher_schedule.start_hour','>=', $input['time_from']);
//            }
//            if (empty($input['time_from']) && !empty($input['time_to'])) {
//                $data->whereTime('teacher_schedule.start_hour','<=', $input['time_to']);
//            }
//            if (!empty($input['time_from']) && !empty($input['time_to'])) {
//                $data->whereTime('teacher_schedule.start_hour','>=', $input['time_from']);
//                $data->whereTime('teacher_schedule.start_hour','<=', $input['time_to']);
//            }

            if (!empty($input['date_time'])) {
                $date_time = explode('|', $input['date_time']);
                array_shift($date_time);

                if (empty($input['time_from']) && empty($input['time_to'])) {
                    $data->where(function ($query) use ($date_time, $input) {
                        for ($i = 0; $i < count($date_time); ++$i) {
                            $date = $date_time[$i];
                            $query->orWhere(function ($subQuery) use ($date) {
                                $subQuery->whereRaw("CONCAT(teacher_schedule.start_date, ' ', teacher_schedule.start_hour) >= ?",
                                    [
                                        date('Y-m-d H:i:s',
                                            strtotime($this->timezone->convertFromLocal(Carbon::parse($date . " " . "00:00:00")->format('Y-m-d H:i:s'))))
                                    ]);
                                $subQuery->whereRaw("CONCAT(teacher_schedule.start_date, ' ', teacher_schedule.start_hour) <= ?",
                                    [
                                        date('Y-m-d H:i:s',
                                            strtotime($this->timezone->convertFromLocal(Carbon::parse($date . " " . "23:59:59")->format('Y-m-d H:i:s'))))
                                    ]);
                            });
                        }
                    });
                }

                if (!empty($input['time_from']) && empty($input['time_to'])) {
                    $data->where(function ($query) use ($date_time, $input) {
                        for ($i = 0; $i < count($date_time); ++$i) {
                            $date = $date_time[$i];
                            $start = $date_time[$i] . " " . $input['time_from'] . ":00";
                            $query->orWhere(function ($subQuery) use ($start, $date) {
                                $subQuery->whereRaw("CONCAT(teacher_schedule.start_date, ' ', teacher_schedule.start_hour) >= ?",
                                    [
                                        date('Y-m-d H:i:s',
                                            strtotime($this->timezone->convertFromLocal(Carbon::parse($start)->format('Y-m-d H:i:s'))))
                                    ]);
                                $subQuery->whereRaw("CONCAT(teacher_schedule.start_date, ' ', teacher_schedule.start_hour) <= ?",
                                    [
                                        date('Y-m-d H:i:s',
                                            strtotime($this->timezone->convertFromLocal(Carbon::parse($date . " " . "23:59:59")->format('Y-m-d H:i:s'))))
                                    ]);
                            });
                        }
                    });
                }
                if (empty($input['time_from']) && !empty($input['time_to'])) {
                    $data->where(function ($query) use ($date_time, $input) {
                        for ($i = 0; $i < count($date_time); ++$i) {
                            $date = $date_time[$i];
                            $start = $date_time[$i] . " " . $input['time_to'] . ":00";
                            $query->orWhere(function ($subQuery) use ($start, $date) {
                                $subQuery->whereRaw("CONCAT(teacher_schedule.start_date, ' ', teacher_schedule.start_hour) >= ?",
                                    [
                                        date('Y-m-d H:i:s',
                                            strtotime($this->timezone->convertFromLocal(Carbon::parse($date . " " . "00:00:00")->format('Y-m-d H:i:s'))))
                                    ]);
                                $subQuery->whereRaw("CONCAT(teacher_schedule.start_date, ' ', teacher_schedule.start_hour) <= ?",
                                    [
                                        date('Y-m-d H:i:s',
                                            strtotime($this->timezone->convertFromLocal(Carbon::parse($start)->format('Y-m-d H:i:s'))))
                                    ]);
                            });
                        }
                    });
                }
                if (!empty($input['time_from']) && !empty($input['time_to'])) {
                    $data->where(function ($query) use ($date_time, $input) {
                        for ($i = 0; $i < count($date_time); ++$i) {
                            $start = $date_time[$i] . " " . $input['time_from'] . ":00";
                            $end = $date_time[$i] . " " . $input['time_to'] . ":00";
                            $query->orWhere(function ($subQuery) use ($start, $end) {
                                $subQuery->whereRaw("CONCAT(teacher_schedule.start_date, ' ', teacher_schedule.start_hour) >= ?",
                                    [
                                        date('Y-m-d H:i:s',
                                            strtotime($this->timezone->convertFromLocal(Carbon::parse($start)->format('Y-m-d H:i:s'))))
                                    ]);
                                $subQuery->whereRaw("CONCAT(teacher_schedule.start_date, ' ', teacher_schedule.start_hour) <= ?",
                                    [
                                        date('Y-m-d H:i:s',
                                            strtotime($this->timezone->convertFromLocal(Carbon::parse($end)->format('Y-m-d H:i:s'))))
                                    ]);
                            });
                        }
                    });
                }
            } else {
                // start_hour
                if (!empty($input['time_from']) && empty($input['time_to'])) {
                    $data->where(function ($query) use ($input) {
                        for ($i = 0; $i < 7; ++$i) {
                            $date = Helper::formatDate(Timezone::convertToLocal(Carbon::now()->addDay($i),
                                'Y-m-d H:i:s'));
                            $start = $date . " " . $input['time_from'] . ":00";
                            $query->orWhere(function ($subQuery) use ($start, $date) {
                                $subQuery->whereRaw("CONCAT(teacher_schedule.start_date, ' ', teacher_schedule.start_hour) >= ?",
                                    [
                                        date('Y-m-d H:i:s',
                                            strtotime($this->timezone->convertFromLocal(Carbon::parse($start)->format('Y-m-d H:i:s'))))
                                    ]);
                                $subQuery->whereRaw("CONCAT(teacher_schedule.start_date, ' ', teacher_schedule.start_hour) <= ?",
                                    [
                                        date('Y-m-d H:i:s',
                                            strtotime($this->timezone->convertFromLocal(Carbon::parse($date . " " . "23:59:59")->format('Y-m-d H:i:s'))))
                                    ]);
                            });
                        }
                    });
                }
                if (empty($input['time_from']) && !empty($input['time_to'])) {
                    $data->where(function ($query) use ($input) {
                        for ($i = 0; $i < 7; ++$i) {
                            $date = Helper::formatDate(Timezone::convertToLocal(Carbon::now()->addDay($i),
                                'Y-m-d H:i:s'));
                            $end = $date . " " . $input['time_to'] . ":00";
                            $query->orWhere(function ($subQuery) use ($date, $end) {
                                $subQuery->whereRaw("CONCAT(teacher_schedule.start_date, ' ', teacher_schedule.start_hour) >= ?",
                                    [
                                        date('Y-m-d H:i:s',
                                            strtotime($this->timezone->convertFromLocal(Carbon::parse($date . " " . "00:00:00")->format('Y-m-d H:i:s'))))
                                    ]);
                                $subQuery->whereRaw("CONCAT(teacher_schedule.start_date, ' ', teacher_schedule.start_hour) <= ?",
                                    [
                                        date('Y-m-d H:i:s',
                                            strtotime($this->timezone->convertFromLocal(Carbon::parse($end)->format('Y-m-d H:i:s'))))
                                    ]);
                            });
                        }
                    });
                }
                if (!empty($input['time_from']) && !empty($input['time_to'])) {
                    $data->where(function ($query) use ($input) {
                        for ($i = 0; $i < 7; ++$i) {
                            $date = Helper::formatDate(Timezone::convertToLocal(Carbon::now()->addDay($i),
                                'Y-m-d H:i:s'));
                            $start = $date . " " . $input['time_from'] . ":00";
                            $end = $date . " " . $input['time_to'] . ":00";
                            $query->orWhere(function ($subQuery) use ($start, $end) {
                                $subQuery->whereRaw("CONCAT(teacher_schedule.start_date, ' ', teacher_schedule.start_hour) >= ?",
                                    [
                                        date('Y-m-d H:i:s',
                                            strtotime($this->timezone->convertFromLocal(Carbon::parse($start)->format('Y-m-d H:i:s'))))
                                    ]);
                                $subQuery->whereRaw("CONCAT(teacher_schedule.start_date, ' ', teacher_schedule.start_hour) <= ?",
                                    [
                                        date('Y-m-d H:i:s',
                                            strtotime($this->timezone->convertFromLocal(Carbon::parse($end)->format('Y-m-d H:i:s'))))
                                    ]);
                            });
                        }
                    });
                }
            }
        }
        $count =$data->paginate(10)->total();
        return [$count, $data->paginate(10)];
    }

    public function getTeacherLessons()
    {
        return DB::table('users')
            ->select(
                'users.id as teacher_id',
                'users.nickname',
                'user_information.nationality',
                'user_information.image_photo as teacher_image',
                DB::raw('(SELECT COUNT(lesson_histories.id) FROM lesson_histories WHERE lesson_histories.teacher_id = users.id) AS number_count_lesson'),
            )
            ->leftJoin('user_information', 'user_information.user_id', '=', 'users.id')
            ->leftJoin('teacher_schedule', 'teacher_schedule.teacher_id', '=', 'users.id')
            ->leftJoin('lesson_histories', 'lesson_histories.teacher_id', '=', 'users.id')
            ->where('teacher_schedule.start_date', Carbon::now()->format('Y-m-d')) // Start_date is today
            ->where('teacher_schedule.start_hour', '<=',
                Carbon::now()->addMinute(5)->format('H:i:00')) // start_hour <= time()->addMinute(5)
            ->where('teacher_schedule.start_hour', '>=',
                Carbon::now()->subMinute(5)->format('H:i:00')) // start_hour >= time()->subMinute(5)
            ->where('teacher_schedule.status', 3) // The teacher has not been scheduled
            ->where('users.auth', 1) // Teacher is authentication
            ->where('users.last_seen', '>=',
                Carbon::now()->subMinute(1)->format('Y-m-d H:i:s')) // Teacher is online (1 minute)
            ->groupBy(
                'users.id',
                'users.nickname',
                'users.email',
                'user_information.nationality',
                'user_information.image_photo'
            )
            ->get();
    }

    public function getPaymentStudentHistoryCoin()
    {
        return DB::table('history_student_payment_coin')
            ->where('student_id', Auth::id())
            ->get();
    }

    public function getCoin($teacher_id)
    {
        return DB::table('teacher_coin')->select('coin')->where('teacher_id', $teacher_id)->first();
    }

    public function getStatusOfTeacherById($teacher_id)
    {
        $teacher = DB::table('users')
            ->select(
                'users.id as teacher_id',
                'users.role',
                'users.nickname',
                'user_information.image_photo',
                'teacher_schedule.start_date',
                'teacher_schedule.start_hour'
            )
            ->leftJoin('user_information', 'user_information.user_id', '=', 'users.id')
            ->join('teacher_schedule', 'teacher_schedule.teacher_id', '=', 'users.id')
            ->where('teacher_schedule.start_date', Carbon::now()->format('Y-m-d')) // Start_date is today
            ->where('teacher_schedule.start_hour', '<=',
                Carbon::now()->addMinute(5)->format('H:i:00')) // start_hour <= time()->addMinute(5)
            ->where('teacher_schedule.start_hour', '>=',
                Carbon::now()->subMinute(5)->format('H:i:00')) // start_hour >= time()->subMinute(5)
            ->where('teacher_schedule.status', 3) // The teacher has not been scheduled
            ->where('users.auth', 1) // Teacher is authentication
            ->where('users.id', $teacher_id)
            ->where('users.last_seen', '>=',
                Carbon::now()->subMinute(1)->format('Y-m-d H:i:s')) // Teacher is online (1 minute)
            ->get()
            ->toArray();

        if (empty($teacher)) {
            return null;
        }
        $teacher = $teacher[0];
        $time = date('Y-m-d H:i:s',
            strtotime(Timezone::convertToLocal(Carbon::parse($teacher->start_date . " " . $teacher->start_hour),
                'Y-m-d H:i:s')));
        $teacher->start_date = date('Y-m-d', strtotime($time));
        $teacher->start_hour = date('H:i:s', strtotime($time));

        return $teacher;
    }

    public function getTotalCoinOfStudent()
    {
        return DB::table('student_total_coins')
            ->select('total_coin')
            ->where('student_id', Auth::id())
            ->first();
    }

    /**
     * @param
     * @return object
     */
    public function getProfileStudent()
    {
        try {
            $studentProfile = DB::table('users')
                ->select(
                    'users.id as user_id',
                    'users.email',
                    'users.nickname',
                    'user_information.id',
                    'user_information.birthday',
                    'user_information.user_id',
                    'user_information.age',
                    'user_information.sex',
                    'user_information.phone_number',
                    'user_information.experience',
                    'user_information.image_photo',
                    'user_information.self-introduction as introduction',
                    'user_information.membership_status',
                    'user_information.nationality',
                    'student_total_coins.*',
                    'user_payment_info.trial_start_date'
                )
                ->leftJoin('user_information', 'user_information.user_id', '=', 'users.id')
                ->leftJoin('student_total_coins', 'student_total_coins.student_id', '=', 'users.id')
                ->leftJoin('user_payment_info', 'user_payment_info.user_id', '=', 'users.id')
                ->whereNull('users.deleted_at')
                ->where('users.id', '=', Auth::user()->id)
                ->first();
            return $studentProfile;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @return object|null
     */
    public function getZoomLinkByStudentId()
    {
        return DB::table('lesson_histories')->select('zoom_link')
            ->where('date', Carbon::now()->format('Y-m-d')) // Start_date is today
            ->where('time', '>=', Carbon::now()->subMinutes(30)->format('H:i:00'))
            ->where('student_id', Auth::id())
            ->orderBy('created_at', 'DESC')
            ->first();
    }

    /**
     * @return object|null
     */
    public function getZoomLinkHistoryByTeacherId()
    {
        return DB::table('lesson_histories')->select('zoom_link')
            ->where('date', Carbon::now()->format('Y-m-d')) // Start_date is today
            ->where('time', '>=', Carbon::now()->subMinutes(30)->format('H:i:00')) //now <= time +30'
            ->where('teacher_id', Auth::id())
            ->orderBy('created_at', 'DESC')
            ->first();
    }

    /**
     * get student lesson history counter
     * @return array
     */
    public function getLessonHistoryCounter()
    {
        $total_lessons = $this->countLessonHistoryWithDate(null, null);

        $this_week = $this->countLessonHistoryWithDate(Carbon::parse(Timezone::convertToLocal(Carbon::now(),
            'Y-m-d'))->endOfWeek()->subDays(6)->format('Y-m-d'),
            Carbon::parse(Timezone::convertToLocal(Carbon::now(), 'Y-m-d'))->endOfWeek()->format('Y-m-d'));

        $last_week = $this->countLessonHistoryWithDate(Carbon::parse(Timezone::convertToLocal(Carbon::now(),
            'Y-m-d'))->endOfWeek()->subDays(6)->subWeek()->format('Y-m-d'),
            Carbon::parse(Timezone::convertToLocal(Carbon::now(), 'Y-m-d'))->endOfWeek()->subWeek()->format('Y-m-d'));

        $this_month = $this->countLessonHistoryWithDate(Carbon::parse(Timezone::convertToLocal(Carbon::now(),
            'Y-m-d'))->firstOfMonth()->format('Y-m-d'),
            Carbon::parse(Timezone::convertToLocal(Carbon::now(), 'Y-m-d'))->endOfMonth()->format('Y-m-d'));

        $last_month = $this->countLessonHistoryWithDate(Carbon::parse(Timezone::convertToLocal(Carbon::now(),
            'Y-m-d'))->subMonthNoOverflow()->firstOfMonth()->format('Y-m-d'),
            Carbon::parse(Timezone::convertToLocal(Carbon::now(),
                'Y-m-d'))->subMonthNoOverflow()->endOfMonth()->format('Y-m-d'));

        $this_year = $this->countLessonHistoryWithDate(Carbon::parse(Timezone::convertToLocal(Carbon::now(),
            'Y-m-d'))->firstOfYear()->format('Y-m-d'),
            Carbon::parse(Timezone::convertToLocal(Carbon::now(), 'Y-m-d'))->endOfYear()->format('Y-m-d'));

        $last_year = $this->countLessonHistoryWithDate(Carbon::parse(Timezone::convertToLocal(Carbon::now(),
            'Y-m-d'))->subYear()->firstOfYear()->format('Y-m-d'),
            Carbon::parse(Timezone::convertToLocal(Carbon::now(), 'Y-m-d'))->subYear()->endOfYear()->format('Y-m-d'));

        $counter = [];
        $counter['this_week'] = $this_week;
        $counter['last_week'] = $last_week;
        $counter['this_month'] = $this_month;
        $counter['last_month'] = $last_month;
        $counter['this_year'] = $this_year;
        $counter['last_year'] = $last_year;
        $counter['total_lesson'] = $total_lessons;
        return $counter;
    }

    /**
     * get student lesson history table
     *
     * @param $student_id
     * @return array
     */
    public function listHistory($student_id)
    {
        $timezone = new TimezoneController();

        $lesson_histories = DB::table('lesson_histories')
            ->join('users', 'lesson_histories.teacher_id', '=', 'users.id')
            ->join('lessons', 'lesson_histories.lesson_id', '=', 'lessons.id')
            ->join('course', 'lessons.course_id', '=', 'course.id')
            ->where('lesson_histories.student_id', $student_id)
            ->leftJoin('teacher_review', 'teacher_review.lesson_histories_id', '=', 'lesson_histories.id')
            ->select('lesson_histories.*', 'users.email', 'users.nickname', 'course.name as course_name',
                'lessons.name as lesson_name', 'teacher_review.star', 'teacher_review.comment',
                'teacher_review.id as teacher_review_id')
            ->orderBy('lesson_histories.date', 'DESC')
            ->orderBy('lesson_histories.time', 'DESC')
            ->get()
            ->filter(function ($value) use ($timezone) {
                $date = Carbon::parse($value->date . ' ' . $value->time);
                $value->date = $timezone->convertToLocal($date, 'Y-m-d');
                $value->time = $timezone->convertToLocal($date, 'H:i');
                return $value;
            })
            ->toArray();
        $booking = DB::table('users')
            ->join('booking', 'users.id', '=', 'booking.student_id')
            ->join('teacher_schedule', 'booking.teacher_schedule_id', '=', 'teacher_schedule.id')
            ->where('users.role', '=', config('constants.role.student'))
            ->where('users.auth', '=', 1)
            ->where('teacher_schedule.status', '=', 2)
            ->where('booking.student_id', $student_id)
            ->whereRaw("CONCAT(teacher_schedule.start_date, ' ',teacher_schedule.start_hour) < ?",
                [Carbon::now()->subMinute(30)->format('Y-m-d H:i:s')])
            ->select('teacher_schedule.teacher_id as teacher_id',
                'teacher_schedule.start_date as date',
                'teacher_schedule.start_hour as time',
                'teacher_schedule.status as status',
                'booking.coin',
                DB::raw('(SELECT users.email FROM users WHERE users.id = teacher_schedule.teacher_id) AS email'),
                DB::raw('(SELECT users.nickname FROM users WHERE users.id = teacher_schedule.teacher_id) AS nickname'))
            ->get()
            ->filter(function ($value) use ($timezone) {
                $date = Carbon::parse($value->date . ' ' . $value->time);
                $value->date = $timezone->convertToLocal($date, 'Y-m-d');
                $value->time = $timezone->convertToLocal($date, 'H:i');
                return $value;
            })
            ->toArray();

        $lesson_histories = array_merge($lesson_histories, $booking);
        return $lesson_histories;
    }

    /**
     * @param $date_start
     * @param $date_end
     * @return int
     */
    public function countLessonHistoryWithDate($date_start, $date_end)
    {
        $timezone = new TimezoneController();
        $lesson_histories = DB::table('lesson_histories')
            ->where('lesson_histories.student_id', Auth::id());
        if ($date_start != null && $date_end != null) {
            $lesson_histories->whereRaw("CONCAT(lesson_histories.date, ' ', '24:00:00') >='" . $timezone->convertFromLocal($date_start)->format('Y-m-d H:i:s') . "'")
                ->whereRaw("CONCAT(lesson_histories.date, ' ', '00:00:00') <='" . $timezone->convertFromLocal($date_end)->format('Y-m-d H:i:s') . "'");
        }

        $lesson_histories = $lesson_histories->count();
        $booking = DB::table('users')
            ->join('booking', 'users.id', '=', 'booking.student_id')
            ->join('teacher_schedule', 'booking.teacher_schedule_id', '=', 'teacher_schedule.id')
            ->where('users.role', '=', config('constants.role.student'))
            ->where('users.auth', '=', 1)
            ->where('teacher_schedule.status', '=', 2)
            ->where('booking.student_id', '=', Auth::id())
            ->whereRaw("CONCAT(teacher_schedule.start_date, ' ',teacher_schedule.start_hour) < ?",
                [Carbon::now()->subMinute(30)->format('Y-m-d H:i:s')]);

        if ($date_start != null && $date_end != null) {
            $booking->whereRaw("CONCAT(teacher_schedule.start_date, ' ', '24:00:00') >='" . $timezone->convertFromLocal($date_start)->format('Y-m-d H:i:s') . "'")
                ->whereRaw("CONCAT(teacher_schedule.start_date, ' ', '00:00:00') <='" . $timezone->convertFromLocal($date_end)->format('Y-m-d H:i:s') . "'");
        }
        $booking = $booking->count();
        return $lesson_histories + $booking;
    }

    public function getCustomerId()
    {
        return DB::table('user_payment_info')->select('stripe_customer_id')->where('user_id', Auth::id())->first();
    }

    public function checkTime($id)
    {
        $bookingInformation = DB::table('booking')
            ->select(
                'booking.student_id',
                'booking.coin',
                'teacher_schedule.teacher_id',
                'teacher_schedule.start_date',
                'teacher_schedule.start_hour'
            )
            ->leftJoin('teacher_schedule', 'booking.teacher_schedule_id', '=', 'teacher_schedule.id')
            ->where('booking.id', $id)
            ->first();
        if (Carbon::now()->format('Y-m-d') == $bookingInformation->start_date && Carbon::now()->addMinute('5')->format('H:i:s') > $bookingInformation->start_hour && Carbon::now()->subMinute('11')->format('H:i:s') < $bookingInformation->start_hour) {
            return true;
        }
        return false;
    }

    public function checkTimeRemove($id)
    {
        $bookingInformation = DB::table('booking')
            ->select(
                'booking.student_id',
                'booking.coin',
                'teacher_schedule.teacher_id',
                'teacher_schedule.start_date',
                'teacher_schedule.start_hour'
            )
            ->leftJoin('teacher_schedule', 'booking.teacher_schedule_id', '=', 'teacher_schedule.id')
            ->where('booking.id', $id)
            ->first();
        if (Carbon::now()->format('Y-m-d') > $bookingInformation->start_date || (Carbon::now()->format('Y-m-d') == $bookingInformation->start_date && Carbon::now()->addHour('1')->format('H:i:s') > $bookingInformation->start_hour)) {
            return false;
        }
        return true;
    }

    public function removeBookingListById($input)
    {
        $getTeacherID = DB::table('users')
            ->select(
                'teacher_schedule.id',
                'teacher_schedule.start_date',
                'teacher_schedule.start_hour',
                'teacher_schedule.teacher_id',
                'booking.coin',

            )
            ->leftJoin('teacher_schedule', 'teacher_schedule.teacher_id', '=', 'users.id')
            ->leftJoin('booking', 'booking.teacher_schedule_id', '=', 'teacher_schedule.id')
            ->leftJoin('teacher_coin', 'teacher_coin.teacher_id', '=', 'teacher_schedule.teacher_id')
            ->where('booking.id', '=', $input)
            ->first();
        $status = false;
        $boolRefundCoin = false;

        $time = Helper::getTime($getTeacherID->start_date, $getTeacherID->start_hour);
//        if($time['years'] > 0 || $time['months'] > 0 || $time['days'] > 0){
//            // no refund coin
//
//            $boolRefundCoin = false;
//        }
//        else {
//            if( $time['years'] > 0 || $time['months'] > 0 || $time['days'] > 0 || $time['hours'] >= 1) {
//                // refund coin  student
//
//                $refundCoin = DB::table('booking')
//                ->leftJoin('student_total_coins', 'student_total_coins.student_id', '=', 'booking.student_id')
//                ->where('booking.student_id', '=', Auth::user()->id);
//                $refundCoin->update([
//                    'student_total_coins.updated_at'=> now()
//                ]);
//                $refundCoin->increment('student_total_coins.total_coin', $getTeacherID->coin);
//            }
//        }
        if (($time['years'] > 0 || $time['months'] > 0 || $time['days'] > 0 || $time['hours'] >= 1) && $getTeacherID->coin > 0) {
            $boolRefundCoin = true;
            $refundCoin = DB::table('booking')
                ->leftJoin('student_total_coins', 'student_total_coins.student_id', '=', 'booking.student_id')
                ->where('booking.student_id', '=', Auth::user()->id);
            $refundCoin->update([
                'student_total_coins.updated_at' => now()
            ]);
            $refundCoin->increment('student_total_coins.total_coin', $getTeacherID->coin);
        }
        // update history student use coin
        DB::beginTransaction();
        try {
//            $updateHistoryUseCoin = DB::table('history_student_use_coin')
//            ->where('history_student_use_coin.student_id', Auth::user()->id)
//            ->where('history_student_use_coin.teacher_id', $getTeacherID->id)
//            ->update(['history_student_use_coin.status' => 4]);
            if ($boolRefundCoin) {
                DB::table('history_student_use_coin')->insert([
                    'student_id' => Auth::id(),
                    'coin' => $getTeacherID->coin,
                    'teacher_id' => $getTeacherID->teacher_id,
                    'status' => config('constants.history_student_use_coin.return'), //4
                    'created_at' => now()
                ]);
            }
            // insert history student use payment

//            $addStudentUsePayment = DB::table('history_student_payment_coin')
//            ->insert([
//                'student_id' => Auth::user()->id,
//                'money' => 0,
//                'coin' => $getTeacherID->coin,
//            ]);

            // update status teacher schedule

            $updateTeacherSchedule = DB::table('booking')
                ->leftJoin('teacher_schedule', 'teacher_schedule.id', '=', 'booking.teacher_schedule_id')
                ->where('booking.id', $input)
                ->update([
                    'teacher_schedule.status' => config('constants.teacher_schedule.free_time'), //3
                    'teacher_schedule.updated_at' => now(),
                ]);

            //remove record of student_courses table by its teacher_schedule_id
            $removeStudentCourse = DB::table('student_courses')
                ->join('booking', 'booking.teacher_schedule_id', '=', 'student_courses.teacher_schedule_id')
                ->where('booking.student_id', '=', Auth::user()->id)
                ->where('booking.id', $input)
                ->delete();

            // remove record of booking table by its id
            $removeBookingList = DB::table('booking')
                ->where('booking.student_id', '=', Auth::user()->id)
                ->where('booking.id', $input)
                ->delete();

            DB::commit();
            if ($removeBookingList && $removeStudentCourse) {
                return true;
            }
            return false;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function checkDisabledButtonWithTime($data, $teacher_id)
    {
        $time = date('Y-m-d H:i:s',
            strtotime($this->timezone->convertFromLocal(Carbon::parse($data['start_date'] . " " . $data['start_hour'])->format('Y-m-d H:i:s'))));
        $data['start_date'] = date('Y-m-d', strtotime($time));
        $data['start_hour'] = date('H:i:s', strtotime($time));
        $schedule = DB::table('teacher_schedule')
            ->where('teacher_id', $teacher_id)
            ->where('start_hour', 'like', $data['start_hour'])
            ->where('start_date', 'like', $data['start_date'])
            ->first();
        if (!is_null($schedule) && Carbon::now()->addMinute('5')->format('H:i:s') > $schedule->start_hour && Carbon::now()->subMinute('5')->format('H:i:s') < $schedule->start_hour) {
            return true;
        }
        return false;
    }

    /**
     * get courses
     * @return Collection
     */
    public function getCourses()
    {
        return DB::table('course')
            ->select('*',
                DB::raw('(SELECT COUNT(lessons.id) FROM lessons WHERE lessons.course_id = course.id) AS num_of_lessons'))
            ->get();
    }

    public function getCourseById($id)
    {
        return DB::table('course')
            ->select('*',
                DB::raw('(SELECT COUNT(lessons.id) FROM lessons WHERE lessons.course_id = course.id) AS num_of_lessons'))
            ->where('id', $id)
            ->first();
    }

    public function getCourseInfoById($id)
    {
        return DB::table('lessons')
            ->select(
                'id',
                'name',
                'number',
                'text_link',
                'video_link'
            )
            ->where('lessons.course_id', $id)
            ->orderBy('lessons.number', 'ASC')
            ->get();
    }

    public function getAllCourse($data)
    {
        return DB::table('course')
            ->select(
                'course.id as course_id',
                'course.name as course_name',
                'lessons.id as lesson_id',
                'lessons.name as lesson_name'
            )
            ->join('lessons', 'lessons.course_id', '=', 'course.id')
            ->join('course_can_teach', 'course_can_teach.course_id', '=', 'course.id')
            ->where('course_can_teach.teacher_id', '=', $data['teacher_id'])
            ->get()->toArray();
    }

    public function updateLesson($data)
    {
        DB::beginTransaction();
        try {
            $record = DB::table('student_courses')
                ->join('teacher_schedule', 'teacher_schedule.id', 'student_courses.teacher_schedule_id')
                ->where('student_courses.teacher_schedule_id', '=', $data['teacher_schedule_id'])
                ->where('student_courses.student_id', '=', Auth::user()->id);
            $record->update([
                'student_courses.lesson_id' => $data['lesson_id'],
                'student_courses.updated_at' => now()
            ]);
            $schedule = $record->select(
                'teacher_schedule.start_date as start_date',
                'teacher_schedule.start_hour as start_hour',
                'teacher_schedule.teacher_id'
            )->first();
            $newRecord = DB::table('lessons')
                ->select(
                    'lessons.id as lesson_id',
                    'course.id as course_id',
                    'lessons.text_link',
                    'lessons.video_link',
                    'course.name as course_name',
                    'lessons.name as lesson_name'
                )
                ->join('course', 'course.id', '=', 'lessons.course_id')
                ->where('lessons.id', '=', $data['lesson_id'])
                ->first();
            $link = '';
            if(!empty($newRecord->text_link)) {
                $link = $link . '<a href="'. $newRecord->text_link .'" target="_blank">テキスト</a>&nbsp;&nbsp;&nbsp;';
            }
            if(!empty($newRecord->video_link)) {
                $link = $link . '<a class="btnShowVideo py-1 mb-0 pt-2" href="javascript:;" data-video_link="'. $newRecord->video_link .'">ビデオ</a>';
            }
            $content = '<div>生徒は予約したスケジュールのレッスンを変更しました。<br>日：'. date('Y-m-d', strtotime($schedule->start_date))
                .'<br>時間：'.date('H:i', strtotime($schedule->start_hour))
                .'<br>コース：'. $newRecord->course_name
                .'<br>レッスン：'. $newRecord->lesson_name
                .'<br><div>'. $link .'</div></div>';
            $notification_id =  Notifications::insertGetId([
                'title' => 'レッスンの変更',
                'content' => $content,
                'receiver_class' => '4',
                'start_date' => null,
                'end_date' => null,
                'created_by' => Auth::user()->id,
                'created_at' => CarBon::now(),
                'updated_at' => CarBon::now()
            ]);
            DB::table('receiver')
                ->insert([
                    'notification_id' => $notification_id,
                    'user_id' => $schedule->teacher_id
                ]);

            $pusherBody['id'] = $notification_id;
            $pusherBody['title'] = 'レッスンの変更';
            $pusherBody['content'] = $content;
            $pusherBody['created_at'] =  CarBon::now();
            $pusherBody['user_to'] = $schedule->teacher_id;

            DB::commit();
            return [$newRecord, $pusherBody];
        } catch (\Exception $e) {
            DB::rollBack();
            return null;
        }
    }

    public function getTeacherReview($id)
    {
        $review = DB::table('teacher_review')->where('teacher_id', $id)
            ->leftJoin('users', 'teacher_review.student_id', 'users.id')
            ->orderByDesc(DB::raw('!ISNULL(comment)'))
            ->orderBy('star' , 'desc')
            ->orderBy('created_at' , 'desc')
            ->select('users.nickname', 'teacher_review.star', 'teacher_review.comment', 'teacher_review.created_at')
            ->paginate(5);
        return $review;
    }

    public function getNumberStar($id)
    {
        $star = [];
        $resultStar = DB::table('teacher_review')->where('teacher_id', $id)->count();

        $star['5star'] = DB::table('teacher_review')->where('teacher_id', $id)
            ->where('star', 5)->get()->count();
        $star['4star'] = DB::table('teacher_review')->where('teacher_id', $id)
            ->where('star', 4)->get()->count();
        $star['3star'] = DB::table('teacher_review')->where('teacher_id', $id)
            ->where('star', 3)->get()->count();
        $star['2star'] = DB::table('teacher_review')->where('teacher_id', $id)
            ->where('star', 2)->get()->count();
        $star['1star'] = DB::table('teacher_review')->where('teacher_id', $id)
            ->where('star', 1)->get()->count();
        if ($resultStar == 0) {
            $star['5'] = $star['4'] = $star['3'] = $star['2'] = $star['1'] = 0;
            $star['result'] = $resultStar;
            return $star;
        }
        $star['5'] = $star['5star'] / $resultStar * 100;
        $star['4'] = $star['4star'] / $resultStar * 100;
        $star['3'] = $star['3star'] / $resultStar * 100;
        $star['2'] = $star['2star'] / $resultStar * 100;
        $star['1'] = $star['1star'] / $resultStar * 100;
        $star['result'] = $resultStar;
        return $star;
    }

    /**
     * @return Builder|object|null
     */
    public function getLatestLesson() {
        return DB::table('lessons')
            ->join('course','lessons.course_id','=','course.id')
            ->select('lessons.*')
            ->whereRaw('course.level_id = (SELECT MIN(level_id) FROM course)')
            ->whereRaw('lessons.number = (SELECT MAX(number) FROM lessons WHERE course_id = course.id)')
            ->first();
    }

    /**
     * @return Builder|object|null
     */
    public function getCurrentLesson() {
        return DB::table('student_courses')
            ->select('*')
            ->where('student_id', '=', Auth::id())
            ->orderBy('updated_at','DESC')
            ->first();
    }
}
