<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Resources\V2\PickupCollection;
use Illuminate\Http\Request;
use App\PickupPoint;
use App\PickupPointTranslation;
use Validator;

class PickuppointController extends Controller
{

    public function pickup_point(Request $request)
    {
        $pickup_point_value = \DB::table('business_settings')->where('type', 'pickup_point')->first();
        $cartItem = \DB::table('carts')->where('user_id',$request->user_id)->groupBy('owner_id')->count();
        $shop = \DB::table('shops')->where('user_id',$request->seller_id)
                    ->selectRaw('SUBSTR(REPLACE(pick_up_point_id, \'"\', \'\'), 2) AS pick_up_point_id')->first();

        if ($pickup_point_value) {
            $pickup_point_value = $pickup_point_value->value == 1;
        } else{
            $pickup_point_value=false;
        }

        if ($shop) {
            $shop = $shop->pick_up_point_id;
        }
        
        $pickup_points=[];
        if ($pickup_point_value && $shop) {
            $shop = rtrim($shop, ']');
            $shop = explode(',', $shop);
            $pickup_points = PickupPoint::where('pick_up_status',1)->whereIn('id', $shop)->orderBy('created_at', 'desc');
            $pickup_points = $pickup_points->get();
        } elseif($pickup_point_value){
            $pickup_points = PickupPoint::where('pick_up_status',1)->orderBy('created_at', 'desc');
            $pickup_points = $pickup_points->get();
        }
        return new PickupCollection($pickup_points);
    }

    public function pickup_points_store(Request $request)
    {
        $rules = array(
            'name'   => 'required',
            'address'   => 'required',
            'phone'         => 'required|min:6',
            'staff_id'   => 'required',
            
        );
        $attributes = array(
            'name'         => 'Name',                      
            'address'   => 'Location',
            'phone'   => 'Phone',
            'staff_id'   => 'Pick-up Point Manager',
        );

        $validator = Validator::make($request->all(), $rules, [], $attributes);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $pickup_point = new PickupPoint;
        $pickup_point->name = $request->name;
        $pickup_point->address = $request->address;
        $pickup_point->phone = $request->phone;
        $pickup_point->pick_up_status = $request->pick_up_status;
        $pickup_point->staff_id = $request->staff_id;
        $pickup_point->save();

        return response()->json([
            'result' => true,
            'message' => trans('messages.api.pickup_point_added')
        ]);
    }

}
