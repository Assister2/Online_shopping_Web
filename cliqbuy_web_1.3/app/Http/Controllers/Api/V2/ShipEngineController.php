<?php

namespace App\Http\Controllers\Api\V2;

use Illuminate\Http\Request;
use App\Models\UserShipEngineSettings;
use App\Models\ShipEngine;
use App\Product;
use App\User;
use App\Cart;
use App\Order;
use App\Address;
use App\OrderDetail;
use App\Shop;
use Auth;

class ShipEngineController extends Controller
{
    /**
     * Show dropdown and fetch rates from shipengine v1/rates api
     *
     * @return \Illuminate\Http\Response
     */
    public function delivery_info($user_id, Request $request) {
        $owner_ids = Cart::where('user_id', $user_id)->select('owner_id')->groupBy('owner_id')->pluck('owner_id')->toArray();
        $currency_symbol = currency_symbol();
        $is_pickup_point_enabled = get_setting('pickup_point') == 1 ? true : false;
        $is_pickup_point_enabled = count(array_unique($owner_ids)) > 1 ? false : $is_pickup_point_enabled;
        $shops = [];

        if (!empty($owner_ids)) {
            foreach ($owner_ids as $owner_id) {
                $shop = array();
                $cart_item = Cart::with(['product', 'user', 'user.addresses'])->where('user_id', $user_id)->where('owner_id', $owner_id);

                $shop_items_raw_data = clone $cart_item;
                $shop_items_raw_data = $shop_items_raw_data->get()->toArray();
                $shop_items_data = array();
                if (!empty($shop_items_raw_data)) {
                    foreach ($shop_items_raw_data as $key => $shop_items_raw_data_item) {
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
                        $shop_items_data_item["quantity"] =intval($shop_items_raw_data_item["quantity"]) ;
                        $shop_items_data_item["lower_limit"] = intval($product->min_qty);
                        $shop_items_data_item["upper_limit"] = intval($product->stocks->where('variant', $shop_items_raw_data_item['variation'])->first()->qty) ;

                        // $cartItem = $cart_item->where('id', $shop_items_raw_data_item['id'])->first();
                        logger('cart_id-'. $shop_items_raw_data_item['id']);
                        $cartItem = Cart::where('id', $shop_items_raw_data_item['id'])->first();

                        $user_address = $cartItem->user->addresses;
                        $shipengine_found = UserShipEngineSettings::where('user_id', $cartItem->owner_id);

                        if(get_setting('ship_engine') && $shipengine_found->count() && $product->shipping_type == 'shipping_providers' && $user_address->count()) {
                            $shipengine_found = $shipengine_found->first();
                            $from_postal_code = $user_address->where('set_default', '1')->first()->postal_code;
                            $req_data = [
                                'carrier_id' => $shipengine_found->carrier_id,
                                'product_user_id' => $cartItem->owner_id,
                                'from_zip_code' => $from_postal_code,
                                'ship_quantity' => $cartItem->quantity,
                                'product_id' => $cartItem->product_id,
                                'user_id' => $user_id,
                            ];

                            $ship_estimate = ship_estimate($req_data);

                            logger('ShipEnginecurl- '. json_encode($ship_estimate));
                            if($ship_estimate['status']) {
                                $estimate_data = $ship_estimate['data'];
                                $lowest_price = $estimate_data[0]['shipping_amount']['original_amount'];
                                $service_type = $estimate_data[0]['service_code'];
                                $package_type = $estimate_data[0]['package_type'];
                                $rate_id = $estimate_data[0]['rate_id'];

                                $shipengineData['id'] = $cartItem->id;
                                $shipengineData['product_id'] = $cartItem->product_id;
                                $shipengineData['shipengine'] = true;
                                $shipengineData['estimate_data'] = $estimate_data;

                                $original_cart = Cart::where('id', $cartItem->id)->first();

                                // update the cart 
                                if(!$original_cart->service_type || $original_cart->service_type == '' || $original_cart->shipping_type != 'ship_engine') {
                                    $original_cart->update(['shipping_cost' => $lowest_price, 'shipping_type' => 'ship_engine', 'service_type' => $service_type, 'package_type' => $package_type, 'rate_id' => $rate_id]);
                                }
                            } else {
                                $shipengineData['id'] = $cartItem->id;
                                $shipengineData['product_id'] = $cartItem->product_id;
                                $shipengineData['shipengine'] = false;
                                $shipengineData['estimate_data'] = [];
                            }
                        } else {
                            $product = \App\Product::find($cartItem->product_id);

                            $cartItem->shipping_cost = 0;
                            $cartItem->shipping_type = '';
                            $cartItem->package_type = '';
                            $cartItem->service_type = '';
                            $cartItem->shipping_type = 'home_delivery';
                            $cartItem->shipping_cost = $product->shipping_cost ?? 0;

                            if($product->is_quantity_multiplied == 1 && get_setting('shipping_type') == 'product_wise_shipping') {
                                $cartItem->shipping_cost =  $cartItem->shipping_cost * $cartItem->quantity;
                            }
                            $cartItem->save();

                            $shipengineData['id'] = $cartItem->id;
                            $shipengineData['product_id'] = $cartItem->product_id;
                            $shipengineData['shipengine'] = false;
                            $shipengineData['estimate_data'] = [];
                        }

                        $cartItem = $cartItem->refresh();
                        $shop_items_data_item["shipengineData"] = $shipengineData;
                        $shop_items_data_item['service_code'] = $cartItem->service_type;
                        $shop_items_data_item['package_type'] = $cartItem->package_type;
                        $shop_items_data_item["shipping_cost"] = (double) $cartItem->shipping_cost;
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

    //updated the quantity for a cart item
    public function updateRateId(Request $request)
    {
        try {
            $url = "https://api.shipengine.com/v1/rates/{$request->rate_id}";
            $post_data = [];
            $curl_res = ShipEnginecurl($url, $post_data, 'GET');
            logger('updateRateId-'.json_encode($curl_res));
        } catch(Exception $e) {
            return [
                'status' => false,
                'ios_status' => 0,
                'message' => $e->getMessage(),
                'rate_id' => '',
            ];
        }

        if(isset($curl_res['error_messages']) && count($curl_res['error_messages'])) {
            return [
                'status' => false,
                'ios_status' => 0,
                'message' => trans('messages.frontend.something_went_wrong'),
                'rate_id' => '',
            ];
        } else {
            $update = Cart::where('id', $request->id)->update(['shipping_cost' => $curl_res['shipping_amount']['amount'], 'shipping_type' => 'ship_engine', 'service_type' => $curl_res['service_code'], 'package_type' => ucwords(str_replace('_', ' ', $curl_res['package_type']))]);

            return [
                'status' => true,
                'ios_status' => 1,
                'rate_id' => $curl_res['rate_id'],
                'message' => trans('messages.api.shipping_cost_changed'),
            ];
        }
    }

    //updated the quantity for a cart item
    public function updateQuantity(Request $request)
    {
        $user_id = $request->user_id;
        $object = Cart::findOrFail($request->id);

        if($object['id'] == $request->id){
            $product = \App\Product::find($object['product_id']);
            $product_stock = $product->stocks->where('variant', $object['variation'])->first();
            $quantity = $product_stock->qty;

            if($quantity >= $request->quantity) {
                if($request->quantity >= $product->min_qty){
                    $object['quantity'] = $request->quantity;
                }
            }

            $object->save();
        }

        $carts = Cart::where('user_id', $user_id)->get();

        $shipengine_found = UserShipEngineSettings::where('user_id', $object->owner_id);
        if(get_setting('ship_engine') && $shipengine_found->count() && $product->shipping_type == 'shipping_providers') {
            $shipengine_found = $shipengine_found->first();
            $from_postal_code = $object->user->addresses->where('set_default', '1')->first()->postal_code;
            $req_data = [
                'carrier_id' => $shipengine_found->carrier_id,
                'product_user_id' => $object->owner_id,
                'from_zip_code' => $from_postal_code,
                'ship_quantity' => $request->quantity,
                'product_id' => $object->product_id,
                'user_id' => $user_id,
            ];

            $ship_estimate = ship_estimate($req_data);

            if($ship_estimate['status']) {
                $estimate_data = $ship_estimate['data'];
                $lowest_price = $estimate_data[0]['shipping_amount']['original_amount'];

                if($lowest_price > $object->shipping_cost) {
                    $original_cart = Cart::where('id', $object->id)->update(['shipping_cost' => $lowest_price]);
                }
            } 
        } else {
            $object->shipping_type = 'home_delivery';
            $object->shipping_cost = $product->shipping_cost ?? 0;

            if($product->is_quantity_multiplied == 1 && get_setting('shipping_type') == 'product_wise_shipping') {
                $object->shipping_cost =  $object->shipping_cost * $object->quantity;
            }
            $object->save();
        }

        return [
            'status' => true,
            'message' => translates('quantity_changed'),
        ];
    }
}
