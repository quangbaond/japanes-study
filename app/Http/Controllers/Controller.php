<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Response success
     * @author: vinhppvk
     *
     * @param $data
     * @param $message
     * @return JsonResponse
     */
    public function responseSuccess($data = null, $message = 'Success')
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'status_code' => Response::HTTP_OK,
            'data' => $data,
        ]);
    }

    /**
     * Response error
     * @author: vinhppvk
     *
     * @param string $message
     * @param null $data
     * @return JsonResponse
     */
    public function responseError($data = null, $message = 'Error')
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'status_code' => Response::HTTP_OK,
            'data' => $data,
        ]);
    }

    /**
     * Check cancelling Premium (update membership_status == 1)
     * @author: vinhppvk
     *
     * @return void
     */
    public function cancellingPremium()
    {
        // Get data
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
        // Check
        if ( ($user->membership_status == 6 || $user->membership_status == 3) && Carbon::now()->format('Y-m-d H:i:s') > $user->premium_end_date) {
            DB::table('user_information')
                ->where('user_id', $user->id)
                ->update([
                    'membership_status' => config('constants.membership.id.free'), // membership_status = 1
                    'updated_at' => now(),
                ]);
        }
    }

    public function cancellingPremiumWithStudentId($id)
    {
        // Get data
        $user = DB::table('users')
            ->select(
                'users.id',
                'user_information.membership_status',
                'user_payment_info.premium_start_date',
                'user_payment_info.premium_end_date'
            )
            ->leftJoin('user_information', 'user_information.user_id', '=', 'users.id')
            ->leftJoin('user_payment_info', 'user_payment_info.user_id', '=', 'users.id')
            ->where('users.id', $id)
            ->first();

        // Check
        if ($user->membership_status == 6 && Carbon::now() > $user->premium_end_date) {
            DB::table('user_information')
                ->where('user_id', $user->id)
                ->update([
                    'membership_status' => config('constants.membership.id.free') // membership_status = 1
                ]);
        }
    }
}
