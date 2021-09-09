<?php

namespace App\Http\Controllers\Admin\Students;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Repositories\Admin\students\ProfileRepository;
use App\Rules\CheckDateRule;
use App\Rules\CheckEmailRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Stripe\Exception\ApiErrorException;
use Timezone;

class ProfileController extends Controller
{
    protected $profileRepository;
    protected $stripe;

    /**
     * Create the __construct.
     *

     */
    public function __construct(ProfileRepository $profileRepository)
    {
        $this->profileRepository = $profileRepository;
        $this->stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
    }

    /**
     * Show my page.
     * @author vinhppvk
     *
     * @return mixed
     * @throws ApiErrorException
     */
    public function index() {
        // Check cancelling premium for user
        $this->cancellingPremium();

        // Get profile
        $profile= $this->profileRepository->getInformation();
        $profile->trial_end_date = is_null($profile->trial_end_date) ? null : Timezone::convertToLocal(Carbon::parse($profile->trial_end_date), 'Y-m-d H:i:s');
        $profile->premium_end_date = is_null($profile->premium_end_date)  ? null : Timezone::convertToLocal(Carbon::parse($profile->premium_end_date), 'Y-m-d H:i:s');
        // Case membership_status == 2 (Trial) || membership_status == 3 (Premium)
        if ($profile->membership_status == 2 || $profile->membership_status == 3) {
            $customer = $this->stripe->customers->retrieve($profile->stripe_customer_id);
            $stripe_plan = $customer->metadata->stripe_plan;
            $plan = DB::table('plans')->select('name')->where('stripe_plan', $stripe_plan)->first();
        }

        // Return view
        return view('admin.students.myPage', [
            'nationality'   => config('nation'),
            'phoneNumber'   => config('phone_number'),
            'profile'       => $profile,
            'plan'          => isset($plan) ? $plan : null
        ]);
    }


    /**
     * Check before when cancel trial payment.
     * @author vinhppvk
     *
     * @param Request $request
     * @return mixed
     */
    public function checkCancelTrialPlan(Request $request)
    {
        // Params
        $input = $request->all();
        $date_now = Carbon::now()->format('Y-m-d H:i:s');

        $user_payment_info = DB::table('user_payment_info')
            ->select('trial_end_date')
            ->where('user_id', Auth::id())
            ->first();

        $before_trial_end_date = Carbon::parse($user_payment_info->trial_end_date)->subHour(config('constants.time_hour_auto_payment'))->format('Y-m-d H:i:s');

        // Check
        if ($date_now <= $user_payment_info->trial_end_date && $date_now >= $before_trial_end_date) {
            return $this->responseError();
        } else {
            $list_booking = $this->profileRepository->listBookingTrial();
            return $this->responseSuccess($list_booking, 'Success');
        }
    }

    /**
     * Check before when cancel trial payment.
     * @author vinhppvk
     *
     * @param Request $request
     * @return mixed
     */
    public function cancelTrialPlan(Request $request)
    {
        DB::beginTransaction();
        try {
            $input = $request->all();

            // Cancel subscriptions
            $this->stripe->subscriptions->cancel($input['stripe_subscription_id']);
            $this->stripe->customers->update(
                $input['stripe_customer_id'],
                ['metadata' => ['stripe_plan' => '']]
            );

            // Update membership_status
            DB::table('user_information')
                ->where('user_id', Auth::id())
                ->update([
                    'membership_status' => 1,
                    'updated_at' => now(),
                ]);

            // Get list booking of student
            $list_booking = $this->profileRepository->listBookingTrial();

            if ($list_booking->count() > 0) {
                //Delete data of table booking
                DB::table('booking')
                    ->where('student_id', '=', Auth::id())
                    ->whereIn('teacher_schedule_id', $list_booking->pluck('teacher_schedule_id'))
                    ->delete();

                //Delete data of table student_courses
                DB::table('student_courses')
                    ->where('student_id', '=', Auth::id())
                    ->whereIn('teacher_schedule_id', $list_booking->pluck('teacher_schedule_id'))
                    ->delete();

                // Refund coin
                $coin_refund = 0;
                foreach ($list_booking as $key => $value) {
                    if ($value->status_coin_refund == 'YES') {
                        $coin_refund = $coin_refund + $value->coin;
                    }
                }

                DB::table('history_student_use_coin')
                    ->insert([
                        'student_id' => Auth::id(),
                        'coin' => $coin_refund,
                        'status' => 4,
                        'created_at' => now()
                    ]);

                // Update student_total_coin
                DB::table('student_total_coins')
                    ->where('student_id', Auth::id())
                    ->update([
                        'total_coin' => DB::raw('total_coin + ' . $coin_refund),
                        'updated_at' => now()
                    ]);

                // Update table: teacher_schedule
                DB::table('teacher_schedule')
                    ->whereIn('id', $list_booking->pluck('teacher_schedule_id'))
                    ->where('status', 2)
                    ->update([
                        'status' => 3,
                        'updated_at' => now(),
                    ]);
            }
            DB::commit();
            return redirect()->route('student.profile')->with('success', __('validation_custom.M053'));
        } catch (\Exception $e) {
            DB::rollback();
        }
    }

    /**
     * Check before when cancel premium payment.
     * @author vinhppvk
     *
     * @param Request $request
     * @return mixed
     */
    public function checkCancelPremiumPlan(Request $request)
    {
        // Get params
        $input = $request->all();
        $date_now = Carbon::now()->format('Y-m-d H:i:s');

        $user_payment_info = DB::table('user_payment_info')
            ->select('premium_end_date')
            ->where('user_id', Auth::id())
            ->first();

        $before_premium_end_date = Carbon::parse($user_payment_info->premium_end_date)->subHour(config('constants.time_hour_auto_payment'))->format('Y-m-d H:i:s');

        // Check
        if ($date_now <= $user_payment_info->premium_end_date && $date_now >= $before_premium_end_date) {
            return $this->responseError();
        } else {
            $list_booking = $this->profileRepository->listBookingPremium($input);
            return $this->responseSuccess($list_booking, 'Success');
        }
    }

    /**
     * Check before when cancel premium payment.
     * @author vinhppvk
     *
     * @param Request $request
     * @return mixed
     */
    public function cancelPremiumPlan(Request $request)
    {
        DB::beginTransaction();
        try {
            $input = $request->all();

            // Cancel subscriptions
            $this->stripe->subscriptions->cancel($input['stripe_subscription_id']);
            $this->stripe->customers->update(
                $input['stripe_customer_id'],
                ['metadata' => ['stripe_plan' => '']]
            );

            // Update membership_status
            DB::table('user_information')
                ->where('user_id', Auth::id())
                ->update([
                    'membership_status' => 6, //cancelling premium
                    'updated_at' => now(),
                ]);

            $list_booking = $this->profileRepository->listBookingPremium($input);

            if ($list_booking->count() > 0) {
                //Delete data of table booking
                DB::table('booking')
                    ->where('student_id', '=', Auth::id())
                    ->whereIn('teacher_schedule_id', $list_booking->pluck('teacher_schedule_id'))
                    ->delete();

                //Delete data of table student_courses
                DB::table('student_courses')
                    ->where('student_id', '=', Auth::id())
                    ->whereIn('teacher_schedule_id', $list_booking->pluck('teacher_schedule_id'))
                    ->delete();

                // Refund coin
                $list_teacher_schedule = DB::table('teacher_schedule')->whereIn('id', $list_booking->pluck('teacher_schedule_id'))->where('status', 2)->get();
                $coin_refund = 0;
                foreach ($list_teacher_schedule->groupBy('teacher_id') as $key => $value) {
                    $coin_of_teacher = DB::table('teacher_coin')->select('coin')->where('teacher_id', $key)->first();
                    $coin_refund = $coin_refund + $coin_of_teacher->coin * count($value);
                }
                DB::table('history_student_use_coin')
                    ->insert([
                        'student_id' => Auth::id(),
                        'coin' => $coin_refund,
                        'status' => 4,
                        'created_at' => now()
                    ]);

                // Update student_total_coin
                DB::table('student_total_coins')
                    ->where('student_id', Auth::id())
                    ->update([
                        'total_coin' => DB::raw('total_coin + ' . $coin_refund),
                        'updated_at' => now()
                    ]);

                // Update table: teacher_schedule
                DB::table('teacher_schedule')
                    ->whereIn('id', $list_booking->pluck('teacher_schedule_id'))
                    ->where('status', 2)
                    ->update([
                        'status' => 3,
                        'updated_at' => now(),
                    ]);
            }
            DB::commit();
            return redirect()->route('student.profile')->with('success', __('validation_custom.M053'));
        } catch (\Exception $e) {
            DB::rollback();
        }
    }

    /**
     * change nickname of student
     * by ThachDang
     * @param Request $request
     */
    public function changeNickname(Request $request) {
        $input = $request->only('old_nickname','new_nickname');

        $rules = [];
        $rules['old_nickname'] = 'required|between:1,50';
        $rules['new_nickname'] = 'required|between:1,50';

        $attributes = [
            'old_nickname' => __('student.current_nickname'),
            'new_nickname' => __('student.new_nickname'),
        ];

        $message = [
            'old_nickname.required'            => __('validation_custom.M001',['attribute'=>':attribute']),
            'old_nickname.in'                  => 'This email is incorrect',
            'old_nickname.between'             => __('validation_custom.M003',['attribute'=>':attribute']),
//            'old_nickname.regex'               => __('validation_custom.M004',['attribute'=>':attribute']),
            'new_nickname.required'            => __('validation_custom.M001',['attribute'=>':attribute']),
            'new_nickname.between'             => __('validation_custom.M003',['attribute'=>':attribute']),
//            'new_nickname.regex'               => __('validation_custom.M004',['attribute'=>':attribute']),
        ];

        $validator = Validator::make($input, $rules, $message)->setAttributeNames($attributes);

        if ($validator->fails()) {
            return $this->responseError(null, $validator->errors());
        } else {
            $is_success = $this->profileRepository->changeNickname($input);
            if($is_success) {
                return $this->responseSuccess();
            }
            return $this->responseError();
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(Request $request) {
        $data = $request->all();

        $attributes = [
            'old_password' => __('student.update.current_password'),
            'new_password' => __('student.update.new_password'),
            'new_password_confirmation' =>  __('student.update.confirm_new_password')
        ];

        $validator = Validator::make(
            $data,
            [
                'new_password' => 'required|between:8,16',
                'new_password_confirmation' => 'required|same:new_password',
                'old_password' => ['required', function ($attribute, $value, $fail) {
                    if (!\Hash::check($value, Auth::user()->password)) {
                        return $fail(__('validation_custom.M035'));
                    }
                }]
            ],
            [
                'new_password.required'             => __('validation_custom.M001',['attribute'=>':attribute']),
                'new_password.between'              => __('validation_custom.M003',['attribute'=>':attribute','min'=>'8','max'=>'16']),
                'new_password_confirmation.required'=>  __('validation_custom.M001',['attribute'=>':attribute']),
                'new_password_confirmation.same'    => __('validation_custom.M025'),
                'old_password.required'             => __('validation_custom.M001',['attribute'=>':attribute'])
            ]
        )->setAttributeNames($attributes);
        if ($validator->fails()) {
            return $this->responseError(null, $validator->errors());
        }

        if ($this->profileRepository->changePassword($data)) {
            return $this->responseSuccess();
        } else {
            return $this->responseError();
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeEmail(Request $request) {
        $input = $request->all();


        $rules = [];
        $rules['old_email'] = ['bail','required','email','between:3,70', new CheckEmailRule(),'in:'.Auth::user()->email];
        $rules['new_email'] = ['bail','required','email','between:3,70', new CheckEmailRule(), 'unique:users,email,'.$input['new_email']];
        $rules['new_email_confirmation'] = 'bail|required|same:new_email';


        $attributes = [
            'old_email'                 => __('student.update.current_email'),
            'new_email'                 => __('student.update.new_email'),
            'new_email_confirmation'    => __('student.update.confirm_new_email')
        ];


        $message = [
            'old_email.required'            => __('validation_custom.M001',['attribute'=>':attribute']),
            'old_email.in'                  => 'This email is incorrect',
            'old_email.email'               => __('validation_custom.M002'),
            'old_email.between'             => __('validation_custom.M003',['attribute'=>':attribute']),
            'old_email.regex'               => ':attribute'.config('validation.regex_alphanumeric'),
            'new_email.required'            => __('validation_custom.M001',['attribute'=>':attribute']),
            'new_email.email'               => __('validation_custom.M002'),
            'new_email.between'             => __('validation_custom.M003',['attribute'=>':attribute']),
            'new_email.regex'               => __('validation_custom.M004',['attribute'=>':attribute']),
            'new_email_confirmation.same'   =>  __('validation_custom.M026'),
            'new_email_confirmation.required'   => __('validation_custom.M001',['attribute'=>':attribute']),
            'new_email.unique'                  => __('validation_custom.M023')
        ];


        $validator = Validator::make($input, $rules, $message)->setAttributeNames($attributes);

        if ($validator->fails()) {
            return $this->responseError(null, $validator->errors());
        } else {
            $this->profileRepository->changeEmail($input);
            return $this->responseSuccess();
        }
    }

    public function updateProfile(Request $request) {
        $input = $request->all();
        $area_code=[];
        $sex=[];
        $nation_code=[];


        foreach (config('phone_number') as $key => $value) {
            array_push($area_code, ($key . '-' . $value['code']));
        }

        foreach (config('constants.sex.id') as $key => $value) {
            array_push($sex, $value);
        }

        foreach (config('nation') as $key => $value) {
            array_push($nation_code, $key);
        }


        $area_code = implode(',',$area_code);
        $sex = implode(',',$sex);
        $nation_code = implode(',',$nation_code);

        $rules = [];
        $year = $input['year'];
        $month = $input['month'];
        $day = $input['day'];
        $input['birthday']= null;

        if(!empty($year) && !empty($month) && !empty($day)) {
            $input['birthday'] = $year."-".$month. "-" . $day;
            $rules['birthday'] = [new CheckDateRule()];
        }
        if( (empty($year)) ) {
            $rules['birthday'] = 'required';
            $rules['year'] = 'required';
        }
        if( empty($month) ) {
            $rules['birthday'] = 'required';
            $rules['month'] = 'required';
        }
        if(  empty($day)  ) {
            $rules['birthday'] = 'required';
            $rules['day'] = 'required';
        }
        if(empty($year) && empty($month) && empty($day)) {
            $rules['birthday'] = '';
            $rules['day'] = '';
            $rules['month'] = '';
            $rules['year'] = '';
        }

        isset($input['sex'])  ? $rules['sex'] = 'numeric|in:'.$sex : $rules['sex'] = '';
        $input['phone_number'] != '' ? $rules['phone_number'] = 'numeric|digits_between:1,11' : $rules['phone_number'] = '';
        $input['area_code'] != '' ? $rules['area_code'] = 'in:'. $area_code : $rules['area_code'] = '';
        $input['nationality'] != '' ? $rules['nationality'] = 'in:'.$nation_code : $rules['nationality'] = '';
        isset($input['image_photo']) ? $rules['image_photo'] = 'mimes:jpeg,jpg,png,gif|max:5120' : $rules['image_photo'] = '';


        $attributes = [
            'year' => __('student.birthday'),
            'month' => __('student.birthday'),
            'day' => __('student.birthday'),
            'sex' => __('student.gender'),
            'phone_number' => __('student.phone_number'),
            'nationality' => __('student.nationality'),
            'image_photo' => __('student.photo'),
            'birthday' => __('student.birthday')
        ];


        $message = [
            'birthday.date_format'              => __('validation_custom.M020'),
            'sex.numeric'                       => __('validation_custom.M006',['attribute'=>':attribute']),
            'phone_number.numeric'              => __('validation_custom.M006',['attribute'=>':attribute']),
            'area_code.in'                      => ':attribute '.config('validation.digits_between'),       //chua co
            'image_photo.max'                   => __('validation_custom.M018',['attribute'=>':attribute','max' => '5120kb']),
            'phone_number.between'              => __('validation_custom.M018',['attribute'=>':attribute']),
            'phone_number.digits_between'       => __('validation_custom.M018',['attribute'=>':attribute','max' => '11']),
            'birthday.required'                 => __('validation_custom.M001',['attribute'=>':attribute']),
            'year.required'                     => __('validation_custom.M001',['attribute'=>':attribute']),
            'month.required'                    => __('validation_custom.M001',['attribute'=>':attribute']),
            'day.required'                      => __('validation_custom.M001',['attribute'=>':attribute']),
        ];

        $validator = Validator::make($input, $rules, $message)->setAttributeNames($attributes);
        if ($validator->fails()) {
            return $this->responseError(null, $validator->errors());
        } else {
            //update profile
            $status = $this->profileRepository->updateProfile($input);
            if($status) {
                return $this->responseSuccess($status,__('validation_custom.M027'));
            }
            else {
                return $this->responseSuccess(null,__('validation_custom.M028'));
            }
        }
    }
}
