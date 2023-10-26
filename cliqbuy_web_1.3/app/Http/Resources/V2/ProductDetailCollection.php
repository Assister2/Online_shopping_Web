<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\User;
use App\Models\Review;
use App\Models\Attribute;
use App\Models\UserShipEngineSettings;


class ProductDetailCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($data) {
                $precision = 2;
                $calculable_price = homeDiscountedBasePrice($data);
                $calculable_price = number_format($calculable_price, $precision, '.', '');
                $calculable_price = floatval($calculable_price);
                // $calculable_price = round($calculable_price, 2);
                $photo_paths = get_images_path($data->photos);

                $photos = [];

                if (!empty($photo_paths)) {
                    for ($i = 0; $i < count($photo_paths); $i++) {
                        if ($photo_paths[$i] != "" ) {
                            $item = array();
                            $item['variant'] = "";
                            $item['path'] = $photo_paths[$i];
                            $photos[]= $item;
                        }

                    }

                }
                foreach ($data->stocks as $stockItem){
                    if($stockItem->image != null && $stockItem->image != ""){
                        $item = array();
                        $item['variant'] = $stockItem->variant;
                        $item['path'] = api_asset($stockItem->image) ;
                        $photos[]= $item;
                    }
                }

                $string_data = str_replace("&nbsp;", "", $data->description);
                $description = strip_tags($string_data);

                $is_show_stock_qty = false;
                $is_show_stock_with_text = false;
                $is_hide_stock = false;

                if ($data->stock_visibility_state == 'text') 
                {
                    $is_show_stock_with_text = true;
                }
                elseif($data->stock_visibility_state == 'hide')
                {
                    $is_hide_stock = true;
                }
                elseif($data->stock_visibility_state == 'quantity')
                {
                    $is_show_stock_qty = true;
                }

                $user_address_count = $merchant_address_count = $ship_engine_hold = 0;
                if(get_setting('ship_engine') && request()->user_id) {
                    $ship_engine_hold = UserShipEngineSettings::where('user_id', $data->user_id)->count();
                    $user = User::find(request()->user_id);
                    $user_address_count = $user->addresses->count();
                    $merchant_address_count = $data->user->addresses->count();

                    // get lowest price from ship engine
                    $shipping_lowest_price = '';
                    $shipping_lowest_original_price = 0;
                    $delivery_days = 0;
                    if($user_address_count && $merchant_address_count) {
                        $from_zip_code = $user->addresses->where('set_default', '1')->first()->postal_code;
                        $req_data = [
                            'ship_quantity' => 1,
                            'from_zip_code' => $from_zip_code,
                            'product_id' => $data->id,
                            'user_id' => request()->user_id,
                        ];

                        $ship_estimate = ship_estimate($req_data);

                        if($ship_estimate['status']) {
                            $estimate_data = $ship_estimate['data'];
                            $shipping_lowest_price = $estimate_data[0]['shipping_amount']['amount'];
                            $shipping_lowest_original_price = $estimate_data[0]['shipping_amount']['original_amount'];
                            $delivery_days = $estimate_data[0]['delivery_days'];
                        }
                    }
                    
                }

                return [
                    'id' => (integer)$data->id,
                    'name' => $data->name,
                    'added_by' => $data->added_by,
                    'seller_id' => $data->user->id,
                    'shop_id' => $data->added_by == 'admin' ? 0 : $data->user->shop->id,
                    'minimum_purchase_qty' => $data->min_qty,
                    'shop_name' => $data->added_by == 'admin' ? 'In House Product' : $data->user->shop->name,
                    'shop_logo' => $data->added_by == 'admin' ? api_asset(get_setting('header_logo')) : api_asset($data->user->shop->logo),
                    'is_show_stock_qty' => $is_show_stock_qty,
                    'is_show_stock_with_text' => $is_show_stock_with_text,
                    'is_hide_stock' => $is_hide_stock,
                    'photos' => $photos,
                    'thumbnail_image' => api_asset($data->thumbnail_img),
                    'tags' => explode(',', $data->tags),
                    'price_high_low' => (double)explode('-', homeDiscountedPrice($data))[0] == (double)explode('-', homeDiscountedPrice($data))[1] ? format_price((double)explode('-', homeDiscountedPrice($data))[0]) : "From " . format_price((double)explode('-', homeDiscountedPrice($data))[0]) . " to " . format_price((double)explode('-', homeDiscountedPrice($data))[1]),
                    'choice_options' => $this->convertToChoiceOptions(json_decode($data->choice_options)),
                    'colors' => json_decode($data->colors),
                    'has_discount' => homeBasePrice($data) != homeDiscountedBasePrice($data),
                    'stroked_price' => api_home_base_price($data),
                    'main_price' => api_home_discounted_base_price($data),
                    'calculable_price' => $calculable_price,
                    'currency_symbol' => currency_symbol(),
                    'current_stock' => (integer)$data->stocks->first()->qty,
                    'unit' => $data->unit,
                    'rating' => (double)$data->rating,
                    'rating_count' => (integer)Review::where(['product_id' => $data->id])->count(),
                    'earn_point' => (double)$data->earn_point,
                    'description' => $description ?? "",
                    'pdf_url' => uploaded_asset($data->pdf) ?? "",
                    'shipping_type' => (string) $data->shipping_type == 'shipping_providers' ? true : false,
                    'merchant_ship_engine_hold' => $ship_engine_hold ? true : false,
                    'user_address_count' => $user_address_count ? true : false,
                    'merchant_address_count' => $merchant_address_count ? true : false,
                    'shipengine_enabled' => get_setting('ship_engine'),
                    'shipengine_image' => static_asset('img/stamps_com.png'),
                    'shipping_lowest_price' => $shipping_lowest_price ?? 0.00,
                    'shipping_lowest_original_price' => $shipping_lowest_original_price ?? 0.00,
                    'delivery_days' => $delivery_days ?? 0,
                    'link' => route('product', $data->slug)
                ];
            })
        ];
    }

    public function with($request)
    {
        return [
            'success' => true,
            'status' => 200,
            'is_coupon_enabled' => get_setting('coupon_system')==1 ? true : false,
            'is_chat_enabled' => get_setting('conversation_system')==1 ? true : false,
        ];
    }

    protected function convertToChoiceOptions($data)
    {
        $result = array();
//        if($data) {
        foreach ($data as $key => $choice) {
            $item['name'] = $choice->attribute_id;
            $item['title'] = Attribute::find($choice->attribute_id)->name;
            $item['options'] = $choice->values;
            array_push($result, $item);
        }
//        }
        return $result;
    }

    protected function convertPhotos($data)
    {
        $result = array();
        foreach ($data as $key => $item) {
            array_push($result, api_asset($item));
        }
        return $result;
    }
}
