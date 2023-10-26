<?php

namespace App\Http\Resources\V2;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PurchaseHistoryCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($data) {
                $pickup_point = \DB::table('pickup_points')->where('id',$data->orderDetails->first()->pickup_point_id)->first();
                
                $pickup_point_name = '';
                $pickup_point_address = '';
                $pickup_point_phone = '';
                $shipping_cost = format_price(convert_price($data->orderDetails->sum('shipping_cost')));
                if ($pickup_point) {
                    $pickup_point_name = $pickup_point->name;
                    $pickup_point_address = $pickup_point->address;
                    $pickup_point_phone = $pickup_point->phone;
                    $shipping_cost = "0.00" . currency_symbol();
                }
                $shipping_address = json_decode($data->shipping_address);
                if (json_decode($data->shipping_address) == []) {
                    $shipping_address = [
                        "name" => "",
                        "email" => "",
                        "address" => "",
                        "country" => "",
                        "city" => "",
                        "postal_code" => "",
                        "phone" => ""
                    ];
                }
                if ($data->grand_total < 1) {
                    $data->grand_total = 0.00;
                }

                logger('shipping_type'. $data->shipping_type);
                return [
                    'id' => $data->id,
                    'code' => $data->code,
                    'user_id' => (int) $data->user_id,
                    'shipping_address' => $shipping_address,
                    'payment_type' => trans('messages.api.'.ucwords(str_replace('_', ' ', $data->payment_type))),
                    'shipping_type' => $data->orderDetails->first()->shipping_type == null ? 'Home Delivery' : 'Pickup Point',
                    'shipping_type_string' => $data->orderDetails->first()->shipping_type != null ? ucwords(str_replace('_', ' ', $data->orderDetails->first()->shipping_type)) : "",
                    'payment_status' => $data->payment_status,
                    'payment_status_string' => ucwords(str_replace('_', ' ', $data->payment_status)) == 'Paid' ? trans('messages.api.paid') : trans('messages.api.unpaid'),
                    'delivery_status' => $data->delivery_status,
                    'delivery_status_string' => $data->delivery_status == 'pending'? trans('messages.api.order_placed') : trans('messages.api.'.$data->delivery_status),
                    'grand_total' => format_price(convert_price($data->grand_total)),
                    'coupon_discount' => format_price(convert_price($data->coupon_discount)),
                    'shipping_cost' => $shipping_cost,
                    'subtotal' => format_price(convert_price($data->orderDetails->sum('price'))),
                    'tax' => format_price(convert_price($data->orderDetails->sum('tax'))),
                    'shipping_type' => $data->orderDetails->first()->shipping_type,
                    'pickup_point_name' => $pickup_point_name,
                    'pickup_point_address' => $pickup_point_address,
                    'pickup_point_phone' => $pickup_point_phone,
                    'date' => Carbon::createFromTimestamp($data->date)->format('d-m-Y'),
                    'cancel_request' => $data->cancel_request == 1,
                    'tracking_number' => $data->orderDetails->first()->tracking_number ?? '',
                    'label_download' => $data->orderDetails->first()->label_download ?? '',
                    'links' => [
                        'details' => route('purchaseHistory.details', $data->id)
                    ]
                ];
            })
        ];
    }

    public function with($request)
    {
        return [
            'success' => true,
            'status' => 200
        ];
    }
}
