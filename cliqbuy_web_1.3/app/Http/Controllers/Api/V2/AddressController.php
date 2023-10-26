<?php

namespace App\Http\Controllers\Api\V2;

use App\City;
use App\State;
use App\Country;
use App\Http\Resources\V2\AddressCollection;
use App\Address;
use App\Http\Resources\V2\CitiesCollection;
use App\Http\Resources\V2\StatesCollection;

use App\Http\Resources\V2\CountriesCollection;
use App\Http\Resources\V2\CountriesDataCollection;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\PickupPoint;
use App\Models\UserShipEngineSettings;
use Validator;

class AddressController extends Controller
{
    public function addresses($id)
    {
        return new AddressCollection(Address::where('user_id', $id)->get());
    }

    public function createShippingAddress(Request $request)
    {
        $rules = array(
            'user_id' => 'required|numeric',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'postal_code' => 'required',
            'phone' => 'required',
        );

        $validation = Validator::make($request->all(), $rules);
        if($validation->fails()) {
            return response()->json([
                'result' => false,
                'message' => trans('messages.api.mandatory_fields_missing'),
                'errors' => $validation->messages(),
            ]);
        }

        if($request->is_validate && get_setting('ship_engine')) {
            return $this->validate_address_from_shipengine($request);
        } else {
            $address_count = \DB::table('addresses')->where('user_id',$request->user_id)->count();

            $address = new Address;
            $address->user_id = $request->user_id;
            $address->address = $request->address;
            $address->set_default = $address_count ? 0 : 1;
            $address->country = ucwords(strtolower($request->country));
            $address->city = ucwords(strtolower($request->city));
            $address->state = ucwords(strtolower($request->state));
            $address->postal_code = $request->postal_code;
            $address->phone = $request->phone;
            $address->save();

            return response()->json([
                'result' => true,
                'ship_engine' => false,
                'message' => trans('messages.api.shipping_add')
            ]);
        }
    }

    public function updateShippingAddress(Request $request)
    {
        if($request->is_validate && get_setting('ship_engine')) {
            return $this->validate_address_from_shipengine($request);
        } else {
            $address = Address::find($request->id);
            $address->address = $request->address;
            $address->country = $request->country;
            $address->city = $request->city;
            $address->state = $request->state;
            $address->postal_code = $request->postal_code;
            $address->phone = $request->phone;
            $address->save();

            return response()->json([
                'result' => true,
                'message' => trans('messages.api.shipping_information_updated')
            ]);
        }
    }

    public function validate_address_from_shipengine($request) {
        try {
            $post_data = [
                [
                    "address_line1" => $request->address,
                    "city_locality" => $request->city,
                    "state_province" => \DB::table('states')->where('name', $request->state)->first()->short_name,
                    "postal_code" => $request->postal_code,
                    "country_code" => \DB::table('countries')->where('name', $request->country)->first()->code
                ]
            ];

            $url = 'https://api.shipengine.com/v1/addresses/validate';
            $curl_res = ShipEnginecurl($url, $post_data, 'POST');
            logger('address validate api res - '. json_encode($curl_res));

            $curl_res = $curl_res[0];
            $res_status = $curl_res['status'];

            $result = false;
            $message = translates('invalid_address');
            $original_address = (object) [];
            $matched_address = (object) [];

            if($res_status == 'verified') {
                $result = true;
                $message = translates('address_verified');
                $original_address = $curl_res['original_address'];
                $matched_address = $curl_res['matched_address'];

                $original_address['country_code'] = \DB::table('countries')->where('code', $original_address['country_code'])->first()->name;
                $original_address['state_province'] = \DB::table('states')->where('short_name', $original_address['state_province'])->first()->name;
                $original_address['phone'] = $request->phone;
                $matched_address['country_code'] = \DB::table('countries')->where('code', $matched_address['country_code'])->first()->name;
                $matched_address['state_province'] = \DB::table('states')->where('short_name', $matched_address['state_province'])->first()->name;
                $matched_address['phone'] = $request->phone;
            }

            return [
                'result' => $result,
                'ship_engine' => true,
                'message' => $message,
                'original_address' => $original_address,
                'matched_address' => $matched_address
            ];
        } catch(\Exception $e) {
            return response()->json([
                'result' => false,
                'ship_engine' => true,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function updateShippingAddressLocation(Request $request)
    {
        $address = Address::find($request->id);
        $address->latitude = $request->latitude;
        $address->longitude = $request->longitude;
        $address->save();

        return response()->json([
            'result' => true,
            'message' => trans('messages.api.shipping_map')
        ]);
    }


    public function deleteShippingAddress(Request $request, $id)
    {
        $address_count = Address::where(['user_id' => $request->user_id, 'set_default' => 1, 'id' => $id])->count();
        if($address_count) {
            return response()->json([
                'result' => false,
                'message' => trans('messages.api.cannot_able_to_delete_default_address')
            ]);
        }

        $address = Address::find($id);
        $address->delete();
        return response()->json([
            'result' => true,
            'message' => trans('messages.api.shipping_delete')
        ]);
    }

    public function makeShippingAddressDefault(Request $request)
    {
        Address::where('user_id', $request->user_id)->update(['set_default' => 0]); //make all user addressed non default first

        $address = Address::find($request->id);
        $address->set_default = 1;
        $address->save();
        return response()->json([
            'result' => true,
            'message' => trans('messages.api.default_shipping_updated')
        ]);
    }

    public function updateAddressInCart(Request $request)
    {
        $pickup_points = PickupPoint::where('id',$request->pickup_point_id)->where('staff_id',$request->staff_id)->first();
        $shipeng_count = Cart::where(['user_id' => $request->user_id, 'shipping_type' => 'ship_engine'])->count();
        
        if ($pickup_points && !$shipeng_count) {
            Cart::where('user_id', $request->user_id)->update(['shipping_type' => 'pickup_point','pickup_point' => $pickup_points->id]);
        }
        else{
            if(!$shipeng_count) {
                Cart::where('user_id', $request->user_id)->update(['shipping_type' => '','pickup_point' => $request->pickup_point_id ]);
            }
        }
        try {
            Cart::where('user_id', $request->user_id)->update(['address_id' => $request->address_id]);

        } catch (\Exception $e) {
            return response()->json([
                'result' => false,
                'message' => trans('messages.api.could_not_save_address')
            ]);
        }

        // check if ship engines is enabled for each product
        $owner_ids = Cart::where('user_id', $request->user_id)->pluck('owner_id')->toArray();
        $shipengine_enabled = false;

        if(get_setting('ship_engine')) {
            $ship_engine_hold = UserShipEngineSettings::whereIn('user_id', $owner_ids)->count();
            if($ship_engine_hold > 0) {
                $shipengine_enabled = true;
            }
        }

        return response()->json([
            'result' => true,
            'message' => trans('messages.api.address_saved'),
            'shipengine_enabled' => $shipengine_enabled
        ]);
    }

    public function getCities()
    {
        $state_id = request()->state_id;
        if ($state_id) 
        {
            $city = City::where('state_id',$state_id)->get();
        }
        else
        {
            $city = City::all();
        }
        return new CitiesCollection($city);
    }

    public function getStates()
    {
        $country_id = request()->country_id;
        if ($country_id) 
        {
            $state = State::where('country_id',$country_id)->where('status',1)->get();
        }
        else
        {
            $state = State::all();
        }
        return new StatesCollection($state);
    }

    public function getCountries()
    {
        return new CountriesCollection(Country::where('status', 1)->get());
    }

    public function getCountriesData()
    {
        return new CountriesDataCollection(Country::where('status', 1)->get());
    }
}
