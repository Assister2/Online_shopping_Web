<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Resources\V2\HomeCategoryCollection;
use App\Models\HomeCategory;
use App\Currency;

class HomeCategoryController extends Controller
{
    public function index()
    {
        return new HomeCategoryCollection(HomeCategory::all());
    }

    public function common_data()
    {

        $currency = Currency::find(get_setting('system_default_currency'));
       
      
        $google = get_setting('google_login') == '1';   
        $apple = get_setting('apple_login') == '1';   
        $facebook = get_setting('facebook_login') == '1';   
        $sitename = get_setting('site_name');   

        return [
            'success' => true,
            'status' => 200,
            'currency_name'=>$currency->name,
            'currency_symbol'=>$currency->symbol,
            'sitename' => $sitename,
            'google_enabled' => $google,
            'facebook_enabled' => $facebook,
            'apple_enabled' => $apple,
            'shipengine_enabled' => get_setting('ship_engine') ? true : false,
        ];

    }
}
