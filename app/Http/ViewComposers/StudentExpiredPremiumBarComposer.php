<?php

namespace App\Http\ViewComposers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Timezone;

class StudentExpiredPremiumBarComposer
{
    /**
     * Create a movie composer.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Bind data to the view.
     *
     * @param View $view
     * @return void
     */
    public function compose(View $view)
    {
        $expire_premium = DB::table('users')
            ->leftJoin('user_information','users.id','=','user_information.user_id')
            ->leftJoin('user_payment_info','users.id','=','user_payment_info.user_id')
            ->where('users.id', Auth::id())
            ->first();
        $myMembership_status = DB::table('users')
            ->select('user_information.membership_status')
            ->leftJoin('user_information', 'user_information.user_id', '=', 'users.id')
            ->where('users.id', '=', Auth::user()->id)
            ->first();

        $message = '';
        if (
            $myMembership_status->membership_status == config('constants.membership.id.free')
            && !is_null($expire_premium->premium_end_date)
            && strtotime($expire_premium->premium_end_date) < strtotime(Timezone::convertToLocal(\Carbon\Carbon::now(), 'Y-m-d H:i:s'))
        ) { // Case: expire premium
            if (request()->is('student/add-coin')) {
                $message = __('validation_custom.M075');
            } elseif(request()->is('student/course/detail/*')) {
                $message = __('validation_custom.M073');
            } elseif(request()->is('student/payment/premium')) {
                $message = '';
            } else {
                $message = __('validation_custom.M037');
            }
        } elseif(
            $myMembership_status->membership_status == config('constants.membership.id.free')
            && !empty($expire_premium->trial_end_date)
        ) {
            if (request()->is('student/add-coin')) {
                $message = __('validation_custom.M075');
            } elseif(request()->is('student/course/detail/*')) {
                $message = __('validation_custom.M073');
            }
        } elseif (
            ($myMembership_status->membership_status == config('constants.membership.id.free') && empty($expire_premium->trial_end_date)) ||
            ($myMembership_status->membership_status == config('constants.membership.id.free') && !is_null($expire_premium->premium_end_date))
        ) {
            if (request()->is('student/add-coin')) {
                $message = __('validation_custom.M076');
            } elseif(request()->is('student/course/detail/*')) {
                $message = __('validation_custom.M077');
            }
        }
        $view->with('expire_premium', $expire_premium)->with('message', $message);
    }
}
