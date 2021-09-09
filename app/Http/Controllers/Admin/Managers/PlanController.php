<?php

namespace App\Http\Controllers\Admin\Managers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PlanRequest;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use App\Models\Plan;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Stripe\Exception\ApiErrorException;

class PlanController extends Controller
{

    protected $stripe;

    /**
     * __construct.
     *
     */
    public function __construct()
    {
        $this->stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
    }

    /**
     * Function list plan.
     *
     * @return View
     */
    public function index()
    {
        $plans = Plan::all();
        return view('admin.managers.plans.index', compact('plans'));
    }

    /**
     * Function create show plan.
     *
     * @return Factory|View
     */
    public function create()
    {
        return view('admin.managers.plans.create');
    }

    /**
     * Function create plan for payment.
     *
     * @param PlanRequest $request
     * @return Redirector
     */
    public function store(PlanRequest $request)
    {
        DB::beginTransaction();
        try {
            $input = $request->except('_token');

            // o not allow creating plan with a duration exceeding 1 year
            if (
                $input['interval'] == 'day' && $input['interval_count'] > 365
                || $input['interval'] == 'week' && $input['interval_count'] > 52
                || $input['interval'] == 'month' && $input['interval_count'] > 12
                || $input['interval'] == 'year' && $input['interval_count'] > 1
            ) {
                return redirect(route('plans.create'))->with('error', __('validation_custom.CM001'));
            }

            //create stripe product
            $stripeProduct = $this->stripe->products->create([
                'name' => $input['name'],
                'description' => $input['description']
            ]);

            //create stripe prices
            $stripePlanCreation = $this->stripe->prices->create([
                'nickname' => 'Mcrew-tech',
                'product' => $stripeProduct->id,
                'unit_amount' => $input['cost'],
                'currency' => config('constants.currency'),
                'recurring' => [
                    'interval' => $input['interval'], //  it can be day,week,month or year
                    'interval_count' => $input['interval_count'],
                    'usage_type' => 'licensed',
                ],
            ]);

            // Insert table: plans
            $data['cost']               = $input['cost'];
            $data['name']               = $input['name'];
            $data['product_id']         = $stripeProduct->id;
            $data['stripe_plan']        = $stripePlanCreation->id;
            $data['interval']           = $input['interval'];
            $data['interval_count']     = $input['interval_count'];
            $data['description']        = $input['description'];
            Plan::create($data);

            DB::commit();
            return redirect(route('plans.list'))->with('success', __('validation_custom.M008'));
        } catch (\Exception $e) {
            DB::rollback();
            return redirect(route('plans.create'))->with('error', __('validation_custom.CM001'));
        }
    }

    /**
     * Function edit show plan.
     *
     * @param $id
     * @return Factory|View
     */
    public function edit($id)
    {
        $plan = $plans = Plan::find($id);
        if (empty($plan)) {
            abort(404);
        }
        return view('admin.managers.plans.edit', compact('plan'));
    }

    /**
     * Function update plan for payment.
     *
     * @param PlanRequest $request
     * @return Redirector
     */
    public function update(PlanRequest $request)
    {

        DB::beginTransaction();
        try {
            $data = $request->except('_token');
            $price = $data['cost'] * 100;

            $plan = Plan::find($data['id_plan']);


            $this->stripe->products->update(
                $plan->product_id,
                [
                    'name' => $data['name'],
                ]
            );

//            $abc = $this->stripe->prices->update(
//                $plan->stripe_plan,
//                [
//                    'nickname' => 'vinh'
//                ]
//            );


            // Update plan
            $plan->name             = $data['name'];
            $plan->cost             = $data['cost'];
            $plan->interval         = $data['interval'];
            $plan->interval_count   = $data['interval_count'];
            $plan->description      = $data['description'];
            $plan->save();

            DB::commit();
            return redirect(route('plans.list'))->with('success', __('validation_custom.M008'));
        } catch (\Exception $e) {
            DB::rollback();
            return redirect(route('plans.edit', ['id' => $data['id_plan']]))->with('error', __('validation_custom.M007'));
        }
    }
}
