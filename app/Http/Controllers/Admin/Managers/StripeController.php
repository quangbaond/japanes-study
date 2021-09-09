<?php

namespace App\Http\Controllers\Admin\Managers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Stripe;
use App\Services\StripeService;

class StripeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $stripeServices;

    public function __construct(StripeService $stripeServices)
    {
        $this->stripeServices = $stripeServices;
    }

    /**
     * payment view
     */
    public function index()
    {
        $stripe = new \Stripe\StripeClient(
            env('STRIPE_SECRET')
        );
        //$response = $stripe->products->all(['limit' => 2]);
        $response = $stripe->products->all();
        $list_product = $response->data;
        return view('admin.managers.stripe.index', compact('list_product'));
    }

}
