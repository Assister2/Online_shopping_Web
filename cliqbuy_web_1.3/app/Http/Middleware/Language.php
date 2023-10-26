<?php

namespace App\Http\Middleware;

use Closure;
use App;
use Session;
use Config;
use Schema;

class Language
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
        // if(\Request::segment(1) != 'admin') {
        //     if($request->locale != ''){
        //         $locale = $request->locale;
        //     }
        //     else{
        //         // $locale = env('DEFAULT_LANGUAGE','en');
        //         $locale = \App\Language::where('default_language', 1)->first()->code;
        //     }
        // } 
        // else {            
        //     $locale = env('DEFAULT_LANGUAGE','en');
        // }
        // App::setLocale($locale);
        // Session::forget('locale');
        // $request->session()->put('locale', $locale); 
        // dd(Session::get('locale'));

        if(\Request::segment(1) != 'admin' && \Request::segment(1) != 'login') {
            if(Session::has('locale') || $request->locale != ''){
                $locale = Session::get('locale');
            }
            else{
                 if (Schema::hasTable('business_settings')) 
                {
                    $locale = \App\Language::where('default_language', 1)->first()->code;
                } else {
                    $locale = env('DEFAULT_LANGUAGE','en');
                }
                
            }
        } else {
            $locale = env('DEFAULT_LANGUAGE','en');
        }
        
        App::setLocale($locale);
        $request->session()->put('locale', $locale);

        return $next($request);
    }
}
