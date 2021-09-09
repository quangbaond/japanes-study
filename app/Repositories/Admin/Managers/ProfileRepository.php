<?php

namespace App\Repositories\Admin\Managers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\UserInformation;
use App\Notifications\PushNotification;
use App\Services\MailService;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;
use Pusher\Pusher;
use Timezone;
class ProfileRepository
{
    protected $mailServices;
    /**
     * UserRepository constructor.
     * @param
     */
    public function __construct(MailService $mailService)
    {
        $this->mailServices = $mailService;
    }

    /**
     * get all course by Thach
     * @return array
     */
    public function getAllCourse() {
        return DB::select( DB::raw("SELECT course.*,course_can_teach.* FROM course LEFT JOIN ((select course_can_teach.course_id,users.id as user_id from course_can_teach INNER JOIN users on course_can_teach.teacher_id = users.id where users.id= ".Auth::user()->id." ) as course_can_teach) on course.id = course_can_teach.course_id") );
    }

    public function getNumberOfLesson() {
        // return DB::select( DB::raw("SELECT count(id) FROM `lesson_histories` WHERE status_lesson = '2' AND teacher_id =") )
//        return DB::table('lesson_histories')
//            ->where('teacher_id',Auth::user()->id)
//            ->count('id');
        $theNumberOfLessonHistories = DB::table('lesson_histories')
            ->select('id')
            ->where('teacher_id', '=', Auth::user()->id)
            ->groupBy('teacher_id')
            ->count('id');
        $theNumberOfBookingHistories = DB::table('booking')
            ->select('booking.id')
            ->join('teacher_schedule', 'teacher_schedule.id' , '=', 'booking.teacher_schedule_id')
            ->where('teacher_schedule.teacher_id', Auth::user()->id)
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
     * get zoom link to show for teacher if they close meeting zoom
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|object|null
     */
    public function getCurrentZoomLink() {
        return DB::table('lesson_histories')->select('zoom_link')
            ->where('date', Carbon::now()->format('Y-m-d')) // Start_date is today
            ->where('time', '>=', Carbon::now()->subMinute(25)->format('H:i:00')) // start_hour >= time()->subMinute(5)
            ->where('teacher_id', Auth::id())->first();
    }
    /**
     * get avatar image of teacher to show in sidebar by ThachDang
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|object|null
     */
    public function getAvatarImageOfTeacher() {
        return DB::table('users')
            ->select('user_information.image_photo')
            ->leftJoin('user_information','users.id','=', 'user_information.user_id')
            ->where('users.id',Auth::id())
            ->first();
    }
    /**
     * @param
     * @return object
     */
    public function profile()
    {
        try {
            $profile = DB::table('users')
                ->select(
                    'users.id as user_id',
                    'users.email',
                    'users.nickname',
                    'user_information.id',
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
                    'user_information.link_zoom',
                    'user_information.self-introduction as introduction')
                ->leftJoin('user_information', 'user_information.user_id', '=', 'users.id')
                ->whereNull('users.deleted_at')
                ->where('users.id', '=', Auth::user()->id)
                ->first();
            return $profile;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @param $data
     * @return bool
     */
    public function changePassword($data)
    {
        $user = DB::table('users')->select('password')->where('id', '=', Auth::user()->id)->first();
        if ( password_verify($data['old_password'], $user->password) ) {
            DB::table('users')
                ->where('id', Auth::user()->id)
                ->update([
                    'password' => bcrypt($data['new_password'])
                ]);
            return true;
        } else {
            return false;
        }
    }

    public function array_diff_customize($current_course, $new_course) {
        $course_can_teach = [];
        foreach ($new_course as $value) {
            if(!in_array($value, $current_course)) {
                array_push($course_can_teach,[
                    'course_id' => $value,
                    'teacher_id' => Auth::user()->id,
                ]);
            }
        }
        return $course_can_teach;
    }

    /**
     * Update user
     * @param $data
     * @return bool
     */
    public function updateProfile($data)
    {
        DB::beginTransaction();
        try{
            // Update AMZ S3
            $image_photo_update = null;
            // Delete image old in AMZ S3
            $image_photo = DB::table('user_information')->select('image_photo')->where('user_id', Auth::user()->id)->first();
            $image_photo_update = $image_photo->image_photo ?? $image_photo_update;
            if(isset($data['image_photo']) && !empty($data['image_photo'])) {
                //db co thi xoa anh cu
                if (!empty($image_photo)) {
                    $array = explode("/", $image_photo->image_photo);
                    $img = max(array_keys($array));
                    $this->deleteImageS3($array[$img]);
                }
                // Insert new image in AMZ S3
                $file = $data["image_photo"];
                $name = time() . rand();
                $filePath = $name;
                Storage::disk('s3')->put($filePath, file_get_contents($file), 'public');

                // Exits url image amazon s3
                if (Storage::disk('s3')->exists($filePath)) {
                    $image_photo_update = Storage::disk('s3')->url($filePath);
                }
            }

            if(isset($data['check_avatar_image']) && $data['check_avatar_image'] === '2') {
                if (!empty($image_photo)) {
                    $array = explode("/", $image_photo->image_photo);
                    $img = max(array_keys($array));
                    $this->deleteImageS3($array[$img]);
                }
                $image_photo_update = null;
            }

            $age = $data['birthday'] != null ? (int)date_diff(date_create($data['birthday']), date_create('today'))->y : null;
            $data_profile = array(
                'user_id'           => Auth::user()->id,
                'sex'               => !empty($data['sex']) ? $data['sex'] : null,
                'nationality'       => !empty($data['nationality']) ? $data['nationality'] : null,
                'membership_status' => !empty($data['membership_status']) ? $data['membership_status'] : null,
                'phone_number'      => !empty($data['phone_number']) ? $data['phone_number'] : null,
                'area_code'         => !empty($data['area_code']) ? $data['area_code'] : null,
                'company_id'        => !empty($data['company_id']) ? $data['company_id'] : null,
                'self-introduction' => !empty($data['self-introduction']) ? $data['self-introduction'] : null,
                'experience'        => !empty($data['experience']) ? $data['experience'] : null,
                'image_photo'       => $image_photo_update,
                'link_youtube'      => !empty($data['link_youtube']) ? $data['link_youtube'] : null,
                'birthday'          => $data['birthday'],
                'certification'     => $data['certification'],
                'age'               => $age,
                'link_zoom'         => $data['link_zoom'],
                'created_at'        => now(),
                'updated_at'        => now(),
            );


            //Update users
            DB::table('users')
                ->where('id', Auth::user()->id)
                ->update([
                    'nickname'  => $data['nickname']
                ]);
            $new_course = explode(',', $data['course']);
            $course_can_teach = [];
            foreach ($new_course as $value) {
                array_push($course_can_teach,[
                    'course_id' => $value,
                    'teacher_id' => Auth::user()->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
            $course = DB::table('course_can_teach')->where('teacher_id', Auth::id());
            $course->delete();
            if($data['course'] != '') {
                $course->insert($course_can_teach);
            }
            $check_profile = DB::table('user_information')->select('id')->where('user_id', Auth::user()->id)->first();

            if ( !empty($check_profile) ) {
                //Update user profile
                UserInformation::find($check_profile->id)->update($data_profile);
            } else {
                //Create user profile
                UserInformation::create($data_profile);
            }

            DB::commit();
            $data = [
                'image_photo' => $image_photo_update,
                'link_youtube' => $data['link_youtube']
            ];
            return $data;
        } catch(\Exception $e) {
            //print_r($e->getTraceAsString());
            dd($e);
            DB::rollback();
            return false;
        }
    }

    protected function deleteImageS3($name)
    {
        if (Storage::disk('s3')->delete( $name)) {
            return true;
        } else {
            return false;
        }
    }

    public function changeEmail($data) {

        $user = DB::table('users')->select('*')->where('email',Auth::user()->email)->first();

        $this->mailServices->sendEmailConfirmationToBeChanged($user,$data['new_email'], 2);

    }

    public function updateEmail($new_email, $user_id) {
        DB::beginTransaction();
        try {
            DB::table('users')->where('id', $user_id)
            ->update([
                'email'   => $new_email
            ]);
            DB::commit();
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            return "error updateEmail";
        }

    }


    /**
     * @return \Illuminate\Support\Collection
     */
    public function notificationsDataTable() {

        $query = DB::table('notifications')
            ->select('users.email','notifications.*' , 'receiver.read_at', 'receiver.user_id')
            ->join('users', 'notifications.created_by', '=', 'users.id')
            ->leftJoin('receiver' , 'notifications.id' , '=' , 'notification_id')
            ->orderBy('created_at' , 'desc');

        // Search title
        if (!empty($_GET["title"])) {
            $query->where(function ($query) {
                $query->where('notifications.title','LIKE','%'.$_GET["title"].'%')
                ->orWhere('notifications.content','LIKE','%'.$_GET["title"].'%');
            })->get();
        }

        // Search email
        if (!empty($_GET["email"])) {
            $email = DB::table('users')->select('email')->where('id',$_GET["email"])->first();
            $query->where('users.email','LIKE','%'.$email->email.'%')->get();
        }

        // Search created_at
        $check['created_at_from'] = false;
        $check['created_at_to'] = false;
        if (!empty($_GET["created_at_from"]) && empty($_GET["created_at_to"])) {
            $check['created_at_from'] = true;
            $check['created_at_to'] = false;
            $query->whereDate('notifications.created_at','>=',  date('Y-m-d', strtotime($_GET["created_at_from"])));
        }
        if (empty($_GET["created_at_from"]) && !empty($_GET["created_at_to"])) {
            $check['created_at_to'] = true;
            $check['created_at_from'] = false;
            $query->whereDate('notifications.created_at','<=',  date('Y-m-d', strtotime($_GET["created_at_to"])));
        }
        if (!empty($_GET["created_at_from"]) && !empty($_GET["created_at_to"])) {
            $check['created_at_to'] = true;
            $check['created_at_from'] = true;
            $query->whereDate('notifications.created_at','>=',  date('Y-m-d', strtotime($_GET["created_at_from"])));
            $query->whereDate('notifications.created_at','<=',  date('Y-m-d', strtotime($_GET["created_at_to"])));
        }
        $query->orderBy('created_at','DESC');
        return $query->get()
                     ->filter(function ($value) use ($check) {
                         $value->created_at = Timezone::convertToLocal(Carbon::parse($value->created_at),'Y-m-d');
                         if($check['created_at_from']) {
                              if(Carbon::parse($value->created_at)->lt(Carbon::parse($_GET["created_at_from"]. '24:00:00'))){
                                  return null;
                              }
                         }
                         if($check['created_at_to']) {
                             if(Carbon::parse($value->created_at)->gt(Carbon::parse($_GET["created_at_to"]. '00:00:00'))){
                                 return null;
                             }
                         }
                         return $value;
                     });
    }

    public function deleteNotification($input) {
        DB::beginTransaction();
        try {
            DB::table('notifications')
                ->whereIn('notifications.id', $input['notification_id'])
                ->delete();

            DB::table('receiver')
                ->whereIn('receiver.notification_id', $input['notification_id'])
                ->delete();
            DB::commit();
            return true;
        }
        catch (Exception $err){
            dd(123);
            DB::rollback();
            return false;
        }
    }


    /**
     * @param $id
     * @return array
     */
    public function getNotificationById($id) {
        $notification = DB::table('notifications')
            ->select('*')
            ->where('id', $id)
            ->first();
        $users = DB::table('notifications')
            ->join('receiver','notifications.id','=','receiver.notification_id')
            ->join('users','users.id','=','receiver.user_id')
            ->select('receiver.*','users.email')
            ->where('receiver.notification_id','=', $id)
            ->get();
        $data['notification'] = $notification;
        $data['users'] = $users;
        return $data;
    }

    public function updateNotification($id, $input) {
        DB::beginTransaction();
        try {
            $data = [
                'start_date' => $input['from_date'],
                'end_date' => $input['to_date'],
                'title' => $input['title'],
                'content' => $input['content'],
                'updated_by' => Auth::id(),
                'updated_at' => now()
            ];

            DB::table('notifications')
                ->where('id', $id)
                ->where('receiver_class','!=',4)
                ->update($data);
            DB::commit();
            return true;
        }
        catch (Exception $err) {
            dd(123);
            DB::rollback();
            return false;
        }
    }
}
