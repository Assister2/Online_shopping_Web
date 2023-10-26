<?php /** @noinspection PhpUndefinedClassInspection */

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\OTPVerificationController;
use App\Models\BusinessSetting;
use App\Models\Customer;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\User;
use App\Notifications\AppEmailVerificationNotification;
use Hash;
use Validator;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        if (User::where('email', $request->email_or_phone)->orWhere('phone', $request->email_or_phone)->first() != null) {
            return response()->json([
                'result' => false,
                'message' => trans('messages.api.user_already_exist'),
                'user_id' => 0
            ], 201);
        }

        if ($request->register_by == 'email') {
            $user = new User([
                'name' => $request->name,
                'email' => $request->email_or_phone,
                'password' => bcrypt($request->password),
                'verification_code' => rand(100000, 999999)
            ]);
        } else {
            $user = new User([
                'name' => $request->name,
                'phone' => $request->email_or_phone,
                'password' => bcrypt($request->password),
                'verification_code' => rand(100000, 999999)
            ]);
        }

        if (BusinessSetting::where('type', 'email_verification')->first()->value != 1) {
            $user->email_verified_at = date('Y-m-d H:m:s');
        } elseif ($request->register_by == 'email') {
            $user->notify(new AppEmailVerificationNotification());
        } else {
            $otpController = new OTPVerificationController();
            $otpController->send_code($user);
        }

        $user->save();

        $customer = new Customer;
        $customer->user_id = $user->id;
        $customer->save();
        return response()->json([
            'result' => true,
            'message' => trans('messages.api.reg_successful_verify'),
            'user_id' => $user->id
        ], 201);
    }

    public function resendCode(Request $request)
    {
        $user = User::where('id', $request->user_id)->first();
        $user->verification_code = rand(100000, 999999);

        if ($request->verify_by == 'email') {
            $user->notify(new AppEmailVerificationNotification());
        } else {
            $otpController = new OTPVerificationController();
            $otpController->send_code($user);
        }

        $user->save();

        return response()->json([
            'result' => true,
            'message' => trans('messages.api.verify_code_sent_again'),
        ], 200);
    }

    public function confirmCode(Request $request)
    {
        $user = User::where('id', $request->user_id)->first();

        if ($user->verification_code == $request->verification_code) {
            $user->email_verified_at = date('Y-m-d H:i:s');
            $user->verification_code = null;
            $user->save();
            return response()->json([
                'result' => true,
                'message' => trans('messages.api.account_now_verify'),
            ], 200);
        } else {
            return response()->json([
                'result' => false,
                'message' => trans('messages.api.code_not_match'),
            ], 200);
        }
    }

    public function login(Request $request)
    {
        /*$request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);*/

        $delivery_boy_condition = $request->has('user_type') && $request->user_type == 'delivery_boy';

        if ($delivery_boy_condition) {
            $user = User::whereIn('user_type', ['delivery_boy'])->where('email', $request->email)->orWhere('phone', $request->email)->first();
        } else {
            $user = User::whereIn('user_type', ['customer', 'seller'])->where('email', $request->email)->orWhere('phone', $request->email)->first();
        }

        // if (!$delivery_boy_condition) {
        //     if (\App\Utility\PayhereUtility::create_wallet_reference($request->identity_matrix) == false) {
        //         return response()->json(['result' => false, 'message' => 'Identity matrix error', 'user' => null], 401);
        //     }
        // }


        if ($user != null) {
            if (Hash::check($request->password, $user->password)) {

                if ($user->email_verified_at == null) {
                    return response()->json(['result' => false,'status_code'=>0,'message' => trans('messages.api.pls_verify_account'), 'user' => null], 200);
                }
                $tokenResult = $user->createToken('Personal Access Token');
                return $this->loginSuccess($tokenResult, $user);


            } else {
                return response()->json(['result' => false,'status_code'=>0, 'message' => trans('messages.api.email_not_found'), 'user' => null], 200);
            }
        } else {
            return response()->json(['result' => false,'status_code'=>0, 'message' => trans('messages.api.user_not_found'), 'user' => null], 200);
        }

    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'result' => true,
            'message' => trans('messages.api.successful_logout')
        ]);
    }

    public function appleLogin(){
        return  response()->json([
            'result' => true,
            'apple_url' => getAppleApiLoginUrl(),
        ]);
    }

    public function appleCallback(Request $request){

        $client_id = get_setting('apple_service_id');

        $client_secret = getAppleClientSecret();

        $params = array(
            'grant_type'    => 'authorization_code',
            'code'          => $request->code,
            'redirect_uri'  => url('api/v2/auth/apple_callback'),
            'client_id'     => $client_id,
            'client_secret' => $client_secret,
        );
        $curl_result = curlPost("https://appleid.apple.com/auth/token",$params);

        $claims = explode('.', $curl_result['id_token'])[1];
        $user_data = json_decode(base64_decode($claims));

        $name_parts = explode('@', $user_data->email);
        $user_name = $name_parts[0];
        $user = User::where('provider_id', $user_data->sub)->first();

        if (!$user) {
            $user_validate = User::where('email',$user_data->email)->first();
        }

        if (isset($user_validate)) {
            flash(translate("user_email_exists"))->error();
            return redirect(route('user.login'));
        }
        if($user == '') {
            $apple_user = new User;
            $apple_user->name = $user_name;
            $apple_user->provider_id = $user_data->sub;
            $apple_user->user_type = 'customer';
            $apple_user->email = $user_data->email;
            $apple_user->email_verified_at = Carbon::now();
            $apple_user->save();

            $tokenResult = $apple_user->createToken('Personal Access Token');
        }

        if (!empty($user) && @$user->banned != '1') {
            $tokenResult = $user->createToken('Personal Access Token');
        }
        $data = isset($apple_user) ? $apple_user :$user;
        $response = $this->loginSuccess($tokenResult,$data,'apple');
     
        return response()->json(json_decode($response));


        return view('auth.apple_responce', compact('response'));
    }

    public function socialLogin(Request $request)
    {
        $rules = array(
            'name'          => 'required',
            'email'         => 'required',
            'provider'   => 'required',
            'avatar_original' => 'required',
        );
        

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()) {
            return response()->json([
                'result' => false,
                'success_message' => $validator->messages()->first()
            ]);
        }

        if (User::where('email', $request->email)->first() != null) {
            $user = User::where('email', $request->email)->first();
            $user->avatar = $request->avatar_original;
            $user->save();
        } else {
            $user = new User([
                'name' => $request->name,
                'email' => $request->email,
                'provider_id' => $request->provider,
                'avatar' => $request->avatar_original,
                'email_verified_at' => Carbon::now()
            ]);
            $user->save();
            $customer = new Customer;
            $customer->user_id = $user->id;
            $customer->save();
        }
        $tokenResult = $user->createToken('Personal Access Token');
        return $this->loginSuccess($tokenResult, $user);
    }

    protected function loginSuccess($tokenResult, $user,$type='')
    {
        $token = $tokenResult->token;
        $token->expires_at = Carbon::now()->addWeeks(100);
        $token->save();
        $profile_image = \App\Upload::where('user_id',$user->id)->get()->last();

        $response = [
            'result' => true,
            'message' => trans('messages.api.logged_in'),
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString(),
            'user' => [
                'id' => $user->id,
                'type' => $user->user_type,
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $profile_image ?  $profile_image->file_original_name ?? $profile_image->file_name : '',
                'avatar_original' =>($user->provider_id == NULL) ? ((!empty($profile_image)) ? api_asset($profile_image->id) : "") : $user->avatar, 
                'phone' => $user->phone ?? "",
                'user_address_count' => get_setting('ship_engine') && !$user->addresses->count() > 0 ? false : true,
            ]
        ];

        if($type=='apple')
            return json_encode($response);
        else
            return  response()->json($response);
    }
}
