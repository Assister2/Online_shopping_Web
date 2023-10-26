<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Resources\V2\UserCollection;
use App\User;
use App\Order;
use Illuminate\Http\Request;
use Lcobucci\JWT\Parser;
use DB;
use Session;

class UserController extends Controller
{
    public function info($id)
    {
        return new UserCollection(User::where('id', $id)->get());
    }

    public function updateName(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        $user->update([
            'name' => $request->name
        ]);
        return response()->json([
            'message' => trans('messages.api.profile_information_updated')
        ]);
    }

    public function getUserInfoByAccessToken(Request $request)
    {
        //$token = $request->bearerToken();
        $token = $request->access_token;

        $false_response = [
            'result' => false,
            'id' => 0,
            'name' => "",
            'email' => "",
            'avatar' => "",
            'avatar_original' => "",
            'phone' => ""
        ];

        if($token == "" || $token == null){
            return response()->json($false_response);
        }

        try {
            $token_id = (new Parser())->parse($token)->getClaims()['jti']->getValue();
        } catch (\Exception $e) {
            return response()->json($false_response);
        }

        $oauth_access_token_data =  DB::table('oauth_access_tokens')->where('id', '=', $token_id)->first();

        if($oauth_access_token_data == null){
            return response()->json($false_response);
        }

        $user = User::where('id', $oauth_access_token_data->user_id)->first();

        if ($user == null) {
            return response()->json($false_response);
        }

        $user_id = $oauth_access_token_data->user_id;

        $ongoing_orders = Order::where('user_id', $user_id)->where('delivery_status', '!=', 'delivered')->get()->count();
        $past_delivered_orders = Order::where('user_id', $user_id)->where('delivery_status', 'delivered')->get()->count();

        $show_delete = true;
        if($ongoing_orders > 0) {
            $delete_message = trans('messages.api.delete_ongoing_order');
        } else if($past_delivered_orders > 0) {
            $delete_message = trans('messages.api.delete_order_history');
        } else {
            $delete_message = trans('messages.api.delete_your_account');
        }

        if($user->user_type == 'seller') {
            $show_delete = false;
            $delete_message = trans('messages.api.delete_merchant_account');
        }

        return response()->json([
            'result' => true,
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => $user->avatar,
            'avatar_original' => api_asset($user->avatar_original),
            'phone' => $user->phone,
            'user_type' => $user->user_type,
            'show_delete' => $show_delete,
            'delete_message' => $delete_message
        ]);

    }
  public function language(Request $request) {

    $user= User::find($request->user_id);
    $user->email_language = $request->language;
    $user->save();
    $language = $user->email_language ?? 'en';
    $lang = (request()->language == 'ar') ? "sa" : request()->language;
    \App::setLocale($lang);

    return response()->json([
      'status_code'       =>  '1',
      'success_message'    => trans('messages.api.update_success'),
      'success' => true,
    ]);
  }    
}