<?php

namespace App\Http\Controllers\Admin\Managers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plan;
use App\Models\User;

class SubscriptionController extends Controller
{
    protected $stripe;

    public function __construct()
    {
        $this->stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
    }

    public function createPlan()
    {
        return view('admin.managers.stripe.create');
    }

    public function storePlan(Request $request)
    {
        $data = $request->except('_token');

        $data['slug'] = strtolower($data['name']);
        $price = $data['cost'] *100;

        //create stripe product
        $stripeProduct = $this->stripe->products->create([
            'name' => $data['name'],
            'description' => $data['description']
        ]);

        //Stripe Plan Creation
//        $stripePlanCreation = $this->stripe->plans->create([
//            'amount' => $price,
//            'currency' => 'usd',
//            'interval' => 'day', //  it can be day,week,month or year
//            'product' => $stripeProduct->id,
//        ]);
        $stripePlanCreation = $this->stripe->prices->create([
            'nickname' => 'mcrew-tech',
            'product' => $stripeProduct->id,
            'unit_amount' => $price,
            'currency' => 'usd',
            'recurring' => [
                'interval' => 'day', //  it can be day,week,month or year
                'interval_count' => 1,
                'usage_type' => 'licensed',
            ],
        ]);

        $data['stripe_plan'] = $stripePlanCreation->id;

        Plan::create($data);

        echo 'plan has been created';
    }
}
