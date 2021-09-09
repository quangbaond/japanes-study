<?php

namespace App\Http\Controllers\Admin\Managers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use MacsiDigital\Zoom\Facades\Zoom;

class ZoomController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {

    }

    /**
     * zoom view
     */
    public function index()
    {
        $user = Zoom::user()->find('khoivinhphan@gmail.com');

        $meeting = Zoom::meeting()->make([
            'topic' => 'test',
            'start_time' => new Carbon('2020-12-16 18:00:00'), 
            'password' => 'scret123',
            "duration" => 30,
            'settings' => [
                'join_before_host' => true,
            ]
        ]);
        $meeting->recurrence()->make([
            'type' => 2,
            'repeat_interval' => 1,
            'weekly_days' => "1",
            'end_times' => 3
        ]);
        $user->meetings()->save($meeting);
    }

}
