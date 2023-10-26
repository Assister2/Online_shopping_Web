<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserShipEngineSettings;
use App\Models\ShipEngine;
use App\Product;
use App\User;
use App\Cart;
use App\Order;
use App\Address;
use App\OrderDetail;
use Auth;

class ShipEngineController extends Controller
{
    /**
     * Validate address from ship engine when user add/edit address
     *
     * @return \Illuminate\Http\Response
     */
    public function validate_address_from_ship_engine(Request $request) {
        $req_data = $request->data;

        // return the functionality if ship engine is turned off
        if(!get_setting('ship_engine')) {
            return [
                'status' => false,
                'ship_engine' => false,
                'address' => $req_data,
            ];
        }

        try {
            $post_data = [
                [
                    "address_line1" => $req_data['address'],
                    "city_locality" => $req_data['city'],
                    "state_province" => \DB::table('states')->where('name', $req_data['state_id'])->first()->short_name,
                    "postal_code" => $req_data['postal_code'],
                    "country_code" => \DB::table('countries')->where('name', $req_data['country'])->first()->code
                ]
            ];

            $url = 'https://api.shipengine.com/v1/addresses/validate';
            $curl_res = ShipEnginecurl($url, $post_data, 'POST');

            logger('address validate api res - '. json_encode($curl_res));
            $curl_res = $curl_res[0];
            $res_status = $curl_res['status'];

            $status = false;
            $original_address = [];
            $matched_address = [];
            $selected_address = [];

            if($res_status == 'verified') {
                $status = true;
                $original_address = $curl_res['original_address'];
                $matched_address = $curl_res['matched_address'];
                $selected_address = $curl_res['original_address'];
            }

            return [
                'status' => $status,
                'ship_engine' => true,
                'original_address' => $original_address,
                'matched_address' => $matched_address,
                'selected_address' => $req_data
            ];
        } catch (\Exception $e) {
            flash($e->getMessage())->warning();
            return [
                'status' => false,
            ];
        }
    }

    /**
     * Connect to ship engine by merchant
     *
     * @return \Illuminate\Http\Response
     */
    public function ship_engine_carriers(Request $request) {
        $data['user_id'] = \Auth::id();
        $data['auth_user_type'] = \Auth::user()->user_type;
        $data['section'] = $data['auth_user_type'] == 'admin' ? 'content' : 'panel_content';
        $data['blade'] = $data['auth_user_type'] == 'admin' ? 'backend.layouts.app' : 'frontend.layouts.user_panel';
        $data['ship_engines'] = ShipEngine::all();
        
        return view('ship_engine.show_carriers', $data);
    }

    /**
     * Connect to ship engine by merchant
     *
     * @return \Illuminate\Http\Response
     */
    public function connect_carriers(Request $request) {
        if(env('SHIP_ENGINE') == 'live') {
            $url = "https://api.shipengine.com/v1/connections/carriers/{$request->carrier}";

            $params = [
                'nickname' => "{$request->carrier} account",
                'username' => env('SHIP_ENGINE_USERNAME'),
                'password' => env('SHIP_ENGINE_PASSWORD'),
            ];
        } else {
            $url = "https://api.shipengine.com/v-beta/ltl/connections/test";

            $params = [
                'credentials' => null
            ];
        }

        $user = \Auth::user();

        // return if seller doesn't add any address
        $address_count = $user->addresses->count();
        if(!$address_count) {
            flash(translates('dont_have_address'))->warning();
            return response()->json([
                'success' => false,
                'message' => translates('dont_have_address'),
            ]);
        }

        //currently working for sandbox
        $curl_res = ShipEnginecurl($url, $params, 'POST');

        if(isset($curl_res['errors'])) {
            flash(translate($curl_res['errors'][0]['message']))->warning();
            return response()->json([
                'success' => false,
                'message' => $curl_res['errors'][0]['message'],
            ]);
        } else {
            //statically save carrier id for local
            $carrier_id = env('SHIP_ENGINE') != 'live' ? 'se-4673540' : $curl_res['carrier_id'];

            $where = ['user_id' => $user->id, 'carrier_id' => $carrier_id];
            $data = ['user_id' => $user->id, 'carrier_id' => $carrier_id, 'carrier_name' => $request->carrier];
            UserShipEngineSettings::UpdateOrCreate($where, $data);

            flash(translates('carrer_connected'))->success();
            return response()->json([
                'success' => true,
                'message' => translates('carrer_connected'),
            ]);
        }
    }


    /**
     * Connect to ship engine by merchant
     *
     * @return \Illuminate\Http\Response
     */
    public function disconnect_carriers(Request $request) {
        $where = ['user_id' => \Auth::id(), 'carrier_name' => $request->carrier];
        $ship_engine = UserShipEngineSettings::where($where);
        $ship_engine_clone = clone $ship_engine;

        if($ship_engine_clone->count()) {
            $engine_id = $ship_engine_clone->first()->id;
            $products = Product::whereRaw("FIND_IN_SET({$engine_id}, shipping_providers) > 0")->count();
            if($products) {
                flash(translates('dont_disconnect'))->warning();
                return response()->json([
                    'success' => false,
                    'message' => translates('dont_disconnect'),
                ]);
            }
        }

        if(env('SHIP_ENGINE') == 'live') {
            if($ship_engine->count()) {
                $url = "https://api.shipengine.com/v1/connections/carriers/{$ship_engine->carrier_name}/{$ship_engine->carrier_id}";
                $curl_res = ShipEnginecurl($url, 'DELETE'); // here success response not verified

                $ship_engine->delete();
                flash(translates('carrer_disconnected'))->success();
                return response()->json([
                    'success' => true,
                    'message' => translates('carrer_disconnected'),
                ]);
            } else {
                flash(translates('not_able_to_disconnect'))->warning();
                return response()->json([
                    'success' => false,
                    'message' => translates('not_able_to_disconnect'),
                ]);
            }
        } else {
            if($ship_engine->count()) {
                $ship_engine->delete();

                flash(translates('carrer_disconnected'))->success();
                return response()->json([
                    'success' => true,
                    'message' => translates('carrer_disconnected'),
                ]);
            } else {
                flash(translates('not_able_to_disconnect'))->warning();
                return response()->json([
                    'success' => false,
                    'message' => translates('not_able_to_disconnect'),
                ]);
            }
        }
    }

     /**
     * Show lowest price for shipping on product details page
     *
     * @return \Illuminate\Http\Response
     */
    public function get_ship_price(Request $request) {
        ['carrier_id' => $carrier_id, 'product_user_id' => $product_user_id, 'product_id' => $product_id] = $request->all();

        $product = Product::find($product_id);
        $product_user = User::find($product_user_id);
        $user = \Auth::user();

        if(!\Auth::check() || !$product_user->addresses->count() || !$user->addresses->count()) {
            return ['status' => false, 'message' => 'user or address not found'];
        }

        $product_user_address = $product_user->addresses->where('set_default', 1)->first();
        $user_address = $user->addresses->where('set_default', 1)->first();

        $req_data = [
            'ship_quantity' => 1,
            'from_zip_code' => $user_address->postal_code,
            'product_id' => $product_id,
            'user_id' => $user->id,
            'carrier_id' => $carrier_id,
        ];

        $curl_res = ship_estimate($req_data);

        $return_data = [];
        if(isset($curl_res['errors']) && count($curl_res['errors'])) {
            return [
                'status' => false,
                'message' => $curl_res['errors'][0]['message'],
                'shipping_amount' => '',
                'delivery_days' => ''
            ];
        }

        foreach($curl_res['data'] as $key => $val) {
            if(isset($val['error_messages']) && count($val['error_messages'])) {
                return [
                    'status' => false,
                    'message' => $val['error_messages'][0],
                    'shipping_amount' => '',
                    'delivery_days' => ''
                ];
                break;
            }

            $return_data[$key]['shipping_amount'] = $val['shipping_amount']['amount'];
            $return_data[$key]['original_shipping_amount'] = $val['shipping_amount']['original_amount'];
            $return_data[$key]['delivery_days'] = $val['delivery_days'];
            break;
        }

        return [
            'status' => true,
            'message' => 'success',
            'shipping_amount' => $return_data[0]['shipping_amount'],
            'original_shipping_amount' => $return_data[0]['original_shipping_amount'],
            'delivery_days' => date('D, F Y', strtotime("+{$return_data[0]['delivery_days']} days"))
        ];
    }

    /**
     * Show lowest price for shipping on product details page
     *
     * @return \Illuminate\Http\Response
     */
    public function show_all_prices(Request $request) {
        $req_data = $request->segment(1) == 'api' ? $request->all() : $request->data;

        return ship_estimate($req_data);
    }

    /**
     * Show dropdown and fetch rates from shipengine v1/rates api
     *
     * @return \Illuminate\Http\Response
     */
    public function get_ship_estimate(Request $request) {
        $carts = Cart::with(['product', 'user', 'user.addresses'])->where('user_id', Auth::id())->select(['owner_id', 'product_id', 'quantity', 'id', 'user_id'])->get();

        $shipengineData = [];
        if(Auth::check()) {
            foreach($carts as $key => $cartItem) {
                $user_address = $cartItem->user->addresses;
                $product = $cartItem->product;
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
                    ];

                    $ship_estimate = ship_estimate($req_data);

                    if($ship_estimate['status']) {
                        $estimate_data = $ship_estimate['data'];
                        $lowest_price = $estimate_data[0]['shipping_amount']['original_amount'];
                        $service_type = $estimate_data[0]['service_code'];
                        $package_type = $estimate_data[0]['package_type'];
                        $rate_id = $estimate_data[0]['rate_id'];

                        $shipengineData[$key]['id'] = $cartItem->id;
                        $shipengineData[$key]['product_id'] = $cartItem->product_id;
                        $shipengineData[$key]['shipengine'] = true;
                        $shipengineData[$key]['estimate_data'] = $estimate_data;

                        $original_cart = Cart::where('id', $cartItem->id)->first();

                        // update the cart 
                        if(!$original_cart->service_type || $original_cart->service_type == '' || $original_cart->shipping_type != 'ship_engine') {
                            $original_cart->update(['shipping_cost' => $lowest_price, 'shipping_type' => 'ship_engine', 'service_type' => $service_type, 'package_type' => $package_type, 'rate_id' => $rate_id]);
                        }
                    }
                } else {
                    $product = \App\Product::find($cartItem->product_id);

                    $cartItem->shipping_cost = 0;
                    $cartItem->shipping_type = '';
                    $cartItem->package_type = '';
                    $cartItem->shipping_type = 'home_delivery';
                    $cartItem->shipping_cost = $product->shipping_cost ?? 0;

                    if($product->is_quantity_multiplied == 1 && get_setting('shipping_type') == 'product_wise_shipping') {
                        $cartItem->shipping_cost =  $cartItem->shipping_cost * $cartItem->quantity;
                    }
                    $cartItem->save();

                    $shipengineData[$key]['id'] = $cartItem->id;
                    $shipengineData[$key]['product_id'] = $cartItem->product_id;
                    $shipengineData[$key]['shipengine'] = false;
                    $shipengineData[$key]['estimate_data'] = [];
                }
            }
        }
        
        return [
            'status' => true,
            'shipengineData' => (array) $shipengineData,
            'carts' => get_cart_return_data(),
        ];
    }

    /**
     * Save tracking number which is getting from merchant for manual submit
     *
     * @return \Illuminate\Http\Response
     */
    public function manual_tracking_number(Request $request) {
        $req_data = $request->data;
        $order_detail = OrderDetail::findOrFail($req_data['order_detail_id']);
        $order_detail->tracking_number = $req_data['tracking_number'];
        $order_detail->carrier_name = $req_data['carrier_name'];
        $order_detail->tracking_type = 'manual';
        $order_detail->label_download = 'https://www.stamps.com/tracking/'; // currently save stamps url
        $order_detail->save();

        return [
            'status' => true,
            'messages' => translates('tracking_number_updated'),
        ];
    }

    /**
     * Get user and seller addresses for the particular order
     *
     * @return \Illuminate\Http\Response
     */
    public function get_shipping_address(Request $request) {
        $order_detail = OrderDetail::findOrFail($request->order_detail_id);
        $user_address = $order_detail->order->user->addresses->where('set_default', '1')->first();
        $merchant_address = Auth::user()->addresses->where('set_default', '1')->first();

        return [
            'status' => true,
            'user_address' => $user_address,
            'merchant_address' => $merchant_address,
        ];
    }

    /**
     * Show lowest price for shipping on product details page
     *
     * @return \Illuminate\Http\Response
     */
    public function get_ship_estimate_for_merchant(Request $request) {
        $req_data = $request->data;
        $order_detail = OrderDetail::where('id', $req_data['order_detail_id'])->first();
        $user_address = Address::where(['user_id' => $order_detail->seller_id, 'set_default' => 1])->first();
        $shipengine_found = UserShipEngineSettings::where('user_id', $order_detail->seller_id)->first();

        $package_type = $order_detail->package_type;
        $service_code = $order_detail->service_code;

        $ship_data = [
            'ship_quantity' => 1,
            'from_zip_code' => $user_address->postal_code,
            'product_id' => $order_detail->product_id,
            'user_id' => $order_detail->order->user_id,
            'carrier_id' => $shipengine_found->carrier_id,
            'count' => $req_data['count'],
            'package_data' => $req_data,
        ];

        $curl_res = (object) ship_estimate($ship_data);

        if(!$curl_res->status) {
            return [
                'status' => false,
                'data' => $curl_res->message,
            ];
        }

        $res = clone ($curl_res);
        $collection_data = collect($curl_res->data);
        $collection_data = $collection_data->where('service_code', $service_code)->where('package_type', $package_type);

        if(count($collection_data)) {
            $collection_data = $collection_data->first();
        } else {
            $collection_data = collect($res->data)->first();
        }

        return [
            'status' => true,
            'data' => $collection_data,
        ];
    }

    /**
     * Create Ship engine label
     *
     * @return \Illuminate\Http\Response
     */
    public function create_ship_engine_label(Request $request) {
        $req_data = $request->data;

        try {
            $order_detail = OrderDetail::where('id', $req_data['order_detail_id'])->first();
            $shipengine_found = UserShipEngineSettings::where('user_id', $order_detail->seller_id)->first();
            $user = User::find($order_detail->order->user_id);
            $seller = User::find($order_detail->seller_id);
            $user_address = $user->addresses->where('set_default', 1)->first();
            $seller_address = $seller->addresses->where('set_default', 1)->first();

            logger('ids-'.$order_detail->order->user_id .' - '. $order_detail->seller_id);
            logger('user-'.json_encode($user));
            logger('seller-'.json_encode($seller));
            logger('user_address-'.json_encode($user_address));
            logger('seller_address-'.json_encode($seller_address));

            $ship_detail1['customs_items']['quantity'] = (int) $order_detail->quantity;
            $ship_detail1['customs_items']['description'] = 'sample';

            $ship_detail['shipments']['validate_address'] = 'no_validation';
            $ship_detail['shipments']['carrier_id'] = $shipengine_found->carrier_id;
            $ship_detail['shipments']['service_code'] = $req_data['service_code'];

            $ship_detail['shipments']['ship_to']['name']           = $user->name;
            $ship_detail['shipments']['ship_to']['company_name']   = $user->name;
            $ship_detail['shipments']['ship_to']['phone']          = $user_address->phone;
            $ship_detail['shipments']['ship_to']['address_line1']  = $user_address->address;
            $ship_detail['shipments']['ship_to']['city_locality']  = $user_address->city;
            $ship_detail['shipments']['ship_to']['state_province'] = \DB::table('states')->where('name', $user_address->state)->first()->short_name;
            $ship_detail['shipments']['ship_to']['postal_code']    = $user_address->postal_code;
            $ship_detail['shipments']['ship_to']['country_code']   = \DB::table('countries')->where('name', $user_address->country)->first()->code;

            $ship_detail['shipments']['ship_from']['name']           = $seller->name;
            $ship_detail['shipments']['ship_from']['company_name']   = $seller->name;
            $ship_detail['shipments']['ship_from']['phone']          = $seller_address->phone;
            $ship_detail['shipments']['ship_from']['address_line1']  = $seller_address->address;
            $ship_detail['shipments']['ship_from']['city_locality']  = $seller_address->city;
            $ship_detail['shipments']['ship_from']['state_province'] = \DB::table('states')->where('name', $seller_address->state)->first()->short_name;
            $ship_detail['shipments']['ship_from']['postal_code']    = explode('-', $seller_address->postal_code)[0];
            $ship_detail['shipments']['ship_from']['country_code']   = \DB::table('countries')->where('name', $seller_address->country)->first()->code;

            $ship_detail['shipments']['customs']['contents']  = "documents";
            $ship_detail['shipments']['customs']['customs_items'][] = $ship_detail1['customs_items'];
            $ship_detail['shipments']['customs']['non_delivery'] = "treat_as_abandoned";

            for($i=0; $i< $req_data['count']; $i++){
                $ship_detail['shipments']['packages'][$i]['weight']['value']= $req_data['package_weight'][$i];
                $ship_detail['shipments']['packages'][$i]['weight']['unit']= $req_data['package_unit'][$i];
                $ship_detail['shipments']['packages'][$i]['dimensions']['unit']= $req_data['dimension_unit'][$i];
                $ship_detail['shipments']['packages'][$i]['dimensions']['length']= $req_data['package_length'][$i];
                $ship_detail['shipments']['packages'][$i]['dimensions']['width']= $req_data['dimension_width'][$i];
                $ship_detail['shipments']['packages'][$i]['dimensions']['height']= $req_data['dimension_height'][$i];          
            }

            $ship_detail_s[1]['shipments'][] = $ship_detail['shipments'];
            $url = 'https://api.shipengine.com/v1/shipments';
            $curl_res = ShipEnginecurl($url, $ship_detail_s[1], 'POST');
            logger('create_ship_engine_label-'.json_encode($curl_res));

            if(isset($curl_res['has_errors']) && $curl_res['has_errors']) {
                return [
                    'status' => false,
                    'message' => translate('something_went_wrong'),
                ];
            } else if(isset($curl_res['errors']) && count($curl_res['errors'])){
                return [
                    'status' => false,
                    'message' => translate('something_went_wrong'),
                ];
            } else {
                $shipment_id = $curl_res['shipments'][0]['shipment_id'];

                $ship_detail['label_format'] = "pdf";
                $url = 'https://api.shipengine.com/v1/labels/shipment/'.$shipment_id;
                $curl_res = ShipEnginecurl($url, $ship_detail, 'POST');
                logger('create_ship_engine_label2-'.json_encode($curl_res));

                if(isset($curl_res['status']) && $curl_res['status'] == 'completed') {
                    $tracking_number = $curl_res['tracking_number'];
                    $label_id = $curl_res['label_id'];
                    $label_download = $curl_res['label_download']['pdf'];

                    $order_detail->tracking_number = $tracking_number;
                    $order_detail->label_id = $label_id;
                    $order_detail->label_download = $label_download;
                    $order_detail->tracking_type = 'ship_engine';
                    $order_detail->carrier_name = $req_data['carrier_name'];
                    $order_detail->save();

                    return [
                        'status' => true,
                        'message' => translates('tracking_number_updated'),
                    ];
                }

                return [
                    'status' => false,
                    'message' => translate('something_went_wrong'),
                ];
            }
        } catch(\Exception $e) {
            logger('create label error-'. $e->getMessage());
            return [
                'status' => false,
                'message' => translate('something_went_wrong'),
            ];
        }
    }
}
