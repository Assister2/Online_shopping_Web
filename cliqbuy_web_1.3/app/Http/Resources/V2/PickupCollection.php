<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PickupCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function($data) {
                return [
                    'id' => (integer) $data->id,
                    'staff_id' => $data->staff_id,
                    'name' => $data->name,
                    'address' => $data->address,
                    'phone' => $data->phone,
                    'pick_up_status' => $data->pick_up_status,
                    'cash_on_pickup_status' => $data->cash_on_pickup_status,
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
