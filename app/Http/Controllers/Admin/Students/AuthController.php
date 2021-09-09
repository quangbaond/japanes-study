<?php

namespace App\Http\Controllers\Admin\Students;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LoginStudentRequest;
use App\Models\Plan;
use App\Models\User;
use App\Repositories\Admin\students\ProfileRepository;
use App\Rules\CheckEmailRule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Services\PayPalService;
use App\Services\MailService;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use App\Repositories\Admin\Managers\StudentRepository;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Cookie;
use App\Rules\CheckDateRule;
use Stripe\Exception\ApiErrorException;
use Timezone;
use App\Http\Controllers\TimezoneController;

class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $paypalServices;
    protected $studentRepository;
    protected $mailServices;
    protected $stripe;
    protected $profileRepository;
    protected $timezone;

    /**
     * Display a listing of the resource.
     *
     * @param PayPalService $paypalServices
     * @param StudentRepository $studentRepository
     * @param MailService $mailServices
     * @param ProfileRepository $profileRepository
     * @param TimezoneController $timezone
     */
    public function __construct(
        PayPalService $paypalServices,
        StudentRepository $studentRepository,
        MailService $mailServices,
        ProfileRepository $profileRepository,
        TimezoneController $timezone
    )
    {
        $this->paypalServices = $paypalServices;
        $this->studentRepository = $studentRepository;
        $this->mailServices = $mailServices;
        $this->stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        $this->profileRepository = $profileRepository;
        $this->timezone = $timezone;
    }

    /**
     * Blade student login
     * @author vinhppvk
     *
     * @return View
     */
    public function index()
    {
        return view('admin.students.login');
    }

    /**
     * Handle logic login student
     * @author vinhppvk
     *
     * @param LoginStudentRequest $request
     * @return RedirectResponse
     */
    public function postLogin(LoginStudentRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $rememberMe = $request->has('remember_me') ? true : false;

        if (Auth::attempt($credentials , $rememberMe)  && Auth::user()->role == config('constants.role.student') && Auth::user()->deleted_at == null && Auth::user()->auth == 1) {
            if ($rememberMe) {
                Cookie::queue('email_student', $request->email, 100000);
                Cookie::queue('password_student', $request->password, 100000);
                Cookie::queue('remember_me_student', 'checked', 100000);
            } else {
                Cookie::queue(Cookie::forget('email_student'));
                Cookie::queue(Cookie::forget('password_student'));
                Cookie::queue(Cookie::forget('remember_me_student'));
            }
            DB::table('users')->where('id', Auth::user()->id)->update(['last_login_at'  => now()]);

            // Show timezone when login
            $ip = geoip()->getLocation(geoip()->getClientIP());
            request()->session()->flash('success', __('validation_custom.M072') . $ip['timezone']);

            return redirect()->intended('student');
        }
        // check language vi
        $language = Session::get('language');
        if($language == 'vi') {
            return redirect()->route('login.student')->with('error', 'Thông tin đăng nhập của bạn không chính xác. Vui lòng thử lại.')->withInput();
        }
        return redirect()->route('login.student')->with('error', 'Your login information was incorrect. Please try again.')->withInput();
    }

    /**
     * Handle logic create user
     * @author vinhppvk
     *
     */
    public function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => 3,
            'password' => Hash::make($data['password'])
        ]);
    }

    /**
     * Handle logout for student.
     * @author vinhppvk
     *
     * @return RedirectResponse
     */
    public function logout() {
        // Delete cache
        Cache::pull('user-is-online-' . Auth::id());
        Session::flush();
        Auth::logout();
        return Redirect('student/login');
    }


    /**
     * Payment of paypal demo
     * @author vinhppvk
     *
     * @return RedirectResponse
     * @throws \Exception
     */
    public function payPal()
    {
        $data = [
            [
                'name' => 'MH-01',
                'quantity' => 1,
                'price' => 10,
                'sku' => '1'
            ]
        ];
        $transactionDescription = "Tobaco";

        $paypalCheckoutUrl = $this->paypalServices
            // ->setCurrency('eur')
            ->setReturnUrl(url('student/payments/status'))
            // ->setCancelUrl(url('paypal/status'))
            ->setItem($data)
            // ->setItem($data[0])
            // ->setItem($data[1])
            ->createPayment($transactionDescription);

        if ($paypalCheckoutUrl) {
            return redirect($paypalCheckoutUrl);
        } else {
            dd(['Error']);
        }
    }

    /**
     * Screen register student
     *
     * @return View
     */
    public function registerStudent()
    {
        $plans = Plan::all();
        return view('admin.students.register.index', ['area_code' => config('phone_number'), 'nationalities' => config('nation'), 'plans' => $plans]);
    }

    /**
     * Validation register step 1
     * @author vinhppvk
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function registerStep1Validation(Request $request)
    {
        $input = $request->all();
        // Rule validation
        $rules = [];
//        $rules['email'] = 'required|email|between:3,70|regex: /^(?=.*[a-z0-9])[a-z0-9!@#$%&*-_.]{1,}$/i';
        $rules['email'] = ['required', 'email', 'between:3,70' , new CheckEmailRule()];
        $rules['email_confirm'] = ['required', 'email', 'between:3,70', new CheckEmailRule(), 'same:email'];
        $rules['password'] = 'required|between:8,16';
        $rules['password_confirm'] = 'required|between:8,16|same:password';
        $rules['nickname'] = 'required|between:1,50';
        $rules['phone_number'] = 'nullable|regex:/^([0-9]*)$/|max:11';
        if ($request->hasFile('image_photo')) {
            $rules['image_photo'] = 'mimes:jpeg,jpg,png,gif|max:5000';
        }

        //Quy
        if (!empty($input['year'])) {
            $rules['month'] = 'required';
        }
        if (!empty($input['year']) || !empty($input['month'])) {
            $rules['day'] = 'required';
        }
        if (!empty($input['year']) || !empty($input['day'])) {
            $rules['month'] = 'required';
        }
        if (!empty($input['month'])) {
            $rules['year'] = 'required';
        }
        if (!empty($input['day'])) {
            $rules['year'] = 'required';
        }
        $rules['birthday'] = [new CheckDateRule()];

        // Set name for field
        $attributes = array(
            'email'                 => __('student.email'),
            'email_confirm'         => __('student.email_confirm'),
            'password'              => __('student.password'),
            'password_confirm'      => __('student.password_confirm'),
            'nickname'              => __('student.nickname'),
            'phone_number'          => __('student.phone_number'),
            'image_photo'           => __('student.image_photo'),
        );

        //Message validation
        $message = [
            'email.regex'           => __('student.regex_haft_width'),
            'email_confirm.regex'   => __('student.regex_haft_width'),
            'phone_number.regex'    => __('validation_custom.M006'),
            'year.required'         => __('student.birthday_format'),
            'month.required'        => __('student.birthday_format'),
            'day.required'          => __('student.birthday_format'),
            'image_photo.mimes'     => '',
            'image_photo.max'     => '',
        ];

        $validator = Validator::make($input, $rules, $message)->setAttributeNames($attributes);

        if ($validator->fails()) {
            return $this->responseError("validation", $validator->errors());
        } else {
            return $this->checkUserStudent($input);
        }
    }

    /**
     * Function check user isset in DB
     * @author vinhppvk
     *
     * @param $input
     * @return JsonResponse
     */
    protected function checkUserStudent($input)
    {
        $check = $this->studentRepository->checkUserStudent($input);
        if ($check['status']) {
            return $this->responseSuccess();
        } else {
            return $this->responseError($check['check'], $check['message']);
        }

    }

    /**
     * Register student step 2
     * @author vinhppvk
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function registerStep2Save(Request $request)
    {
        $check = $this->studentRepository->registerStudentStep2($request, 1);
        if ($check['status']) {
            return $this->responseSuccess();
        } else {
            return $this->responseError($check['check'], $check['message']);
        }

    }

    /**
     * Activate users
     * @author vinhppvk
     *
     * @param $token
     * @return View
     */
    public function activateUser($token)
    {
        $data = $this->mailServices->activateUser($token);
        if ($data['status']) {
            return view('admin.students.register.step5');
        } else {
            if ($data['flag'] == 'expire') {
                return view('admin.students.register.expire');
            } else {
                abort(404);
            }
        }
    }

    /**
     * Send mail (link url) to auth student
     * @author vinhppvk
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function sendMailUpdateAuth(Request $request)
    {
        $input = $request->all();
        $user = DB::table('users')->select('*')->where('email', $input['email'])->first();
        $this->mailServices->sendActivationMail($user);
        return $this->responseSuccess();
    }

    /**
     * login Zalo
     * @author vinhppvk
     *
     * @return void
     */
    public function loginZalo(){
        return Socialite::with('zalo')->redirect();
    }

    /**
     * callbackZaloLogin
     *  @author vinhppvk
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function callbackZaloLogin(Request $request){
        // Case error
        if (!empty($request->input('error'))) {
            return Redirect::to(route('login.student'));
        }

        $user = Socialite::with('zalo')->user();
        $check = $this->studentRepository->studentLoginZalo($user);
        if ($check['status'] == true) {
            if (empty(Auth::user()->password)) {
                return Redirect::to(route('student-password'));
            } else {
                return Redirect::to(route('student-dashboard'));
            }
        }
        return Redirect::to(route('login.student'))->with('error', __('login.error_login'));

    }

    /**
     * login facebook
     * @author vinhppvk
     *
     * @return void
     */
    public function loginFacebook(){
        return Socialite::with('facebook')->redirect();
    }

    /**
     * callbackFacebookLogin
     * @author vinhppvk
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function callbackFacebookLogin(Request $request){
        // Case error
        if (!empty($request->input('error_code'))) {
            return Redirect::to(route('login.student'));
        }

        $user = Socialite::with('facebook')->user();
        $check = $this->studentRepository->studentLoginSocail($user);
        if ($check['status'] == true) {
            if (empty(Auth::user()->password)) {
                return Redirect::to(route('student-password'));
            } else {
                return Redirect::to(route('student-dashboard'));
            }
        }
        return Redirect::to(route('login.student'))->with('error', __('login.error_login'));
    }

    /**
     * login facebook
     * @author vinhppvk
     *
     * @return void
     */
    public function loginGoogle(){
        return Socialite::with('google')->redirect();
    }

    /**
     * callbackGoogleLogin
     * @author vinhppvk
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function callbackGoogleLogin(Request $request){
        $user = Socialite::with('google')->user();
        $check = $this->studentRepository->studentLoginSocail($user);
        if ($check['status'] == true) {
            if (empty(Auth::user()->password)) {
                return Redirect::to(route('student-password'));
            } else {
                return Redirect::to(route('student-dashboard'));
            }
        }
        return Redirect::to(route('login.student'))->with('error', __('login.error_login'));
    }

    /**
     * Function validation payment credit subscriptions
     * @author vinhppvk
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ApiErrorException
     */
    public function validationPayment(Request $request)
    {
        $input = $request->all();

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

        if ($validator->fails()) { // Validation
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

    /**
     * Function handle payment credit subscriptions
     * @author vinhppvk
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ApiErrorException
     */
    public function handlePayment(Request $request)
    {
        // Params data
        $input = $request->all();
        $stripe_plan = DB::table('plans')->select('stripe_plan')->where('id', $input['plan_id'])->first();
        $check_card_register_student = $this->studentRepository->checkCardOfRegisterStudent($input, $stripe_plan);
        if ($check_card_register_student['status']) {
            //Insert user(student) in DB
            $user = $this->studentRepository->registerStudentStep2($request, 2);

            //Update customer stripe
            $this->stripe->customers->update(
                $check_card_register_student['data']['customer_id'],
                [
                    'metadata' => [
                        'user_id'       => $user['user_id'],
                        'stripe_plan'   => $stripe_plan->stripe_plan
                    ]
                ]
            );

            // Insert table: user_payment_info
            DB::table('user_payment_info')->insert([
                'user_id'                   => $user['user_id'],
                'stripe_customer_id'        => $check_card_register_student['data']['customer_id'],
                'payment_method'            => 2,
                'stripe_subscription_id'    => $check_card_register_student['data']['subscriptions_id'],
                'trial_start_date'          => Carbon::now(),
                'trial_end_date'            => $this->timezone->convertFromLocalNotLogin(Carbon::parse($input['date_deadline'])->format('Y-m-d H:i:s')),
                'created_at'                => Carbon::now(),
                'updated_at'                => Carbon::now(),
            ]);

            return $this->responseSuccess("", 'Success');
        } else {
            return $this->responseError("", $check_card_register_student['data']);
        }
    }

    /**
     * termsOfService
     * @author vinhppvk
     *
     * @return  view
     */
    public function termOfService()
    {
        return view('admin.students.termsOfService');
    }

    /**
     * Function active user when change email
     * @author vinhppvk
     *
     * @param $new_email
     * @param $token
     * @return  view
     */
    public function activateUserWhenChange($new_email, $token)
    {
        $data = $this->mailServices->activateUser($token);
        if ($data['status']) {
            $user = $this->profileRepository->updateNewEmail($new_email, $data['user_id']);
            return view('mails.mail-of-student-when-changed-successfully');
        } else {
            if ($data['flag'] == 'expire') {
                return view('admin.students.register.expire');
            } else {
                abort(404);
            }
        }
    }

    /**
     * Function show date deadline plan
     * @author vinhppvk
     *
     * @param Request $request
     * @return  JsonResponse
     */
    public function showDateDeadline(Request $request)
    {
        $date = Carbon::now()->addMinute(config('constants.time_minute_trial_end'))->addHour(config('constants.time_hour_auto_payment'))->format('Y-m-d H:i:s');
        $data = $this->timezone->convertToLocalWhenNotLogin(Carbon::parse($date));
        return $this->responseSuccess($data, 'Success');
    }
}

