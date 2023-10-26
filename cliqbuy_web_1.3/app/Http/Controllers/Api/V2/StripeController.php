<?php

namespace App\Http\Controllers\Api\V2;

use App\CustomerPackage;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomerPackageController;
use App\Http\Controllers\WalletController;
use App\Order;
use App\User;
use Illuminate\Http\Request;
use Stripe\Exception\CardException;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use App\Models\Cart;
use App\Models\UserShipEngineSettings;

class StripeController extends Controller
{
    public function stripe(Request $request)
    {
        // validate if merchant has turned off the ship engine after product added to cart
        $cartItems = Cart::with(['product'])->where('user_id', $request->user_id);

        // validate if merchant has turned off the ship engine after product added to cart
        $ship_items = clone $cartItems;
        $ship_items = $ship_items->where('shipping_type', 'ship_engine')->get(['owner_id', 'product_id']);

        foreach($ship_items as $items) {
            $ship_engine_found = UserShipEngineSettings::where('user_id', $items->owner_id)->count();
            $product_shipping_type = $items->product->shipping_type;

            if(!$ship_engine_found || $product_shipping_type != 'shipping_providers' || !get_setting('ship_engine')) {
                return response()->json(['status_code' => '0','result' => false, 'message' => translates('ship_engine_turned_off'), 'ship_engine_error' => true]);
            }
        }

        $payment_type = $request->payment_type;
        // $order_id = $request->order_id;
        $amount = $request->amount;
        $user_id = $request->user_id;
        $device_type = $request->device_type;
        return view('frontend.payment.stripe_app', compact('device_type','payment_type', 'amount', 'user_id'));
    }

    public function create_checkout_session(Request $request)
    {
        $amount = 0;

        $carts = Cart::with(['product'])->where('user_id', $request->user_id)->get();

        $subtotal = 0;
        $tax = 0;
        $shipping = 0;
        foreach ($carts as $key => $cartItem) {
            $subtotal += ($cartItem['price'] * $cartItem['quantity']) - $cartItem['discount'];
            $tax += $cartItem['tax'] * $cartItem['quantity'];
            $shipping += $cartItem['shipping_cost'];
        }

        if ($request->payment_type == 'cart_payment') {
            $amount = $subtotal + $shipping + $tax;
        } elseif ($request->payment_type == 'wallet_payment') {
            $amount = $request->amount;
        }

            $amount = round($amount * 100);

        \Stripe\Stripe::setApiKey(get_setting('stripe_secret'));

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => \App\Currency::findOrFail(\App\BusinessSetting::where('type', 'home_default_currency')->first()->value)->code,
                        'product_data' => [
                            'name' => "Payment"
                        ],
                        'unit_amount' => $amount,
                    ],
                    'quantity' => 1,
                ]
            ],
            'mode' => 'payment',
            'success_url' => route('api.stripe.success', ["device_type" => $request->device_type,"payment_type" => $request->payment_type, "amount" => $request->amount, "user_id" => $request->user_id]),
            'cancel_url' => route('api.stripe.cancel',["device_type" => $request->device_type]),
        ]);

        return response()->json(['id' => $session->id, 'status' => 200]);
    }

    public function success(Request $request)
    {
        try {
            $user= User::find($request->user_id);
            $lang = $user->email_language ?? 'en';
            \App::setLocale($lang);

            $payment = ["status" => "Success"];

            $payment_type = $request->payment_type;

            if ($payment_type == 'cart_payment') {
                $order_ids = order_create($request->user_id,$request->seller_id,'stripe');
                checkout_done($order_ids, json_encode($payment));
            }

            if ($payment_type == 'wallet_payment') {

                wallet_payment_done($request->user_id, $request->amount, 'Stripe', json_encode($payment));
            }

            if(isset($request->device_type) && $request->device_type == 1){
                $result=array('success_message'=>trans('messages.api.payment_successful'),'status_code'=>'1');
                return view('json_response.json_response',array('result' =>json_encode($result)));

            }

            return response()->json(['status_code' => '1','result' => true, 'message' => trans('messages.api.payment_successful')]);


        } catch (\Exception $e) {
            logger('online payment error- '. $e->getMessage());
            return response()->json(['status_code' => '0','result' => false, 'message' => trans('messages.api.payment_unsuccessful')]);
        }
    }

    public function cancel(Request $request)
    {
        if(isset($request->device_type) && $request->device_type == 1){
            $result=array('success_message'=>trans('messages.api.payment_cancelled'),'status_code'=>'0');
            return view('json_response.json_response',array('result' =>json_encode($result)));

        }

        return response()->json(['status_code' => '0','result' => false, 'message' => trans('messages.api.payment_cancelled')]);
    }
}
