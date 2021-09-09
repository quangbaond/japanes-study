<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Spatie\WebhookClient\Models\WebhookCall;
use Stripe\Exception\ApiErrorException;

class InvoicePaymentSucceeded
{
    protected $stripe;

    /**
     * Create the event listener.
     *

     */
    public function __construct()
    {
        $this->stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
    }

    /**
     * Handle the event.
     *
     * @param WebhookCall $webhookCall
     * @return void
     * @throws ApiErrorException
     */
    public function handle(WebhookCall $webhookCall)
    {
        // Get id customer from webhook
        $customer_id = $webhookCall->payload['data']['object']['customer'];
        $amount_due = $webhookCall->payload['data']['object']['amount_due'];
        $customer = $this->stripe->customers->retrieve(
            $customer_id,
            []
        );

        $user_id = $customer->metadata->user_id;
        $stripe_plan = $customer->metadata->stripe_plan;

        if ($amount_due != 0) {
            // Search plans
            $plan = DB::table('plans')->select('interval', 'interval_count')->where('stripe_plan', $stripe_plan)->first();
            if (!empty($plan)) {
                $premium_end_date = Carbon::now();
                if ($plan->interval == 'day') {
                    $premium_end_date = Carbon::now()->addDay($plan->interval_count)->addHour(config('constants.time_hour_auto_payment')); // addHour() : dùng để xử lý việc chờ tự động thanh toan
                }

                if ($plan->interval == 'week') {
                    $premium_end_date = Carbon::now()->addWeek($plan->interval_count)->addHour(config('constants.time_hour_auto_payment'));
                }

                if ($plan->interval == 'month') {
                    $premium_end_date = Carbon::now()->addMonth($plan->interval_count)->addHour(config('constants.time_hour_auto_payment'));
                }

                if ($plan->interval == 'year') {
                    $premium_end_date = Carbon::now()->addYear($plan->interval_count)->addHour(config('constants.time_hour_auto_payment'));
                }

                // Update table: user_payment_info
                DB::table('user_payment_info')
                    ->where('user_id', $user_id)
                    ->update([
                        'premium_start_date'    => Carbon::now(),
                        'premium_end_date'      => $premium_end_date,
                        'updated_at'            => Carbon::now(),
                    ]);

                // Update table: user_information
                DB::table('user_information')
                    ->where('user_id', $user_id)
                    ->update([
                        'membership_status' => 3,
                        'updated_at' => Carbon::now(),
                    ]);

                dd('Update DB success');
            }
        }
        dd('Not update DB');
    }
}
