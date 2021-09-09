<?php


namespace App\Repositories\Admin\Managers;

use App\Http\Controllers\TimezoneController;
use Carbon\Carbon;
use Google_Service_Calendar;
use Illuminate\Http\Request;
use App\Services\GoogleClientService;
use Timezone;

class CalendarRepository
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
     * Create event for calendar
     * by sitranv
     *
     * @param $schedule_data
     * @param $teacher_zoom_url
     * @param $attendees
     * @return bool
     */
    public function createEvent($schedule_data, $teacher_zoom_url, $attendees)
    {
        $timezone = new TimezoneController();
        $calendarService = new Google_Service_Calendar($this->googleClientServices);
        try {
            foreach ($schedule_data as $time) {
                foreach ($attendees as $attendee){
                    $date_time = $time->start_date.' '.$time->start_hour;
                    $date_time = $timezone->convertToUserLocalWithId(Carbon::parse($date_time), $attendee['id'], 'Y-m-d H:i:s');

                    $event = new \Google_Service_Calendar_Event([
                        'summary' => '【Japanese study】 Lesson schedule',
                        'description' => 'Link zoom to join lesson: '.$teacher_zoom_url,
                        'start' => [
                            'dateTime' => $this->convertTime(Carbon::parse($date_time))
                        ],
                        'end' => [
                            'dateTime' => $this->convertTime(Carbon::parse($date_time)->addMinute(30))
                        ],
                        'attendees' => $this->getAttendees([$attendee['email']])
                    ]);
                    $calendarService->events->insert('primary', $event);
                }
            }
            return true;
        }
        catch (\Exception $err) {
            return false;
        }
    }

    /**
     * Convert time
     * by vinhppvk
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
     * by vinhppvk
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
