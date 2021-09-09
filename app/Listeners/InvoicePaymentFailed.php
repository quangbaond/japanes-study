<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Spatie\WebhookClient\Models\WebhookCall;
use Stripe\Exception\ApiErrorException;

class InvoicePaymentFailed
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
        $customer = $this->stripe->customers->retrieve(
            $customer_id,
            []
        );

        $user_id = $customer->metadata->user_id;

        DB::table('user_information')
            ->where('user_id', $user_id)
            ->update([
                'membership_status' => 1
            ]);
    }
}
