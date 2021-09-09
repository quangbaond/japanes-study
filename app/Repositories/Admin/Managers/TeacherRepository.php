<?php
namespace App\Repositories\Admin\Managers;

use App\Http\Controllers\TimezoneController;
use App\Models\User;
use App\Models\UserInformation;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Services\MailService;
use MacsiDigital\Zoom\Facades\Zoom;
use function JmesPath\search;
use Timezone;

class TeacherRepository
{
    /**
     * Create a new repository instance.
     *
     * @return void
     */
    protected $mailServices;

    /**
     * Display a listing of the resource.
     *
     * @param MailService $mailServices
     */
    public function __construct(MailService $mailServices)
    {
        $this->mailServices = $mailServices;
    }
	/**
     * Function insert teacher.
     *
     * @param $input
     * @return bool
     */
    public function insertTeacher($input)
    {
        DB::beginTransaction();
        try {
            // Insert table users with role: teacher
            $password = $this->generateRandomString(9);
            $user_id = DB::table('users')
                ->insertGetId([
                    'email'         => $input['email'],
                    'nickname'      => $input['nickname'],
                    'role'          => config('constants.role.teacher'),
                    'auth'          => 1,
                    'password'      => bcrypt($password),
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);

            // Insert AMZ S3
            if (!empty($input['image_photo'])) {
                $file = $input["image_photo"];
                $name = time() . rand();
                $filePath = $name;
                Storage::disk('s3')->put($filePath, file_get_contents($file), 'public');

                // Get url images
                if (Storage::disk('s3')->exists($filePath)) {
                    $image_photo_insert = Storage::disk('s3')->url($filePath);
                } else {
                    $image_photo_insert = null;
                }
            } else {
                $image_photo_insert = null;
            }

            // Insert table user_information
            $birthday = !empty($input['year']) ? date('Y-m-d', strtotime($input['year'] . '-' . $input['month'] . '-' . $input['day'])) : null;
            $age = !empty($input['year']) ? (int)date_diff(date_create($birthday), date_create('today'))->y : null;
            $data = [
                'user_id'                   => $user_id,
                'sex'                       => !empty($input['sex']) ? $input['sex'] : null,
                'nationality'               => !empty($input['nationality']) ? $input['nationality'] : null,
                'introduction_from_admin'   => !empty($input['introduction_from_admin']) ? $input['introduction_from_admin'] : null,
                'phone_number'              => !empty($input['phone_number']) ? $input['phone_number'] : null,
                'area_code'                 => !empty($input['area_code']) ? $input['area_code'] : null,
                'image_photo'               => $image_photo_insert,
                'birthday'                  => $birthday,
                'age'                       => $age,
                'created_at'                => now(),
                'updated_at'                => now(),
            ];
            DB::table('user_information')->insert($data);
            $data_mail  = array('nickname' => $input['nickname'], 'email' => $input['email'],'password' => $password,'role' => config('constants.role.teacher'),'title' => '[Study Japanese] ログイン情報のお知らせ','url' => route('login.teacher'));
            $data_coin = [
                'teacher_id' => $user_id,
                'coin' => $input['lessonCoin'],
                'created_at' => now(),
                'updated_at' => now()
            ];
            $course_can_teacher = [];
            for($i = 0; $i < count($input['course']); ++$i) {
                array_push($course_can_teacher,[
                    'course_id' => $input['course'][$i],
                    'teacher_id' => $user_id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
            DB::table('course_can_teach')->insert($course_can_teacher);
            DB::table('teacher_coin')->insert($data_coin);
            $this->mailServices->sendLoginInfoMail($data_mail);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }
	/**
     * Function update teacher.
     *
     * @param $input
     * @param $user
     * @return bool
     */
    public function updateTeacher($input, $user)
    {
        DB::beginTransaction();
        try {
            // Update table users
            $password = $this->generateRandomString(9);
            DB::table('users')
                ->where('email', $input['email'])
                ->update([
                    'nickname'      => $input['nickname'],
                    'password'      => bcrypt($password),
                    'last_seen'     => null,
                    'last_login_at' => null,
                    'deleted_at'    => null,
                    'updated_at'    => now(),
                ]);

            // Update AMZ S3
            $image_photo_update = null;
            // Delete image old in AMZ S3
            $image_photo = DB::table('user_information')->select('image_photo')->where('user_id', $user->id)->first();
            if (!empty($image_photo)) {
                $array = explode("/", $image_photo->image_photo);
                $img = max(array_keys($array));
                $this->deleteImageS3($array[$img]);
            }
            if (!empty($input['image_photo'])) {
                // Insert image new in AMZ S3
                $file = $input["image_photo"];
                $name = time() . rand();
                $filePath = $name;
                Storage::disk('s3')->put($filePath, file_get_contents($file), 'public');

                // Exits url image amazon s3
                if (Storage::disk('s3')->exists($filePath)) {
                    $image_photo_update = Storage::disk('s3')->url($filePath);
                }
            }

            // Update table user_information
            $birthday = !empty($input['year']) ? date('Y-m-d', strtotime($input['year'] . '-' . $input['month'] . '-' . $input['day'])) : null;
            $age = !empty($input['year']) ? (int)date_diff(date_create($birthday), date_create('today'))->y : null;
            $data = [
                'sex'                       => !empty($input['sex']) ? $input['sex'] : null,
                'nationality'               => !empty($input['nationality']) ? $input['nationality'] : null,
                'introduction_from_admin'   => !empty($input['introduction_from_admin']) ? $input['introduction_from_admin'] : null,
                'phone_number'              => !empty($input['phone_number']) ? $input['phone_number'] : null,
                'area_code'                 => !empty($input['area_code']) ? $input['area_code'] : null,
                'image_photo'               => $image_photo_update,
                'birthday'                  => $birthday,
                'age'                       => $age,
                'updated_at'                => now(),
            ];
            $data_mail  = array('nickname' => $input['nickname'], 'email' => $input['email'],'password' => $password,'role' => config('constants.role.teacher'),'title' => '[Study Japanese] ログイン情報のお知らせ','url' => route('login.teacher'));

            $data_coin = [
                'teacher_id' => $user->id,
                'coin' => $input['lessonCoin'],
                'created_at' => now(),
                'updated_at' => now()
            ];
            $course_can_teacher = [];
            for($i = 0; $i < count($input['course']); ++$i) {
                array_push($course_can_teacher,[
                    'course_id' => $input['course'][$i],
                    'teacher_id' => $user->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
            DB::table('course_can_teach')->insert($course_can_teacher);
            DB::table('teacher_coin')->insert($data_coin);

            $this->mailServices->sendLoginInfoMail($data_mail);
            DB::table('user_information')->where('user_id', $user->id)->update($data);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }


    public function getAllCourses() {
        return DB::table('course')
            ->select(
                'id',
                'name'
            )
            ->get();
    }
    /**
     * Create teacher form.
     *
     * @param $input
     * @return array
     */
    public function createTeacher($input){
        $user = DB::table('users')->select('id', 'role', 'deleted_at')->where('email', $input['email'])->first();
        if ($user) {
            if (empty($user->deleted_at) || $user->role != config('constants.role.teacher')) {
                // Error
                return [
                    'status'    => false,
                    'message'   => config('constants.email_isset'),
                ];
            }
            // Update student
            $check = $this->updateTeacher($input, $user);
            if($check == true){
               return [
                    'status'    => true,
                    'message'   => config('constants.register_success')
                ];
            } else {
                return [
                    'status'    => false,
                    'message'   => __('validation_custom.M028')
                ];
            }

        } else {
            // Insert teacher
            $check = $this->insertTeacher($input);
            if($check == true){
               return [
                    'status'    => true,
                    'message'   => config('constants.register_success')
                ];
            } else {
                return [
                    'status'    => false,
                    'message'   => __('validation_custom.M007')
                ];
            }
        }
    }

    protected function generateRandomString($length = 9) {
        return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
    }

    protected function deleteImageS3($name)
    {
        if (Storage::disk('s3')->delete($name)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Function delete teacher.
     *
     * @param $input
     * @return bool
     */
    public function deleteAll($input)
    {
        DB::beginTransaction();
        try {
            DB::table('users')
                ->whereIn('id', $input['user_id'])
                ->update([
                    'deleted_by' => Auth::user()->id,
                    'deleted_at' => now(),
                    'updated_at' => now(),
                ]);
            DB::table('course_can_teach')
                ->whereIn('teacher_id', $input['user_id'])
                ->delete();
            DB::table('teacher_coin')
                ->whereIn('teacher_id', $input['user_id'])
                ->delete();

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    public function getScheduleData($date_start, $date_end)
    {
        $timezone = new TimezoneController();
        $data = DB::table('teacher_schedule')
            ->select('*')
            ->where('teacher_id', Auth::user()->id);
        $data->whereRaw('CONCAT(start_date," ",start_hour) >= ?', [$timezone->convertFromLocal($date_start)]);
        $data->whereRaw('CONCAT(start_date," ",start_hour) <= ?',[$timezone->convertFromLocal($date_end)]);
//        $data->where('status', '=', '2');
        if (!empty($_GET['status'])) {
            if ($_GET['status'] != 4) {
                $data->where('status', '=', $_GET['status']);
            }
        }
        $data->whereNull('deleted_at');
        $data->orderBy('start_hour', 'ASC');
        $data = $data->get()
            ->filter(function ($value) use ($timezone) {
                $date = Carbon::parse($value->start_date.' '.$value->start_hour);
                $value->start_hour = $timezone->convertToLocal($date, "H:i");
                $value->start_date = $timezone->convertToLocal($date , "Y-m-d");
                if (!empty($_GET['from_time'])) {
                    if(Carbon::parse($value->start_hour)->lt(Carbon::parse($_GET['from_time'])))
                        return null;
                }
                if (!empty($_GET['to_time'])) {
                    if(Carbon::parse($value->start_hour)->gt(Carbon::parse($_GET['to_time'])))
                        return null;
                }
                return $value;
            });
        $data = $data->toArray();
        //array start from index = 0
        $temp = [];
        foreach ($data as $schedule) {
            array_push($temp, $schedule);
        }
        //sort array
        for ($i = 0 ; $i < sizeof($temp) -1 ; $i++) {
            for ($j = $i + 1; $j < sizeof($temp); $j++) {
                if(strtotime($temp[$i]->start_hour) >= strtotime($temp[$j]->start_hour)) {
                    $schedule_temp = $temp[$j];
                    $temp[$j] = $temp[$i];
                    $temp[$i] = $schedule_temp;
                }
            }
        }

        return $temp;
    }


    /**
     * insert Schedule
     */
    public function addSchedule($data, $list_exits, $remove)
    {
        $timezone = new TimezoneController();
        DB::beginTransaction();
        try {
            if (!empty($remove)) {
                foreach ($remove as $item_remove) {
                    $info = DB::table('teacher_schedule')->where('id', $item_remove)->first();
                    if($info){
                        $time = $info->start_date.' '.$info->start_hour;
                        if(strtotime($time) - time() >= 1800){

                            DB::table('teacher_schedule')->where('id', $item_remove)->where('status', 3)->delete();
                        } else {
                            return false;
                        }
                    }

                }

            }
            foreach ($data as $key => $value) {
                //DB::table('teacher_schedule')->where('start_date',$key)->where('status',3)->delete();
                $tmp_key = array_keys($value);
                foreach ($value as $key2 => $p) {
                    //echo $key2;die;
                    //print_r($list_exits);die;
                    if (isset($list_exits[$key2])) {
                        $id = $list_exits[$key2];
                        $info = DB::table('teacher_schedule')
                            ->where('id', $id)
                            ->where('teacher_id', Auth::id())
                            ->first();
                    } else {
                        $info = DB::table('teacher_schedule')
                            ->where('start_date', $key)
                            ->where('start_hour', $p)
                            ->where('teacher_id', Auth::id())
                            ->first();
                    }

                    if (!$info) {
                        $date = $timezone->convertFromLocal($key .' '. $p);
                        $data_insert = [
                            'teacher_id' => Auth::id(),
                            'start_date' => $date->format('Y-m-d'),
                            'start_hour' => $date->format('H:i:00'),
                            'status' => 3,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                        //print_r($data_insert);die;
                        DB::table('teacher_schedule')->insert($data_insert);
//                        dd( DB::table('teacher_schedule')->insert($data_insert));
                    } else {
                        $date = Carbon::parse($timezone->convertToLocal(Carbon::parse($info->start_date .' '. $info->start_hour), 'Y-m-d H:i:s'));
                        $info->start_date = $date->format('Y/m/d');
                        $info->start_hour = $date->format('H:i:00');
                        if ($info->status == 3 && $info->start_hour != $p . ':00') {
                            $date = $timezone->convertFromLocal($info->start_date .' '. $p);
                            $p = $date->format('H:i:00');
                            $data_update = [
                                'id' => $info->id,
                                'start_hour' => $p,
                                'updated_at' => now(),
                            ];

                            DB::table('teacher_schedule')->where('id', $info->id)->update($data_update);
                        } elseif ($info->status == 3 && !empty($info->deleted_at)) {
                            $date = $timezone->convertFromLocal($info->start_date .' '. $p);
                            $p = $date->format('H:i:00');
                            $data_update = [
                                'id' => $info->id,
                                'start_hour' => $p,
                                'updated_at' => now(),
                                'deleted_at' => null
                            ];
                            DB::table('teacher_schedule')->where('id', $info->id)->update($data_update);
                        }
                    }
                }
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return false;
        }

    }

    /**
     * remove schedule by list id
     */
    public function removeSchedule($data)
    {
        DB::beginTransaction();
        try {
            foreach ($data as $key => $value) {

                $info = DB::table('teacher_schedule')->where('id', $value)->first();
                if($info){
                    $time = $info->start_date.' '.$info->start_hour;
                    if(strtotime($time) - time() >= 1800){

                        DB::table('teacher_schedule')->where('id', $value)->where('status', '=',3)->delete();
                    } else {
                        return false;
                    }
                }


            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            dd($e);
            DB::rollback();
            return false;
        }
    }

    public function addScheduleWithStudent($data)
    {
        DB::beginTransaction();
        try {

            //booked
            if( $data['coin'] > 0  && !(isset($data['book_type']) && $data['book_type'] == '2')) {

                $history_use_coin = [
                    'student_id' => $data['student_id'],
                    'coin' => $data['coin'],
                    'teacher_id' => Auth::id(),
                    'status' => config('constants.history_student_use_coin.start_lesson_now'),
                    'created_at' => now()
                ];
                DB::table('history_student_use_coin')->insert($history_use_coin);
                $total_coin = DB::table('student_total_coins')
                    ->where('student_id', $data['student_id']);
                $total_coin->update(['updated_at' => now()]);
                $total_coin->decrement('total_coin', $data['coin']);

            }
            //sudden lesson
            if(isset($data['type']) && $data['type'] == '1' && is_null($data['book_type'])) {
                $teacher_schedule_id = DB::table('teacher_schedule')->select('id')
                    ->where('start_hour', 'like', '%' . $data['start_hour'] . '%')
                    ->where('start_date', 'like', '%' . $data['start_date'] . '%')
                    ->first();
                $student_course_data = [
                    'student_id' => $data['student_id'],
                    'lesson_id' => $data['lesson_id'],
                    'teacher_schedule_id' => $teacher_schedule_id->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                DB::table('student_courses')->insert($student_course_data);
            }
            DB::table('teacher_schedule')
                ->where('teacher_id', Auth::id())
                ->where('start_hour', 'like', $data['start_hour'] . "%")
                ->where('start_date', 'like', $data['start_date'])
                ->where('status', '=', config('constants.teacher_schedule.booking'))
                ->update([
                    'status' => config('constants.teacher_schedule.done'),
                    'updated_at' => now(),
                ]);
//            $lesson_name = DB::table('lessons')
//                ->select('name','id')
//                ->where('id', $data['lesson_id'])
//                ->where('course_id', $data['course_id'])
//                ->first();
            $zoom_link = $this->makeMeetingRoomWithZoomApplication($data['teacher_id']);
            DB::table('lesson_histories')->insert([
                'student_id' => $data['student_id'],
                'lesson_id' => $data['lesson_id'],
                'course_id' => $data['course_id'],
                'teacher_id' => Auth::id(),
                'date' => $data['start_date'],
                'time' => $data['start_hour'],
                'zoom_link' => $zoom_link,
                'type' => $data['type'],
                'coin' => $data['coin'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            DB::commit();
            return $zoom_link;
        } catch (\Throwable $th) {
            dd($th);
            DB::rollback();
            return null;
        }
    }

    public function makeMeetingRoomWithZoomApplication($teacher_id)
    {
//        dd($topic);
//        $user = Zoom::user()->find('khoivinhphan@gmail.com');
//
//        $meeting = Zoom::meeting()->make([
//            'topic' => $topic,
//            'start_time' => new Carbon('2020-12-16 18:00:00'),
//            'password' => 'scret123',
//            "duration" => 30,
//            'settings' => [
//                'join_before_host' => true,
//            ]
//        ]);
//        $meeting->recurrence()->make([
//            'type' => 2,
//            'repeat_interval' => 1,
//            'weekly_days' => "1",
//            'end_times' => 3
//        ]);
//        $user->meetings()->save($meeting);
//        return $meeting->join_url;
        $data = DB::table('users')
            ->select('user_information.link_zoom')
            ->leftJoin('user_information', 'user_information.user_id', '=', 'users.id')
            ->where('users.id', '=', $teacher_id)
            ->first();
        return $data->link_zoom;
    }

    public function isSuddenOrBookedToCancel($data) {
        return DB::table('teacher_schedule')
            ->join('student_courses', 'student_courses.teacher_schedule_id', '=', 'teacher_schedule.id')
            ->where('teacher_schedule.teacher_id', Auth::id())
            ->where('teacher_schedule.start_hour', 'like', $data['start_hour'])
            ->where('teacher_schedule.start_date', 'like', $data['start_date'])
            ->where('student_courses.lesson_id', '=', $data['lesson_id'])
            ->first();
    }
    public function changeStatusOfTeacher($data, $status_schedule, $before_status)
    {
        DB::beginTransaction();
        try {
            $exist = DB::table('teacher_schedule')
                ->where('teacher_id', Auth::id())
                ->where('start_hour', 'like', $data['start_hour'])
                ->where('start_date', 'like', $data['start_date'])
                ->where('status', $before_status)->first();
            if (!is_null($exist)) {
                $exist = DB::table('teacher_schedule')
                    ->where('teacher_id', Auth::id())
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
     * @param
     * @return object
     */
    public function teacherDetail($id)
    {
        try {
            $teacherDetail = DB::table('users')
                ->select(
                    'users.id',
                    'users.email',
                    'users.nickname',
                    // 'user_information.id',
                    'user_information.birthday',
                    'user_information.user_id',
                    'user_information.age',
                    'user_information.sex',
                    'user_information.area_code',
                    'user_information.nationality',
                    'user_information.phone_number',
                    'user_information.experience',
                    'user_information.image_photo',
                    'user_information.introduction_from_admin',
                    'user_information.certification',
                    'user_information.link_youtube',
                    'user_information.self-introduction as introduction',
                    'teacher_coin.coin'
                )
                ->leftJoin('user_information', 'user_information.user_id', '=', 'users.id')
                ->leftJoin('teacher_coin', 'teacher_coin.teacher_id', '=', 'users.id')
                ->where('users.id', '=', $id)
                ->whereNull('users.deleted_at')
                ->first();
            return $teacherDetail;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getAllCourseOfTeacherById($id) {
        return DB::select( DB::raw("SELECT course.*,course_can_teach.* FROM course LEFT JOIN ((select course_can_teach.course_id,users.id as user_id from course_can_teach INNER JOIN users on course_can_teach.teacher_id = users.id where users.id= ".$id." ) as course_can_teach) on course.id = course_can_teach.course_id") );
//        return DB::table('course')
//            ->select('course.*',
//            'course_can_teacher.*'
//            )
//            ->leftJoin('')
    }

    /**
     * @param $id
     * @return \Illuminate\Support\Collection
     */
    public function todaySchedule($id)
    {
        $today = Carbon::now()->format('Y-m-d');
//        $today = Carbon::today()->format('Y-m-d');
        return DB::table('booking')
            ->join('teacher_schedule', 'booking.teacher_schedule_id', '=', 'teacher_schedule.id')
            ->join('users', 'booking.student_id', '=', 'users.id')
            ->join('student_courses', 'booking.teacher_schedule_id', '=', 'student_courses.teacher_schedule_id')
            ->join('lessons', 'lessons.id', '=', 'student_courses.lesson_id')
            ->join('course', 'course.id', '=', 'lessons.course_id')
            ->select('users.nickname as user_nickname',
                'users.id as student_id', 'users.email as user_email',
                'teacher_schedule.start_hour',
                'teacher_schedule.start_date',
                'lessons.name as lesson_name',
                'lessons.text_link',
                'lessons.video_link',
                'course.name as course_name',
                'teacher_schedule.id as schedule_id'
            )
            ->where([['teacher_schedule.start_date', $today],
                ['teacher_schedule.teacher_id', $id],
                ['teacher_schedule.start_hour','>=', Carbon::now()->subMinutes(30)->format('H:i:00')]])
            ->whereIn('teacher_schedule.status', [2, 3])
            ->orderBy('teacher_schedule.start_hour')
            ->get()
            ->filter(function ($value) {
                $value->start_hour = Timezone::convertToLocal(Carbon::parse($value->start_date.' '.$value->start_hour) , "H:i");
                $value->start_date = Timezone::convertToLocal(Carbon::parse($value->start_date.' '.$value->start_hour) , "Y-m-d");
                return $value;
            });

    }

    /**
     * get counter
     * @param $id
     * @return array
     */
    public function getLessonCounter($id)
    {
        $this_week = $this->countTeacherLessonHistoryWithDate(Carbon::parse(Timezone::convertToLocal(Carbon::now(),'Y-m-d 00:00:00'))->endOfWeek()->subDays(6)->format('Y-m-d 00:00:00'), Carbon::parse(Timezone::convertToLocal(Carbon::now(),'Y-m-d 00:00:00'))->endOfWeek()->format('Y-m-d 23:59:00'));

        $last_week = $this->countTeacherLessonHistoryWithDate(Carbon::parse(Timezone::convertToLocal(Carbon::now(),'Y-m-d 00:00:00'))->endOfWeek()->subDays(6)->subWeek()->format('Y-m-d 00:00:00'), Carbon::parse(Timezone::convertToLocal(Carbon::now(),'Y-m-d 00:00:00'))->endOfWeek()->subWeek()->format('Y-m-d 23:59:00'));

        $this_month = $this->countTeacherLessonHistoryWithDate(Carbon::parse(Timezone::convertToLocal(Carbon::now(),'Y-m-d 00:00:00'))->firstOfMonth()->format('Y-m-d 00:00:00'), Carbon::parse(Timezone::convertToLocal(Carbon::now(),'Y-m-d 00:00:00'))->endOfMonth()->format('Y-m-d 23:59:00'));

        $last_month = $this->countTeacherLessonHistoryWithDate(Carbon::parse(Timezone::convertToLocal(Carbon::now(),'Y-m-d 00:00:00'))->subMonthNoOverflow()->firstOfMonth()->format('Y-m-d 00:00:00'), Carbon::parse(Timezone::convertToLocal(Carbon::now(),'Y-m-d 00:00:00'))->subMonthNoOverflow()->endOfMonth()->format('Y-m-d 23:59:00'));

        $this_year = $this->countTeacherLessonHistoryWithDate(Carbon::parse(Timezone::convertToLocal(Carbon::now(),'Y-m-d 00:00:00'))->firstOfYear()->format('Y-m-d 00:00:00'), Carbon::parse(Timezone::convertToLocal(Carbon::now(),'Y-m-d 00:00:00'))->endOfYear()->format('Y-m-d 23:59:00'));

        $last_year = $this->countTeacherLessonHistoryWithDate(Carbon::parse(Timezone::convertToLocal(Carbon::now(),'Y-m-d 00:00:00'))->subYear()->firstOfYear()->format('Y-m-d 00:00:00'), Carbon::parse(Timezone::convertToLocal(Carbon::now(),'Y-m-d 00:00:00'))->subYear()->endOfYear()->format('Y-m-d 23:59:00'));


//        dd(Carbon::parse(Timezone::convertToLocal(Carbon::now(),'Y-m-d 00:00:00'))->subMonthNoOverflow()->firstOfMonth()->format('Y-m-d 00:00:00'), Carbon::parse(Timezone::convertToLocal(Carbon::now(),'Y-m-d 00:00:00'))->subMonthNoOverflow()->endOfMonth()->format('Y-m-d 24:00:00'));
        $counter = [];
        $counter['this_week'] = $this_week;
        $counter['last_week'] = $last_week;
        $counter['this_month'] = $this_month;
        $counter['last_month'] = $last_month;
        $counter['this_year'] = $this_year;
        $counter['last_year'] = $last_year;
//        dd($counter);
        return $counter;
    }

    /**
     * get num of teacher lesson histories
     * by sitranv
     * @param $date_start
     * @param $date_end
     * @return int
     */
    public function countTeacherLessonHistoryWithDate($date_start, $date_end) {
        $timezone = new TimezoneController();

        $lesson_histories = DB::table('lesson_histories')
            ->where('lesson_histories.teacher_id','=', Auth::id())
            ->whereRaw("CONCAT(lesson_histories.date, ' ', '23:59:00') >='".$timezone->convertFromLocal($date_start)->format('Y-m-d H:i:s')."'")
            ->whereRaw("CONCAT(lesson_histories.date, ' ', '00:00:00') <='".$timezone->convertFromLocal($date_end)->format('Y-m-d H:i:s')."'")
            ->count();

        $booking  = DB::table('users')
            ->join('teacher_schedule','users.id','=','teacher_schedule.teacher_id')
            ->join('booking','booking.teacher_schedule_id','=','teacher_schedule.id')
            ->where('teacher_schedule.status','=',2)
            ->where('users.id','=', Auth::id())
            ->whereRaw("CONCAT(teacher_schedule.start_date, ' ',teacher_schedule.start_hour) < ?", [ Carbon::now()->subMinute(30)->format('Y-m-d H:i:s') ])
            ->whereRaw("CONCAT(teacher_schedule.start_date, ' ', '23:59:00') >='".$timezone->convertFromLocal($date_start)->format('Y-m-d H:i:s')."'")
            ->whereRaw("CONCAT(teacher_schedule.start_date, ' ', '00:00:00') <='".$timezone->convertFromLocal($date_end)->format('Y-m-d H:i:s')."'")
            ->count();

        return $booking + $lesson_histories;
    }
    /**
     * reset password for teacher by id
     * by Thachdh
     * @param $id
     * @return bool
     */
    public function resetPasswordForTeacherById($id) {
        $password = $this->generateRandomString(9);
        DB::beginTransaction();
        try {
            DB::table('users')
                ->where('users.id', $id)
                ->update([
                'password' => bcrypt($password),
                'updated_at' => now()
            ]);
            $teacher = DB::table('users')->select('users.nickname',
                'users.email',
                'users.password')
                ->where('users.id', $id)->first();
            $data_mail = array('nickname' => $teacher->nickname,
                'email' => $teacher->email,
                'password' => $password,
                'title' => ' [Study Japanese] パスワードリセット成功のお知らせ',
                'url' => route('login.teacher'));
            $content_page = 'mails.mail-reset-password-teacher';
            $this->mailServices->sendInfoResetPassword($data_mail, $content_page);
            DB::commit();
            return true;
        }
        catch (\Exception $exception) {
            DB::rollBack();
            return false;
        }
    }

    /**
     * @param $data
     * @param $id
     */
    public function updateProfileForTeacherById($data, $id) {
        DB::beginTransaction();
        try{
            $age = $data['birthday'] != null ? (int)date_diff(date_create($data['birthday']), date_create('today'))->y : null;
            $data_profile = array(
                'user_id'           => $id,
                'sex'               => !empty($data['sex']) ? $data['sex'] : null,
                'nationality'       => !empty($data['nationality']) ? $data['nationality'] : null,
                'membership_status' => !empty($data['membership_status']) ? $data['membership_status'] : null,
                'phone_number'      => !empty($data['phone_number']) ? $data['phone_number'] : null,
                'area_code'         => !empty($data['area_code']) ? $data['area_code'] : null,
                'company_id'        => !empty($data['company_id']) ? $data['company_id'] : null,
                'introduction_from_admin' => !empty($data['introduction_from_admin']) ? $data['introduction_from_admin'] : null,
                'experience'        => !empty($data['experience']) ? $data['experience'] : null,
                'birthday'          => $data['birthday'],
                'certification'     => $data['certification'],
                'age'               => $age,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            );

            //Update users
            DB::table('users')
                ->where('id', $id)
                ->update([
                    'nickname'  => $data['nickname'],
                    'updated_at' => now()
                ]);
            $new_course = explode(',', $data['course']);
            $course_can_teach = [];
            foreach ($new_course as $value) {
                array_push($course_can_teach,[
                    'course_id' => $value,
                    'teacher_id' => $id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }

            //update course
            $course = DB::table('course_can_teach')->where('teacher_id', $id);
            $course->delete();
            if($data['course'] != '') {
                $course->insert($course_can_teach);
            }

            //update  coin

            if($data['coin'] != '') {
                $teacher_coin = DB::table('teacher_coin')->where('teacher_id', $id)->first();
                if($teacher_coin) {
                    DB::table('teacher_coin')
                        ->where('teacher_id', $id)
                        ->update([
                        'coin' => $data['coin'],
                        'updated_at' => Carbon::now()
                    ]);
                }
                else {
                    DB::table('teacher_coin')->insert([
                        'teacher_id' => $id,
                        'coin' => $data['coin'],
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ]);
                }
            }

            $check_profile = DB::table('user_information')->select('id')->where('user_id', $id)->first();

            if ( !empty($check_profile) ) {
                //Update user profile
                UserInformation::find($check_profile->id)->update($data_profile);
            } else {
                //Create user profile
                UserInformation::create($data_profile);
            }

            DB::commit();
            return true;
        } catch(\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    /**
     * @return object|null
     */
    public function getTeacherZoomLink() {
        return DB::table('users')
            ->leftJoin('user_information', 'users.id', '=', 'user_information.user_id')
            ->select('users.*', 'user_information.link_zoom')
            ->where('users.id', Auth::id())
            ->where('users.role', config('constants.role.teacher'))
            ->first();
    }

    /**
     * get teacher lesson history statistics
     * by sitranv
     * @return array
     */
    public function getTeacherStatistic() {
        $timezone = new TimezoneController();
        $data_lesson_histories = DB::table('users')
            ->join('lesson_histories', 'lesson_histories.teacher_id', '=','users.id')
            ->where('users.role', '=' , config('constants.role.teacher'))
            ->where('users.auth', '=',1)
            ->groupBy( 'users.id',
                'users.nickname',
                'users.email');

        // Search teacher ID
            if (!empty($_GET["teacher_id"]) && empty($_GET["teacher_email"])) {
                $data_lesson_histories->whereIn('users.id', $_GET["teacher_id"]);
            }

            if (empty($_GET["teacher_id"]) && !empty($_GET["teacher_email"])) {
                $data_lesson_histories->whereIn('users.id', $_GET["teacher_email"]);
            }

            if (!empty($_GET["teacher_id"]) && !empty($_GET["teacher_email"])) {
                $array_id = array_merge($_GET["teacher_id"], $_GET['teacher_email']);
                $data_lesson_histories->whereIn('users.id', $array_id);
            }

        // Search date
            if (!empty($_GET["date_from"]) && empty($_GET["date_to"])) {
                $data_lesson_histories->whereRaw("CONCAT(lesson_histories.date, ' ', lesson_histories.time) >='".$timezone->convertFromLocal($_GET["date_from"].' 00:00:00')->format('Y-m-d H:i:s')."'")
                ->select('users.id as teacher_id',
                    'users.nickname as teacher_nickname',
                    'users.email as teacher_email',
                    DB::raw("(SELECT COUNT(lesson_histories.id) FROM lesson_histories WHERE lesson_histories.teacher_id = users.id AND CONCAT(lesson_histories.date, ' ', lesson_histories.time) >='".$timezone->convertFromLocal($_GET["date_from"].' 00:00:00')->format('Y-m-d H:i:s')."' ) AS total_lessons"),
                    DB::raw("(SELECT SUM(lesson_histories.coin) FROM lesson_histories WHERE lesson_histories.teacher_id = users.id AND CONCAT(lesson_histories.date, ' ', lesson_histories.time) >='".$timezone->convertFromLocal($_GET["date_from"].' 00:00:00')->format('Y-m-d H:i:s')."' )  AS total_coins")
                );
            }

            if (empty($_GET["date_from"]) && !empty($_GET["date_to"])) {
                $data_lesson_histories->whereRaw("CONCAT(lesson_histories.date, ' ',lesson_histories.time) <='".$timezone->convertFromLocal($_GET["date_to"].' 24:00:00')->format('Y-m-d H:i:s')."'")
                ->select('users.id as teacher_id',
                    'users.nickname as teacher_nickname',
                    'users.email as teacher_email',
                    DB::raw("(SELECT COUNT(lesson_histories.id) FROM lesson_histories WHERE lesson_histories.teacher_id = users.id AND CONCAT(lesson_histories.date, ' ',lesson_histories.time) <='".$timezone->convertFromLocal($_GET["date_to"].' 24:00:00')->format('Y-m-d H:i:s')."' )  AS total_lessons"),
                    DB::raw("(SELECT SUM(lesson_histories.coin) FROM lesson_histories WHERE lesson_histories.teacher_id = users.id AND CONCAT(lesson_histories.date, ' ',lesson_histories.time) <='".$timezone->convertFromLocal($_GET["date_to"].' 24:00:00')->format('Y-m-d H:i:s')."' )  AS total_coins")
                );
            }

            if (!empty($_GET["date_from"]) && !empty($_GET["date_to"])) {
                $data_lesson_histories->whereRaw("CONCAT(lesson_histories.date, ' ', lesson_histories.time) >='".$timezone->convertFromLocal($_GET["date_from"].' 00:00:00')->format('Y-m-d H:i:s')."'");
                $data_lesson_histories->whereRaw("CONCAT(lesson_histories.date, ' ',lesson_histories.time) <='".$timezone->convertFromLocal($_GET["date_to"].' 24:00:00')->format('Y-m-d H:i:s')."'")
                    ->select('users.id as teacher_id',
                        'users.nickname as teacher_nickname',
                        'users.email as teacher_email',
                        DB::raw("(SELECT COUNT(lesson_histories.id) FROM lesson_histories WHERE lesson_histories.teacher_id = users.id AND CONCAT(lesson_histories.date, ' ', lesson_histories.time) >='".$timezone->convertFromLocal($_GET["date_from"].' 00:00:00')->format('Y-m-d H:i:s')."' AND CONCAT(lesson_histories.date, ' ',lesson_histories.time) <='".$timezone->convertFromLocal($_GET["date_to"].' 24:00:00')->format('Y-m-d H:i:s')."' )  AS total_lessons"),
                        DB::raw("(SELECT SUM(lesson_histories.coin) FROM lesson_histories WHERE lesson_histories.teacher_id = users.id AND CONCAT(lesson_histories.date, ' ', lesson_histories.time) >='".$timezone->convertFromLocal($_GET["date_from"].' 00:00:00')->format('Y-m-d H:i:s')."' AND CONCAT(lesson_histories.date, ' ',lesson_histories.time) <='".$timezone->convertFromLocal($_GET["date_to"].' 24:00:00')->format('Y-m-d H:i:s')."' )  AS total_coins")
                    );

            }

            if(empty($_GET["date_from"]) && empty($_GET["date_to"])) {
                $data_lesson_histories->select('users.id as teacher_id',
                    'users.nickname as teacher_nickname',
                    'users.email as teacher_email',
                    DB::raw('(SELECT COUNT(lesson_histories.id) FROM lesson_histories WHERE lesson_histories.teacher_id = users.id)  as total_lessons'),
                    DB::raw('(SELECT SUM(lesson_histories.coin) FROM lesson_histories WHERE lesson_histories.teacher_id = users.id)  as total_coins',),
                );
            }

        $data_lesson_histories = $data_lesson_histories->get()->toArray();


        $data_booking_histories = DB::table('users')
            ->join('teacher_schedule', 'teacher_schedule.teacher_id', '=','users.id')
            ->join('booking', 'booking.teacher_schedule_id','=','teacher_schedule.id')
            ->where('users.role', '=' , config('constants.role.teacher'))
            ->where('users.auth', '=',1)
            ->where('teacher_schedule.status', '=', 2)
            ->where(function ($query) {
                $query->where('teacher_schedule.start_date','<', Carbon::now()->format('Y-m-d'))
                    ->orWhere([['teacher_schedule.start_date','=', Carbon::now()->format('Y-m-d')],
                        ['teacher_schedule.start_hour','<', Carbon::now()->subMinute(30)->format('H:i:00')]]);
                return $query;
            })
            ->groupBy( 'users.id',
                'users.nickname',
                'users.email',
                'teacher_schedule.teacher_id');


            // Search teacher ID
            if (!empty($_GET["teacher_id"]) && empty($_GET["teacher_email"])) {
                $data_booking_histories->whereIn('users.id', $_GET["teacher_id"]);
            }

            if (empty($_GET["teacher_id"]) && !empty($_GET["teacher_email"])) {
                $data_booking_histories->whereIn('users.id', $_GET["teacher_email"]);
            }

            if (!empty($_GET["teacher_id"]) && !empty($_GET["teacher_email"])) {
                $array_id = array_merge($_GET["teacher_id"], $_GET['teacher_email']);
                $data_booking_histories->whereIn('users.id', $array_id);
            }

            // Search date
            if (!empty($_GET["date_from"]) && empty($_GET["date_to"])) {
                $data_booking_histories->whereRaw("CONCAT(teacher_schedule.start_date, ' ',teacher_schedule.start_hour) >='".$timezone->convertFromLocal($_GET["date_from"].' 00:00:00')->format('Y-m-d H:i:s')."'")
                    ->select('users.id as teacher_id',
                        'users.nickname as teacher_nickname',
                        'users.email as teacher_email',
                        DB::raw("(SELECT COUNT(booking.id) FROM booking INNER JOIN teacher_schedule ON booking.teacher_schedule_id = teacher_schedule.id WHERE teacher_schedule.teacher_id = users.id AND teacher_schedule.status = 2  AND CONCAT(teacher_schedule.start_date, ' ',teacher_schedule.start_hour) < '".Carbon::now()->subMinute(30)->format('Y-m-d H:i:s')."' AND CONCAT(teacher_schedule.start_date, ' ','24:00:00') >='".$timezone->convertFromLocal($_GET["date_from"].' 00:00:00')->format('Y-m-d H:i:s')."' )  AS total_lessons"),
                        DB::raw("(SELECT SUM(booking.coin) FROM booking INNER JOIN teacher_schedule ON booking.teacher_schedule_id = teacher_schedule.id WHERE teacher_schedule.teacher_id = users.id AND teacher_schedule.status = 2  AND CONCAT(teacher_schedule.start_date, ' ',teacher_schedule.start_hour) < '".Carbon::now()->subMinute(30)->format('Y-m-d H:i:s')."' AND CONCAT(teacher_schedule.start_date, ' ',24:00:00) >='".$timezone->convertFromLocal($_GET["date_from"].' 00:00:00')->format('Y-m-d H:i:s')."' )  AS total_coins")
                    );
            }

            if (empty($_GET["date_from"]) && !empty($_GET["date_to"])) {
                $data_booking_histories->whereRaw("CONCAT(teacher_schedule.start_date, ' ',teacher_schedule.start_hour) <='".$timezone->convertFromLocal($_GET["date_to"].' 24:00:00')->format('Y-m-d H:i:s')."'")
                    ->select('users.id as teacher_id',
                        'users.nickname as teacher_nickname',
                        'users.email as teacher_email',
                        DB::raw("(SELECT COUNT(booking.id) FROM booking INNER JOIN teacher_schedule ON booking.teacher_schedule_id = teacher_schedule.id WHERE teacher_schedule.teacher_id = users.id AND teacher_schedule.status = 2  AND CONCAT(teacher_schedule.start_date, ' ',teacher_schedule.start_hour) < '".Carbon::now()->subMinute(30)->format('Y-m-d H:i:s')."' AND CONCAT(teacher_schedule.start_date, ' ','00:00:00') <='".$timezone->convertFromLocal($_GET["date_to"].' 24:00:00')->format('Y-m-d H:i:s')."')  AS total_lessons"),
                        DB::raw("(SELECT SUM(booking.coin) FROM booking INNER JOIN teacher_schedule ON booking.teacher_schedule_id = teacher_schedule.id WHERE teacher_schedule.teacher_id = users.id AND teacher_schedule.status = 2  AND CONCAT(teacher_schedule.start_date, ' ',teacher_schedule.start_hour) < '".Carbon::now()->subMinute(30)->format('Y-m-d H:i:s')."' AND CONCAT(teacher_schedule.start_date, ' ','00:00:00') <='".$timezone->convertFromLocal($_GET["date_to"].' 24:00:00')->format('Y-m-d H:i:s')."')  AS total_coins")
                    );
            }

            if (!empty($_GET["date_from"]) && !empty($_GET["date_to"])) {
                $data_booking_histories->whereRaw("CONCAT(teacher_schedule.start_date, ' ',teacher_schedule.start_hour) >='".$timezone->convertFromLocal($_GET["date_from"].' 00:00:00')->format('Y-m-d H:i:s')."'");
                $data_booking_histories->whereRaw("CONCAT(teacher_schedule.start_date, ' ',teacher_schedule.start_hour) <='".$timezone->convertFromLocal($_GET["date_to"].' 24:00:00')->format('Y-m-d H:i:s')."'")
                    ->select('users.id as teacher_id',
                        'users.nickname as teacher_nickname',
                        'users.email as teacher_email',
                        DB::raw("(SELECT COUNT(booking.id) FROM booking INNER JOIN teacher_schedule ON booking.teacher_schedule_id = teacher_schedule.id WHERE teacher_schedule.teacher_id = users.id AND teacher_schedule.status = 2  AND CONCAT(teacher_schedule.start_date, ' ',teacher_schedule.start_hour) < '".Carbon::now()->subMinute(30)->format('Y-m-d H:i:s')."' AND CONCAT(teacher_schedule.start_date, ' ','24:00:00') >='".$timezone->convertFromLocal($_GET["date_from"].' 00:00:00')->format('Y-m-d H:i:s')."'  AND CONCAT(teacher_schedule.start_date, ' ','00:00:00') <='".$timezone->convertFromLocal($_GET["date_to"].' 24:00:00')->format('Y-m-d H:i:s')."')  AS total_lessons"),
                        DB::raw("(SELECT SUM(booking.coin) FROM booking INNER JOIN teacher_schedule ON booking.teacher_schedule_id = teacher_schedule.id WHERE teacher_schedule.teacher_id = users.id AND teacher_schedule.status = 2  AND CONCAT(teacher_schedule.start_date, ' ',teacher_schedule.start_hour) < '".Carbon::now()->subMinute(30)->format('Y-m-d H:i:s')."' AND CONCAT(teacher_schedule.start_date, ' ','24:00:00') >='".$timezone->convertFromLocal($_GET["date_from"].' 00:00:00')->format('Y-m-d H:i:s')."'  AND CONCAT(teacher_schedule.start_date, ' ','00:00:00') <='".$timezone->convertFromLocal($_GET["date_to"].' 24:00:00')->format('Y-m-d H:i:s')."')  AS total_coins")
                    );
            }

            if(empty($_GET["date_from"]) && empty($_GET["date_to"])) {
                $data_booking_histories->select('users.id as teacher_id',
                    'users.nickname as teacher_nickname',
                    'users.email as teacher_email',
                    DB::raw('(SELECT COUNT(booking.id) FROM booking INNER JOIN teacher_schedule ON booking.teacher_schedule_id = teacher_schedule.id WHERE teacher_schedule.teacher_id = users.id AND teacher_schedule.status = 2  AND CONCAT(teacher_schedule.start_date, " ",teacher_schedule.start_hour) < "'.Carbon::now()->subMinute(30)->format('Y-m-d H:i:s').'") AS total_lessons'),
                    DB::raw('(SELECT SUM(booking.coin) FROM booking INNER JOIN teacher_schedule ON booking.teacher_schedule_id = teacher_schedule.id WHERE teacher_schedule.teacher_id = users.id AND teacher_schedule.status = 2  AND CONCAT(teacher_schedule.start_date, " ",teacher_schedule.start_hour) < "'.Carbon::now()->subMinute(30)->format('Y-m-d H:i:s').'") AS total_coins'),
                );
            }

        $data_booking_histories = $data_booking_histories->get()->toArray();

//        dd($data_lesson_histories, $data_booking_histories);


        //merge two data
        foreach ($data_lesson_histories as $lesson_history) {
            $lesson_history->check = false;
            foreach ($data_booking_histories as $booking_history) {
                if($booking_history->teacher_id === $lesson_history->teacher_id) {
                    $booking_history->total_lessons += $lesson_history->total_lessons;
                    $booking_history->total_coins += $lesson_history->total_coins;
                    $lesson_history->check = true;
                    break;
                }
            }
            if($lesson_history->check == false) {
                array_push($data_booking_histories, $lesson_history);
            }
        }

        //sort by teacher_id
        for($i = 0; $i < sizeof($data_booking_histories); $i++) {
            for($j = $i + 1; $j < sizeof($data_booking_histories); $j++) {
                if($data_booking_histories[$i]->teacher_id <= $data_booking_histories[$j]->teacher_id) {
                    $temp = $data_booking_histories[$i];
                    $data_booking_histories[$i] =$data_booking_histories[$j];
                    $data_booking_histories[$j] = $temp;
                }
            }
        }
        return $data_booking_histories;
    }

    /**
     * get teacher lesson histories
     * @return array
     */
    public function getTeacherLessonHistories() {
        $timezone = new TimezoneController();
        //get lesson histories data
        $data_lesson_histories = DB::table('users')
            ->join('lesson_histories','users.id','=', 'lesson_histories.teacher_id')
            ->join('lessons','lesson_histories.lesson_id','=','lessons.id')
            ->join('course','course.id','=','lessons.course_id')
            ->where('users.role','=', config('constants.role.teacher'))
            ->where('users.auth', '=',1)
            ->orderBy('lesson_histories.date','DESC')
            ->orderBy('lesson_histories.time','DESC')
            ->select(   'lesson_histories.id as lesson_histories_id',
                        'users.email as teacher_email',
                        'users.id as teacher_id',
                        'lesson_histories.date as lesson_histories_date',
                        'lesson_histories.time as lesson_histories_time',
                        'lesson_histories.coin as lesson_histories_coin',
                        'course.name as course_name',
                        'lessons.name as lesson_content',
                        'lesson_histories.student_id as student_id',
                        DB::raw('(SELECT users.email FROM users WHERE users.id = lesson_histories.student_id) AS student_email')
                    );

        $check['date_from'] = false;
        $check['date_to'] = false;
        if (!empty($_GET["date_from"]) && empty($_GET["date_to"])) {
            $check['date_from'] = true;
            $check['date_to'] = false;
            $data_lesson_histories->whereRaw("CONCAT(lesson_histories.date, ' ', '24:00:00') >='".$timezone->convertFromLocal($_GET["date_from"].' 00:00:00')->format('Y-m-d H:i:s')."'");
        }

        if (empty($_GET["date_from"]) && !empty($_GET["date_to"])) {
            $check['date_from'] = false;
            $check['date_to'] = true;
            $data_lesson_histories->whereRaw("CONCAT(lesson_histories.date, ' ','00:00:00') <='".$timezone->convertFromLocal($_GET["date_to"].' 24:00:00')->format('Y-m-d H:i:s')."'");
        }

        if (!empty($_GET["date_from"]) && !empty($_GET["date_to"])) {
            $check['date_from'] = true;
            $check['date_to'] = true;
            $data_lesson_histories->whereRaw("CONCAT(lesson_histories.date, ' ', '24:00:00') >='".$timezone->convertFromLocal($_GET["date_from"].' 00:00:00')->format('Y-m-d H:i:s')."'");
            $data_lesson_histories->whereRaw("CONCAT(lesson_histories.date, ' ','00:00:00') <='".$timezone->convertFromLocal($_GET["date_to"].' 24:00:00')->format('Y-m-d H:i:s')."'");
        }

        // Search ID
        if (!empty($_GET["teacher_id"]) && empty($_GET['teacher_email'])) {
//            $data_lesson_histories->whereIn('users.id', $_GET["teacher_id"]);
            $data_lesson_histories->where(function ($query) {
                $query->whereIn('lesson_histories.teacher_id',$_GET["teacher_id"])
                    ->orWhereIn('lesson_histories.student_id',$_GET["teacher_id"]);
                return $query;
            });
        }
        // Search email
        if (empty($_GET["teacher_id"]) && !empty($_GET["teacher_email"])) {
            $data_lesson_histories->where(function ($query) {
                $query->whereIn('lesson_histories.teacher_id',$_GET["teacher_email"])
                    ->orWhereIn('lesson_histories.student_id',$_GET["teacher_email"]);
                return $query;
            });
        }

        if(!empty($_GET["teacher_id"]) && !empty($_GET["teacher_email"])) {
            $array_id = array_merge($_GET["teacher_id"], $_GET["teacher_email"]);
            $data_lesson_histories->where(function ($query) use ($array_id) {
                $query->whereIn('lesson_histories.teacher_id', $array_id)
                    ->orWhereIn('lesson_histories.student_id', $array_id);
                return $query;
            });
        }

        $data_lesson_histories = $data_lesson_histories
            ->get()
            ->filter(function ($value) use ($check) {
                $date = Carbon::parse($value->lesson_histories_date. ' ' . $value->lesson_histories_time);
                $value->lesson_histories_date = Timezone::convertToLocal($date,'Y-m-d');
                $value->lesson_histories_time = Timezone::convertToLocal($date,'H:i');
                if($check['date_from']) {
                    if(Carbon::parse($value->lesson_histories_date. ' ' . $value->lesson_histories_time)->lt(Carbon::parse($_GET["date_from"]. '00:00:00'))){
                        return null;
                    }
                }
                if($check['date_to']) {
                    if(Carbon::parse($value->lesson_histories_date. ' ' . $value->lesson_histories_time)->gt(Carbon::parse($_GET["date_to"]. '00:00:00')->addDay(1))){
                        return null;
                    }
                }
                return $value;
            })
            ->toArray();

//        dd($data_lesson_histories);

        //get teacher schedule booking
        $data_booking_histories = DB::table('users')
            ->join('teacher_schedule','users.id','=','teacher_schedule.teacher_id')
            ->join('booking','booking.teacher_schedule_id','=','teacher_schedule.id')
            ->where('users.role','=', config('constants.role.teacher'))
            ->where('users.auth', '=', 1)
            ->where('teacher_schedule.status', '=', 2)
            ->select('users.email as teacher_email',
                            'users.id as teacher_id',
                            'teacher_schedule.start_date as lesson_histories_date',
                            'teacher_schedule.start_hour as lesson_histories_time',
                            'booking.coin as lesson_histories_coin',
                            'teacher_schedule.status as teacher_schedule_status',
                            'booking.student_id as student_id',
                            DB::raw('(SELECT users.email FROM users WHERE users.id  = booking.student_id) AS student_email'))
            ->whereRaw("CONCAT(teacher_schedule.start_date, ' ',teacher_schedule.start_hour) < ?", [ Carbon::now()->subMinute(30)->format('Y-m-d H:i:s') ]);

        $check['date_from'] = false;
        $check['date_to'] = false;
        if (!empty($_GET["date_from"]) && empty($_GET["date_to"])) {
            $check['date_from'] = true;
            $check['date_to'] = false;
            $data_booking_histories->whereRaw("CONCAT(teacher_schedule.start_date, ' ',teacher_schedule.start_hour) >='".$timezone->convertFromLocal($_GET["date_from"].' 00:00:00')->format('Y-m-d H:i:s')."'");
        }

        if (empty($_GET["date_from"]) && !empty($_GET["date_to"])) {
            $check['date_from'] = false;
            $check['date_to'] = true;
            $data_booking_histories->whereRaw("CONCAT(teacher_schedule.start_date, ' ',teacher_schedule.start_hour) <='".$timezone->convertFromLocal($_GET["date_to"].' 24:00:00')->format('Y-m-d H:i:s')."'");
        }

        if (!empty($_GET["date_from"]) && !empty($_GET["date_to"])) {
            $check['date_from'] = true;
            $check['date_to'] = true;
            $data_booking_histories->whereRaw("CONCAT(teacher_schedule.start_date, ' ',teacher_schedule.start_hour) >='".$timezone->convertFromLocal($_GET["date_from"].' 00:00:00')->format('Y-m-d H:i:s')."'");
            $data_booking_histories->whereRaw("CONCAT(teacher_schedule.start_date, ' ',teacher_schedule.start_hour) <='".$timezone->convertFromLocal($_GET["date_to"].' 24:00:00')->format('Y-m-d H:i:s')."'");
        }

        // Search ID
        if (!empty($_GET["teacher_id"]) && empty($_GET['teacher_email'])) {
//            $data_lesson_histories->whereIn('users.id', $_GET["teacher_id"]);
            $data_booking_histories->where(function ($query) {
                $query->whereIn('teacher_schedule.teacher_id',$_GET["teacher_id"])
                    ->orWhereIn('booking.student_id',$_GET["teacher_id"]);
                return $query;
            });
        }
        // Search email
        if (empty($_GET["teacher_id"]) && !empty($_GET["teacher_email"])) {
            $data_booking_histories->where(function ($query) {
                $query->whereIn('teacher_schedule.teacher_id',$_GET["teacher_email"])
                    ->orWhereIn('booking.student_id',$_GET["teacher_email"]);
                return $query;
            });
        }

        if(!empty($_GET["teacher_id"]) && !empty($_GET["teacher_email"])) {
            $array_id = array_merge($_GET["teacher_id"], $_GET["teacher_email"]);
            $data_booking_histories->where(function ($query) use ($array_id) {
                $query->whereIn('teacher_schedule.teacher_id', $array_id)
                    ->orWhereIn('booking.student_id', $array_id);
                return $query;
            });
        }
        $data_booking_histories = $data_booking_histories
            ->get()
            ->filter(function ($value) use ($check) {
                $date = Carbon::parse($value->lesson_histories_date. ' ' . $value->lesson_histories_time);
                $value->lesson_histories_date = Timezone::convertToLocal($date,'Y-m-d');
                $value->lesson_histories_time = Timezone::convertToLocal($date,'H:i');
                if($check['date_from']) {
                    if(Carbon::parse($value->lesson_histories_date. ' ' . $value->lesson_histories_time)->lt(Carbon::parse($_GET["date_from"]. '00:00:00'))){
                        return null;
                    }
                }
                if($check['date_to']) {
                    if(Carbon::parse($value->lesson_histories_date. ' ' . $value->lesson_histories_time)->gt(Carbon::parse($_GET["date_to"]. '00:00:00')->addDay(1))){
                        return null;
                    }
                }
                return $value;
            })
            ->toArray();
//        dd($data_lesson_histories, $data_booking_histories);

        $data_lesson_histories = array_merge($data_lesson_histories, $data_booking_histories);
        return $data_lesson_histories;
    }

    /**
     * @return array
     */
    public function getBiggestDateOfSchedule() {
        $teacher_role = config('constants.role.teacher');
        $teacher_id = Auth::id();
        return DB::select(DB::raw('SELECT MAX(teacher_schedule.start_date) AS max_date FROM users INNER JOIN teacher_schedule ON users.id = teacher_schedule.teacher_id WHERE users.role = '.$teacher_role.' AND users.auth = 1 AND users.id = '.$teacher_id.''.''));
    }

    /**
     * @return array
     */
    public function getSmallestDateOfSchedule() {
        $teacher_role = config('constants.role.teacher');
        $teacher_id = Auth::id();
        return DB::select(DB::raw('SELECT MIN(teacher_schedule.start_date) AS min_date FROM users INNER JOIN teacher_schedule ON users.id = teacher_schedule.teacher_id WHERE users.role = '.$teacher_role.' AND users.auth = 1 AND users.id = '.$teacher_id.''.''));
    }

    /**
     * get courses
     * @return Collection
     */
    public function getCourses() {
        return DB::table('course')
            ->select('*',
                DB::raw('(SELECT COUNT(lessons.id) FROM lessons WHERE lessons.course_id = course.id) AS num_of_lessons'))
            ->get();
    }

    public function getCourseById($id) {
        return DB::table('course')
//            ->join('course_can_teach','course_can_teach.course_id','=','course.id')
//            ->where('course_can_teach.teacher_id','=', Auth::id())
            ->select('course.*',
                DB::raw('(SELECT COUNT(lessons.id) FROM lessons WHERE lessons.course_id = course.id) AS num_of_lessons'))
            ->where('course.id', $id)
            ->first();
    }

    public function getLessonsOfCoursesByCourseId($id) {
        return DB::table('course')
            ->join('lessons','lessons.course_id','=','course.id')
            ->select('lessons.*')
            ->where('course.id', $id)
            ->get();
    }

    public function getCourseInfoById($id, $teacher_id = null) {
        $teacher_id = $teacher_id ? $teacher_id: Auth::id();
        return DB::table('course')
            ->join('course_can_teach','course_can_teach.course_id','=','course.id')
            ->join('lessons','lessons.course_id','=','course.id')
            ->where('course_can_teach.teacher_id','=', $teacher_id)
            ->select('lessons.*')
            ->where('course.id', $id)
            ->get();
    }

    public function getTeacherCourses($teacher_id) {
        return DB::table('course')
            ->join('course_can_teach','course.id','=','course_can_teach.course_id')
            ->select('course.id as course_id', 'course.name as course_name')
            ->where('course_can_teach.teacher_id', '=', $teacher_id)
            ->orderBy('course.level_id', 'DESC')
            ->get();
    }
}
