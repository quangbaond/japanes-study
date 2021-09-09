<?php

namespace App\Http\Controllers\Admin\Students;

use App\Http\Controllers\TimezoneController;
use App\Models\Plan;
use App\Repositories\Admin\Managers\StudentRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Stripe\Exception\ApiErrorException;
use Timezone;

class PaymentController extends Controller
{

    protected $studentRepository;
    protected $stripe;
    protected $timezone;

    /**
     * Create a new controller instance.
     *
     * @param StudentRepository $studentRepository
     * @param TimezoneController $timezone
     */
    public function __construct(StudentRepository $studentRepository, TimezoneController $timezone)
    {
        $this->studentRepository = $studentRepository;
        $this->stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        $this->timezone = $timezone;
    }

    /**
     * Function show payment
     * @author vinhppvk
     *
     * @return View
     */
    public function get7DaysTrial()
    {
        $user_payment_info = DB::table('user_payment_info')->select('id')->where('user_id', Auth::user()->id)->first();
        if (!empty($user_payment_info)) {
            return view('admin.students.getTrial.notGetTrial');
        }
        $plans = Plan::all();
        $user = DB::table('users')
            ->select('users.email', 'user_information.area_code', 'user_information.phone_number')
            ->leftJoin('user_information', 'user_information.user_id', '=', 'users.id')
            ->where('users.id', Auth::user()->id)
            ->first();
        return view('admin.students.getTrial.index', compact('plans', 'user'));
    }

    /**
     * Function validation payment credit
     * @author vinhppvk
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ApiErrorException
     */
    public function validationPaymentCredit(Request $request)
    {
        // Params data
        $input = $request->all();

        if ($input['choice_payment'] == 1) { // Payment with Registered credit card
            return $this->responseSuccess();
        } elseif ($input['choice_payment'] == 2) { //Payment with new credit card
            $input['number_card'] = str_replace(' ', '', $input['number_card']);

            // Rule validation
            $rules = [];
            $rules['name_card']         = 'required';
            $rules['number_card']       = 'required|numeric';
            $rules['cvc']               = 'required';
            $rules['date_expiration']   = 'required';

            // Set name for field
            $attributes = array(
                'name_card'         => __('student.step3.name_card'),
                'number_card'       => __('student.step3.number_card'),
                'cvc'               => __('student.step3.cvc'),
                'date_expiration'   => __('student.step3.expiration_date'),
            );

            //Message validation
            $message = [];

            $validator = Validator::make($input, $rules, $message)->setAttributeNames($attributes);

            if ($validator->fails()) {
                return $this->responseError("validation", $validator->errors());
            } else {
                $checkPaymentMethod = $this->studentRepository->checkPaymentMethod($input);
                if ($checkPaymentMethod['status']) { // Create payment method success
                    return $this->responseSuccess($checkPaymentMethod['data'], 'success');
                } else { // crete payment method error
                    return $this->responseError("", $checkPaymentMethod['data']);
                }
            }
        }
    }

    /**
     * Function handle payment for student
     * @author vinhppvk
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ApiErrorException
     */
    public function save7DaysTrial(Request $request)
    {
        // Params data
        $input = $request->all();
        $stripe_plan = DB::table('plans')->select('stripe_plan')->where('id', $input['plan_id'])->first();
        $check_card_trial_and_premium = $this->studentRepository->checkCardTrial($input, $stripe_plan);
        if ($check_card_trial_and_premium['status']) {
            // Insert or update table: user_information
            $user_information = DB::table('user_information')->select('id')->where('user_id', Auth::user()->id)->first();
            if (empty($user_information)) { // Insert
                DB::table('user_information')->insert([
                    'user_id'               => Auth::id(),
                    'membership_status'     => 2,
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),
                ]);
            } else { // Update
                DB::table('user_information')
                    ->where('user_id', Auth::id())
                    ->update([
                        'membership_status' => 2,
                        'updated_at'        => Carbon::now(),
                    ]);
            }

            // Insert  table: user_payment_info
            DB::table('user_payment_info')->insert([
                'user_id'                   => Auth::id(),
                'stripe_customer_id'        => $check_card_trial_and_premium['data']['customer_id'],
                'payment_method'            => 2,
                'stripe_subscription_id'    => $check_card_trial_and_premium['data']['subscriptions_id'],
                'trial_start_date'          => Carbon::now(),
                'trial_end_date'            => $this->timezone->convertFromLocalNotLogin(Carbon::parse($input['date_deadline'])->format('Y-m-d H:i:s')),
                'created_at'                => Carbon::now(),
                'updated_at'                => Carbon::now(),
            ]);

            return $this->responseSuccess("", 'Success');
        } else {
            return $this->responseError("", $check_card_trial_and_premium['data']);
        }
    }

    /**
     * Function show payment premium
     * @author vinhppvk
     *
     * @return View
     */
    public function getPremium()
    {
        $plans = Plan::all();
        $user = DB::table('users')
            ->select('users.email', 'user_information.area_code', 'user_information.phone_number', 'user_information.membership_status')
            ->leftJoin('user_information', 'user_information.user_id', '=', 'users.id')
            ->where('users.id', Auth::user()->id)
            ->first();
        $user_payment_info = DB::table('user_payment_info')->select('id', 'premium_end_date')->where('user_id', Auth::user()->id)->first();
        if (!empty($user_payment_info)) {
            if ($user_payment_info->premium_end_date >= Carbon::now()->format('Y-m-d H:i:s') && ($user->membership_status == config('constants.membership.id.premium') || $user->membership_status == config('constants.membership.id.cancelling_premium'))) {
                return view('admin.students.getPremium.notGetPremium', compact('user_payment_info'));
            } else {
                return view('admin.students.getPremium.index', compact('plans', 'user'));
            }
        }
        return view('admin.students.getPremium.index', compact('plans', 'user'));
    }

    /**
     * Function handle payment premium for student
     * @author vinhppvk
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ApiErrorException
     */
    public function saveGetPremium(Request $request)
    {
        // Params data
        $input = $request->all();
        $stripe_plan = DB::table('plans')->select('stripe_plan')->where('id', $input['plan_id'])->first();
        if ($input['choice_payment'] == 1) { // Payment with Registered credit card
            $check_card_premium = $this->studentRepository->checkCardPremium($input, $stripe_plan);

            if ($check_card_premium['status']) { // Case: card ok
                $this->insertUserPaymentInfo($input, $check_card_premium['data']['subscriptions_id']);
                return $this->responseSuccess("", 'Success');
            } else { // Case: card not ok
                return $this->responseError("", $check_card_premium['data']);
            }

        } elseif ($input['choice_payment'] == 2) { //Payment with new credit card
            $check_card_premium_new = $this->studentRepository->checkCardPremiumNew($input, $stripe_plan);

            if ($check_card_premium_new['status']) {
                $this->insertUserPaymentInfo($input, $check_card_premium_new['data']['subscriptions_id']);
                return $this->responseSuccess("", 'Success');
            } else {
                return $this->responseError("", $check_card_premium_new['data']);
            }
        }
    }

    /**
     * Function insert DB: user_payment_info
     * @author vinhppvk
     *
     * @param $input
     * @param $stripe_subscription_id
     * @return void
     */
    protected function insertUserPaymentInfo($input, $stripe_subscription_id )
    {
        DB::beginTransaction();
        try {
            // Insert or update table: user_information
            DB::table('user_information')
                ->where('user_id', Auth::user()->id)
                ->update([
                    'membership_status' => 3,
                    'updated_at'        => Carbon::now(),
                ]);

            // Get plans
            $plan = DB::table('plans')->select('interval', 'interval_count')->where('id', $input['plan_id'])->first();
            $premium_end_date = Carbon::now();
            if ($plan->interval == 'day') {
                $premium_end_date = Carbon::now()
                    ->addDay($plan->interval_count)
                    ->addHour(config('constants.time_hour_auto_payment')); // addHour() : time when auto payment
            }

            if ($plan->interval == 'week') {
                $premium_end_date = Carbon::now()
                    ->addWeek($plan->interval_count)
                    ->addHour(config('constants.time_hour_auto_payment')); // addHour() : time when auto payment
            }

            if ($plan->interval == 'month') {
                $premium_end_date = Carbon::now()
                    ->addMonth($plan->interval_count)
                    ->addHour(config('constants.time_hour_auto_payment')); // addHour() : time when auto payment
            }

            if ($plan->interval == 'year') {
                $premium_end_date = Carbon::now()
                    ->addYear($plan->interval_count)
                    ->addHour(config('constants.time_hour_auto_payment')); // addHour() : time when auto payment
            }

            // Update table: user_payment_info
            DB::table('user_payment_info')
                ->where('user_id', Auth::id())
                ->update([
                    'payment_method'            => 2,
                    'stripe_subscription_id'    => $stripe_subscription_id,
                    'premium_start_date'        => Carbon::now(),
                    'premium_end_date'          => $premium_end_date,
                    'updated_at'                => Carbon::now(),
                ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }
    }
}
