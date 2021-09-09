<?php
namespace App\Repositories\Admin\Managers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentStripeRepository {

    public function getEmailByCustomerId($array, $query_string) {
        return DB::table('users')
            ->select(
                'users.email',
                'users.id',
                'user_payment_info.stripe_customer_id'
            )
            ->leftJoin('user_payment_info', 'user_payment_info.user_id', '=', 'users.id')
            ->whereIn('user_payment_info.stripe_customer_id', $array)
            ->orWhereIn('users.id', $query_string)
            ->get()
            ->toArray();
    }

    public function getStripeCustomerIdByUserId($id) {
        return  DB::table('users')
            ->select('user_payment_info.stripe_customer_id')
            ->leftJoin('user_payment_info', 'user_payment_info.user_id', '=', 'users.id')
            ->where('users.id', (int) $id)
            ->first();
    }
}
