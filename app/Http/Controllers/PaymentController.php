<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Discount;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductSize;
use App\Models\Size;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentController extends ApiController
{
    public function send(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cart' => 'required',
            'cart.*.id' => 'required|integer',
            'cart.*.qty' => 'required|integer',
            'address_id' => 'required|integer|exists:addresses,id'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->messages(), 422);
        }

        if (Address::find($request->address_id) == null) {
            return $this->errorResponse('آدرس وارد شده، حذف و یا وجود ندارد', 422);
        }

        $totalAmount = 0;
        foreach ($request->cart as $orderItem) {
            if($orderItem['property'] == 'size')
            {
                $product = Product::findOrFail($orderItem['id']);
                $size = Size::where('size_value', $orderItem['sellingProperty'])->first();
                $productSize = ProductSize::where('size_id', $size->id)->where('product_id', $product->id)->first();
                if ($productSize->quantity < $orderItem['qty']) {
                    return $this->errorResponse('تعداد محصول وارد شده اشتباه است', 422);
                }
            }elseif ($orderItem['property'] == 'color') {

            }
            $totalAmount += $orderItem['sellingDiscount'] != null ? $orderItem['sellingPrice'] * $orderItem['qty'] : $orderItem['originalPrice'] * $orderItem['qty'];
        }

        $couponAmount = 0;
        $coupon = null;

        if ($request->coupon) {
            $coupon = Discount::where('code', $request->coupon)->where('expired_at', '>', Carbon::now())->first();

            if ($coupon == null) {
                return $this->errorResponse('کد تخفیف وارد شده وجود ندارد', 422);
            }

            if (Order::where('user_id', Auth()->id())->where('discount_id', $coupon->id)->where('payment_status', 1)->exists()) {
                return $this->errorResponse('شما قبلا از این کد تخفیف استفاده کرده اید', 422);
            }

            $couponAmount = ($totalAmount * $coupon->percentage) / 100;
        }

        $payingAmount = $totalAmount - $couponAmount;

        $amounts = [
            'totalAmount' => $totalAmount,
            'discountedAmount' => $couponAmount,
            'payingAmount' => $payingAmount,
        ];

        
        $api = env('PAY_IR_API_KEY');
        $amount = $payingAmount;
        $mobile = "شماره موبایل";
        $factorNumber = "شماره فاکتور";
        $description = "توضیحات";
        $redirect = env('PAY_IR_CALLBACK_URL');
        $result = $this->sendRequest($api, $amount, $redirect, $mobile, $factorNumber, $description);
        dd($result);
        $result = json_decode($result);
        if($result->status) {
            OrderController::create($request, $coupon, $amounts, $result->token);
            $go = "https://pay.ir/pg/$result->token";
            return $this->successResponse('success',200,[
                'url' => $go
            ]);
        } else {
            return $this->errorResponse($result->errorMessage, 422);
        }
    }

    public function verify(Request $request)
    {
        return 'verify';
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'status' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->messages(), 422);
        }

        $api = env('PAY_IR_API_KEY');
        $token = $request->token;
        $result = json_decode($this->verifyRequest($api, $token));

        if (isset($result->status)) {
            if ($result->status == 1) {
                if (Transaction::where('trans_id', $result->transId)->exists()) {
                    return $this->succesResponse('error',200,[
                        'status' => false,
                        'error' => 'این تراکنش قبلا توی سیستم ثبت شده است'
                    ]);
                }
                OrderController::update($token, $result->transId);
                return $this->successResponse('success', 200,[
                    'status' => true,
                    'transId' => $result->transId
                ]);
            } else {
                return $this->errorResponse('تراکنش با خطا مواجه شد', 422);
            }
        } else {
            if ($request->status == 0) {
                return $this->successResponse('error',200,[
                    'status' => false,
                    'error' => 'تراکنش شما ناموفق بود'
                ]);
            }
        }
    }

    public function sendRequest($api, $amount, $redirect, $mobile = null, $factorNumber = null, $description = null)
    {
        return $this->curl_post('https://pay.ir/pg/send', [
            'api'          => $api,
            'amount'       => $amount,
            'redirect'     => $redirect,
            'mobile'       => $mobile,
            'factorNumber' => $factorNumber,
            'description'  => $description,
        ]);
    }

    function verifyRequest($api, $token)
    {
        return $this->curl_post('https://pay.ir/pg/verify', [
            'api'     => $api,
            'token' => $token,
        ]);
    }

    public function curl_post($url, $params)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
        ]);
        $res = curl_exec($ch);
        curl_close($ch);

        return $res;
    }
}
