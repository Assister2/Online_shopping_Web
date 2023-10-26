<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Shop;
use App\User;
use App\Seller;
use App\Country;
use App\State;
use App\City;
use App\BusinessSetting;
use Auth;
use Hash;
use App\Notifications\EmailVerificationNotification;

class ShopController extends Controller
{

    public function __construct()
    {
        $this->middleware('user', ['only' => ['index']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shop = Auth::user()->shop;
        return view('frontend.user.seller.shop', compact('shop'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // find shop of auth user
        $shop = Shop::where('user_id',@Auth::user()->id)->first();
        if($shop){
            return redirect('/');
        }

        if(Auth::check() && Auth::user()->user_type == 'admin'){
            flash(translate('admin_can_not_be_a_seller'))->error();
            return back();
        } elseif(isSingleStoreActivated()){
            abort(404);
        }
        else{
            return view('frontend.seller_form');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = null;
        if(!Auth::check()){
            if(User::where('email', $request->email)->first() != null){
                flash(translate('email_already_exists'))->error();
                return back();
            }

            $shop_already_exists = Shop::where('name', $request->name)->orWhere('phone', $request->phone)->count();
            if($shop_already_exists) {
                flash(translate('shop_name_or_phone_exists'))->success();
                return back();
            }

            if($request->password == $request->password_confirmation){
                $user = new User;
                $user->name = $request->name;
                $user->email = $request->email;
                $user->user_type = "seller";
                $user->password = Hash::make($request->password);
                $user->save();
            }
            else{
                flash(translate('password_did_not_match'))->error();
                return back();
            }
        }
        else{
            $shop_already_exists = Shop::where('name', $request->name)->orWhere('phone', $request->phone)->where('user_id', '!=', Auth::id())->count();
            if($shop_already_exists) {
                flash(translate('shop_name_or_phone_exists'))->success();
                return back();
            }

            $user = Auth::user();
            if($user->customer != null){
                $user->customer->delete();
            }
            $user->user_type = "seller";
            $user->save();
        }

        $seller = new Seller;
        $seller->user_id = $user->id;
        $seller->save();

        if(Shop::where('user_id', $user->id)->first() == null){
            $shop = new Shop;
            $shop->user_id = $user->id;
            $shop->name = $request->name;
            $shop->country = Country::find($request->country)->name;
            $shop->city = City::find($request->city)->name;
            $shop->state = State::find($request->state_id)->name;
            $shop->postal_code = $request->postal_code;
            $shop->address = $request->address;
            $shop->phone = $request->phone;
            $shop->slug = preg_replace('/\s+/', '-', $request->name).'-'.$shop->id;

            if($shop->save()){
                auth()->login($user, false);
                if(BusinessSetting::where('type', 'email_verification')->first()->value != 1){
                    $user->email_verified_at = date('Y-m-d H:m:s');
                    $user->save();
                }
                else {
                    $user->notify(new EmailVerificationNotification());
                }

                flash(translate('your_shop'))->success();
                return redirect()->route('dashboard');
            }
            else{
                $seller->delete();
                $user->user_type == 'customer';
                $user->save();
            }
        }

        flash(translate('something_went_wrong'))->error();
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
        //
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
        $shop = Shop::find($id);

        if($request->has('name') && $request->has('address') && $request->has('country') && $request->has('city') && $request->has('postal_code')){

            $phone_already_exists = Shop::where('phone', $request->phone)->where('id', '!=', $id)->count();
            if($phone_already_exists) {
                flash(translate('phone_already_taken'))->error();
                return back();
            }

            $shop_name_exists = Shop::where('name', $request->name)->where('id', '!=', $id)->count();
            if($shop_name_exists) {
                flash(translate('shop_name_already_taken'))->error();
                return back();
            }

            $shop->name = $request->name;
            if ($request->has('shipping_cost')) {
                $shop->shipping_cost = $request->shipping_cost;
            }
            $shop->address = $request->address;
            $shop->country = Country::find($request->country)->name;
            $shop->city = City::find($request->city)->name;
            $shop->state = State::find($request->state_id)->name;
            $shop->postal_code = $request->postal_code;
            $shop->phone = $request->phone;
            $shop->slug = preg_replace('/\s+/', '-', $request->name).'-'.$shop->id;

            $shop->meta_title = $request->meta_title;
            $shop->meta_description = $request->meta_description;
            $shop->logo = $request->logo;

            if ($request->has('pick_up_point_id')) {
                $shop->pick_up_point_id = json_encode($request->pick_up_point_id);
            }
            else {
                $shop->pick_up_point_id = json_encode(array());
            }
        }

        elseif($request->has('facebook') || $request->has('google') || $request->has('twitter') || $request->has('youtube') || $request->has('instagram')){
            $shop->facebook = $request->facebook;
            $shop->google = $request->google;
            $shop->twitter = $request->twitter;
            $shop->youtube = $request->youtube;
        }

        else{
            $shop->sliders = $request->sliders;
        }

        if ($request->has('sliders')) {
            if ($request->sliders == null) {
                flash(translate('banner_field_is_required'))->error();
                return back();   
            }
        }    

        if($shop->save()){
            flash(translate('your_shop_has_been_updated_successfully'))->success();
            return back();
        }

        flash(translate('something_went_wrong'))->error();
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
        //
    }

    public function verify_form(Request $request)
    {
        if(Auth::user()->seller->verification_info == null){
            $shop = Auth::user()->shop;
            return view('frontend.user.seller.verify_form', compact('shop'));
        }
        else {
            flash(translate('sent_verification_already'))->error();
            return back();
        }
    }

    public function verify_form_store(Request $request)
    {
        $data = array();
        $i = 0;
        foreach (json_decode(BusinessSetting::where('type', 'verification_form')->first()->value) as $key => $element) {
            $item = array();
            if ($element->type == 'text') {
                $item['type'] = 'text';
                $item['label'] = $element->label;
                $item['value'] = $request['element_'.$i];
            }
            elseif ($element->type == 'select' || $element->type == 'radio') {
                $item['type'] = 'select';
                $item['label'] = $element->label;
                $item['value'] = $request['element_'.$i];
            }
            elseif ($element->type == 'multi_select') {
                $item['type'] = 'multi_select';
                $item['label'] = $element->label;
                $item['value'] = json_encode($request['element_'.$i]);
            }
            elseif ($element->type == 'file') {
                $item['type'] = 'file';
                $item['label'] = $element->label;
                $item['value'] = $request['element_'.$i]->store('uploads/verification_form');
            }
            array_push($data, $item);
            $i++;
        }
        $seller = Auth::user()->seller;
        if (env('APP_ENV') =='live') {
            $seller->verification_status = 1;
        }
        $seller->verification_info = json_encode($data);
        if($seller->save()){
            flash(translate('shop_verification_success2'))->success();
            return redirect()->route('dashboard');
        }

        flash(translate('something_went_wrong'))->error();
        return back();
    }
}
