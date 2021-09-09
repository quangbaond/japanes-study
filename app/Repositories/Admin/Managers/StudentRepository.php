<?php

namespace App\Repositories\Admin\Managers;

use App\Http\Controllers\TimezoneController;
use App\Models\User;
use App\Models\UserInformation;
use App\Services\MailService;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Stripe\Exception\ApiErrorException;
use Timezone;
class StudentRepository
{
    /**
     * Create a new repository instance.
     *
     * @return void
     */
    protected $mailServices;
    protected $stripe;
    protected $timezone;

    /**
     * Display a listing of the resource.
     *
     * @param MailService $mailServices
     */
    public function __construct(MailService $mailServices, TimezoneController $timezone)
    {
        $this->mailServices = $mailServices;
        $this->stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        $this->timezone = $timezone;
    }


    /**
     * @return Collection
     */
    public function studentDataTable()
    {
        $data = DB::table('users')
            ->select('users.*','user_information.phone_number','user_information.membership_status','company.name')
            ->leftJoin('user_information', 'users.id', '=','user_information.user_id')
            ->leftJoin('company', 'user_information.company_id','=', 'company.id')
            ->whereNull('users.deleted_at')
            ->Where('users.role', '=', 3);
        // Search email
        if (!empty($_GET["email"])) {
            $data->where('users.email','LIKE','%'.$_GET["email"].'%');
        }
        // Search student id
        if (!empty($_GET["student_id"])) {
            $data->where('users.id','=',$_GET["student_id"]);
        }
        // Search phone_number
        if (!empty($_GET["phone_number"])) {
            $data->where('user_information.phone_number','LIKE','%'.$_GET["phone_number"].'%');
        }
        // Search company_name
        if (!empty($_GET["company_name"])) {
            $data->where('company.id','=',$_GET["company_name"]);
        }
        // Search membership_status
        if (!empty($_GET["membership_status"])) {
            $data->where('user_information.membership_status','=',$_GET["membership_status"]);
        }
        // Search created_at
        if (!empty($_GET["from_date"]) && empty($_GET["to_date"])) {
            $data->whereDate('users.created_at','>=',  date('Y-m-d', strtotime($this->timezone->convertFromLocal(Carbon::parse($_GET["from_date"])->format('Y-m-d H:i:s')))));
        }
        if (empty($_GET["from_date"]) && !empty($_GET["to_date"])) {
            $data->whereDate('users.created_at','<=',  date('Y-m-d', strtotime($this->timezone->convertFromLocal(Carbon::parse($_GET["to_date"])->format('Y-m-d H:i:s')))));
        }
        if (!empty($_GET["from_date"]) && !empty($_GET["to_date"])) {
            $data->whereDate('users.created_at','>=',  date('Y-m-d', strtotime($this->timezone->convertFromLocal(Carbon::parse($_GET["from_date"])->format('Y-m-d H:i:s')))));
            $data->whereDate('users.created_at','<=',  date('Y-m-d', strtotime($this->timezone->convertFromLocal(Carbon::parse($_GET["to_date"])->format('Y-m-d H:i:s')))));
        }

        return $data->get();
    }

    /**
     * @return Collection
     */
    public function studentBookingSubstituteDataTable()
    {
        //update membership_status
//        DB::beginTransaction();
//        try {
//            DB::table('users')
//                ->join('user_information','users.id','=','user_information.user_id')
//                ->join('user_payment_info','users.id','=','user_payment_info.user_id')
//                ->where('user_information.membership_status','=',6)
//                ->where('users.role', '=', 3)
//                ->where('user_payment_info.premium_end_date','<=', Carbon::now())
//                ->update(['user_information.membership_status' => 1]);
//            DB::commit();
//        }
//        catch (\Exception $err) {
//            dd($err);
//            DB::rollBack();
//        }

        $data = DB::table('users')
            ->select('users.*','user_information.phone_number','user_information.membership_status','company.name')
            ->leftJoin('user_information', 'users.id', '=','user_information.user_id')
            ->leftJoin('company', 'user_information.company_id','=', 'company.id')
            ->leftJoin('user_payment_info','users.id','=','user_payment_info.user_id')
            ->whereNull('users.deleted_at')
            ->Where('users.role', '=', 3)
            ->whereIn('user_information.membership_status', [2, 3, 4, 5, 6])
            ->where(function($query) {
                $query->where([['user_payment_info.premium_end_date', '>=', Carbon::now()], ['user_information.membership_status', 6]])
                    ->orWhereIn('user_information.membership_status', [2, 3, 4, 5]);
            });
        // Search email
        if (!empty($_GET["student_email"])) {
            $data->where('users.email','LIKE','%'.$_GET["student_email"].'%');
        }
        // Search nickname
        if (!empty($_GET["student_name"])) {
            $data->where('users.nickname','LIKE','%'.$_GET["student_name"].'%');
        }
        // Search student id
        if (!empty($_GET["student_id"])) {
            $data->where('users.id','=',$_GET["student_id"]);
        }
        // Search company_name
        if (!empty($_GET["company_name"])) {
            $data->where('company.id','=',$_GET["company_name"]);
        }

        return $data->get();
    }


    /**
     * Create student form.
     *
     * @param $input
     * @return array
     */
    public function createStudent($input){
        $user = DB::table('users')->select('id', 'role', 'deleted_at')->where('email', $input['email'])->first();
        if ($user) {
            if (empty($user->deleted_at) || $user->role != config('constants.role.student')) {
                // Error
                return [
                    'status'    => false,
                    'message'   => config('constants.email_isset'),
                ];
            }
            // Update student
            return [
                'status'    => $this->updateStudent($input, $user),
                'message'   => config('constants.register_success')
            ];
        } else {
            // Insert student
            return [
                'status'    => $this->insertStudent($input),
                'message'   => config('constants.register_success')
            ];
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

    /**
     * Function insert student.
     *
     * @param $input
     * @return bool
     */
    public function insertStudent($input)
    {
        DB::beginTransaction();
        try {
            // Insert table users with role: student

            $password = $this->generateRandomString(9);
            $user_id = DB::table('users')
                ->insertGetId([
                    'email'         => $input['email'],
                    'nickname'      => $input['nickname'],
                    'role'          => config('constants.role.student'),
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
                //dd($filePath);
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
                'user_id'           => $user_id,
                'sex'               => !empty($input['sex']) ? $input['sex'] : null,
                'nationality'       => !empty($input['nationality']) ? $input['nationality'] : null,
                'membership_status' => !empty($input['membership_status']) ? $input['membership_status'] : null,
                'phone_number'      => !empty($input['phone_number']) ? $input['phone_number'] : null,
                'area_code'         => !empty($input['area_code']) ? $input['area_code'] : null,
                'company_id'        => !empty($input['company_id']) ? $input['company_id'] : null,
                'image_photo'       => $image_photo_insert,
                'birthday'          => $birthday,
                'age'               => $age,
                'created_at'        => now(),
                'updated_at'        => now(),
            ];
            DB::table('user_information')->insert($data);
            $data_mail  = array('nickname' => $input['nickname'], 'email' => $input['email'],'password' => $password,'role' => config('constants.role.student'),'title' => '[Study Japanese] Login information notification','url' => route('login.student'));
            $this->mailServices->sendLoginInfoMail($data_mail);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    /**
     * Function update student.
     *
     * @param $input
     * @param $user
     * @return bool
     */
    public function updateStudent($input, $user)
    {
        DB::beginTransaction();
        try {
            // Update table users
            $password = $this->generateRandomString(9);
            DB::table('users')
                ->where('email',$input['email'])
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
                'sex'               => !empty($input['sex']) ? $input['sex'] : null,
                'nationality'       => !empty($input['nationality']) ? $input['nationality'] : null,
                'membership_status' => !empty($input['membership_status']) ? $input['membership_status'] : null,
                'phone_number'      => !empty($input['phone_number']) ? $input['phone_number'] : null,
                'area_code'         => !empty($input['area_code']) ? $input['area_code'] : null,
                'company_id'        => !empty($input['company_id']) ? $input['company_id'] : null,
                'image_photo'       => $image_photo_update,
                'birthday'          => $birthday,
                'age'               => $age,
                'updated_at'        => now(),
            ];
            DB::table('user_information')->where('user_id', $user->id)->update($data);
            $data_mail  = array('nickname' => $input['nickname'], 'email' => $input['email'],'password' => $password,'role' => config('constants.role.student'),'title' => '[Study Japanese] Login information notification','url' => route('login.student'));
            $this->mailServices->sendLoginInfoMail($data_mail);

            // DB commit
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    protected function generateRandomString($length = 9) {
        return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
    }

    /**
     * Function delete student.
     *
     * @param $input
     * @return bool
     */
    public function deleteAll($input)
    {
        DB::beginTransaction();
        try {
            DB::table('users')
                ->where('id',$input['user_id'])
                ->update([
                    'deleted_by' => Auth::user()->id,
                    'deleted_at' => now(),
                    'updated_at' => now(),
                ]);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    /**
     * Function update login user
     *
     * @param $id
     * @return bool
     */
    public function updateStudentLogin($id){
        DB::beginTransaction();
        try {
            DB::table('users')->where('id',$id)->update(['last_login_at' => now()]);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    /**
     * [studentLoginZalo]
     * @param  object $info
     * @return array
     */
    public function studentLoginZalo($info){
        try {
            $user = DB::table('users')->select('id','zalo_id', 'deleted_at', 'role')->where('zalo_id', $info['id'])->first();
            if($user){
                if(empty($user->deleted_at) && $user->role == config('constants.role.student')){
                    Auth::loginUsingId($user->id, true);
                    return [
                        'status' => $this->updateStudentLogin($user->id),
                        'message' => 'test'
                    ];
                } else {
                    return [
                        'status' => false,
                        'message' => __('validation_custom.M021')
                    ];
                }
            } else {

                return [
                    'status' => $this->insertUserZalo($info),
                    'message' => 'login_sucess'
                ];

            }
        } catch (\Exception $e) {
            return [
                'status' => false,
                'message' => 'Login fail.'
            ];
        }

    }
    /**
     * [studentLoginGoogle]
     * @param   $info
     * @return array
     */
    public function studentLoginSocail($info){
        $user = DB::table('users')->select('id','email', 'deleted_at', 'role')->where('email', $info->email)->first();
        if($user){
            if(empty($user->deleted_at) && $user->role == config('constants.role.student')){
                Auth::loginUsingId($user->id, true);
                return [
                    'status' => $this->updateStudentLogin($user->id),
                    'message' => 'Login success'
                ];
            } else {
                return [
                    'status' => false,
                    'message' => __('validation_custom.M021')
                ];
            }
        } else {
            return [
                'status' => $this->insertUserSocial($info),
                'message' => 'Login success'
            ];
        }
    }

    /**
     * Insert users about zalo
     *
     * @param $info
     * @return bool
     */
    public function insertUserZalo($info){
        DB::beginTransaction();
        try {
            // Update table users
            $user_id = DB::table('users')
                ->insertGetId([
                    'zalo_id'       => $info['id'],
                    'nickname'      => $info['name'],
                    'role'          => config('constants.role.student'),
                    'auth'          => 1,
                    'last_login_at' => now(),
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            $data['user_id'] = $user_id;
            $data['membership_status'] = config('constants.membership.id.free');
            $data['created_at'] = now();
            $data['updated_at'] = now();
            DB::table('user_information')->insert($data);
            Auth::loginUsingId($user_id, true);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    /**
     * Insert users about social
     *
     * @param $info
     * @return bool
     */
    public function insertUserSocial($info){
        DB::beginTransaction();
        try {
            $user_id = DB::table('users')
                ->insertGetId([
                    'email'         => $info->email,
                    'nickname'      => $info->name,
                    'role'          => config('constants.role.student'),
                    'auth'          => 1,
                    'last_login_at' => now(),
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            $data['user_id'] = $user_id;
            $data['created_at'] = now();
            $data['updated_at'] = now();
            $data['membership_status'] = config('constants.membership.id.free');
            DB::table('user_information')->insert($data);
            Auth::loginUsingId($user_id, true);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    /**
     * Function update info.
     *
     * @param $info
     * @return bool
     */
    public function updateInfo($info){
        DB::beginTransaction();
        try {
            // Update table users
            $data['password'] = bcrypt($info['password']);
            if(empty(Auth::user()->email)){
                $email = $info['email'];
                $data['email'] = $info['email'];
            } else {
                $email = Auth::user()->email;
            }
            DB::table('users')->where('id',Auth::user()->id)->update($data);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    /**
     * Function register student.
     *
     * @param $request
     * @param int $membership_status
     * @return array
     */
    public function registerStudentStep2($request, $membership_status = 0)
    {
        DB::beginTransaction();
        try {
            $input = $request->all();

            // Insert table users with role: student
            $user = User::create([
                'email'         => $input['email'],
                'nickname'      => $input['nickname'],
                'role'          => config('constants.role.student'),
                'auth'          => 0,
                'password'      => bcrypt($input['password']),
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
                'user_id'           => $user->id,
                'sex'               => !empty($input['sex']) ? (Int)$input['sex'] : null,
                'nationality'       => !empty($input['nationality']) ? $input['nationality'] : null,
                'membership_status' => $membership_status,
                'phone_number'      => !empty($input['phone_number']) ? $input['phone_number'] : null,
                'area_code'         => !empty($input['area_code']) ? $input['area_code'] : null,
                'company_id'        => !empty($input['company_id']) ? $input['company_id'] : null,
                'image_photo'       => $image_photo_insert,
                'birthday'          => $birthday,
                'age'               => $age,
                'created_at'        => now(),
                'updated_at'        => now(),
            ];
            DB::table('user_information')->insert($data);

            // Send mail
            $this->mailServices->sendActivationMail($user, 1);
            DB::commit();
            return [
                'status'    => true,
                'message'   => config('validation_custom.register_success'),
                'user_id'   => $user->id
            ];
        } catch (\Exception $e) {
            DB::rollback();
            return [
                'status' => false,
                'message' => config('validation_custom.register_error')
            ];
        }
    }

    /**
     * Function register student step 1
     *
     * @param $input
     * @return array
     */
    public function checkUserStudent($input)
    {
        $user = DB::table('users')->select('id', 'deleted_at', 'auth')->where('email', $input['email'])->first();
        if ($user) {
            // Case: users.deleted_at != null
            if (!empty($user->deleted_at)) {
                return [
                    'status'    => false,
                    'check'      => 'email_delete',
                    'message'   => __('validation_custom.M036')
                ];
            }

            // Case: users.role = 0 (not auth)
            if ($user->auth == 0) {
                return [
                    'status'    => false,
                    'check'      => 'email_not_auth',
                    'message'   => __('validation_custom.email_not_auth')
                ];
            }

            // Case: users.role = 1 (is auth)
            if ($user->auth == 1) {
                return [
                    'status'    => false,
                    'check'      => 'email_isset',
                    'message'   => __('validation_custom.email_isset')
                ];
            }
        } else {
            // Go to step 2
            return [
                'status'    => true,
                'check'     => 'insert',
                'message'   => ""
            ];
        }
    }


    /**
     * Function check create payment method
     * By: vinhppvk
     *
     * @param $input
     * @return array
     * @throws ApiErrorException
     */
    public function checkPaymentMethod($input)
    {
        try {
            $exp_month = Carbon::parse($input['date_expiration'])->format('m');
            $exp_year = Carbon::parse($input['date_expiration'])->format('Y');

            // Create payment method
           $paymentMethods = $this->stripe->paymentMethods->create([
                'type' => 'card',
                'card' => [
                    'number' => $input['number_card'],
                    'exp_month' => $exp_month,
                    'exp_year' => $exp_year,
                    'cvc' => $input['cvc'],
                ],
            ]);
           return [
                'status' => true,
                'data' => $paymentMethods,
           ];
        } catch(\Stripe\Exception\CardException $e) {
            //echo 'Status is:' . $e->getHttpStatus() . '\n';
            //echo 'Type is:' . $e->getError()->type . '\n';
            //echo 'Code is:' . $e->getError()->code . '\n';
            //echo 'Param is:' . $e->getError()->param . '\n';
            //echo 'Message is:' . $e->getError()->message . '\n';
            $error = [];
            if ($e->getError()->code == 'card_declined') {
                $error['number_card'] = [
                    0 => __('validation_custom.M032'),
                ];
            }

            if ($e->getError()->code == 'incorrect_number') {
                $error['number_card'] = [
                    0 => __('validation_custom.M032'),
                ];
            }

            if ($e->getError()->code == 'invalid_expiry_month' || $e->getError()->code == 'invalid_expiry_year') {
                $error['date_expiration'] = [
                    0 => $e->getError()->message,
                ];
            }

            if ($e->getError()->code == 'invalid_cvc') {
                $error['cvc'] = [
                    0 => __('validation_custom.M033'),
                ];
            }
            return [
                'status' => false,
                'data' => $error
            ];
        }
    }


    /**
     * Function check card
     * By: vinhppvk
     *
     * @param $master_coin
     * @param $customer_id
     * @param $paymentMethods
     * @param $description
     * @return array
     * @throws ApiErrorException
     */
    public function checkCardMoney($master_coin, $customer_id, $paymentMethods, $description)
    {
        try {
            $paymentIntents = $this->stripe->paymentIntents->create([
                'amount' => $master_coin->amount,
                'currency' => config('constants.currency'),
                'customer' => $customer_id,
                'payment_method_types' => ['card'],
                'description' => $description,
                'payment_method' => $paymentMethods,
            ]);

            $this->stripe->paymentIntents->confirm(
                $paymentIntents->id,
                ['payment_method' => $paymentMethods]
            );
            return [
                'status' => true,
                'data' => ''
            ];

        } catch(\Stripe\Exception\CardException $e) {
            $error_card = 'Your card error';
            return [
                'status' => false,
                'data' => $error_card
            ];
        }
    }

    /**
     * Function check card of register student
     * By: vinhppvk
     *
     * @param $input
     * @param $stripe_plan
     * @return array
     * @throws ApiErrorException
     */
    public function checkCardOfRegisterStudent($input, $stripe_plan)
    {
        try {
            // Create customer stripe
            $customer = $this->stripe->customers->create([
                'email' => $input['email'],
                'payment_method' => $input['payment_method'],
                'invoice_settings' => [
                    "custom_fields"=> null,
                    "default_payment_method" => $input['payment_method'],
                    "footer"=> null
                ]
            ]);

            //Create subscriptions stripe
            $trial_end = $this->timezone->convertFromLocalNotLogin(Carbon::parse($input['date_deadline'])->subHour(config('constants.time_hour_auto_payment'))->format('Y-m-d H:i:s'))->timestamp;
            $billing_cycle_anchor = $this->timezone->convertFromLocalNotLogin(Carbon::parse($input['date_deadline'])->subHour(config('constants.time_hour_auto_payment'))->format('Y-m-d H:i:s'))->timestamp;
            $subscriptions = $this->stripe->subscriptions->create([
                'customer' => $customer->id,
                'items' => [
                    ['price' => $stripe_plan->stripe_plan],
                ],
                'trial_end' => $trial_end, //If the subscription has a trial, the end of that trial.
                'billing_cycle_anchor' => $billing_cycle_anchor //Determines the date of the first full invoice, and, for plans with month or year intervals, the day of the month for subsequent invoices.
            ]);
            return [
                'status' => true,
                'data' => [
                    'customer_id' => $customer->id,
                    'subscriptions_id' => $subscriptions->id
                ]
            ];

        } catch(\Stripe\Exception\CardException $e) {
            //echo 'Status is:' . $e->getHttpStatus() . '\n';
            //echo 'Type is:' . $e->getError()->type . '\n';
            //echo 'Code is:' . $e->getError()->code . '\n';
            //echo 'Param is:' . $e->getError()->param . '\n';
            //echo 'Message is:' . $e->getError()->message . '\n';
            $error_card = 'Your card error';
            return [
                'status' => false,
                'data' => $error_card
            ];
        }
    }

    /**
     * Function check card trial of register student
     * By: vinhppvk
     *
     * @param $input
     * @param $stripe_plan
     * @return array
     * @throws ApiErrorException
     */
    public function checkCardTrial($input, $stripe_plan)
    {
        try {
            // Create customer stripe
            $customer = $this->stripe->customers->create([
                'email' => Auth::user()->email,
                'payment_method' => $input['payment_method'],
                'invoice_settings' => [
                    "custom_fields"=> null,
                    "default_payment_method" => $input['payment_method'],
                    "footer"=> null
                ],
                [
                    'metadata' => [
                        'user_id'       => Auth::id(),
                        'stripe_plan'   => $stripe_plan->stripe_plan
                    ]
                ]
            ]);

            // Create subscriptions stripe
            $trial_end = $this->timezone->convertFromLocalNotLogin(Carbon::parse($input['date_deadline'])->subHour(config('constants.time_hour_auto_payment'))->format('Y-m-d H:i:s'))->timestamp;
            $billing_cycle_anchor = $this->timezone->convertFromLocalNotLogin(Carbon::parse($input['date_deadline'])->subHour(config('constants.time_hour_auto_payment'))->format('Y-m-d H:i:s'))->timestamp;
            $subscriptions = $this->stripe->subscriptions->create([
                'customer' => $customer->id,
                'items' => [
                    ['price' => $stripe_plan->stripe_plan],
                ],
                'trial_end' => $trial_end, //If the subscription has a trial, the end of that trial.
                'billing_cycle_anchor' => $billing_cycle_anchor //Determines the date of the first full invoice, and, for plans with month or year intervals, the day of the month for subsequent invoices.
            ]);
            return [
                'status' => true,
                'data' => [
                    'customer_id' => $customer->id,
                    'subscriptions_id' => $subscriptions->id
                ]
            ];
        } catch(\Stripe\Exception\CardException $e) {
            //echo 'Status is:' . $e->getHttpStatus() . '\n';
            //echo 'Type is:' . $e->getError()->type . '\n';
            //echo 'Code is:' . $e->getError()->code . '\n';
            //echo 'Param is:' . $e->getError()->param . '\n';
            //echo 'Message is:' . $e->getError()->message . '\n';
            $error_card = 'Your card error';
            return [
                'status' => false,
                'data' => $error_card
            ];
        }
    }

    /**
     * Function check card premium of register student
     * By: vinhppvk
     *
     * @param $input
     * @param $stripe_plan
     * @return array
     * @throws ApiErrorException
     */
    public function checkCardPremium($input, $stripe_plan)
    {
        try {
            $customer = DB::table('user_payment_info')->select('stripe_customer_id')->where('user_id', Auth::id())->first();

            // Update customer
            $this->stripe->customers->update(
                $customer->stripe_customer_id,
                [
                    'metadata' => [
                        'user_id' => Auth::id(),
                        'stripe_plan' => $stripe_plan->stripe_plan
                    ]
                ]
            );

            // Create subscriptions
            $subscriptions = $this->stripe->subscriptions->create([
                'customer' => $customer->stripe_customer_id,
                'items' => [
                    ['price' => $stripe_plan->stripe_plan],
                ],
            ]);

            if ($subscriptions['status'] == "active") {
                return [
                    'status' => true,
                    'data' => [
                        'customer_id' => $customer->stripe_customer_id,
                        'subscriptions_id' => $subscriptions->id
                    ]
                ];
            } else {
                $this->stripe->subscriptions->cancel($subscriptions->id);
                $this->stripe->customers->update(
                    $customer->stripe_customer_id,
                    ['metadata' => ['stripe_plan' => '']]
                );
                return [
                    'status' => false,
                    'data' => "Error card"
                ];
            }

        } catch(\Stripe\Exception\CardException $e) {
            //echo 'Status is:' . $e->getHttpStatus() . '\n';
            //echo 'Type is:' . $e->getError()->type . '\n';
            //echo 'Code is:' . $e->getError()->code . '\n';
            //echo 'Param is:' . $e->getError()->param . '\n';
            //echo 'Message is:' . $e->getError()->message . '\n';
            $error_card = 'Your card error';
            return [
                'status' => false,
                'data' => $error_card
            ];
        }
    }

    /**
     * Function check card premium new of register student
     * By: vinhppvk
     *
     * @param $input
     * @param $stripe_plan
     * @return array
     * @throws ApiErrorException
     */
    public function checkCardPremiumNew($input, $stripe_plan)
    {
        try {
            $customer = DB::table('user_payment_info')->select('stripe_customer_id')->where('user_id', Auth::id())->first();

            // Update payment method default customer
            $paymentMethod = $this->stripe->paymentMethods->attach(
                $input['payment_method'],
                ['customer' => $customer->stripe_customer_id]
            );

            $this->stripe->customers->update(
                $customer->stripe_customer_id,
                [
                    'invoice_settings' => [
                        "custom_fields"=> null,
                        "default_payment_method" => $paymentMethod->id,
                        "footer"=> null
                    ],
                    'metadata' => [
                        'user_id' => Auth::id(),
                        'stripe_plan' => $stripe_plan->stripe_plan
                    ]
                ]
            );

            // Create subscription
            $subscriptions = $this->stripe->subscriptions->create([
                'customer' => $customer->stripe_customer_id,
                'items' => [
                    ['price' => $stripe_plan->stripe_plan],
                ],
            ]);

            if ($subscriptions['status'] == "active") {
                return [
                    'status' => true,
                    'data' => [
                        'customer_id' => $customer->stripe_customer_id,
                        'subscriptions_id' => $subscriptions->id
                    ]
                ];
            } else {
                // Cancel subscriptions
                $this->stripe->subscriptions->cancel($subscriptions->id);
                $this->stripe->customers->update(
                    $customer->stripe_customer_id,
                    ['metadata' => ['stripe_plan' => '']]
                );

                return [
                    'status' => false,
                    'data' => "Error card"
                ];
            }

        } catch(\Stripe\Exception\CardException $e) {
            //echo 'Status is:' . $e->getHttpStatus() . '\n';
            //echo 'Type is:' . $e->getError()->type . '\n';
            //echo 'Code is:' . $e->getError()->code . '\n';
            //echo 'Param is:' . $e->getError()->param . '\n';
            //echo 'Message is:' . $e->getError()->message . '\n';
            $error_card = 'Your card error';
            return [
                'status' => false,
                'data' => $error_card
            ];
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|object|null
     */
    public function getStudentInformationById($id) {
        try {
            return DB::table('users')
                ->select(
                    'users.id',
                    'users.email',
                    'users.nickname',
                    'user_information.birthday',
                    'user_information.user_id',
                    'user_information.age',
                    'user_information.sex',
                    'user_information.area_code',
                    'user_information.nationality',
                    'user_information.phone_number',
                    'user_information.image_photo',
                    'user_information.membership_status',
                    'user_information.company_id',
                    'student_total_coins.total_coin'
                )
                ->leftJoin('user_information', 'user_information.user_id', '=', 'users.id')
                ->leftJoin('student_total_coins', 'student_total_coins.student_id', '=', 'users.id')
                ->where('users.id', '=', $id)
                ->whereNull('users.deleted_at')
                ->first();
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @param $id
     * @return bool
     */
    public function resetPasswordForStudentById($id) {
        $password = $this->generateRandomString(9);
        DB::beginTransaction();
        try {
            DB::table('users')
                ->where('users.id', $id)
                ->update([
                    'password' => bcrypt($password),
                    'updated_at' => now()
                ]);
            $student = DB::table('users')->select('users.nickname',
                'users.email',
                'users.password')
                ->where('users.id', $id)->first();
            $data_mail = array('nickname' => $student->nickname,
                'email' => $student->email,
                'password' => $password,
                'title' => '[Study Japanese] Notification of successful password reset',
                'url' => route('login.student'));
            $content_page = 'mails.mail-reset-password-student';
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
     * @return bool
     */
    public function updateProfileForStudentById($data, $id) {

        DB::beginTransaction();
        try{
            $age = $data['birthday'] != null ? (int)date_diff(date_create($data['birthday']), date_create('today'))->y : null;
            $data_profile = array(
                'user_id'           => $id,
                'sex'               => !empty($data['sex']) ? $data['sex'] : null,
                'nationality'       => !empty($data['nationality']) ? $data['nationality'] : null,
                'phone_number'      => !empty($data['phone_number']) ? $data['phone_number'] : null,
                'area_code'         => !empty($data['area_code']) ? $data['area_code'] : null,
                'company_id'        => $data['company_id'],
                'birthday'          => $data['birthday'],
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
     * @param $data contains the number of coin to refund
     * @param $id of student
     * @return Collection|null
     */
    public function refundCoinForStudentById($data, $id) {
        DB::beginTransaction();
        try {
            $coin = $data['theNumberOfCoin'];
            $hasStudent = DB::table('student_total_coins')
                ->select('expiration_date')
                ->where('student_id', $id)->first();
            if($hasStudent) {
                $student = DB::table('student_total_coins')
                    ->where('student_id', $id);
                $student->increment('total_coin', $data['theNumberOfCoin']);
                $student->update([
                    'expiration_date' => Carbon::now()->addMonth('2'),
                    'updated_at' => Carbon::now()
                ]);
                $coin = $student->get('total_coin');
                $coin = $coin[0]->total_coin;
            }
            else {
                DB::table('student_total_coins')->insert([
                    'student_id' => $id,
                    'total_coin' => $data['theNumberOfCoin'],
                    'expiration_date' => Carbon::now()->addMonth('2'),
                    'updated_at' => Carbon::now(),
                    'created_at' => Carbon::now()
                ]);
            }
            DB::table('history_student_use_coin')->insert([
                'student_id' => $id,
                'coin' => $data['theNumberOfCoin'],
                'teacher_id' => null,
                'status' => '4',
                'created_at' => Carbon::now()
            ]);
            DB::commit();
            return $coin;
        }
        catch (\Exception $e) {
            DB::rollBack();
            return null;
        }
    }

    /**
     * @return object|null
     */
    public function getMemberStatus($id)
    {
        return DB::table('users')
            ->select('user_information.membership_status')
            ->leftJoin('user_information','users.id','=','user_information.user_id')
            ->where('users.id', $id)->first();
    }


    /**
     * @return object|null
     */
    public function getDateExpirePremium($id) {
        return DB::table('users')
            ->leftJoin('user_information','users.id','=','user_information.user_id')
            ->join('user_payment_info','users.id','=','user_payment_info.user_id')
            ->where('user_information.membership_status',6)
            ->where('users.id', $id)
            ->select('user_payment_info.premium_end_date')
            ->first();
    }

    public function getLessonCourseLearnLast($student_id, $teacher_id) {
        $lesson_learn_last = DB::table('student_courses')
            ->join('lessons', 'student_courses.lesson_id','=','lessons.id')
            ->select('student_courses.lesson_id','lessons.number','lessons.course_id')
            ->where('student_courses.student_id', $student_id)
            ->orderByDesc('student_courses.updated_at')
            ->orderByDesc('student_courses.lesson_id')
            ->first();

        if (empty($lesson_learn_last)) {  // case: student doesnt have any lesson before -> get first teacher course
            return DB::table('course')
                ->select('course.id as course_id', 'course.name as course_name', 'lessons.id as lesson_id', 'lessons.name as lesson_name', 'lessons.number as lesson_number')
                ->join('lessons', 'lessons.course_id', '=', 'course.id')
                ->whereRaw('course.id = (SELECT MIN(course_id) FROM course_can_teach WHERE teacher_id = '.$teacher_id.')')
                ->where('lessons.number', '=' ,1 )
                ->first();
        } else {  //case : student has lesson
            // Get number of lessons
            $number_lesson = DB::table('lessons')->select('number')->where('id', $lesson_learn_last->lesson_id)->first();
            // Get number max of course
            $max_number_lesson = DB::table('lessons')->where('course_id', $lesson_learn_last->course_id)->max('number');

            //Get max lesson id
            $max_lesson = DB::table('lessons')->whereRaw('lessons.id = (SELECT MAX(lessons.id) FROM lessons)')->first();

            if($lesson_learn_last->lesson_id == $max_lesson->id) { //case max course
                return DB::table('course')
                    ->select('course.id as course_id', 'course.name as course_name', 'lessons.id as lesson_id', 'lessons.name as lesson_name', 'lessons.number', 'lessons.description')
                    ->join('lessons', 'lessons.course_id', '=', 'course.id')
                    ->where('lessons.id', $lesson_learn_last->lesson_id)
                    ->first();
            }

            if (($number_lesson->number + 1) > $max_number_lesson) { // Case: course next
                return DB::table('course')
                    ->select('course.id as course_id', 'course.name as course_name', 'lessons.id as lesson_id', 'lessons.name as lesson_name', 'lessons.number', 'lessons.description')
                    ->join('lessons', 'lessons.course_id', '=', 'course.id')
                    ->where('course.id', $lesson_learn_last->course_id + 1)
                    ->first();
            } else { // lesson next of course
                return DB::table('course')
                    ->select('course.id as course_id', 'course.name as course_name', 'lessons.id as lesson_id', 'lessons.name as lesson_name', 'lessons.number', 'lessons.description')
                    ->join('lessons', 'lessons.course_id', '=', 'course.id')
                    ->where('lessons.number', $lesson_learn_last->number + 1)
                    ->where('course.id','=', $lesson_learn_last->course_id)
                    ->first();
            }
        }
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
     * @param $student_id
     * @return Builder|object|null
     */
    public function getCurrentLesson($student_id) {
        return DB::table('student_courses')
            ->select('*')
            ->where('student_id', '=', $student_id)
            ->orderBy('updated_at','DESC')
            ->first();
    }
}
