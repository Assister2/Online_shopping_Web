<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Socialite;
use App\User;
use App\Customer;
use App\Cart;
use Session;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    /*protected $redirectTo = '/';*/


    /**
      * Redirect the user to the Google authentication page.
      *
      * @return \Illuminate\Http\Response
      */
    public function redirectToProvider($provider)
    {
        if ($provider == 'apple') {
             return  redirect(getAppleLoginUrl());
        } else{
            return Socialite::driver($provider)->redirect();
        }
    }

    public function appleCallback(Request $request){

        $client_id = get_setting('apple_service_id');

        $client_secret = getAppleClientSecret();

        $params = array(
            'grant_type'    => 'authorization_code',
            'code'          => $request->code,
            'redirect_uri'  => url('apple_callback'),
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

            if(Auth::loginUsingId($apple_user->id,true)) {
                return redirect()->intended('mainmenu');
            }
        }

        if ($user->banned != '1') {
            if(Auth::loginUsingId($user->id,true)) {
                return redirect()->intended('mainmenu');
            }
        } else{
            flash(translate("login_failed"))->error();
            return redirect(route('user.login'));
        }
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback(Request $request, $provider)
    {
        try {
            if($provider == 'twitter'){
                $user = Socialite::driver('twitter')->user();
            }
            else{
                $user = Socialite::driver($provider)->stateless()->user();
            }
        } catch (\Exception $e) {
            flash("something_went_wrong")->error();
            return redirect()->route('user.login');
        }

        // check if they're an existing user
        $existingUser = User::where('provider_id', $user->id)->orWhere('email', $user->email)->first();

        if($existingUser){
            // log them in
            auth()->login($existingUser, true);
        } else {
            // create a new user
            $newUser                  = new User;
            $newUser->name            = $user->name;
            $newUser->email           = $user->email;
            $newUser->email_verified_at = date('Y-m-d H:m:s');
            $newUser->provider_id     = $user->id;
            $newUser->save();

            $customer = new Customer;
            $customer->user_id = $newUser->id;
            $customer->save();

            auth()->login($newUser, true);
        }
        if(session('link') != null){
            return redirect(session('link'));
        }
        else{
            return redirect()->route('dashboard');
        }
    }

    /**
        * Get the needed authorization credentials from the request.
        *
        * @param  \Illuminate\Http\Request  $request
        * @return array
        */
       protected function credentials(Request $request)
       {
           if(filter_var($request->get('email'), FILTER_VALIDATE_EMAIL)) {
               return $request->only($this->username(), 'password');
           }
           return ['phone'=>$request->get('email'),'password'=>$request->get('password')];
       }

    /**
     * Check user's role and redirect user based on their role
     * @return
     */
    public function authenticated()
    {
        if(session('temp_user_id') != null){
            Cart::where('temp_user_id', session('temp_user_id'))
                    ->update(
                            [
                                'user_id' => auth()->user()->id,
                                'temp_user_id' => null
                            ]
            );

            Session::forget('temp_user_id');
        }
        
        if(auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'staff')
        {
            
            return redirect()->route('admin.dashboard');
        } else {

            if(session('link') != null){
                return redirect(session('link'));
            }
            else{
                return redirect()->route('mainmenu');
            }
        }
    }

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        flash(translate('invalid_email_or_password'))->error();
        return back();
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        if(auth()->user() != null && (auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'staff')){
            $redirect_route = 'login';
        }
        else{
            $redirect_route = 'home';
        }
        
        //User's Cart Delete
        if(auth()->user()){
            Cart::where('user_id', auth()->user()->id)->delete();
        }
        
        $this->guard()->logout();
        // $set_locale = \Session::get('locale');
        $request->session()->invalidate();
        // \Session::put('locale',$set_locale);

        return $this->loggedOut($request) ?: redirect()->route($redirect_route);
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
