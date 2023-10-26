<?php

namespace App\Http\Controllers\Api\V2;

use App\Address;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Product;
use App\Models\OrderDetail;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\BusinessSetting;
use App\Models\UserShipEngineSettings;
use App\User;
use DB;
use Mail;
use Session;
use App\Mail\InvoiceEmailManager;

class OrderController extends Controller
{
    public function store(Request $request, $set_paid = false)
    {
        $cartItems = Cart::with(['product'])->where('user_id', $request->user_id);

        // validate if merchant has turned off the ship engine after product added to cart
        $ship_items = clone $cartItems;
        $ship_items = $ship_items->where('shipping_type', 'ship_engine')->get(['owner_id', 'product_id']);

        foreach($ship_items as $items) {
            $ship_engine_found = UserShipEngineSettings::where('user_id', $items->owner_id)->count();
            $product_shipping_type = $items->product->shipping_type;

            if(!$ship_engine_found || $product_shipping_type != 'shipping_providers' || !get_setting('ship_engine')) {
                return response()->json([
                    'order_id' => 0,
                    'result' => false,
                    'ship_engine_error' => true,
                    'message' => translates('ship_engine_turned_off')
                ]);
            }
        }

        $cartItems = $cartItems->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'order_id' => 0,
                'result' => false,
                'message' => trans('messages.api.cart_empty')
            ]);
        }

        $user = User::find($request->user_id);

        $address = Address::where('id', $cartItems->first()->address_id)->first();
        $shippingAddress = [];
        if ($address != null) {
            $shippingAddress['name']        = $user->name;
            $shippingAddress['email']       = $user->email;
            $shippingAddress['address']     = $address->address;
            $shippingAddress['country']     = $address->country;
            $shippingAddress['city']        = $address->city;
            $shippingAddress['postal_code'] = $address->postal_code;
            $shippingAddress['phone']       = $address->phone;
            if($address->latitude || $address->longitude) {
                $shippingAddress['lat_lang'] = $address->latitude.','.$address->longitude;
            }
        }

        $subtotal = 0;
        $tax = 0;
        $shipping = 0;

        $order_ids = [];

        foreach ($cartItems as $key => $cartItem) {
            $product = Product::find($cartItem->product_id);

            $order = new Order;
            $order->user_id = $request->user_id;
            $order->seller_id = $product->user_id;
            $order->shipping_address = json_encode($shippingAddress);
            $order->payment_type = $request->payment_option ?? 'cash_on_delivery';
            $order->payment_status = $set_paid ? 'paid' : 'unpaid';
            $order->shipping_method = get_setting('shipping_type');
            $order->delivery_viewed = '0';
            $order->payment_status_viewed = '0';
            $order->code = date('Ymd-His') . rand(10, 99);
            $order->date = strtotime('now');
            $order->save();

            $order_ids[$key] = $order->id;

            if (!$product) {
                return response()->json([
                    'result' => false,
                    'message' => trans('messages.api.product_not_found')
                ]);
            }

            $subtotal = $cartItem->price * $cartItem->quantity;
            $tax = $cartItem->tax * $cartItem->quantity;
            $product_variation = $cartItem->variation;

            $product_stocks = $product->stocks->where('variant', $cartItem->variation)->first();
            $product_stocks->qty -= $cartItem->quantity;
            $product_stocks->save();

            $order_detail = new OrderDetail;
            $order_detail->order_id = $order->id;
            $order_detail->seller_id = $product->user_id;
            $order_detail->product_id = $product->id;
            $order_detail->variation = $product_variation;
            $order_detail->price = $cartItem->price * $cartItem->quantity;
            $order_detail->tax = $cartItem->tax * $cartItem->quantity;
            $order_detail->shipping_type = $cartItem->shipping_type;
            $order_detail->product_referral_code = $cartItem->product_referral_code;
            $order_detail->shipping_cost = $cartItem->shipping_cost;
            $order_detail->quantity = $cartItem->quantity;
            $order_detail->payment_status = $set_paid ? 'paid' : 'unpaid';

            $shipping = $order_detail->shipping_cost;
            if ($cartItem->shipping_type == 'pickup_point') {
                $order_detail->pickup_point_id = $cartItem->pickup_point;
            }
            $order_detail->save();

            $product->update([
                'num_of_sale' => DB::raw('num_of_sale + ' . $cartItem->quantity)
            ]);

            $order->grand_total = $subtotal + $tax + $shipping;

            if($cartItem->coupon_code != '') {
                $order->grand_total -= $cartItem->discount;
                if ($order->grand_total < 0) {
                    $order->grand_total = 0;
                    $order->payment_status = 'paid';
                }
                if (Session::has('club_point')) {
                    $order->club_point = Session::get('club_point');
                }
                $order->coupon_discount = $cartItem->discount;

                $coupon_usage = new CouponUsage;
                $coupon_usage->user_id = $request->user_id;
                $coupon_usage->coupon_id = $cartItem->coupon_code;
                $coupon_usage->save();
            }

            $order->save();

            if (\App\Addon::where('unique_identifier', 'otp_system')->first() != null &&
            \App\Addon::where('unique_identifier', 'otp_system')->first()->activated &&
            SmsTemplate::where('identifier', 'order_placement')->first()->status == 1) {
                try {
                    $otpController = new OTPVerificationController;
                    $otpController->send_order_code($order);
                } catch (\Exception $e) {

                }
            }

            //sends Notifications to user
            send_notification($order, 'placed');
            if (get_setting('google_firebase') == 1 && $order->user->device_token != null) {
                $request->device_token = $order->user->device_token;
                $request->title = "Order placed !";
                $request->text = trans('messages.api.an_order_placed', ['code' => $order->code]);

                $request->type = "order";
                $request->id = $order->id;
                $request->user_id = $order->user->id;

                send_firebase_notification($request);
            }

            $array['view'] = 'emails.invoice';
            $array['subject'] = translate('Your order has been placed') . ' - ' . $order->code;
            $array['from'] = get_setting('mail_from_address');
            $array['order'] = $order;

            //sends email to customer with the invoice pdf attached
            if (get_setting('mail_username') != null) {
                try {
                    Mail::to($order->user->email)->queue(new InvoiceEmailManager($array));
                    Mail::to(User::where('user_type', 'admin')->first()->email)->queue(new InvoiceEmailManager($array));
                } catch (\Exception $e) {
                    logger('mail error - '. $e->getMessage());
                }
            }

            if ($product->added_by != 'admin') {
                try {
                    Mail::to(\App\User::find($product->user_id)->email)->queue(new InvoiceEmailManager($array));
                } catch (\Exception $e) {
                    logger('mail error - '. $e->getMessage());
                }
            }
        }

        Cart::where('user_id', $request->user_id)->delete();

        return response()->json([
            'order_id' => $order_ids,
            'result' => true,
            'message' => trans('messages.api.your_order_placed')
        ]);
    }

}
