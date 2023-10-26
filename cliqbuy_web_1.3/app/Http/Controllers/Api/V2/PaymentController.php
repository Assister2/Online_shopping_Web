<?php

namespace App\Http\Controllers\Api\V2;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function cashOnDelivery(Request $request)
    {
        $order = new OrderController;
        return $order->store($request);
    }

    public function fullDiscount(Request $request)
    {
        $response = '';
        $order_id = order_create($request->user_id,$request->seller_id,$request->payment_type);
        $checkout = checkout_done($order_id, json_encode($response));
        return response()->json(['result' => true, 'message' => trans('messages.api.payment_is_successful')]);

    }
}
