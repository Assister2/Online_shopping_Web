<?php


namespace App\Http\Controllers\Api\V2;


use App\Coupon;
use App\CouponUsage;
use Illuminate\Http\Request;
use App\Models\Cart;

class CheckoutController
{
    public function apply_coupon_code(Request $request)
    {
        $cart_items = Cart::where('user_id', $request->user_id)->get();
        $coupon = Coupon::where('code', $request->coupon_code)->first();

        if ($cart_items->isEmpty()) {
            return response()->json([
                'result' => false,
                'message' => trans('messages.api.cart_empty')
            ]);
        }

        if ($coupon == null) {
            return response()->json([
                'result' => false,
                'message' => trans('messages.api.invalid_coupon_code')
            ]);
        }

        $in_range = strtotime(date('d-m-Y')) >= $coupon->start_date && strtotime(date('d-m-Y')) <= $coupon->end_date;

        if (!$in_range) {
            return response()->json([
                'result' => false,
                'message' => trans('messages.api.coupon_expire')
            ]);
        }

        $is_used = CouponUsage::where('user_id', $request->user_id)->where('coupon_id', $coupon->id)->first() != null;

        if ($is_used) {
            return response()->json([
                'result' => false,
                'message' => trans('messages.api.already_used_coupon')
            ]);
        }


        $coupon_details = json_decode($coupon->details);

        if ($coupon->type == 'product_base') {
            $coupon_discount = 0;
            foreach ($cart_items as $key => $cartItem) {
                foreach ($coupon_details as $key => $coupon_detail) {
                    if ($coupon_detail->product_id == $cartItem['product_id']) {
                        if ($coupon->discount_type == 'percent') {
                            $cart = Cart::where('id',$cartItem['id'])->first();

                            $coupon_discount = $cartItem['price'] * $cartItem['quantity'] * 
                            $coupon->discount / 100;
                            $cart->discount = $coupon_discount;
                            $cart->coupon_code = $request->coupon_code;
                            $cart->coupon_applied = 1;
                            $cart->save();
                        }
                    }
                    elseif($coupon_detail->product_id != $cartItem['product_id'] && count($cart_items) < 2)
                    {
                        return response()->json([
                            'result' => false,
                            'message' => trans('messages.api.coupon_invalid')
                        ]);
                    }
                }
            }


            // Cart::where('user_id', $request->user_id)->update([
            //     'discount' => $coupon_discount / count($cart_items),
            //     'coupon_code' => $request->coupon_code,
            //     'coupon_applied' => 1
            // ]);

            return response()->json([
                'result' => true,
                'message' => trans('messages.api.coupon_applied')
            ]);

        }


    }

    public function remove_coupon_code(Request $request)
    {
        Cart::where('user_id', $request->user_id)->update([
            'discount' => 0.00,
            'coupon_code' => "",
            'coupon_applied' => 0
        ]);

        return response()->json([
            'result' => true,
            'message' => trans('messages.api.coupon_removed')
        ]);
    }
}
