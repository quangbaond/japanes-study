<?php

namespace App\Http\Controllers\Admin\Students;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        return view('admin.students.stripe');
    }

    /**
     * handling payment with POST
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws Stripe\Exception\ApiErrorException
     */
    public function handlePost(Request $request)
    {
        $params = $request->all();
        $this->stripeServices->handlePostPayments($params);
        Session::flash('success', 'Payment has been successfully processed.');
        return back();
    }
}
