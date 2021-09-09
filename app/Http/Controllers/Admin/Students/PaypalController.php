<?php

namespace App\Http\Controllers\Admin\Students;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\PayPalService;
use Illuminate\Support\Facades\Auth;

class PaypalController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $paypalServices;

    public function __construct(PayPalService $paypalServices)
    {
        $this->paypalServices = $paypalServices;
    }

    public function index()
    {
        $data = [
            [
                'name' => 'MH-01',
                'quantity' => 1,
                'price' => 10,
                'sku' => '1'
            ]
        ];
        $transactionDescription = "Tobaco";

        $paypalCheckoutUrl = $this->paypalServices
            // ->setCurrency('eur')
            ->setReturnUrl(url('student/payments/status'))
            // ->setCancelUrl(url('paypal/status'))
            ->setItem($data)
            // ->setItem($data[0])
            // ->setItem($data[1])
            ->createPayment($transactionDescription);

        if ($paypalCheckoutUrl) {
            return redirect($paypalCheckoutUrl);
        } else {
            dd(['Error']);
        }
    }

    public function status()
    {
        $paymentStatus = $this->paypalServices->getPaymentStatus();
        if (!$paymentStatus) {
            return "Bạn đã huỷ việc thanh toán đơn hàng";
        }

        if ($paymentStatus->state == "approved") {
            return "Đơn hàng đã được thanh toán";
        } else {
            return "Đã có lỗi trong lúc quá trình thanh toán";
        }
    }

    public function paymentList()
    {
        $limit = 10;
        $offset = 0;

        try {
            $paymentList = $this->paypalServices->getPaymentList($limit, $offset);
            dd($paymentList);
        } catch (\Exception $e) {
            dd("Error");
        }


    }

    public function paymentDetail($paymentId)
    {
        try {
            $paymentDetails = $this->paypalServices->getPaymentDetails($paymentId);
            dd($paymentDetails);
        } catch (\Exception $e) {
            dd("Error");
        }


    }
}
