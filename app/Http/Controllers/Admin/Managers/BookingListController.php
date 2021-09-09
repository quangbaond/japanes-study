<?php

namespace App\Http\Controllers\Admin\Managers;

use App\Helpers\Helper;
use App\Repositories\Admin\Managers\BookingListRepository;
use App\Repositories\Admin\PusherRepository;
use App\Rules\CheckDateRule;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;
use Timezone;

class BookingListController extends Controller
{

    protected $bookingRepository, $pusherRepository;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(BookingListRepository $bookingListRepository, PusherRepository $pusherRepository)
    {
        $this->bookingRepository = $bookingListRepository;
        $this->pusherRepository  = $pusherRepository;
    }


    public function bookingList() {
        return view('admin.managers.bookingList');
    }

    /**
     *
     */
    public function getAllBooking(Request $request) {
        $input = $request->only('email', 'nickname', 'to_date', 'from_date');
        $allBooking = $this->bookingRepository->getAllBooking($input);

        return DataTables::of($allBooking)
            ->addColumn('start_hour', function ($booking) {
                return date('H:i', strtotime(Timezone::convertToLocal(\Carbon\Carbon::parse($booking->start_date . " ". $booking->start_hour), 'Y-m-d H:i:s')));
            })
            ->addColumn('start_date', function ($booking) {
                return Helper::formatDate(Timezone::convertToLocal(\Carbon\Carbon::parse($booking->start_date . " ". $booking->start_hour), 'Y-m-d H:i:s'));
            })
            ->addColumn('action', function ($booking) {
                return '<button class="btn btn-secondary btn-sm btn-flat btnCancelBooking">キャンセル</button>';
            })
            ->addColumn('created_at', function ($booking) {
                return date('Y/m/d H:i:s', strtotime(Timezone::convertToLocal(\Carbon\Carbon::parse($booking->start_date . " ". $booking->start_hour), 'Y-m-d H:i:s')));
            })
            ->rawColumns(['start_hour','start_date', 'action', 'created_at'])
            ->make(true);
    }

    /*
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchLiveNickname(Request $request) {
        $data = [];
        if($request->has('q')){
            $search = $request->q;

            $data = DB::table('users')->select('id', 'nickname')
                ->whereIn('role', [config('constants.role.teacher'), config('constants.role.student')])
                ->where('users.id','LIKE',"%$search%")
                ->get();
        }
        return response()->json($data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchLiveEmail(Request $request) {
        $data = [];
        if($request->has('q')){
            $search = $request->q;

            $data = DB::table('users')->select('email')
                ->where('users.email','LIKE',"%$search%")
                ->whereNotIn('users.role', [config('constants.role.admin'), config('constants.role.child-admin')])
                ->get();
        }
        return response()->json($data);
    }

    /**
     * validate form search list booking
     * by ThachDang
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function validateSearchForm(Request $request) {
        $input = $request->all();
        ($input['from_date'] != '' && $input['to_date'] != '') ? $rules['to_date'] = 'after_or_equal:from_date' : $rules['to_date'] = '';

        $attributes = array(
            'from_date'    => '決済日',
        );


        $date_from = explode('/',$input['from_date']);
        $date_to = explode('/', $input['to_date']);

        $input['format_date_from'] = implode('-',$date_from);
        $input['format_date_to'] = implode('-',$date_to);

        $rules['format_date_from'] = [new CheckDateRule()];
        $rules['format_date_to'] = [new CheckDateRule()];


        $message = [
            'to_date.after_or_equal'     => config('validation.after_or_equal'),
            'format_date_from.date_format'              => __('validation_custom.M020'),
            'format_date_to.date_format'                => __('validation_custom.M020'),
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
    public function detail(Request $request) {
        $booking_id = $request->only('id');

        $bookingDetail = $this->bookingRepository->getBookingDetailById($booking_id);

        $bookingTime = date('Y/m/d H:i:s', strtotime($bookingDetail->start_date . " " . $bookingDetail->start_hour));
        $now = date('Y/m/d H:i:s', strtotime(Carbon::now()->addHour('1')));
        $bookingDetail->coin  = ( $bookingTime > $now && in_array((int) $bookingDetail->student_membership_status, [2,3,6])) ? $bookingDetail->coin : 0;
        $bookingDetail->start_date = date('Y-m-d', strtotime(Timezone::convertToLocal(\Carbon\Carbon::parse($bookingDetail->start_date . " " . $bookingDetail->start_hour), 'Y-m-d H:i:s')));
        $bookingDetail->start_hour = date('H:i:s', strtotime(Timezone::convertToLocal(\Carbon\Carbon::parse($bookingDetail->start_date . " " . $bookingDetail->start_hour), 'Y-m-d H:i:s')));

        return $this->responseSuccess($bookingDetail);
    }

    public function deleteBooking(Request $request) {
        $booking_id = $request->only('id');
        $isSuccess = $this->bookingRepository->deleteBookingById($booking_id);
        if(!is_null($isSuccess)) {
            $channel = 'notification-user';
            $this->pusherRepository->sendNotify($channel, $isSuccess[0]->student_id, $isSuccess[1]);
            $this->pusherRepository->sendNotify($channel, $isSuccess[0]->teacher_id, $isSuccess[1]);
            return $this->responseSuccess(null, __('validation_custom.M044'));
        }
        return $this->responseError(null, __('validation_custom.028'));
    }
}
