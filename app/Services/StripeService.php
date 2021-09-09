<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Stripe;

class StripeService
{

    /**
     * handling payment with POST
     * @param $params
     * @return void
     * @throws Stripe\Exception\ApiErrorException
     */
    public function handlePostPayments($params)
    {
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        Stripe\Charge::create ([
            "amount" => 100 * 10,
            "currency" => "usd",
            "source" => $params['stripeToken'],
            "description" => "Making test payment."
        ]);
    }



}
