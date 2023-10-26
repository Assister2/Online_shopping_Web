<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Order;
use Hash;
use Auth;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.admin_profile.index');
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
        //
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
    public function update(Request $request, $id, HomeController $home_controller)
    {
        if(env('DEMO_MODE') == 'On'){
            flash(translate('action_not_permitted_demo'))->error();
            return back();
        }

        $email = $request->email;
        if(isUnique($email)) {
            $home_controller->send_email_change_verification_mail($request, $email);
            flash(translate('verification_mail_sent'))->success();
            return back();
        }

        // flash(translate('Email already exists!'))->warning();
        // return back();

        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        if($request->new_password != null && ($request->new_password == $request->confirm_password)){
            $user->password = Hash::make($request->new_password);
        } else if($request->new_password != null) {
            flash("password_and_confirm_not_match")->warning();
            return back();
        }
        $user->avatar_original = $request->avatar;
        if($user->save()){
            flash(translate('your_profile_has_been_updated_successfully'))->success();
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

    public function request_delete_account(Request $request) {
        $ongoing_orders = Order::where('user_id', auth()->user()->id)->where('delivery_status', '!=', 'delivered')->get()->count();
        $past_delivered_orders = Order::where('user_id', auth()->user()->id)->where('delivery_status', 'delivered')->get()->count();

        $data['status'] = 'success';
        if($ongoing_orders > 0) {
            $data['status_code'] = 1;
            $data['status_message'] = translate("ongoing order");
            $data['status_text'] = 'ongoing_orders';
        } else if($past_delivered_orders > 0) {
            $data['status_code'] = 2;
            $data['status_message'] = translate("If you delete");
            $data['status_text'] = 'past_delivered_orders';
        } else {
            $data['status_code'] = 3;
            $data['status_message'] = translate("Are you sure");
            $data['status_text'] = 'no_order_history';
        }
        return response()->json($data, 200);
    }

    public function otp_to_delete(Request $request, HomeController $home_controller) {
        $user = auth()->user();
        $user->otp = rand(1000, 9999);
        $user->save();
        $response = $home_controller->send_otp_to_delete_account($user);
        return response()->json($response, 200);
    }

    public function confirm_otp_to_delete(Request $request, HomeController $home_controller) {
        $user = User::find(auth()->user()->id);
        if($request->otp == $user->otp) {

            \App\Cart::where('user_id', auth()->user()->id)->delete();
            \Auth::logout();
            $user->delete();

            $home_controller->account_deleted_mail($user);
            return response()->json(array(
                'status_code' => 1,
                'status_message' => translate("Account deleted successfully"),
                'status_text' => 'success',
            ), 200);
        } else {
            return response()->json(array(
                'status_code' => 0,
                'status_message' => '<span class="text-danger">'.trans("messages.front_end.otp_is_incorrect").'</span>',
                'status_text' => 'false',
            ), 200);
        }
    }
}
