<?php

namespace App\Http\Controllers\Admin\Managers;

use App\Helpers\Helper;
use App\Repositories\Admin\Managers\BookingListRepository;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;

class HomeController extends Controller
{

    protected $bookingRepository;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(BookingListRepository $bookingListRepository)
    {
        $this->bookingRepository = $bookingListRepository;
    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index()
    {
        return view('admin.managers.home');
    }

    public function bookingList() {

//        $allBooking = $this->bookingRepository->getAllBooking();
//        dd($allBooking);
        return view('admin.managers.bookingList');
    }

    /**
     *
     */
    public function getAllBooking() {
        $allBooking = $this->bookingRepository->getAllBooking();
        return DataTables::of($allBooking)
            ->addColumn('start_hour', function ($booking) {
                return date('H:i', strtotime($booking->start_hour));
            })
            ->addColumn('start_date', function ($booking) {
                return Helper::formatDate($booking->start_date);
            })
            ->addColumn('action', function ($booking) {
                return '<a href="#" class="btn btn-secondary btn-sm btn-flat">詳細</a>';
            })
            ->addColumn('created_at', function ($booking) {
                return date('Y/m/d H:i:s', strtotime($booking->created_at));
            })
            ->rawColumns(['start_hour','start_date', 'action', 'created_at'])
            ->make(true);
    }

    public function lessonHistory()
    {
        return view('admin.managers.lessonHistory');
    }
}
