<?php

namespace App\Http\Controllers\Admin\Managers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Google_Service_Calendar;
use Illuminate\Http\Request;
use App\Services\GoogleClientService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CalendarController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $googleClientServices;

    public function __construct(GoogleClientService $googleClientServices)
    {
        $this->googleClientServices = $googleClientServices->getClient();
    }

    /**
     * payment view
     */
    public function index()
    {
        return view('admin.managers.calendar.index');
    }

    /**
     * Create event for calendar
     * @ vinhppvk
     *
     * @param Request $request
     * @return void
     */
    public function createEvent(Request $request)
    {
        $calendarService = new Google_Service_Calendar($this->googleClientServices);
        $event = new \Google_Service_Calendar_Event([
            'summary' => 'abc',
            'description' => 'abc',
            'start' => [
                'dateTime' => $this->convertTime(Carbon::now()->addDay(1))
            ],
            'end' => [
                'dateTime' => $this->convertTime(Carbon::now()->addDay(1)->addMinute(60))
            ],
            'attendees' => $this->getAttendees(['vinhppvk@mcrew-tech.com'])
        ]);
        $event1 = new \Google_Service_Calendar_Event([
            'summary' => 'abc11',
            'description' => 'abc11',
            'start' => [
                'dateTime' => $this->convertTime(Carbon::now()->addDay(2))
            ],
            'end' => [
                'dateTime' => $this->convertTime(Carbon::now()->addDay(2)->addMinute(60))
            ],
            'attendees' => $this->getAttendees(['vinhppvk@mcrew-tech.com'])
        ]);
        $calendarService->events->insert('primary', $event);
        $calendarService->events->insert('primary', $event1);
        dd('Success');
    }

    /**
     * Convert time
     * @author vinhppvk
     *
     * @param $time
     * @param int $min
     * @return string
     */
    private function convertTime($time, $min = 0)
    {
        return Carbon::parse($time)->addMinutes($min)->toIso8601String();
    }

    /**
     * Email attendees
     * @author vinhppvk
     *
     * @param $emails
     * @return array
     */
    private function getAttendees($emails)
    {
        $attendees = [];

        foreach ($emails as $email) {
            if ($email) {
                $attendees[] = [
                    'email' => $email
                ];
            }
        }
        return $attendees;
    }

}
