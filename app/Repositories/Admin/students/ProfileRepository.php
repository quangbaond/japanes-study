<?php

namespace App\Repositories\Admin\students;

use App\Services\MailService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\UserInformation;
use Illuminate\Support\Facades\Storage;
use App\Models\Notifications;
use Timezone;

class ProfileRepository
{
    protected $mailServices;
    protected $stripe;

    /**
     * ProfileRepository constructor.
     * @author vinhppvk
     *
     * @param MailService $mailService
     */
    public function __construct(MailService $mailService)
    {
        $this->mailServices = $mailService;
        $this->stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
    }

    public function getInformation() {
        return DB::table('users')
            ->select('users.email',
                'users.nickname',
                'user_information.membership_status',
                'users.id',
                'student_total_coins.total_coin',
                'user_information.birthday',
                'user_information.sex',
                'user_information.area_code',
                'user_information.phone_number',
                'user_information.nationality',
                'user_information.image_photo',
                'user_payment_info.id as user_payment_info_id',
                'user_payment_info.trial_end_date',
                'user_payment_info.premium_end_date',
                'user_payment_info.stripe_customer_id',
                'user_payment_info.stripe_subscription_id'
            )
            ->leftJoin('user_information','users.id', '=', 'user_information.user_id')
            ->leftJoin('student_total_coins','users.id', '=', 'student_total_coins.student_id')
            ->leftJoin('user_payment_info','users.id', '=', 'user_payment_info.user_id')
            ->where('users.id',Auth::id())->first();
    }

    public function changeNickname($data) {
        DB::beginTransaction();
        try {
            DB::table('users')->select('id')
                ->where('id', Auth::id())
                ->update([
                    'nickname' => $data['new_nickname']
                ]);
            DB::commit();
            return true;
        } catch (\Throwable $th) {
            DB::rollback();
            return false;
        }
    }
    public function changePassword($data) {
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

    public function changeEmail($data) {

        $user = DB::table('users')->select('*')->where('email',Auth::user()->email)->first();

        $this->mailServices->sendEmailConfirmationToStudentWhenChanged($user,$data['new_email'], 2);

    }

    public function updateNewEmail($new_email, $user_id) {
        DB::beginTransaction();
        try {
            $customer = DB::table('users')
                ->select(
                    'user_payment_info.stripe_customer_id'
                )
                ->leftJoin('user_payment_info', 'user_payment_info.user_id', '=', 'users.id')
                ->where('users.id', $user_id)
                ->first();

            if (!empty($customer->stripe_customer_id)) {
                $this->stripe->customers->update(
                    $customer->stripe_customer_id,
                    [
                        'email' => $new_email
                    ]
                );
            }

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

    public function updateProfile($data) {
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
                'phone_number'      => !empty($data['phone_number']) ? $data['phone_number'] : null,
                'area_code'         => !empty($data['area_code']) ? $data['area_code'] : null,
                'image_photo'       => $image_photo_update,
                'birthday'          => $data['birthday'],
                'age'               => $age,
                'created_at'        => now(),
                'updated_at'        => now(),
            );

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
                'image_photo' => $image_photo_update
            ];
            return $data;
        } catch(\Exception $e) {
            DB::rollback();
            return null;
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

    public function listBookingTrial()
    {
        return DB::table('users')
            ->select(
                'users.email',
                'users.nickname',
                'teacher_schedule.id as teacher_schedule_id',
                'teacher_schedule.teacher_id',
                'teacher_coin.coin',
                DB::raw('CONCAT(teacher_schedule.start_date, " ",teacher_schedule.start_hour) AS start_date_hour'),
                DB::raw('(SELECT IF( TIMESTAMPDIFF(MINUTE, "'.Carbon::now()->format('Y-m-d H:i:s').'",CONCAT(teacher_schedule.start_date, " ", teacher_schedule.start_hour) ) > 60 , "YES", "NO")) AS status_coin_refund'),
                DB::raw('CONCAT(teacher_schedule.start_date, " ", teacher_schedule.start_hour) AS start_date_hour'),
                DB::raw('(SELECT nickname FROM users WHERE id = teacher_schedule.teacher_id) AS nickname_teacher'),
                DB::raw('(SELECT email FROM users WHERE id = teacher_schedule.teacher_id) AS email_teacher')
            )
            ->leftJoin('booking', 'booking.student_id', '=', 'users.id')
            ->leftJoin('teacher_schedule', 'teacher_schedule.id', '=', 'booking.teacher_schedule_id')
            ->join('teacher_coin', 'teacher_coin.teacher_id', '=', 'teacher_schedule.teacher_id')
            ->where('users.id', Auth::id())
            ->where('teacher_schedule.status', 2)
            ->whereRaw("CONCAT(teacher_schedule.start_date, ' ',teacher_schedule.start_hour) >= ?", [ Carbon::now()->format('Y-m-d H:i:s') ])
            ->orderBy('teacher_schedule.start_date')
            ->orderBy('teacher_schedule.start_hour')
            ->get()
            ->filter(function($value) {
                $value->start_date = Timezone::convertToLocal(Carbon::parse($value->start_date_hour) , "Y-m-d");
                $value->start_hour = Timezone::convertToLocal(Carbon::parse($value->start_date_hour) , "H:i:s");
                return $value;
            });
    }

    public function listBookingPremium($input)
    {
        return DB::table('users')
            ->select(
                'users.email',
                'users.nickname',
                'teacher_schedule.id as teacher_schedule_id',
                'teacher_schedule.teacher_id',
                'teacher_coin.coin',
                DB::raw('CONCAT(teacher_schedule.start_date, " ",teacher_schedule.start_hour) AS start_date_hour'),
                DB::raw('CONCAT(teacher_schedule.start_date, " ", teacher_schedule.start_hour) AS start_date_hour'),
                DB::raw('(SELECT nickname FROM users WHERE id = teacher_schedule.teacher_id) AS nickname_teacher'),
                DB::raw('(SELECT email FROM users WHERE id = teacher_schedule.teacher_id) AS email_teacher')
            )
            ->leftJoin('booking', 'booking.student_id', '=', 'users.id')
            ->leftJoin('teacher_schedule', 'teacher_schedule.id', '=', 'booking.teacher_schedule_id')
            ->join('teacher_coin', 'teacher_coin.teacher_id', '=', 'teacher_schedule.teacher_id')
            ->where('users.id', Auth::id())
            ->where('teacher_schedule.status', 2)
            ->whereRaw("CONCAT(teacher_schedule.start_date, ' ',teacher_schedule.start_hour) >= ?", [ Carbon::parse($input['premium_end_date'])->format('Y-m-d H:i:s') ])
            ->orderBy('teacher_schedule.start_date')
            ->orderBy('teacher_schedule.start_hour')
            ->get()
            ->filter(function($value) {
                $value->start_date = Timezone::convertToLocal(Carbon::parse($value->start_date_hour) , "Y-m-d");
                $value->start_hour = Timezone::convertToLocal(Carbon::parse($value->start_date_hour) , "H:i:s");
                return $value;
            });
    }
    public function notificationsDataTable() {
        $user = Auth::user()->id;

        $query= Notifications::leftJoin('receiver' , 'notifications.id' , '=' , 'receiver.notification_id')
        ->orwhere('receiver.user_id' , $user)
        ->join('users', 'notifications.created_by', '=', 'users.id')
        ->select('users.email','notifications.*', 'receiver.read_at')
        ->orderBy('created_at' , 'desc');

        // Search title
        if (!empty($_GET["title"]) && empty($_GET["created_at_to"]) && empty($_GET["created_at_from"])) {
            $query->where(function ($query) {
                $query->where('notifications.title','LIKE','%'.$_GET["title"].'%')
                        ->orWhere('notifications.content','LIKE','%'.$_GET["title"].'%');
            });
        }
        if (!empty($_GET["created_at"]) && empty($_GET["title"])) {
            $query->whereDate('notifications.created_at','>=',  date('Y-m-d', strtotime($_GET["created_at"])));
        }
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
}
