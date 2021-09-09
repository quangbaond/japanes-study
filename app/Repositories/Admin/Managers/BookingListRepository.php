<?php
namespace App\Repositories\Admin\Managers;

use App\Http\Controllers\TimezoneController;
use App\Models\User;
use App\Models\UserInformation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use MacsiDigital\Zoom\Facades\Zoom;
use Timezone;

class BookingListRepository
{
    protected $timezone;

    /**
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|object|null
     */

    public function __construct(TimezoneController $timezone)
    {
        $this->timezone = $timezone;
    }

    public function getMyTimeZone() {
        return DB::table('users')
            ->select('users.timezone')
            ->where('id', Auth::id())
            ->first();
    }
    /**
     * @param $data
     * @return \Illuminate\Support\Collection
     */
    public function getAllBooking($data) {
        $bookingList =  DB::table('booking')
            ->select(
                'users.id',
                'users.email as student_email',
                'users.nickname as student_nickname',
                'users.id as student_id',
                'teacher_schedule.start_hour',
                'teacher_schedule.start_date',
                'teacher_schedule.teacher_id',
                'booking.coin',
                'booking.created_at',
                'booking.id as booking_id',
                DB::raw('(SELECT users.email FROM users WHERE users.id = teacher_schedule.teacher_id) AS teacher_email'),
                DB::raw('(SELECT users.nickname FROM users WHERE users.id = teacher_schedule.teacher_id) AS teacher_nickname')
            )
            ->leftJoin('users','users.id', '=', 'booking.student_id')
            ->leftJoin('teacher_schedule', 'teacher_schedule.id', '=', 'booking.teacher_schedule_id')
            ->join('teacher_coin', 'teacher_coin.teacher_id', '=', 'teacher_schedule.teacher_id')
            ->where('teacher_schedule.status', '=', config('constants.teacher_schedule.booking'));

        if(!empty($data['email']) && !empty($data['nickname'])) {
            $bookingList->where(function($query) use ($data) {
                $query->orWhere(function($subQuery) use ($data) {
                    $subQuery->whereIn('users.id', $data['nickname']);
                    $subQuery->whereIn('users.email', $data['email']);
                });
                $query->orWhere(function($subQuery) use ($data) {
                    $subQuery->whereIn('users.id', $data['nickname']);
                    $subQuery->whereRaw("(SELECT users.email FROM users WHERE users.id = teacher_schedule.teacher_id) in ('". implode("','", $data['email']) ."') ");
                });
                $query->orWhere(function($subQuery) use ($data) {
                    $subQuery->whereRaw("(SELECT users.id FROM users WHERE users.id = teacher_schedule.teacher_id) in ('". implode("','", $data['nickname']) ."') ");
                    $subQuery->whereIn('users.email', $data['email']);
                });
                $query->orWhere(function($subQuery) use ($data) {
                    $subQuery->whereRaw("(SELECT users.email FROM users WHERE users.id = teacher_schedule.teacher_id) in ('". implode("','", $data['email']) ."') ");
                    $subQuery->whereRaw("(SELECT users.id FROM users WHERE users.id = teacher_schedule.teacher_id) in ('". implode("','", $data['nickname']) ."') ");
                });
            });

        }
        if(!empty($data['email']) && empty($data['nickname'])) {
            $bookingList->where(function($query) use ($data) {
                $query->orWhereIn('users.email', $data['email']);
                $query->orWhereRaw("(SELECT users.email FROM users WHERE users.id = teacher_schedule.teacher_id) in ('". implode("','", $data['email']) ."') ");
            });
        }
        if(!empty($data['nickname']) && empty($data['email'])) {
            $bookingList->where(function($query) use ($data) {
                $query->orWhereIn('users.id', $data['nickname']);
                $query->orWhereRaw("(SELECT users.id FROM users WHERE users.id = teacher_schedule.teacher_id) in ('". implode("','", $data['nickname']) ."') ");
            });
        }

        if( !empty($data['to_date'] || !empty($data['from_date']))) {
            if(!empty($data['from_date'])) {
                $bookingList->whereRaw('CONCAT(teacher_schedule.start_date, " ", teacher_schedule.start_hour) >= ?', date('Y-m-d H:i:s', strtotime($this->timezone->convertFromLocal(Carbon::parse($_GET["from_date"] . " " . "00:00:00")->format('Y-m-d H:i:s')))));
            }
            if(!empty($data['to_date'])) {
                $bookingList->whereRaw('CONCAT(teacher_schedule.start_date, " ", teacher_schedule.start_hour) <= ?', date('Y-m-d H:i:s', strtotime($this->timezone->convertFromLocal(Carbon::parse($_GET["to_date"] . " " . "23:59:59")->format('Y-m-d H:i:s')))));
            }
        }
        else {
            $bookingList->where(function($query) {
                $query->where(function ($subQuery) {
                    $subQuery->where('teacher_schedule.start_date', '=', Carbon::now()->format('Y-m-d'));
                    $subQuery->where('teacher_schedule.start_hour', '>=', Carbon::now()->format('H:i:s'));
                });
                $query->orWhere('teacher_schedule.start_date', '>', Carbon::now()->format('Y-m-d'));
            });
        }
        return $bookingList->get();
    }

    public function getBookingDetailById($id) {
        return  DB::table('booking')
            ->select(
                'users.nickname as student_nickname',
                'users.email as student_email',
                'teacher_schedule.start_hour',
                'teacher_schedule.start_date',
                'booking.coin',
                'booking.id as booking_id',
                'user_information.membership_status as student_membership_status',
                DB::raw('(SELECT users.email FROM users WHERE users.id = teacher_schedule.teacher_id) AS teacher_email'),
                DB::raw('(SELECT users.nickname FROM users WHERE users.id = teacher_schedule.teacher_id) AS teacher_nickname')
            )
            ->leftJoin('user_information','user_information.user_id', '=', 'booking.student_id')
            ->leftJoin('users','users.id', '=', 'booking.student_id')
            ->leftJoin('teacher_schedule', 'teacher_schedule.id', '=', 'booking.teacher_schedule_id')
            ->join('teacher_coin', 'teacher_coin.teacher_id', '=', 'teacher_schedule.teacher_id')
            ->where('teacher_schedule.status', '=', config('constants.teacher_schedule.booking'))
            ->where('booking.id', '=', $id)
            ->first();
    }

    public function deleteBookingById($id) {
        DB::beginTransaction();
        try {
            //check time to refund coin for student
            $bookingDetail = $this->getBookingDetailById($id);
            $bookingTime = date('Y/m/d H:i:s', strtotime($bookingDetail->start_date . " " . $bookingDetail->start_hour));
            $now = date('Y/m/d H:i:s', strtotime(\Illuminate\Support\Carbon::now()->addHour('1')));
            $theNumberOfCoinMoreThanZero  = ( $bookingTime > $now && in_array((int) $bookingDetail->student_membership_status, [2, 3, 6])) ? $bookingDetail->coin : 0;

            $booking = DB::table('booking')
                ->select(
                'student_id',
                        'teacher_schedule_id',
                    'teacher_schedule.teacher_id'
                )
                ->leftJoin('teacher_schedule', 'teacher_schedule.id', '=', 'booking.teacher_schedule_id')
                ->where('booking.id', '=', $id);



            $bookingInformation = $booking->first();
            //remove record of student_courses table by its teacher_schedule_id
            $removeStudentCourse = DB::table('student_courses')
                ->join('booking', 'booking.teacher_schedule_id', '=', 'student_courses.teacher_schedule_id')
                ->where('booking.student_id', '=', $bookingInformation->student_id)
                ->where('booking.id', $id)
                ->delete();

            //update status of teacher schedule to free time
            DB::table('teacher_schedule')
                ->where('id', '=', $bookingInformation->teacher_schedule_id)
                ->update([
                    'status' => config('constants.teacher_schedule.free_time'),
                    'updated_at' => now()
                ]);

            // return if cancel before 1 hour
            if($theNumberOfCoinMoreThanZero > 0) {
                $refundCoin = DB::table('student_total_coins')
                    ->where('student_id', '=', $bookingInformation->student_id);
                $refundCoin->update([
                    'updated_at' => now()
                ]);
                $refundCoin->increment('total_coin', $theNumberOfCoinMoreThanZero);
                DB::table('history_student_use_coin')->insert([
                    'student_id' => $bookingInformation->student_id,
                    'coin' => $theNumberOfCoinMoreThanZero,
                    'status' => '4',
                    'teacher_id' => $bookingInformation->teacher_id,
                    'created_at' => now()
                ]);
            }
            $notification = DB::table('notifications')->insertGetId([
                'title' => '予約のキャンセル/Cancel booking',
                'content' =>  $bookingTime .' のスケジュールはキャンセルされました。
----------------------------------------
The schedule at '. $bookingTime . ' have been canceled.
----------------------------------------
Lịch học vào ngày '. $bookingTime . ' đã bị huỷ.',
                'receiver_class' => '4',
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
            $notification1 = DB::table('notifications')->where('id', '=', $notification)->first();
            DB::table('receiver')->insert([
                [
                    'notification_id' => $notification,
                    'user_id' => $bookingInformation->student_id
                ],
                [
                    'notification_id' => $notification,
                    'user_id' => $bookingInformation->teacher_id
                ],
            ]);
            $booking->delete();

            DB::commit();
            return [$bookingInformation, $notification1];
        } catch(\Exception $e) {
            return null;
        }
    }
}
