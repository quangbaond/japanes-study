<?php


namespace App\Http\Controllers\Admin;

use App\Jobs\StripeWebhooks\HandleCustomerCreated;
use App\Listeners\CustomerCreated;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class WebhookController extends Controller
{

    public function handleWebhook(Request $request)
    {

    }

}
