<?php

namespace App\Http\Controllers\Admin\Students;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Stripe;

class SubscriptionController extends Controller
{
    protected $stripe;

    public function __construct()
    {
        $this->stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
    }

    public function create(Request $request, Plan $plan)
    {
        $plan = Plan::findOrFail($request->get('plan'));

        $user = $request->user();
        $paymentMethod = $request->paymentMethod;

        // Create customer
        $customer = $this->stripe->customers->create([
            'email' => 'vinhphan123@gmail.com',
            'payment_method' => $paymentMethod,
            'invoice_settings' => [
                "custom_fields"=> null,
                "default_payment_method" => $paymentMethod,
                "footer"=> null
            ]
        ]);

        //Create subscriptions
        $this->stripe->subscriptions->create([
            'customer' => $customer->id,
            'items' => [
                ['price' => $plan->stripe_plan],
            ],
        ]);
        return redirect()->route('plans.index')->with('success', 'Your plan subscribed successfully');

//        $user->createOrGetStripeCustomer();
//        $user->updateDefaultPaymentMethod($paymentMethod);
//        $user->newSubscription('default', $plan->stripe_plan)
//            ->create($paymentMethod, [
//                'email' => $user->email,
//            ]);
//
//        return redirect()->route('plans.index')->with('success', 'Your plan subscribed successfully');
    }

    /**
     * List subscriptions of user
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function listSub()
    {
        // Data
        $customer_id = "";
        $customers = $this->stripe->customers->all()->data;
        $subscription = $this->stripe->subscriptions->all()->data;
        //Handle data
        foreach ($customers as $item) {
            if ($item->email == Auth::user()->email) {
                $customer_id = $item->id;
                break;
            }
        }

        // Return data
        return view('admin.students.stripe.list', compact('subscription', 'customer_id'));

    }

    public function cancel(Request $request)
    {
        $input = $request->all();
        try {
            $stripe = new \Stripe\StripeClient(
                env('STRIPE_SECRET')
            );
            $stripe->subscriptions->cancel(
                $input['sub_id'],
                []
            );
            return redirect()->route('subscription.list')->with('success', __('notification.update-success'));
        } catch (\Exception $e) {
            return redirect()->route('subscription.list')->with('error', __('notification.update-error'));
        }
    }
}
