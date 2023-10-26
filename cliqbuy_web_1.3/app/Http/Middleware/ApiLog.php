<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use App;
use App\User;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Token\Builder;
use DB;

class ApiLog
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {   

        Session::put('api_currency',$request->header('currency'));

        if ($request->header('lang'))
        {
            App::setLocale($request->header('lang'));
        }
        else
        {
            if($request->bearerToken() || $request->access_token) {
                $token = $request->bearerToken() ?? $request->access_token;

                $false_response = [
                    'result' => false,
                    'message' => "access token required"
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

                $user= User::find($oauth_access_token_data->user_id);
                $lang = $user->email_language ?? 'en';
                App::setLocale($lang);

            } else if($request->language) {
                App::setLocale($request->language);

            } else {
                App::setLocale('en');
            }
        }
        
        $response = $next($request);
        
        // Log Only in Developement Not in Production
        if(strtolower(config('app.env')) == 'local' || true){

            $log_response = [
                "URI"    => $request->getUri(),
                "METHOD" => $request->getMethod(),
                "REQUEST_BODY" => $request->all(),
                "RESPONSE" => $response->getContent(),
                "Token" => $request->header('Authorization'),
                "currency" => $request->header('currency'),
             ];

            // info($log_response);

        }

        return $response;
    }
}
