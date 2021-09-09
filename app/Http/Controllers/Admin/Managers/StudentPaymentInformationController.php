<?php

namespace App\Http\Controllers\Admin\Managers;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;

use App\Http\Controllers\TimezoneController;
use App\Repositories\Admin\Managers\PaymentStripeRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use function PHPUnit\Framework\throwException;
use Timezone;

class StudentPaymentInformationController extends Controller
{
    protected $paymentStripeRepository;
    protected $stripe;
    protected $timezone;
    /**
     * Display a listing of the resource.
     *
     * @param UserRepository $userRepository
     * @param ProfileRepository $profileRepository
     * @param StudentRepository $studentRepository
     */
    function __construct(PaymentStripeRepository $paymentStripeRepository, TimezoneController $timezone)
    {
        $this->paymentStripeRepository = $paymentStripeRepository;
        $this->stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        $this->timezone = $timezone;
    }

    /**
     * Display students.
     * @return View
     */
    public function index(Request $request)
    {
        $buttonPrevious = false;
        $buttonNext = false;
        $next_url = "";
        $previous_url = "";
        $paymentHistories=null;
        $emailMatchCustomerId = null;
        $emailMatchCustomerIdSelected = null;
        $query_string = $request->only('first_id', 'last_id','user_id', 'from_date', 'to_date');
        try {
//            //case: don't search
//            if( !(isset($query_string['user_id']) || isset($query_string['from_date']) || isset($query_string['to_date'])) ) {
//
//                //case: when click button previous
//                if(isset($query_string['last_id'])) {
//                    $buttonPrevious = true;
//
//                    $array_parameters = [
//                        'starting_after'=>$query_string['last_id'],
//                        'limit' => 11
//                    ];
//
//                    $paymentHistories = $this->getDataFromStripeApi($array_parameters);
//
//                    if(count($paymentHistories) == 11) {
//                        $buttonNext = true;
//                        $paymentHistories = array_slice($paymentHistories, 0, 10);
//                        $next_url = route('admin.payment.index') . "?last_id=" . $paymentHistories[count($paymentHistories)-1]->id;
//                    }
//                    $previous_url = route('admin.payment.index') . "?first_id=" . $paymentHistories[0]->id;
//
//                }//case: when click button next
//                elseif(isset($query_string['first_id'])) {
//                    $buttonNext = true;
//
//                    $array_parameters = [
//                        'ending_before'=>$query_string['first_id'],
//                        'limit' => 11
//                    ];
//                    $paymentHistories = $this->getDataFromStripeApi($array_parameters);
//
//                    if(count($paymentHistories) == 11) {
//                        $buttonPrevious = true;
//                        $paymentHistories = array_slice($paymentHistories, 1, 10);
//                        $previous_url = route('admin.payment.index') . "?first_id=" . $paymentHistories[0]->id;
//                    }
//                    $next_url = route('admin.payment.index') . "?last_id=" . $paymentHistories[count($paymentHistories)-1]->id;
//                }// case: begin
//                else {
//
//                    $array_parameters = [
//                        'limit' => 11,
//                    ];
//                    $paymentHistories = $this->getDataFromStripeApi($array_parameters);
//
//                    if(count($paymentHistories) == 11) {
//                        $buttonNext = true;
//                        $paymentHistories = array_slice($paymentHistories, 0, 10);
//                        $next_url = route('admin.payment.index') . "?last_id=" . $paymentHistories[count($paymentHistories)-1]->id;
//                        $previous_url = route('admin.payment.index') . "?first_id=" . $paymentHistories[0]->id;
//                    }
//                }
//            }
//            //case: search
//            else {
//                if(isset($query_string['user_id']) && ( !isset($query_string['to_date']) && !isset($query_string['to_date']) ) ){
//                    //get stripe_customer_id from db
//                    $stripe_customer_id = $this->paymentStripeRepository->getStripeCustomerIdByUserId($query_string['user_id'])->stripe_customer_id;
//                    //case: when click button previous
//                    if(isset($query_string['last_id'])) {
//                        $buttonPrevious = true;
//
//                        $array_parameters = [
//                            'customer' => $stripe_customer_id,
//                            'starting_after'=>$query_string['last_id'],
//                            'limit' => 11
//                        ];
//
//                        $paymentHistories = $this->getDataFromStripeApi($array_parameters);
//
//                        if(count($paymentHistories) == 11) {
//                            $buttonNext = true;
//                            $paymentHistories = array_slice($paymentHistories, 0, 10);
//                            $next_url = route('admin.payment.index') . "?last_id=" . $paymentHistories[count($paymentHistories)-1]->id;
//                        }
//                        $previous_url = route('admin.payment.index') . "?first_id=" . $paymentHistories[0]->id;
//
//                    }//case: when click button next
//                    elseif(isset($query_string['first_id'])) {
//                        $buttonNext = true;
//
//                        $array_parameters = [
//                            'customer' => $stripe_customer_id,
//                            'ending_before'=>$query_string['first_id'],
//                            'limit' => 11
//                        ];
//                        $paymentHistories = $this->getDataFromStripeApi($array_parameters);
//
//                        if(count($paymentHistories) == 11) {
//                            $buttonPrevious = true;
//                            $paymentHistories = array_slice($paymentHistories, 1, 10);
//                            $previous_url = route('admin.payment.index') . "?first_id=" . $paymentHistories[0]->id;
//                        }
//                        $next_url = route('admin.payment.index') . "?last_id=" . $paymentHistories[count($paymentHistories)-1]->id;
//                    }// case: begin
//                    else {
//
//                        $array_parameters = [
//                            'customer' => $stripe_customer_id,
//                            'limit' => 11,
//                        ];
//                        $paymentHistories = $this->getDataFromStripeApi($array_parameters);
//
//                        if(count($paymentHistories) == 11) {
//                            $buttonNext = true;
//                            $paymentHistories = array_slice($paymentHistories, 0, 10);
//                            $next_url = route('admin.payment.index') . "?last_id=" . $paymentHistories[count($paymentHistories)-1]->id;
//                            $previous_url = route('admin.payment.index') . "?first_id=" . $paymentHistories[0]->id;
//                        }
//                    }
//                }
//                elseif( !isset($query_string['user_id']) && ( isset($query_string['to_date']) && isset($query_string['to_date']) )) {
//                    $paymentHistories = $this->stripe->paymentIntents->all(
//                        [
//                        'created[lt]' => strtotime($query_string['to_date'] . " 23:59:59"),
//                        'created[gt]' => strtotime($query_string['from_date']. " 00:00:00"),
//                            'limit' => 11
//                        ])->data;
//                }
//                else {
//                    $stripe_customer_id = $this->paymentStripeRepository->getStripeCusomerIdByUserId($query_string['user_id'])->stripe_customer_id;
//                    $paymentHistories = $this->stripe->paymentIntents->all(
//                        [
//                            'customer' => $stripe_customer_id,
//                            'created[lt]' => strtotime($query_string['to_date'] . " 23:59:59"),
//                            'created[gt]' => strtotime($query_string['from_date']. " 00:00:00"),
//                            'limit' => 11
//                        ])->data;
//                }
//            }
            //case: when click button previous
            if(isset($query_string['last_id'])) {
                $buttonPrevious = true;

                //get multiple people
                if(isset($query_string['user_id'])) {
                    $query_custom = $query_string;
                    $paymentHistories=[];
                    for ($i=0; $i < count($query_string['user_id']); $i++){
                        $array_parameters = [
                            'limit' => 100
                        ];
                        $query_custom['user_id'] = $query_string['user_id'][$i];
                        $stripe_customer_id = $this->paymentStripeRepository->getStripeCustomerIdByUserId($query_custom['user_id'])->stripe_customer_id;
                        $query_custom['user_id'] = $stripe_customer_id;
                        if(is_null($stripe_customer_id)) {
                            continue;
                        }
                        $array_parameters = $this->addParameterForArray($array_parameters, $query_custom);
                        $paymentHistoriesById = $this->getDataFromStripeApi($array_parameters);
                        array_push($paymentHistories, $paymentHistoriesById);
                    }
                    $paymentHistories = $this->mergeArray($paymentHistories);
                    $indexOfPrevious = array_search($query_string['last_id'], array_column($paymentHistories, 'id'), true);
                    $paymentHistories = array_slice($paymentHistories, $indexOfPrevious +1 , 11);
                }
                else {
                    $array_parameters = [
                        'starting_after'=>$query_string['last_id'],
                        'limit' => 11
                    ];
                    $array_parameters = $this->addParameterForArray($array_parameters, $query_string);
                    $paymentHistories = $this->getDataFromStripeApi($array_parameters);
                }

                if(count($paymentHistories) >= 11) {
                    $buttonNext = true;
                    $paymentHistories = array_slice($paymentHistories, 0, 10);
                    $next_url = route('admin.payment.index') . "?last_id=" . $paymentHistories[count($paymentHistories)-1]->id;
                    $next_url = $this->addQueryString($next_url, $query_string);
                }
                $previous_url = route('admin.payment.index') . "?first_id=" . $paymentHistories[0]->id;
                $previous_url = $this->addQueryString($previous_url, $query_string);

            }//case: when click button previous
            elseif(isset($query_string['first_id'])) {
                $buttonNext = true;

                //get multiple people
                if(isset($query_string['user_id'])) {
                    $query_custom = $query_string;
                    $paymentHistories=[];
                    for ($i=0; $i < count($query_string['user_id']); $i++){
                        $array_parameters = [
                            'limit' => 100
                        ];
                        $query_custom['user_id'] = $query_string['user_id'][$i];
                        $stripe_customer_id = $this->paymentStripeRepository->getStripeCustomerIdByUserId($query_custom['user_id'])->stripe_customer_id;
                        $query_custom['user_id'] = $stripe_customer_id;
                        if(is_null($stripe_customer_id)) {
                            continue;
                        }
                        $array_parameters = $this->addParameterForArray($array_parameters, $query_custom);
                        $paymentHistoriesById = $this->getDataFromStripeApi($array_parameters);
                        if(is_null($paymentHistoriesById)) {
                            continue;
                        }
                        array_push($paymentHistories, $paymentHistoriesById);
                    }
                    $paymentHistories = $this->mergeArray($paymentHistories);

                    $indexOfPrevious = array_search($query_string['first_id'], array_column($paymentHistories, 'id'), true);
                    if($indexOfPrevious > 12) {
                        $paymentHistories = array_slice($paymentHistories, $indexOfPrevious - 12, 11);
                    }
                    else {
                        $paymentHistories = array_slice($paymentHistories, 0, 10);
                    }
                }
                else {
                    $array_parameters = [
                        'ending_before'=>$query_string['first_id'],
                        'limit' => 11
                    ];
                    $array_parameters = $this->addParameterForArray($array_parameters, $query_string);
                    $paymentHistories = $this->getDataFromStripeApi($array_parameters);
                }

                if(count($paymentHistories) >= 11) {
                    $buttonPrevious = true;
                    $paymentHistories = array_slice($paymentHistories, 1, 10);
                    $previous_url = route('admin.payment.index') . "?first_id=" . $paymentHistories[0]->id;
                    $previous_url = $this->addQueryString($previous_url, $query_string);
                }
                $next_url = route('admin.payment.index') . "?last_id=" . $paymentHistories[count($paymentHistories)-1]->id;
                $next_url = $this->addQueryString($next_url, $query_string);
            }// case: begin
            else {
                $array_parameters = [
                    'limit' => 11,
                ];

                //get multiple people
                if(isset($query_string['user_id'])) {
                    $query_custom = $query_string;
                    $paymentHistories=[];
                    for ($i=0; $i < count($query_string['user_id']); $i++){
                        $query_custom['user_id'] = $query_string['user_id'][$i];
                        $stripe_customer_id = $this->paymentStripeRepository->getStripeCustomerIdByUserId($query_custom['user_id'])->stripe_customer_id;
                        $query_custom['user_id'] = $stripe_customer_id;
                        if(is_null($stripe_customer_id)) {
                            continue;
                        }
                        $array_parameters = $this->addParameterForArray($array_parameters, $query_custom);
                        $paymentHistoriesById = $this->getDataFromStripeApi($array_parameters);
                        if(is_null($paymentHistoriesById)) {
                            continue;
                        }
                        array_push($paymentHistories, $paymentHistoriesById);
                    }
                    $paymentHistories = $this->mergeArray($paymentHistories);
                }
                else {
                    $array_parameters = $this->addParameterForArray($array_parameters, $query_string);
                    $paymentHistories = $this->getDataFromStripeApi($array_parameters);
                }


                if(count($paymentHistories) >= 11) {
                    $buttonNext = true;
                    $paymentHistories = array_slice($paymentHistories, 0, 10);
                    $next_url = route('admin.payment.index') . "?last_id=" . $paymentHistories[count($paymentHistories)-1]->id;
                    $next_url = $this->addQueryString($next_url, $query_string);
                    $previous_url = route('admin.payment.index') . "?first_id=" . $paymentHistories[0]->id;
                    $previous_url = $this->addQueryString($previous_url, $query_string);
                }
            }
            $listCustomerId = array_column($paymentHistories, 'customer');
            if(isset($query_string['user_id'])) {
                $emailMatchCustomerId = $this->paymentStripeRepository->getEmailByCustomerId($listCustomerId, $query_string['user_id']);
            }
            else {
                $emailMatchCustomerId = $this->paymentStripeRepository->getEmailByCustomerId($listCustomerId, []);
            }
            $emailMatchCustomerIdSelected = array_combine(array_column($emailMatchCustomerId, 'id'), array_column($emailMatchCustomerId, 'email'));
            $emailMatchCustomerId = array_combine(array_column($emailMatchCustomerId, 'stripe_customer_id'), array_column($emailMatchCustomerId, 'email'));

            foreach ($paymentHistories as $paymentHistory) {
                $paymentHistory->created = Helper::formatDateHIS(Timezone::convertToLocal(Carbon::parse($paymentHistory->created), 'Y-m-d H:i:s'));
            }
            return view('admin.managers.StudentPaymentInformation.infor', compact(
                'paymentHistories',
                'buttonPrevious',
                'buttonNext',
                'emailMatchCustomerId',
                'next_url',
                'previous_url',
                'emailMatchCustomerIdSelected'
            ));
        } catch (\Exception $e) {
            return view('admin.managers.StudentPaymentInformation.infor', compact(
                'paymentHistories',
                'buttonPrevious',
                'buttonNext',
                'emailMatchCustomerId',
                'next_url',
                'previous_url'));
        }
    }

    public function getDataFromStripeApi($array_parameters) {
        try {
            return $this->stripe->paymentIntents->all($array_parameters)->data;
        } catch (\Exception $e) {
            return null;
        }

    }

    public function addParameterForArray($array, $query_string) {
        if(isset($query_string['user_id'])) {
            $array['customer'] = $query_string['user_id'];
        }
        if( isset($query_string['from_date']) ) {
            $array['created[gt]'] =  strtotime($this->timezone->convertFromLocal(Carbon::parse($query_string['from_date'] . " 00:00:00")->format('Y-m-d H:i:s')));
        }
        if(isset($query_string['to_date'])) {
            $array['created[lt]'] =  strtotime($this->timezone->convertFromLocal(Carbon::parse($query_string['to_date'] . " 23:59:59")->format('Y-m-d H:i:s')));
        }
        return $array;
    }
    public function addQueryString($url, $query_string) {
        if(isset($query_string['user_id'])) {
            for($i = 0; $i < count($query_string['user_id']); ++$i) {
                $url = $url . "&user_id[]=" . $query_string['user_id'][$i];
            }
        }
        if( isset($query_string['from_date']) ) {
            $url = $url . "&from_date=" . $query_string['from_date'] ;
        }
        if(isset($query_string['to_date'])) {
            $url = $url . "&to_date=" . $query_string['to_date'];
        }
        return $url;
    }

    public function mergeArray($array) {
        try{
            $data = [];
            for($i=0; $i < count($array); ++$i) {
                for($j=0; $j < count($array[$i]); ++$j) {
                    array_push($data, $array[$i][$j]);
                }
            }
            return $data;
        }
        catch (\Exception $e) {
            return [];
        }
    }
    public function validateSearchForm(Request $request) {
        $input = $request->all();
        ($input['from_date'] != '' && $input['to_date'] != '') ? $rules['to_date'] = 'after_or_equal:from_date' : $rules['to_date'] = '';

        $attributes = array(
            'from_date'    => '決済日',
        );

        $message = [
            'to_date.after_or_equal'     => config('validation.after_or_equal'),
        ];

        $validator = Validator::make($input, $rules, $message)->setAttributeNames($attributes);

        if ($validator->fails()) {
            return $this->responseError(null, $validator->errors());
        } else {
            return $this->responseSuccess();
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchEmail(Request $request)
    {
        $data = [];
        if($request->has('q')){
            $search = $request->q;

            $data = DB::table('users')->select('users.id', 'users.email')
                ->where('users.email','LIKE',"%$search%")
                ->get();
        }
        return response()->json($data);
    }
}

