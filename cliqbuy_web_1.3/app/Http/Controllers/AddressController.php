<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Address;
use App\State;
use App\City;
use App\Country;
use Auth;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $address = new Address;
        if($request->has('customer_id')){
            $address->user_id = $request->customer_id;
        }
        else{
            $address->user_id = Auth::user()->id;
        }

        $address_count = \DB::table('addresses')->where('user_id',$address->user_id)->count();

        $address->address = $request->address;
        $address->set_default = $address_count > 0 ? 0:1;
        $address->country = Country::find($request->country)->name;
        $address->city = City::find($request->city)->name;
        $address->state = State::find($request->state_id)->name;
        $address->longitude = $request->longitude;
        $address->latitude = $request->latitude;
        $address->postal_code = $request->postal_code;
        $address->phone = $request->phone;
        $address->save();

        flash(translate('address_added'))->success();
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['address_data'] = Address::findOrFail($id);
        
        $returnHTML = view('frontend.user.address.edit_address_modal', $data)->render();
        return response()->json(array('data' => $data, 'html'=>$returnHTML));
//        return ;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $address = Address::findOrFail($id);
        $address->address = $request->address;
        $address->country = Country::find($request->country)->name;
        $address->state = State::find($request->state_id)->name;
        $address->city = City::find($request->city)->name;
        $address->longitude = $request->longitude;
        $address->latitude = $request->latitude;
        $address->postal_code = $request->postal_code;
        $address->phone = $request->phone;
        $address->save();

        flash(translate('address_updated'))->warning();
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $address = Address::findOrFail($id);
        if(!$address->set_default){
            flash(translate('address_deleted'))->warning();
            $address->delete();
            return back();
        }
        flash(translate('cant_delete_def_address'))->warning();
        return back();
    }

    public function set_default($id){
        foreach (Auth::user()->addresses as $key => $address) {
            $address->set_default = 0;
            $address->save();
        }
        $address = Address::findOrFail($id);
        $address->set_default = 1;
        $address->save();

        return back();
    }

    public function final_address_save(Request $request){
        $data = $request['data'];
        $selected_value = isset($data['selected_value']) ? $data['selected_value'] : 'original_address';
        $selected_address = $data[$selected_value];

        try {
            \DB::beginTransaction();
            
            if($data['address_id'] != '') {
                $address = Address::findOrFail($data['address_id']);
                $get_address = $this->address_records($selected_address);

                $address->address = $get_address['address_line1'];
                $address->country = $get_address['country_name'];
                $address->city = $get_address['city_name'];
                $address->state = $get_address['state_name'];
                $address->postal_code = $get_address['postal_code'];
                $address->phone = $data['phone'];
                $address->save();

                $message = translate('address_updated');
            } else {
                $address = new Address;
                $address->user_id = Auth::user()->id;

                $get_address = $this->address_records($selected_address);
                $address->address = $get_address['address_line1'];
                $address->set_default = $get_address['address_count'] > 0 ? 0 : 1;
                $address->country = $get_address['country_name'];
                $address->city = $get_address['city_name'];
                $address->state = $get_address['state_name'];
                $address->postal_code = $get_address['postal_code'];
                $address->phone = $data['phone'];
                $address->save();

                $message = translate('address_added');
            }

            \DB::commit();
            flash($message)->success();
            
            return [
                'status' => true,
                'address' => $address
            ];
        } catch (\Exception $e) {
            \DB::rollback();

            flash(translate('something_went_wrong'))->warning();
            logger('address_err - '. $e->getMessage());
            return [
                'status' => false,
                'address' => $e->getMessage()
            ];
        }
    }

    public function address_records($address) {
        $data['address_count'] = \DB::table('addresses')->where('user_id', \Auth::id())->count();
        
        $country_found = \DB::table('countries')->where('code', $address['country_code']);
        $data['country_name'] = $country_found->count() ? $country_found->first()->name : $address['country_code'];

        $city_found = \DB::table('cities')->where('name', $address['city_locality']);
        $data['city_name'] = $city_found->count() ? $city_found->first()->name : $address['city_locality'];

        $state_found = \DB::table('states')->where('short_name', $address['state_province']);
        $data['state_name'] = $state_found->count() ? $state_found->first()->name : $address['state_province'];

        $data['address_line1'] = $address['address_line1'];
        $data['postal_code'] = $address['postal_code'];
        return $data;
    }
}
