<?php

namespace App\Http\Controllers\Api\V2;

use App\City;
use App\Country;
use App\Http\Resources\V2\AddressCollection;
use App\Address;
use App\Http\Resources\V2\CitiesCollection;
use App\Http\Resources\V2\CountriesCollection;
use App\Order;
use App\Upload;
use App\User;
use App\Wishlist;
use Illuminate\Http\Request;
use App\Models\Cart;
use Hash;
use Illuminate\Support\Facades\File;
use Storage;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Token\Builder;
use DB;

use App\Http\Controllers\HomeController;

class ProfileController extends Controller
{
    public function counters($user_id)
    {
        $user = User::find($user_id);
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
            'cart_item_count' => Cart::where('user_id', $user_id)->count(),
            'wishlist_item_count' => Wishlist::where('user_id', $user_id)->count(),
            'order_count' => Order::where('user_id', $user_id)->count(),
            'user_type' => $user->user_type,
            'show_delete' => $show_delete,
            'delete_message' => $delete_message,
        ]);
    }

    public function update(Request $request)
    {
        $user = User::find($request->id);

        $user->name = $request->name;

        if ($request->password != "") {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json([
            'result' => true,
            'message' => trans('messages.api.profile_update')
        ]);
    }

    public function update_device_token(Request $request)
    {
        $user = User::find($request->id);

        $user->device_token = $request->device_token;


        $user->save();

        return response()->json([
            'result' => true,
            'message' => trans('messages.api.device_token_updated')
        ]);
    }

    public function updateImage(Request $request)
    {

        $type = array(
            "jpg" => "image",
            "jpeg" => "image",
            "png" => "image",
            "svg" => "image",
            "webp" => "image",
            "gif" => "image",
        );

        try {
            $image = $request->image;
            $request->filename;
            $realImage = base64_decode($image);

            $dir = public_path('uploads/all');
            $full_path = "$dir/$request->filename";

            $file_put = file_put_contents($full_path, $realImage); // int or false

            if ($file_put == false) {
                return response()->json([
                    'result' => false,
                    'message' => trans('messages.api.file_uploading_error'),
                    'path' => ""
                ]);
            }


            $upload = new Upload;
            $extension = strtolower(File::extension($full_path));
            $size = File::size($full_path);

            if (!isset($type[$extension])) {
                unlink($full_path);
                return response()->json([
                    'result' => false,
                    'message' => trans('messages.api.only_image_can_uploaded'),
                    'path' => ""
                ]);
            }


            $upload->file_original_name = null;
            $arr = explode('.', File::name($full_path));
            for ($i = 0; $i < count($arr) - 1; $i++) {
                if ($i == 0) {
                    $upload->file_original_name .= $arr[$i];
                } else {
                    $upload->file_original_name .= "." . $arr[$i];
                }
            }

            //unlink and upload again with new name
            unlink($full_path);
            $newFileName = rand(10000000000,9999999999).date("YmdHis").".".$extension;
            $newFullPath = "$dir/$newFileName";

            $file_put = file_put_contents($newFullPath, $realImage);

            if ($file_put == false) {
                return response()->json([
                    'result' => false,
                    'message' => trans('messages.api.file_uploading_error'),
                    'path' => ""
                ]);
            }

            $newPath = "uploads/all/$newFileName";

            if (env('FILESYSTEM_DRIVER') == 's3') {
                Storage::disk('s3')->put($newPath, file_get_contents(base_path('public/') . $newPath));
                unlink(base_path('public/') . $newPath);
            }

            $upload->extension = $extension;
            $upload->file_name = $newPath;
            $upload->user_id = $request->id;
            $upload->type = $type[$upload->extension];
            $upload->file_size = $size;
            $upload->save();

            $user  = User::find($request->id);
            $user->avatar_original = $upload->id;
            $user->save();



            return response()->json([
                'result' => true,
                'message' => trans('messages.api.image_updated'),
                'path' => api_asset($upload->id)
            ]);


        } catch (\Exception $e) {
            return response()->json([
                'result' => false,
                'message' => $e->getMessage(),
                'path' => ""
            ]);
        }

    }

    public function delete_account_otp(Request $request, HomeController $home_controller) {

        $token = $request->bearerToken() ?? $request->access_token;

        $false_response = [
            'result' => false,
            'message' => trans('messages.api.token_required'),
        ];
        if($token == "" || $token == null){
            return response()->json($false_response);
        }

       try {
            $token_ids = (new Parser(new JoseEncoder()))->parse($token);
            $token_id = $token_ids->claims()->get('jti');
            
         } catch (\Exception $e) {
            return response()->json($false_response);
         }
        $oauth_access_token_data =  DB::table('oauth_access_tokens')->where('id', '=', $token_id)->first();

        if($oauth_access_token_data == null){
            return response()->json($false_response);
        }

        $user = User::where('id', $oauth_access_token_data->user_id)->first();
        $user->otp = rand(1000, 9999);
        info($user->otp);
        info('delete_account_user_otp');
        $user->save();

        $response = $home_controller->send_otp_to_delete_account($user);
        return response()->json($response);
    }

    public function delete_account(Request $request, HomeController $home_controller) {
        
        $token = $request->bearerToken() ?? $request->access_token;

        $false_response = [
            'result' => false,
            'status'=>0,
            'message' => trans('messages.api.token_required')
        ];
        if($token == "" || $token == null){
            return response()->json($false_response);
        }

        try {
           // $token_id = (new Parser())->parse($token)->getClaims()['jti']->getValue();
             $token_ids = (new Parser(new JoseEncoder()))->parse($token);
            $token_id = $token_ids->claims()->get('jti');
        } catch (\Exception $e) {
            return response()->json($false_response);
        }
        $oauth_access_token_data =  DB::table('oauth_access_tokens')->where('id', '=', $token_id)->first();

        if($oauth_access_token_data == null){
            return response()->json($false_response);
        }

        $user = User::where('id', $oauth_access_token_data->user_id)->first();

        $response = [
            'result' => false,
            'status'=>0,
            'message' => translate("Unknown")
        ];

        if($request->has('otp') && $request->otp == $user->otp) {

            \App\Cart::where('user_id', $user->id)->delete();
            $request->user()->token()->revoke();
            $user->delete();

            $mail_response = $home_controller->account_deleted_mail($user);
            $response['result'] = ($mail_response['status'] == 1) ? true : false;
            $response['status'] = 1;
            $response['message'] = $mail_response['message'];
        } else {
            $response['status'] = 0;
            $response['message'] = ($request->otp == '') ? translate("otp_required") : translate("otp_is_incorrect");
        }
        
        return response()->json($response);
    }
}
