<?php

namespace App\Http\Controllers\Api\V2;

use App\CustomerPackage;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomerPackageController;
use App\Http\Controllers\WalletController;
use App\Order;
use App\Models\UserShipEngineSettings;
use Illuminate\Http\Request;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use App\Models\Cart;
use App\User;

class PaypalController extends Controller
{

    public function getUrl(Request $request)
    {
        // Creating an environment

        $carts = Cart::with(['product'])->where('user_id', $request->user_id);

        // validate if merchant has turned off the ship engine after product added to cart
        $ship_items = clone $carts;
        $ship_items = $ship_items->where('shipping_type', 'ship_engine')->get(['owner_id', 'product_id']);

        foreach($ship_items as $items) {
            $ship_engine_found = UserShipEngineSettings::where('user_id', $items->owner_id)->count();
            $product_shipping_type = $items->product->shipping_type;

            if(!$ship_engine_found || $product_shipping_type != 'shipping_providers' || !get_setting('ship_engine')) {
                return response()->json(['result' => false, 'url' => '', 'message' => translates('ship_engine_turned_off'), 'ship_engine_error' => true]);
            }
        }

        $carts = $carts->get();

        $subtotal = 0;
        $tax = 0;
        $shipping = 0;
        foreach ($carts as $key => $cartItem) {
            $subtotal += ($cartItem['price'] * $cartItem['quantity']) - $cartItem['discount'];
            $tax += $cartItem['tax'] * $cartItem['quantity'];
            $shipping += $cartItem['shipping_cost'];
        }

        $clientId = get_setting('paypal_client_id');
        $clientSecret = get_setting('paypal_client_secret');

        if (get_setting('paypal_sandbox') == 1) {
            $environment = new SandboxEnvironment($clientId, $clientSecret);
            $currency_code = 'USD';
        } else {
            $environment = new ProductionEnvironment($clientId, $clientSecret);
            $currency_code = \App\Currency::find(get_setting('system_default_currency'))->code;
        }
        $client = new PayPalHttpClient($environment);

        if ($request->payment_type == 'cart_payment') {
            $amount = $subtotal + $shipping + $tax;
        } elseif ($request->payment_type == 'wallet_payment') {
            $amount = $request->amount;
        }

        $order_create_request = new OrdersCreateRequest();
        $order_create_request->prefer('return=representation');
        $order_create_request->body = [
            "intent" => "CAPTURE",
            "purchase_units" => [[
                "reference_id" => rand(000000, 999999),
                "amount" => [
                    "value" => number_format($amount, 2, '.', ''),
                    "currency_code" => $currency_code
                ]
            ]],
            "application_context" => [
                "cancel_url" => route('api.paypal.cancel'),
                "return_url" => route('api.paypal.done', ["device_type" => $request->device_type,"payment_type" => $request->payment_type, "amount" => $request->amount, "seller_id" => $request->seller_id, "user_id" => $request->user_id]),
            ]
        ];

        try {
            // Call API with your client and get a response for your call
            $response = $client->execute($order_create_request);
            // If call returns body in response, you can get the deserialized version from the result attribute of the response
            //return Redirect::to($response->result->links[1]->href);
            return response()->json(['result' => true, 'url' => $response->result->links[1]->href, 'message' => trans('messages.api.redirect_uri_found')]);
        } catch (HttpException $ex) {
            return response()->json(['result' => false, 'url' => '', 'message' => trans('messages.api.redirect_uri_not_found')]);
        }
    }


    public function getCancel(Request $request)
    {
        return response()->json(['result' => true, 'message' => trans('messages.api.payment_failed_or_cancelled')]);
    }

    public function getDone(Request $request)
    {
        //dd($request->all());
        // Creating an environment
        $user= User::find($request->user_id);
        $lang = $user->email_language ?? 'en';
        \App::setLocale($lang);

        $clientId = get_setting('paypal_client_id');
        $clientSecret = get_setting('paypal_client_secret');

        if (get_setting('paypal_sandbox') == 1) {
            $environment = new SandboxEnvironment($clientId, $clientSecret);
        } else {
            $environment = new ProductionEnvironment($clientId, $clientSecret);
        }
        $client = new PayPalHttpClient($environment);

        // $response->result->id gives the orderId of the order created above

        $ordersCaptureRequest = new OrdersCaptureRequest($request->token);
        $ordersCaptureRequest->prefer('return=representation');
        try {
            // Call API with your client and get a response for your call
            $response = $client->execute($ordersCaptureRequest);

            // If call returns body in response, you can get the deserialized version from the result attribute of the response

            if ($request->payment_type == 'cart_payment') {
                $order_ids = order_create($request->user_id,$request->seller_id,'paypal');
                checkout_done($order_ids, json_encode($response));
            }

            if ($request->payment_type == 'wallet_payment') {

                wallet_payment_done($request->user_id, $request->amount, 'Paypal', json_encode($response));
            }

                if(isset($request->device_type) && $request->device_type == 1){
                $result=array('success_message'=>trans('messages.api.payment_successful'),'status_code'=>'1');
                return view('json_response.json_response',array('result' =>json_encode($result)));

                }
                return response()->json(['status_code' => '1', 'result' => true, 'message' => trans('messages.api.payment_successful')]);

        } catch (HttpException $ex) {
            logger('online payment error- '. $ex->getMessage());
            return response()->json(['status_code' => '0','result' => false, 'message' => trans('messages.front_end.payment_failed')]);
        }
    }

}
