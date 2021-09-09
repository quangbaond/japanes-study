<?php

namespace App\Http\Controllers\Admin\Students;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Repositories\Admin\Managers\StudentRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Timezone;
use Stripe\Exception\ApiErrorException;
use Yajra\DataTables\Facades\DataTables;

class AddCoinController extends Controller
{
    protected $studentRepository;
    protected $stripe;

    /**
     * Create a new controller instance.
     *
     * @param StudentRepository $studentRepository
     */
    public function __construct(StudentRepository $studentRepository)
    {
        $this->studentRepository = $studentRepository;
        $this->stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
    }

    /**
     * Show history coin of student
     * @author vinhppvk
     *
     * @return View
     */
    public function index()
    {
        // Check cancelling premium for user
        $this->cancellingPremium();

        // Get data
        $student = DB::table('users')
            ->select(
            'student_total_coins.total_coin',
                'student_total_coins.expiration_date',
                'user_information.membership_status',
                'users.email',
                'user_information.phone_number',
                'user_information.area_code'
            )
            ->leftJoin('student_total_coins', 'student_total_coins.student_id', '=', 'users.id')
            ->leftJoin('user_information', 'user_information.user_id', '=', 'users.id')
            ->where('users.id', Auth::id())
            ->first();
        $history = DB::table('history_student_use_coin')->orderBy('id', 'DESC')->where('student_id', Auth::id())->count();
//        if (
//            $student->membership_status == config('constants.membership.id.free')
//            || $student->membership_status == config('constants.membership.id.Special')
//            || $student->membership_status == config('constants.membership.id.other_company')
//        ) {
//            return view('admin.students.addCoin.notAddCoin');
//        }
        $master_coin = DB::table('master_coin')->get();
        return view('admin.students.addCoin.index', compact('master_coin', 'student', 'history'));
    }

    /**
     * Check modal add coin when not load page
     * @author vinhppvk
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function checkCancelPremium(Request $request)
    {
        $user = DB::table('users')
            ->select(
                'users.id',
                'user_information.membership_status',
                'user_payment_info.premium_start_date',
                'user_payment_info.premium_end_date'
            )
            ->leftJoin('user_information', 'user_information.user_id', '=', 'users.id')
            ->leftJoin('user_payment_info', 'user_payment_info.user_id', '=', 'users.id')
            ->where('users.id', Auth::id())
            ->first();
        if ( ($user->membership_status == 6 || $user->membership_status == 3) && \Carbon\Carbon::now()->format('Y-m-d H:i:s') > $user->premium_end_date) {
            // Update table user_information
            DB::table('user_information')
                ->where('user_id', Auth::id())
                ->update([
                    'membership_status' => 1
                ]);

            return $this->responseError();
        } else {
            if ($user->membership_status == 1) {
                return $this->responseSuccess(false);
            } else {
                return $this->responseSuccess(true);
            }

        }
    }

    /**
     * Display history user coin of student datatable.
     * @author vinhppvk
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function historyDataTable()
    {
        $history = DB::table('history_student_use_coin')->orderBy('id', 'DESC')->where('student_id', Auth::id())->get();
        return Datatables::of($history)
            ->addColumn('created_at', function ($student) {
                return Helper::formatDateHIS(Timezone::convertToLocal(Carbon::parse($student->created_at), 'Y-m-d H:i:s'));
            })
            ->addColumn('status', function ($student) {
                if ($student->status == 1) {
                    return '<span class="text-info">'.__('student.history_student_use_coin.add_coin').'</span>';
                }
                if ($student->status == 2) {
                    return '<span class="text-danger">'.__('student.history_student_use_coin.booking').'</span>';
                }
                if ($student->status == 3) {
                    return '<span class="text-warning">'.__('student.history_student_use_coin.start_lesson_now').'</span>';
                }
                if ($student->status == 4) {
                    return '<span class="text-primary">'.__('student.history_student_use_coin.refund').'</span>';
                }
            })
            ->addColumn('coin', function ($student) {
                if ($student->status == 1) {
                    return '<span class="text-bold text-info"> +'.$student->coin.'</span>';
                }
                if ($student->status == 2) {
                    return '<span class="text-bold text-danger"> -'.$student->coin.'</span>';
                }
                if ($student->status == 3) {
                    return '<span class="text-bold text-warning"> -'.$student->coin.'</span>';
                }
                if ($student->status == 4) {
                    return '<span class="text-bold text-primary"> +'.$student->coin.'</span>';
                }
            })
            ->rawColumns(['created_at', 'coin', 'status'])
            ->make(true);
    }

    protected function insertTablePaymentCoin($total_coin)
    {
        DB::beginTransaction();
        try {
            //Insert table: history_student_use_coin
            DB::table('history_student_use_coin')->insert([
                'student_id'    => Auth::id(),
                'coin'          => $total_coin,
                'status'        => 1,
                'created_at'    => now(),
            ]);

            //Insert or update table: student_total_coins
            $student_total_coins = DB::table('student_total_coins')->select('id')->where('student_id', Auth::id())->first();
            if (empty($student_total_coins)) { // Insert
                DB::table('student_total_coins')->insert([
                    'student_id'        => Auth::id(),
                    'total_coin'        => $total_coin,
                    'expiration_date'   => Carbon::now()->addMonth(config('constants.expiration_date_add_coin')),
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ]);
            } else { //Update
                DB::table('student_total_coins')
                    ->where('student_id', Auth::id())
                    ->update([
                        'total_coin'        => DB::raw('total_coin +'.$total_coin),
                        'expiration_date'   => Carbon::now()->addMonth(config('constants.expiration_date_add_coin')),
                        'updated_at'        => now(),
                    ]);
            }
            $info_total_coin = DB::table('student_total_coins')->select('total_coin', 'expiration_date')->where('student_id', Auth::id())->first();
            $info_total_coin->expiration_date_timezone = Timezone::convertToLocal(\Carbon\Carbon::parse($info_total_coin->expiration_date), 'Y-m-d H:i:s');
            DB::commit();
            return $info_total_coin;
        } catch (\Exception $e) {
            DB::rollback();
        }
    }

    /**
     * Handle validation payment coin for student
     * @author vinhppvk
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ApiErrorException
     */
    public function validationPaymentCoin(Request $request)
    {
        $input = $request->all();

        $input['number_card'] = str_replace(' ', '', $input['number_card']);

        if ($input['choice_payment'] == 1) { // Payment with Registered credit card
            // Get student
            $user = DB::table('users')
                ->select('users.id', 'user_payment_info.stripe_customer_id')
                ->leftJoin('user_payment_info', 'user_payment_info.user_id', '=', 'users.id')
                ->where('users.id', Auth::id())
                ->first();
            $customer_id = $user->stripe_customer_id;
            $customer = $this->stripe->customers->retrieve($customer_id,[]);
            $paymentMethods = $this->stripe->paymentMethods->retrieve($customer->invoice_settings->default_payment_method, []);
            return $this->responseSuccess($paymentMethods, 'success');
        } elseif ($input['choice_payment'] == 2) { //Payment with new credit card
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
    }

    /**
     * Handle payment coin for student
     * @author vinhppvk
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ApiErrorException
     */
    public function paymentCoin(Request $request)
    {
        $input = $request->all();
        // Get student
        $user = DB::table('users')
            ->select('users.id', 'user_payment_info.stripe_customer_id')
            ->leftJoin('user_payment_info', 'user_payment_info.user_id', '=', 'users.id')
            ->where('users.id', Auth::id())
            ->first();

        $customer_id = $user->stripe_customer_id;
        $master_coin = DB::table('master_coin')->where('id', $input['id_master_coin'])->first();
        $total_coin = $master_coin->coin + $master_coin->bonus_coin;

        if ($input['choice_payment'] == 1) { // Payment with Registered credit card
            $customer = $this->stripe->customers->retrieve($customer_id,[]);
            $check_card_money = $this->studentRepository->checkCardMoney($master_coin, $customer_id, $customer->invoice_settings->default_payment_method, 'Add coin: Payment with registered credit card');
            if ($check_card_money['status']) {
                $info = $this->insertTablePaymentCoin($total_coin);
                return $this->responseSuccess($info, 'Success');
            } else {
                return $this->responseError("", $check_card_money['data']);
            }
        } elseif ($input['choice_payment'] == 2) { //Payment with new credit card
            $check_card_money = $this->studentRepository->checkCardMoney($master_coin, $customer_id, $input['payment_method'], 'Add coin: Payment with new credit card');
            if ($check_card_money['status']) {
                $info = $this->insertTablePaymentCoin($total_coin);
                return $this->responseSuccess($info, 'Success');
            } else {
                return $this->responseError("", $check_card_money['data']);
            }
        }


    }

}
