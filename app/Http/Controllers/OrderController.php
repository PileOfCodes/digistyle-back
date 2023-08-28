<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public static function create($request, $coupon, $amounts, $token)
    {

        DB::beginTransaction();

        $order = Order::create([
            'user_id' => Auth()->id(),
            'address_id' => $request->address_id,
            'discount_id' => $coupon == null ? null : $coupon->id,
            'total_amount' => $amounts['totalAmount'],
            'discounted_amount' => $amounts['discountedAmount'],
            'paying_amount' => $amounts['payingAmount'],
        ]);

        foreach ($request->cart as $orderItem) {
            $product = Product::findOrFail($orderItem['id']);
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'price' => $orderItem['sellingDiscount'] != null ? $orderItem['sellingPrice'] : $orderItem['originalPrice'],
                'quantity' => $orderItem['qty'],
                'subtotal' => ($orderItem['sellingPrice'] * $orderItem['qty'])
            ]);
        }

        Transaction::create([
            'user_id' => Auth()->id(),
            'order_id' => $order->id,
            'amount' => $amounts['payingAmount'],
            'token' => $token
        ]);

        DB::commit();
    }

    public static function update($token, $transId)
    {
        DB::beginTransaction();

        $transaction = Transaction::where('token', $token)->firstOrFail();

        $transaction->update([
            'status' => 1,
            'trans_id' => $transId
        ]);

        $order = Order::findOrFail($transaction->order_id);

        $order->update([
            'status' => 1,
            'payment_status' => 1
        ]);

        foreach (OrderItem::where('order_id', $order->id)->get() as $item) {
            $product = Product::find($item->product_id);
            $product->update([
                'quantity' => ($product->quantity -  $item->quantity)
            ]);
        }

        DB::commit();
    }
}
