<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Resources\V2\CartCollection;
use App\Models\Cart;
use App\Models\Color;
use App\Models\FlashDeal;
use App\Models\FlashDealProduct;
use App\Models\UserShipEngineSettings;
use App\Product;
use App\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function summary($user_id, $owner_id)
    {
        $items = Cart::where('user_id', $user_id)->get();

        if ($items->isEmpty()) {

            if (request()->device_id == '1') {
                $cart_summary = [
                    'sub_total' => format_price(0.00),
                    'tax' => format_price(0.00),
                    'shipping_cost' => format_price(0.00),
                    'discount' => format_price(0.00),
                    'grand_total' => format_price(0.00),
                    'grand_total_value' => 0.00,
                    'coupon_code' => "",
                    'coupon_applied' => false,
                    'is_coupon_enabled' => get_setting('coupon_system')==1 ? true : false,
                ];

                $array_list = [
                    'status' => 1,
                    'cart_summary'   => array($cart_summary)
                ];
                return response()->json($array_list);
            }

            return response()->json([
                'sub_total' => format_price(0.00),
                'tax' => format_price(0.00),
                'shipping_cost' => format_price(0.00),
                'discount' => format_price(0.00),
                'grand_total' => format_price(0.00),
                'grand_total_value' => 0.00,
                'coupon_code' => "",
                'coupon_applied' => false,
                'is_coupon_enabled' => get_setting('coupon_system')==1 ? true : false,
            ]);
        }

        $sum = 0.00;
        $subtotal_item = 0.00;
        $tax_item = 0.00;
        $shipping_item = 0.00;
        $discount_item = 0.00;
        foreach ($items as $key => $cartItem) {
            $item_sum = 0;
            $item_sum += ($cartItem->price + $cartItem->tax) * $cartItem->quantity;
            $item_sum += getShippingCost($items, $key) - $cartItem->discount;
            $subtotal_item += $cartItem->price * $cartItem->quantity;
            $tax_item += $cartItem->tax * $cartItem->quantity;
            $shipping_item += getShippingCost($items, $key);
            $discount_item += $cartItem->discount;

            $sum +=  $item_sum;
        }

        if ($sum < 1) {
            $sum = 0.00;
        }

        if ($items[0]->pickup_point) {
            $shipping_item = 0.00;
        }

        if (request()->device_id == '1') {
            $cart_summary = [
                'sub_total' => format_price($subtotal_item),
                'tax' => format_price($tax_item),
                'shipping_cost' => format_price($shipping_item),
                'discount' => format_price($discount_item),
                'grand_total' => format_price($sum),
                'grand_total_value' => (float)($sum),
                'coupon_code' => $items[0]->coupon_code,
                'coupon_applied' => $items[0]->coupon_applied == 1,
                'is_coupon_enabled' => get_setting('coupon_system')==1 ? true : false,
            ];

            $array_list = [
                'status' => 1,
                'cart_summary'   => array($cart_summary)
            ];
            return response()->json($array_list);
        }
            
        return response()->json([
            'sub_total' => format_price($subtotal_item),
            'tax' => format_price($tax_item),
            'shipping_cost' => format_price($shipping_item),
            'discount' => format_price($discount_item),
            'grand_total' => format_price($sum),
            'grand_total_value' => (float)($sum),
            'coupon_code' => $items[0]->coupon_code,
            'coupon_applied' => $items[0]->coupon_applied == 1,
            'is_coupon_enabled' => get_setting('coupon_system')==1 ? true : false,
        ]);


    }

    public function getList($user_id)
    {
        $owner_ids = Cart::where('user_id', $user_id)->select('owner_id')->groupBy('owner_id')->pluck('owner_id')->toArray();
        $currency_symbol = currency_symbol();
        $is_pickup_point_enabled = get_setting('pickup_point') == 1 ? true : false;
        $is_pickup_point_enabled = count(array_unique($owner_ids)) > 1 ? false : $is_pickup_point_enabled;
        $shops = [];
        $shipengine_found = false;
        if (!empty($owner_ids)) {
            foreach ($owner_ids as $owner_id) {
                $shop = array();
                $shop_items_raw_data = Cart::with(['product', 'user', 'user.addresses'])->where('user_id', $user_id)->where('owner_id', $owner_id)->get()->toArray();
                $shop_items_data = array();
                if (!empty($shop_items_raw_data)) {
                    foreach ($shop_items_raw_data as $shop_items_raw_data_item) {
                        $product = Product::where('id', $shop_items_raw_data_item["product_id"])->first();
                        $shop_items_data_item["id"] = intval($shop_items_raw_data_item["id"]) ;
                        $shop_items_data_item["owner_id"] =intval($shop_items_raw_data_item["owner_id"]) ;
                        $shop_items_data_item["user_id"] =intval($shop_items_raw_data_item["user_id"]) ;
                        $shop_items_data_item["product_id"] =intval($shop_items_raw_data_item["product_id"]) ;
                        $shop_items_data_item["product_name"] = $product->name;
                        $shop_items_data_item["product_thumbnail_image"] = api_asset($product->thumbnail_img);
                        $shop_items_data_item["variation"] = $shop_items_raw_data_item["variation"] ?? '';
                        $shop_items_data_item["price"] =(double) $shop_items_raw_data_item["price"];
                        $shop_items_data_item["currency_symbol"] = $currency_symbol;
                        $shop_items_data_item["tax"] =(double) $shop_items_raw_data_item["tax"];
                        $shop_items_data_item["shipping_cost"] =(double) $shop_items_raw_data_item["shipping_cost"];
                        $shop_items_data_item["quantity"] =intval($shop_items_raw_data_item["quantity"]) ;
                        $shop_items_data_item["lower_limit"] = intval($product->min_qty) ;
                        $shop_items_data_item["upper_limit"] = intval($product->stocks->where('variant', $shop_items_raw_data_item['variation'])->first()->qty) ;
                        $shop_items_data_item['shipengine_found'] = false;


                        $shipengine_count = UserShipEngineSettings::where('user_id', $shop_items_raw_data_item['owner_id']);

                        if(get_setting('ship_engine') && $shipengine_count->count() && $shop_items_raw_data_item['product']['shipping_type'] == 'shipping_providers' && count($shop_items_raw_data_item['user']['addresses'])) {
                            $shop_items_data_item['shipengine_found'] = true;
                        }

                        $shop_items_data[] = $shop_items_data_item;
                    }
                }


                $shop_data = Shop::where('user_id', $owner_id)->first();
                if ($shop_data) {
                    $shop['name'] = $shop_data->name;
                    $shop['owner_id'] =(int) $owner_id;
                    $shop['is_pickup_point_enabled'] = $is_pickup_point_enabled;
                    $shop['cart_items'] = $shop_items_data;
                } else {
                    $shop['name'] = "Inhouse";
                    $shop['owner_id'] =(int) $owner_id;
                    $shop['is_pickup_point_enabled'] = $is_pickup_point_enabled;
                    $shop['cart_items'] = $shop_items_data;
                }
                $shops[] = $shop;
            }
        }

        if (request()->device_id == '1') {
            $array_list = [
                'status' => 1,
                'shops'   => $shops
            ];
            return response()->json($array_list);
        }
        return response()->json($shops);
    }


    public function add(Request $request)
    {
        $users = \DB::table('users')->where('id',$request->user_id)->first();
        if ($users == null) {
            if(session()->get('temp_user_id')) {
                $temp_user_id = session()->get('temp_user_id');
            } else {
                $temp_user_id = bin2hex(random_bytes(10));
                session()->put('temp_user_id', $temp_user_id);
            }

            $cart = new Cart;
            $cart->owner_id = $request->owner_id;
            $cart->product_id = $request->id;
            $cart->temp_user_id = $temp_user_id;
            $cart->price = $request->price;
            $cart->variation = $request->variation;
            $cart->quantity = $request->quantity;
            $cart->save();   
        }
        
        $product = Product::findOrFail($request->id);

        $variant = $request->variant;
        $tax = 0;

        if ($variant == '')
            $price = $product->unit_price;
        else {
            $product_stock = $product->stocks->where('variant', $variant)->first();
            $price = $product_stock->price;
        }

        //discount calculation based on flash deal and regular discount
        //calculation of taxes
        $discount_applicable = false;

        if ($product->discount_start_date == null) {
            $discount_applicable = true;
        }
        elseif (strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date &&
            strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date) {
            $discount_applicable = true;
        }

        if ($discount_applicable) {
            if($product->discount_type == 'percent'){
                $price -= ($price*$product->discount)/100;
            }
            elseif($product->discount_type == 'amount'){
                $price -= $product->discount;
            }
        }

        foreach ($product->taxes as $product_tax) {
            if ($product_tax->tax_type == 'percent') {
                $tax += ($price * $product_tax->tax) / 100;
            } elseif ($product_tax->tax_type == 'amount') {
                $tax += $product_tax->tax;
            }
        }

        if ($product->min_qty > $request->quantity) {
            return response()->json(['result' => false, 'message' => trans('messages.api.minimum')." {$product->min_qty} ".trans('messages.api.items_should_be_ordered')], 200);
        }

        $stock = $product->stocks->where('variant', $variant)->first() ? $product->stocks->where('variant', $variant)->first()->qty : 0;

        $variant_string = $variant != null && $variant != "" ? "for ($variant)" : "";
        if ($stock < $request->quantity) {
            if ($stock == 0) {
                return response()->json(['result' => false, 'message' => trans('messages.api.stock_out')], 200);
            } else {
                return response()->json(['result' => false, 'message' => trans('messages.api.only')." {$stock} ".trans('messages.api.items_are_available')." {$variant_string}"], 200);
            }
        }

        Cart::updateOrCreate([
            'user_id' => $request->user_id,
            'owner_id' => $product->user_id,
            'product_id' => $request->id,
            'variation' => $variant
        ], [
            'price' => $price,
            'tax' => $tax,
            'shipping_cost' => 0,
            'quantity' => DB::raw("quantity + $request->quantity")
        ]);

        // if(\App\Utility\NagadUtility::create_balance_reference($request->cost_matrix) == false){
        //     return response()->json(['result' => false, 'message' => 'Cost matrix error' ]);
        // }

        return response()->json([
            'result' => true,
            'message' => trans('messages.api.product_added_to_cart')
        ]);
    }

    public function changeQuantity(Request $request)
    {
        $cart = Cart::find($request->id);
        if ($cart != null) {

            if ($cart->product->stocks->where('variant', $cart->variation)->first()->qty >= $request->quantity) {
                $cart->update([
                    'quantity' => $request->quantity
                ]);

                return response()->json(['result' => true, 'message' => trans('messages.api.cart_update')], 200);
            } else {
                return response()->json(['result' => false, 'message' => trans('max_avail_quan_reached')], 200);
            }
        }

        return response()->json(['result' => false, 'message' => trans('messages.front_end.something_went_wrong')], 200);
    }

    public function process(Request $request)
    {
        $cart_ids = explode(",", $request->cart_ids);
        $cart_quantities = explode(",", $request->cart_quantities);

        Cart::where('user_id', $request->user_id)->update([
            'discount' => 0.00,
            'coupon_code' => "",
            'coupon_applied' => 0
        ]);

        if (!empty($cart_ids)) {
            $i = 0;
            foreach ($cart_ids as $cart_id) {
                $cart_item = Cart::where('id', $cart_id)->first();
                $product = Product::where('id', $cart_item->product_id)->first();

                if ($product->min_qty > $cart_quantities[$i]) {
                    return response()->json(['result' => false, 'message' => trans('messages.api.minimum') ."{$product->min_qty}". trans('messages.api.item_order') ."{$product->name}"], 200);
                }

                $stock = $cart_item->product->stocks->where('variant', $cart_item->variation)->first()->qty;
                $variant_string = $cart_item->variation != null && $cart_item->variation != "" ? " ($cart_item->variation)" : "";
                if ($stock >= $cart_quantities[$i]) {
                    $cart_item->update([
                        'quantity' => $cart_quantities[$i]
                    ]);

                } else {
                    if ($stock == 0) {
                        return response()->json(['result' => false, 'message' => trans('messages.api.no_item_available'). "{$product->name}{$variant_string}," .trans('messages.api.remove_from_cart')], 200);
                    } else {
                        return response()->json(['result' => false, 'message' => trans('messages.api.only'). "{$stock}" .trans('messages.api.item_available'). "{$product->name}{$variant_string}"], 200);
                    }

                }

                $i++;
            }

            return response()->json(['result' => true, 'message' => trans('messages.api.cart_update')], 200);

        } else {
            return response()->json(['result' => false, 'message' => trans('messages.api.cart_empty')], 200);
        }


    }

    public function destroy($id)
    {
        Cart::destroy($id);
        return response()->json(['result' => true, 'message' => trans('messages.api.product_removed_cart')], 200);
    }
}
