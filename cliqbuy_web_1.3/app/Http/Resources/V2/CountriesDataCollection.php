<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\State;
use App\City;
use App\Address;

class CountriesDataCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function($data) {

                return [
                    'id'      => (int) $data->id,
                    'code' => $data->code,
                    'name' => $data->name,
                    'status' => (int) $data->status,
                    'states' => State::select('id', 'name', 'cost', 'status')->with(['cities' => function($query) { $query->select('id', 'country_id', 'state_id', 'name', 'cost'); }])->where('country_id', $data->id)->get()
                ];
            }),
            'addresses' => Address::where('user_id', ($request->user_id ?? 0))->get(),
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
