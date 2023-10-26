<?php

use App\Http\Controllers\ClubPointController;
use App\Http\Controllers\AffiliateController;
use Illuminate\Support\Facades\Notification;
use App\Notifications\OrderNotification;
use Jose\Component\Core\AlgorithmManager;
use Jose\Component\KeyManagement\JWKFactory;
use Jose\Component\Signature\Algorithm\ES256;
use Jose\Component\Signature\JWSBuilder;
use Jose\Component\Signature\Serializer\CompactSerializer;
use App\Currency;
use App\BusinessSetting;
use App\Product;
use App\ProductStock;
use App\Address;
use App\SubSubCategory;
use App\FlashDealProduct;
use App\CustomerPackage;
use App\FlashDeal;
use App\OtpConfiguration;
use App\Upload;
use App\Translation;
use App\City;
use App\CommissionHistory;
use App\Utility\TranslationUtility;
use App\Utility\CategoryUtility;
use App\Utility\MimoUtility;
use Twilio\Rest\Client;
use App\Wallet;
use App\Order;
use App\User;
use App\FirebaseNotification;
use App\Addon;
use App\Wishlist;
use App\Cart;
use App\OrderDetail;
use App\CouponUsage;
use App\Models\Coupon;
use App\Models\GeneralSetting;
use App\Models\OweAmount;
use App\Models\UserShipEngineSettings;
use App\Mail\InvoiceEmailManager;
//use Stichoza\GoogleTranslate\GoogleTranslate;

//highlights the selected navigation on admin panel
if (!function_exists('sendSMS')) {
    function sendSMS($to, $from, $text, $template_id)
    {
        if (OtpConfiguration::where('type', 'nexmo')->first()->value == 1) {
            $api_key = env("NEXMO_KEY"); //put ssl provided api_token here
            $api_secret = env("NEXMO_SECRET"); // put ssl provided sid here

            $params = [
                "api_key" => $api_key,
                "api_secret" => $api_secret,
                "from" => $from,
                "text" => $text,
                "to" => $to
            ];

            $url = "https://rest.nexmo.com/sms/json";
            $params = json_encode($params);

            $ch = curl_init(); // Initialize cURL
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($params),
                'accept:application/json'
            ));
            $response = curl_exec($ch);
            curl_close($ch);

            return $response;
        } elseif (OtpConfiguration::where('type', 'twillo')->first()->value == 1) {
            $sid = env("TWILIO_SID"); // Your Account SID from www.twilio.com/console
            $token = env("TWILIO_AUTH_TOKEN"); // Your Auth Token from www.twilio.com/console

            $client = new Client($sid, $token);
            try {
                $message = $client->messages->create(
                    $to, // Text this number
                    array(
                        'from' => env('VALID_TWILLO_NUMBER'), // From a valid Twilio number
                        'body' => $text
                    )
                );
            } catch (\Exception $e) {

            }

        } elseif (OtpConfiguration::where('type', 'ssl_wireless')->first()->value == 1) {
            $token = env("SSL_SMS_API_TOKEN"); //put ssl provided api_token here
            $sid = env("SSL_SMS_SID"); // put ssl provided sid here

            $params = [
                "api_token" => $token,
                "sid" => $sid,
                "msisdn" => $to,
                "sms" => $text,
                "csms_id" => date('dmYhhmi') . rand(10000, 99999)
            ];

            $url = env("SSL_SMS_URL");
            $params = json_encode($params);

            $ch = curl_init(); // Initialize cURL
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($params),
                'accept:application/json'
            ));

            $response = curl_exec($ch);

            curl_close($ch);

            return $response;
        } elseif (OtpConfiguration::where('type', 'fast2sms')->first()->value == 1) {

            if (strpos($to, '+91') !== false) {
                $to = substr($to, 3);
            }

            if (env("ROUTE") == 'dlt_manual') {
                $fields = array(
                    "sender_id" => env("SENDER_ID"),
                    "message" => $text,
                    "template_id" => $template_id,
                    "entity_id" => env("ENTITY_ID"),
                    "language" => env("LANGUAGE"),
                    "route" => env("ROUTE"),
                    "numbers" => $to,
                );
            } else {
                $fields = array(
                    "sender_id" => env("SENDER_ID"),
                    "message" => $text,
                    "language" => env("LANGUAGE"),
                    "route" => env("ROUTE"),
                    "numbers" => $to,
                );
            }


            $auth_key = env('AUTH_KEY');

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://www.fast2sms.com/dev/bulkV2",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($fields),
                CURLOPT_HTTPHEADER => array(
                    "authorization: $auth_key",
                    "accept: */*",
                    "cache-control: no-cache",
                    "content-type: application/json"
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            return $response;
        } elseif (OtpConfiguration::where('type', 'mimo')->first()->value == 1) {
            $token = MimoUtility::getToken();

            MimoUtility::sendMessage($text, $to, $token);
            MimoUtility::logout($token);
        }
        elseif (OtpConfiguration::where('type', 'mimsms')->first()->value == 1) {
            $url = "https://esms.mimsms.com/smsapi";
              $data = [
                "api_key" => env('MIM_API_KEY'),
                "type" => "text",
                "contacts" => $to,
                "senderid" => env('MIM_SENDER_ID'),
                "msg" => $text,
              ];
              $ch = curl_init();
              curl_setopt($ch, CURLOPT_URL, $url);
              curl_setopt($ch, CURLOPT_POST, 1);
              curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
              curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
              $response = curl_exec($ch);
              curl_close($ch);
              return $response;
        }
    }
}

//highlights the selected navigation on admin panel
if (!function_exists('areActiveRoutes')) {
    function areActiveRoutes(array $routes, $output = "active")
    {
        foreach ($routes as $route) {
            if (Route::currentRouteName() == $route) return $output;
        }

    }
}

//highlights the selected navigation on frontend
if (!function_exists('areActiveRoutesHome')) {
    function areActiveRoutesHome(array $routes, $output = "active")
    {
        foreach ($routes as $route) {
            if (Route::currentRouteName() == $route) return $output;
        }

    }
}

//highlights the selected navigation on frontend
if (!function_exists('default_language')) {
    function default_language()
    {
        return env("DEFAULT_LANGUAGE");
    }
}

/**
 * Save JSON File
 * @return Response
 */
if (!function_exists('convert_to_usd')) {
    function convert_to_usd($amount)
    {
        $business_settings = BusinessSetting::where('type', 'system_default_currency')->first();
        if ($business_settings != null) {
            $currency = Currency::find($business_settings->value);
            return (floatval($amount) / floatval($currency->exchange_rate)) * Currency::where('code', 'USD')->first()->exchange_rate;
        }
    }
}

if (!function_exists('convert_to_kes')) {
    function convert_to_kes($amount)
    {
        $business_settings = BusinessSetting::where('type', 'system_default_currency')->first();
        if ($business_settings != null) {
            $currency = Currency::find($business_settings->value);
            return (floatval($amount) / floatval($currency->exchange_rate)) * Currency::where('code', 'KES')->first()->exchange_rate;
        }
    }
}

//filter products based on vendor activation system
if (!function_exists('filter_products')) {
    function filter_products($products)
    {
        $verified_sellers = verified_sellers_id();
        if (BusinessSetting::where('type', 'vendor_system_activation')->first()->value == 1) {
            return $products->where('approved', '1')->where('published', '1')->where('auction_product', 0)->orderBy('created_at', 'desc')->where(function ($p) use ($verified_sellers) {
                $p->where('added_by', 'admin')->orWhere(function ($q) use ($verified_sellers) {
                    $q->whereIn('user_id', $verified_sellers);
                });
            });
        } else {
            return $products->where('published', '1')->where('auction_product', 0)->where('added_by', 'admin');
        }
    }
}

//cache products based on category
if (!function_exists('get_cached_products')) {
    function get_cached_products($category_id = null)
    {
        $products = \App\Product::where('published', 1)->where('approved', '1')->where('auction_product', 0);
        $verified_sellers = verified_sellers_id();
        if (BusinessSetting::where('type', 'vendor_system_activation')->first()->value == 1) {
            $products = $products->where(function ($p) use ($verified_sellers) {
                $p->where('added_by', 'admin')->orWhere(function ($q) use ($verified_sellers) {
                    $q->whereIn('user_id', $verified_sellers);
                });
            });
        } else {
            $products = $products->where('added_by', 'admin');
        }

        if ($category_id != null) {
            return Cache::remember('products-category-' . $category_id, 86400, function () use ($category_id, $products) {
                // $category_ids = CategoryUtility::children_ids($category_id);
                $category_ids[] = $category_id;
                return $products->whereIn('category_id', $category_ids)->latest()->take(12)->get();
            });
        } else {
            return Cache::remember('products', 86400, function () use ($products) {
                return $products->latest()->get();
            });
        }
    }
}

if (!function_exists('verified_sellers_id')) {
    function verified_sellers_id()
    {
        return App\Seller::where('verification_status', 1)->get()->pluck('user_id')->toArray();
    }
}

//converts currency to home default currency
if (!function_exists('convert_price')) {
    function convert_price($price)
    {

        // $business_settings = BusinessSetting::where('type', 'system_default_currency')->first();
        // if ($business_settings != null) {
        //     $currency = Currency::find($business_settings->value);
        //     $price = floatval($price) / floatval($currency->exchange_rate);
        // }
        if (Session::get('currency_code')) {
            $code = Session::get('currency_code');
        }
        else
        {
            $code = \App\Currency::findOrFail(get_setting('system_default_currency'))->code;
        }
        return currencyConvert('USD', $code, $price);
        // if (Session::has('currency_code')) {
        //     $currency = Currency::where('code', Session::get('currency_code', $code))->first();
        // } else {
        //     $currency = Currency::where('code', $code)->first();
        // }
        
        // $price = floatval($price) * floatval($currency->exchange_rate);

        // return $price;
    }
}

if (!function_exists('currencyConvert')) {
    function currencyConvert($from, $to, $price = 0)
    {
        $price = floatval($price);
        
        if($from == $to) {
            return number_format($price, 2, '.', '');
        }

        if($price == 0) {
            return number_format(0, 2, '.', '');
        }
        if(!$to){
            $business_settings = BusinessSetting::where('type', 'system_default_currency')->first();
            if ($business_settings != null) {
                $currency = Currency::find($business_settings->value);
                // $price = floatval($price) / floatval($currency->exchange_rate);
            }

            $code = \App\Currency::findOrFail(get_setting('system_default_currency'))->code;
            if (Session::has('currency_code')) {
                $currency = Currency::where('code', Session::get('currency_code', $code))->first();
            } else {
                $currency = Currency::where('code', $code)->first();
            }            
        }else{
                $currency = Currency::where('code', $to)->first();
            }  

        \Log::info('Currency Value '.json_encode(  $currency));
        $rate = Currency::whereCode($from)->first()->exchange_rate;
        $session_rate = $currency->exchange_rate;
        $usd_amount = $price / $rate;
        return number_format($usd_amount * $session_rate, 2, '.', '');
    }
}

//formats currency
if (!function_exists('format_price')) {
    function format_price($price)
    {   
        if (get_setting('decimal_separator') == 1) {
            $fomated_price = number_format($price, get_setting('no_of_decimals'));
        } else {
            $fomated_price = number_format($price, get_setting('no_of_decimals'), ',', ' ');
        }

        if (get_setting('symbol_format') == 1) {
            return currency_symbol() . $fomated_price;
        } else if (get_setting('symbol_format') == 3) {
            return currency_symbol() . ' ' . $fomated_price;
        } else if (get_setting('symbol_format') == 4) {
            return $fomated_price . ' ' . currency_symbol();
        }
        return $fomated_price . currency_symbol();

    }
}

//formats price to home default price with convertion
if (!function_exists('single_price')) {
    function single_price($price)
    {
        return format_price(convert_price($price));
    }
}

//Shows Price on page based on low to high
if (!function_exists('home_price')) {
    function home_price($product)
    {
        $lowest_price = $product->unit_price;
        $highest_price = $product->unit_price;

        if ($product->variant_product) {
            foreach ($product->stocks as $key => $stock) {
                if ($lowest_price > $stock->price) {
                    $lowest_price = $stock->price;
                }
                if ($highest_price < $stock->price) {
                    $highest_price = $stock->price;
                }
            }
        }

        foreach ($product->taxes as $product_tax) {
            if ($product_tax->tax_type == 'percent') {
                $lowest_price += ($lowest_price * $product_tax->tax) / 100;
                $highest_price += ($highest_price * $product_tax->tax) / 100;
            } elseif ($product_tax->tax_type == 'amount') {
                $lowest_price += $product_tax->tax;
                $highest_price += $product_tax->tax;
            }
        }

        $lowest_price = convert_price($lowest_price);
        $highest_price = convert_price($highest_price);

        if ($lowest_price == $highest_price) {
            return format_price($lowest_price);
        } else {
            return format_price($lowest_price) . ' - ' . format_price($highest_price);
        }
    }
}

//Shows Price on page based on low to high with discount
if (!function_exists('home_discounted_price')) {
    function home_discounted_price($product)
    {
        $lowest_price = $product->unit_price;
        $highest_price = $product->unit_price;

        if ($product->variant_product) {
            foreach ($product->stocks as $key => $stock) {
                if ($lowest_price > $stock->price) {
                    $lowest_price = $stock->price;
                }
                if ($highest_price < $stock->price) {
                    $highest_price = $stock->price;
                }
            }
        }

        $discount_applicable = false;

        if ($product->discount_start_date == null) {
            $discount_applicable = true;
        } elseif (strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date &&
            strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date) {
            $discount_applicable = true;
        }

        foreach ($product->taxes as $product_tax) {
            if ($product_tax->tax_type == 'percent') {
                $lowest_price += ($lowest_price * $product_tax->tax) / 100;
                $highest_price += ($highest_price * $product_tax->tax) / 100;
            } elseif ($product_tax->tax_type == 'amount') {
                $lowest_price += $product_tax->tax;
                $highest_price += $product_tax->tax;
            }
        }

        if ($discount_applicable) {
            if ($product->discount_type == 'percent') {
                $lowest_price -= ($lowest_price * $product->discount) / 100;
                $highest_price -= ($highest_price * $product->discount) / 100;
            } elseif ($product->discount_type == 'amount') {
                $lowest_price -= $product->discount;
                $highest_price -= $product->discount;
            }
        }

        $lowest_price = convert_price($lowest_price);
        $highest_price = convert_price($highest_price);

        if ($lowest_price == $highest_price) {
            return format_price($lowest_price);
        } else {
            return format_price($lowest_price) . ' - ' . format_price($highest_price);
        }
    }
}

//Shows Base Price
if (!function_exists('home_base_price_by_stock_id')) {
    function home_base_price_by_stock_id($id)
    {
        $product_stock = ProductStock::findOrFail($id);
        $price = $product_stock->price;
        $tax = 0;

        foreach ($product_stock->product->taxes as $product_tax) {
            if ($product_tax->tax_type == 'percent') {
                $tax += ($price * $product_tax->tax) / 100;
            } elseif ($product_tax->tax_type == 'amount') {
                $tax += $product_tax->tax;
            }
        }
        $price += $tax;
        return format_price(convert_price($price));
    }
}
if (!function_exists('home_base_price')) {
    function home_base_price($product)
    {
        $price = $product->unit_price;
        $tax = 0;

        foreach ($product->taxes as $product_tax) {
            if ($product_tax->tax_type == 'percent') {
                $tax += ($price * $product_tax->tax) / 100;
            } elseif ($product_tax->tax_type == 'amount') {
                $tax += $product_tax->tax;
            }
        }
        $price += $tax;
        return format_price(convert_price($price));
    }
}

if (!function_exists('api_home_base_price')) {
    function api_home_base_price($product)
    {
        $price = $product->unit_price;
        $tax = 0;

        foreach ($product->taxes as $product_tax) {
            if ($product_tax->tax_type == 'percent') {
                $tax += ($price * $product_tax->tax) / 100;
            } elseif ($product_tax->tax_type == 'amount') {
                $tax += $product_tax->tax;
            }
        }
        $price += $tax;
        return format_price($price);
    }
}

//Shows Base Price with discount
if (!function_exists('home_discounted_base_price_by_stock_id')) {
    function home_discounted_base_price_by_stock_id($id)
    {
        $product_stock = ProductStock::findOrFail($id);
        $product = $product_stock->product;
        $price = $product_stock->price;
        $tax = 0;

        $discount_applicable = false;

        if ($product->discount_start_date == null) {
            $discount_applicable = true;
        } elseif (strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date &&
            strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date) {
            $discount_applicable = true;
        }

        if ($discount_applicable) {
            if ($product->discount_type == 'percent') {
                $price -= ($price * $product->discount) / 100;
            } elseif ($product->discount_type == 'amount') {
                $price -= $product->discount;
            }
        }

        foreach ($product->taxes as $product_tax) {
            if ($product_tax->tax_type == 'percent') {
                $tax += ($price * $product_tax->tax) / 100;
            } elseif ($product_tax->tax_type == 'amount') {
                $tax += $product_tax->tax;
            }
        }
        $price += $tax;

        return format_price(convert_price($price));
    }
}

//Shows Base Price with discount
if (!function_exists('api_home_discounted_base_price')) {
    function api_home_discounted_base_price($product)
    {
        $price = $product->unit_price;
        $tax = 0;

        $discount_applicable = false;

        if ($product->discount_start_date == null) {
            $discount_applicable = true;
        } elseif (strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date &&
            strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date) {
            $discount_applicable = true;
        }

        if ($discount_applicable) {
            if ($product->discount_type == 'percent') {
                $price -= ($price * $product->discount) / 100;
            } elseif ($product->discount_type == 'amount') {
                $price -= $product->discount;
            }
        }

        foreach ($product->taxes as $product_tax) {
            if ($product_tax->tax_type == 'percent') {
                $tax += ($price * $product_tax->tax) / 100;
            } elseif ($product_tax->tax_type == 'amount') {
                $tax += $product_tax->tax;
            }
        }
        $price += $tax;

        return format_price($price);
    }
}
//Shows Base Price with discount
if (!function_exists('home_discounted_base_price')) {
    function home_discounted_base_price($product)
    {
        $price = $product->unit_price;
        $tax = 0;

        $discount_applicable = false;

        if ($product->discount_start_date == null) {
            $discount_applicable = true;
        } elseif (strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date &&
            strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date) {
            $discount_applicable = true;
        }

        if ($discount_applicable) {
            if ($product->discount_type == 'percent') {
                $price -= ($price * $product->discount) / 100;
            } elseif ($product->discount_type == 'amount') {
                $price -= $product->discount;
            }
        }

        foreach ($product->taxes as $product_tax) {
            if ($product_tax->tax_type == 'percent') {
                $tax += ($price * $product_tax->tax) / 100;
            } elseif ($product_tax->tax_type == 'amount') {
                $tax += $product_tax->tax;
            }
        }
        $price += $tax;

        return format_price(convert_price($price));
    }
}

if (!function_exists('currency_symbol')) {
    function currency_symbol()
    {
        $code = \App\Currency::findOrFail(get_setting('system_default_currency'))->code;
        if (Session::has('currency_code')) {
            $currency = Currency::where('code', Session::get('currency_code', $code))->first();
        } else {
            $currency = Currency::where('code', $code)->first();
        }
        return $currency->symbol;
    }
}

if (!function_exists('currency_code')) {
    function currency_code()
    {
        $code = \App\Currency::findOrFail(get_setting('system_default_currency'))->code;
        if (Session::has('currency_code')) {
            $currency = Currency::where('code', Session::get('currency_code', $code))->first();
        } else {
            $currency = Currency::where('code', $code)->first();
        }
        return $currency->code;
    }
}

if (!function_exists('system_default_currency')) {
    function system_default_currency()
    {
        $id = \App\Currency::findOrFail(get_setting('system_default_currency'))->id;
        if (Session::has('currency_code')) {
            $currency = Currency::where('id', $id)->first();
            Session::get('currency_code', $currency->code);
        } else {
            $currency = Currency::where('id', $id)->first();
        }
        return $currency->id;
    }
}

if (!function_exists('renderStarRating')) {
    function renderStarRating($rating, $maxRating = 5)
    {
        $fullStar = "<i class = 'las la-star active'></i>";
        $halfStar = "<i class = 'las la-star half'></i>";
        $emptyStar = "<i class = 'las la-star'></i>";
        $rating = $rating <= $maxRating ? $rating : $maxRating;

        $fullStarCount = (int)$rating;
        $halfStarCount = ceil($rating) - $fullStarCount;
        $emptyStarCount = $maxRating - $fullStarCount - $halfStarCount;

        $html = str_repeat($fullStar, $fullStarCount);
        $html .= str_repeat($halfStar, $halfStarCount);
        $html .= str_repeat($emptyStar, $emptyStarCount);
        echo $html;
    }
}


//Api
if (!function_exists('homeBasePrice')) {
    function homeBasePrice($product)
    {
        $price = $product->unit_price;
        $tax = 0;
        foreach ($product->taxes as $product_tax) {
            if ($product_tax->tax_type == 'percent') {
                $tax += ($price * $product_tax->tax) / 100;
            } elseif ($product_tax->tax_type == 'amount') {
                $tax += $product_tax->tax;
            }
        }

        $price += $tax;
//        if ($product->tax_type == 'percent') {
//            $price += ($price * $product->tax) / 100;
//        } elseif ($product->tax_type == 'amount') {
//            $price += $product->tax;
//        }
        return $price;
    }
}

if (!function_exists('homeDiscountedBasePrice')) {
    function homeDiscountedBasePrice($product)
    {
        $price = $product->unit_price;
        $tax = 0;

        $discount_applicable = false;

        if ($product->discount_start_date == null) {
            $discount_applicable = true;
        } elseif (strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date &&
            strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date) {
            $discount_applicable = true;
        }

        if ($discount_applicable) {
            if ($product->discount_type == 'percent') {
                $price -= ($price * $product->discount) / 100;
            } elseif ($product->discount_type == 'amount') {
                $price -= $product->discount;
            }
        }

        foreach ($product->taxes as $product_tax) {
            if ($product_tax->tax_type == 'percent') {
                $tax += ($price * $product_tax->tax) / 100;
            } elseif ($product_tax->tax_type == 'amount') {
                $tax += $product_tax->tax;
            }
        }
        $price += $tax;
        return $price;
    }
}

if (!function_exists('homePrice')) {
    function homePrice($product)
    {
        $lowest_price = $product->unit_price;
        $highest_price = $product->unit_price;
        $tax = 0;

        if ($product->variant_product) {
            foreach ($product->stocks as $key => $stock) {
                if ($lowest_price > $stock->price) {
                    $lowest_price = $stock->price;
                }
                if ($highest_price < $stock->price) {
                    $highest_price = $stock->price;
                }
            }
        }

//        if ($product->tax_type == 'percent') {
//            $lowest_price += ($lowest_price*$product->tax)/100;
//            $highest_price += ($highest_price*$product->tax)/100;
//        }
//        elseif ($product->tax_type == 'amount') {
//            $lowest_price += $product->tax;
//            $highest_price += $product->tax;
//        }
        foreach ($product->taxes as $product_tax) {
            if ($product_tax->tax_type == 'percent') {
                $lowest_price += ($lowest_price * $product_tax->tax) / 100;
                $highest_price += ($highest_price * $product_tax->tax) / 100;
            } elseif ($product_tax->tax_type == 'amount') {
                $lowest_price += $product_tax->tax;
                $highest_price += $product_tax->tax;
            }
        }

        $lowest_price = convertPrice($lowest_price);
        $highest_price = convertPrice($highest_price);

        return $lowest_price . ' - ' . $highest_price;
    }
}

if (!function_exists('homeDiscountedPrice')) {
    function homeDiscountedPrice($product)
    {
        $lowest_price = $product->unit_price;
        $highest_price = $product->unit_price;

        if ($product->variant_product) {
            foreach ($product->stocks as $key => $stock) {
                if ($lowest_price > $stock->price) {
                    $lowest_price = $stock->price;
                }
                if ($highest_price < $stock->price) {
                    $highest_price = $stock->price;
                }
            }
        }

        $discount_applicable = false;

        if ($product->discount_start_date == null) {
            $discount_applicable = true;
        } elseif (strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date &&
            strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date) {
            $discount_applicable = true;
        }

        if ($discount_applicable) {
            if ($product->discount_type == 'percent') {
                $lowest_price -= ($lowest_price * $product->discount) / 100;
                $highest_price -= ($highest_price * $product->discount) / 100;
            } elseif ($product->discount_type == 'amount') {
                $lowest_price -= $product->discount;
                $highest_price -= $product->discount;
            }
        }

        foreach ($product->taxes as $product_tax) {
            if ($product_tax->tax_type == 'percent') {
                $lowest_price += ($lowest_price * $product_tax->tax) / 100;
                $highest_price += ($highest_price * $product_tax->tax) / 100;
            } elseif ($product_tax->tax_type == 'amount') {
                $lowest_price += $product_tax->tax;
                $highest_price += $product_tax->tax;
            }
        }

        $lowest_price = convertPrice($lowest_price);
        $highest_price = convertPrice($highest_price);

        return $lowest_price . ' - ' . $highest_price;
    }
}

if (!function_exists('brandsOfCategory')) {
    function brandsOfCategory($category_id)
    {
        $brands = [];
        $subCategories = SubCategory::where('category_id', $category_id)->get();
        foreach ($subCategories as $subCategory) {
            $subSubCategories = SubSubCategory::where('sub_category_id', $subCategory->id)->get();
            foreach ($subSubCategories as $subSubCategory) {
                $brand = json_decode($subSubCategory->brands);
                foreach ($brand as $b) {
                    if (in_array($b, $brands)) continue;
                    array_push($brands, $b);
                }
            }
        }
        return $brands;
    }
}

if (!function_exists('convertPrice')) {
    function convertPrice($price)
    {
        $business_settings = BusinessSetting::where('type', 'system_default_currency')->first();
        if ($business_settings != null) {
            $currency = Currency::find($business_settings->value);
            $price = floatval($price) / floatval($currency->exchange_rate);
        }
        $code = Currency::findOrFail(BusinessSetting::where('type', 'system_default_currency')->first()->value)->code;
        if (Session::has('currency_code')) {
            $currency = Currency::where('code', Session::get('currency_code', $code))->first();
        } else {
            $currency = Currency::where('code', $code)->first();
        }
        $price = floatval($price) * floatval($currency->exchange_rate);
        return $price;
    }
}

function getRestrictedRoutes($type = 'admin') {
    if($type == 'admin') {
        $rules = [
            'admin/sellers',
            'admin/sellers/create',
            'admin/sellers/{id}',
            'admin/sellers/{seller}/edit',
            'admin/seller/payments',
            'admin/seller/payments/show/{id}',
            'admin/all_orders',
            'admin/seller_orders',
            'admin/products/seller',
            'admin/products/all',
            'admin/seller_sale_report',
            'admin/vendor_commission',
            'withdraw_requests_all',
            'admin/sellers/login/{id}',
        ];
    } else if($type == 'shops') {
        $rules = [
            'withdraw_requests',
        ];
    }
    return $rules ?? [];
}

function getCustomTranslateKeys() {
    return array_change_key_case([
        'Inhouse orders'    => 'Orders',
        'In House Products' => 'Products',
        'In House Product Order' => 'Product Order',
        'Inhouse Product Order report' => 'Product Order report',
    ]); // Change all keys to lower case
}

function translates($key, $lang = null)
{
    $key = strtolower(str_replace(" ","_",$key));
    return trans('messages.ship_engine.'.$key);
}

function translate($key, $lang = null)
{
    // GoogleTranslate::trans($key, \App::getLocale(), 'en');
    // return $key;
    if(Request::segment(1) == 'admin' && !substr_count($key,"_")) {
        return $key;
    }

    $key = strtolower(str_replace(" ","_",$key));
    return trans('messages.front_end.'.$key);
/*


   // return $key;
    // Custom Keys - Start
    if( isSingleStoreActivated() ) {
        $customKeys = getCustomTranslateKeys();

        $key        = array_key_exists( strtolower($key) , $customKeys) 
            ? $customKeys[strtolower($key)] 
            : $key;
    }
    // Custom Keys - End

    if ($lang == null) {
        $lang = App::getLocale();
    }

    $translation_def = Translation::where('lang', env('DEFAULT_LANGUAGE', 'en'))->where('lang_key', $key)->first();
    if ($translation_def == null) {
        $translation_def = new Translation;
        $translation_def->lang = env('DEFAULT_LANGUAGE', 'en');
        $translation_def->lang_key = $key;
        $translation_def->lang_value = $key;
        $translation_def->save();
    }

    $translations = translations();

    $translation_locale = array_first(array_filter($translations, function($trans) use($lang,$key) {
         return (($trans['lang'] == $lang) && ($trans['lang_key'] == $key));
    }));


    //Check for session lang
    $translation_locale = Translation::where('lang_key', $key)->where('lang', $lang)->first();

    if (!empty($translation_locale) && $translation_locale['lang_value'] != null) {
        return $translation_locale['lang_value'];
    } elseif ($translation_def->lang_value != null) {
        return $translation_def->lang_value;
    } else {
        return $key;
    }*/
}

function remove_invalid_charcaters($str)
{
    $str = str_ireplace(array("\\"), '', $str);
    return str_ireplace(array('"'), '\"', $str);
}

function getShippingCost($carts, $index)
{
    $admin_products = array();
    $seller_products = array();
    $calculate_shipping = 0;

    foreach ($carts as $key => $cartItem) {
        $product = \App\Product::find($cartItem['product_id']);
        if ($product->added_by == 'admin') {
            array_push($admin_products, $cartItem['product_id']);
        } else {
            $product_ids = array();
            if (array_key_exists($product->user_id, $seller_products)) {
                $product_ids = $seller_products[$product->user_id];
            }
            array_push($product_ids, $cartItem['product_id']);
            $seller_products[$product->user_id] = $product_ids;
        }
    }

    //Calculate Shipping Cost
    if (get_setting('shipping_type') == 'flat_rate') {
        $calculate_shipping = get_setting('flat_rate_shipping_cost');
    } elseif (get_setting('shipping_type') == 'seller_wise_shipping') {
        if (!empty($admin_products)) {
            $calculate_shipping = get_setting('shipping_cost_admin');
        }
        if (!empty($seller_products)) {
            foreach ($seller_products as $key => $seller_product) {
                $calculate_shipping += \App\Shop::where('user_id', $key)->first()->shipping_cost;
            }
        }
    } elseif (get_setting('shipping_type') == 'area_wise_shipping') {
        $shipping_info = Address::where('id', $carts[0]['address_id'])->first();
        
        $city = City::where('name', $shipping_info->city)->first();
        if ($city != null) {
            $calculate_shipping = $city->cost;
        }
    }

    $cartItem = $carts[$index];
    $product = \App\Product::find($cartItem['product_id']);

    if ($product->digital == 1) {
        return $calculate_shipping = 0;
    }

    if (get_setting('shipping_type') == 'flat_rate') {
        return $calculate_shipping / count($carts);
    } elseif (get_setting('shipping_type') == 'seller_wise_shipping') {
        if ($product->added_by == 'admin') {
            return get_setting('shipping_cost_admin') / count($admin_products);
        } else {
            return \App\Shop::where('user_id', $product->user_id)->first()->shipping_cost / count($seller_products[$product->user_id]);
        }
    } elseif (get_setting('shipping_type') == 'area_wise_shipping') {
        if ($product->added_by == 'admin') {
            return $calculate_shipping / count($admin_products);
        } else {
            return $calculate_shipping / count($seller_products[$product->user_id]);
        }
    } else {
        // if shipping type is ship engine then shipping cost is calculated from delivery info page
        if($cartItem['shipping_type'] == 'ship_engine') {
            return $cartItem['shipping_cost'];
        }
        return \App\Product::find($cartItem['product_id'])->shipping_cost;
    }
}

function timezones()
{

    return Array(
                '(GMT-12:00) International Date Line West' => 'Pacific/Kwajalein',
                '(GMT-11:00) Midway Island' => 'Pacific/Midway',
                '(GMT-11:00) Samoa' => 'Pacific/Apia',
                '(GMT-10:00) Hawaii' => 'Pacific/Honolulu',
                '(GMT-09:00) Alaska' => 'America/Anchorage',
                '(GMT-08:00) Pacific Time (US & Canada)' => 'America/Los_Angeles',
                '(GMT-08:00) Tijuana' => 'America/Tijuana',
                '(GMT-07:00) Arizona' => 'America/Phoenix',
                '(GMT-07:00) Mountain Time (US & Canada)' => 'America/Denver',
                '(GMT-07:00) Chihuahua' => 'America/Chihuahua',
                '(GMT-07:00) La Paz' => 'America/Chihuahua',
                '(GMT-07:00) Mazatlan' => 'America/Mazatlan',
                '(GMT-06:00) Central Time (US & Canada)' => 'America/Chicago',
                '(GMT-06:00) Central America' => 'America/Managua',
                '(GMT-06:00) Guadalajara' => 'America/Mexico_City',
                '(GMT-06:00) Mexico City' => 'America/Mexico_City',
                '(GMT-06:00) Monterrey' => 'America/Monterrey',
                '(GMT-06:00) Saskatchewan' => 'America/Regina',
                '(GMT-05:00) Eastern Time (US & Canada)' => 'America/New_York',
                '(GMT-05:00) Indiana (East)' => 'America/Indiana/Indianapolis',
                '(GMT-05:00) Bogota' => 'America/Bogota',
                '(GMT-05:00) Lima' => 'America/Lima',
                '(GMT-05:00) Quito' => 'America/Bogota',
                '(GMT-04:00) Atlantic Time (Canada)' => 'America/Halifax',
                '(GMT-04:00) Caracas' => 'America/Caracas',
                '(GMT-04:00) La Paz' => 'America/La_Paz',
                '(GMT-04:00) Santiago' => 'America/Santiago',
                '(GMT-03:30) Newfoundland' => 'America/St_Johns',
                '(GMT-03:00) Brasilia' => 'America/Sao_Paulo',
                '(GMT-03:00) Buenos Aires' => 'America/Argentina/Buenos_Aires',
                '(GMT-03:00) Georgetown' => 'America/Argentina/Buenos_Aires',
                '(GMT-03:00) Greenland' => 'America/Godthab',
                '(GMT-02:00) Mid-Atlantic' => 'America/Noronha',
                '(GMT-01:00) Azores' => 'Atlantic/Azores',
                '(GMT-01:00) Cape Verde Is.' => 'Atlantic/Cape_Verde',
                '(GMT) Casablanca' => 'Africa/Casablanca',
                '(GMT) Dublin' => 'Europe/London',
                '(GMT) Edinburgh' => 'Europe/London',
                '(GMT) Lisbon' => 'Europe/Lisbon',
                '(GMT) London' => 'Europe/London',
                '(GMT) UTC' => 'UTC',
                '(GMT) Monrovia' => 'Africa/Monrovia',
                '(GMT+01:00) Amsterdam' => 'Europe/Amsterdam',
                '(GMT+01:00) Belgrade' => 'Europe/Belgrade',
                '(GMT+01:00) Berlin' => 'Europe/Berlin',
                '(GMT+01:00) Bern' => 'Europe/Berlin',
                '(GMT+01:00) Bratislava' => 'Europe/Bratislava',
                '(GMT+01:00) Brussels' => 'Europe/Brussels',
                '(GMT+01:00) Budapest' => 'Europe/Budapest',
                '(GMT+01:00) Copenhagen' => 'Europe/Copenhagen',
                '(GMT+01:00) Ljubljana' => 'Europe/Ljubljana',
                '(GMT+01:00) Madrid' => 'Europe/Madrid',
                '(GMT+01:00) Paris' => 'Europe/Paris',
                '(GMT+01:00) Prague' => 'Europe/Prague',
                '(GMT+01:00) Rome' => 'Europe/Rome',
                '(GMT+01:00) Sarajevo' => 'Europe/Sarajevo',
                '(GMT+01:00) Skopje' => 'Europe/Skopje',
                '(GMT+01:00) Stockholm' => 'Europe/Stockholm',
                '(GMT+01:00) Vienna' => 'Europe/Vienna',
                '(GMT+01:00) Warsaw' => 'Europe/Warsaw',
                '(GMT+01:00) West Central Africa' => 'Africa/Lagos',
                '(GMT+01:00) Zagreb' => 'Europe/Zagreb',
                '(GMT+02:00) Athens' => 'Europe/Athens',
                '(GMT+02:00) Bucharest' => 'Europe/Bucharest',
                '(GMT+02:00) Cairo' => 'Africa/Cairo',
                '(GMT+02:00) Harare' => 'Africa/Harare',
                '(GMT+02:00) Helsinki' => 'Europe/Helsinki',
                '(GMT+02:00) Istanbul' => 'Europe/Istanbul',
                '(GMT+02:00) Jerusalem' => 'Asia/Jerusalem',
                '(GMT+02:00) Kyev' => 'Europe/Kiev',
                '(GMT+02:00) Minsk' => 'Europe/Minsk',
                '(GMT+02:00) Pretoria' => 'Africa/Johannesburg',
                '(GMT+02:00) Riga' => 'Europe/Riga',
                '(GMT+02:00) Sofia' => 'Europe/Sofia',
                '(GMT+02:00) Tallinn' => 'Europe/Tallinn',
                '(GMT+02:00) Vilnius' => 'Europe/Vilnius',
                '(GMT+03:00) Baghdad' => 'Asia/Baghdad',
                '(GMT+03:00) Kuwait' => 'Asia/Kuwait',
                '(GMT+03:00) Moscow' => 'Europe/Moscow',
                '(GMT+03:00) Nairobi' => 'Africa/Nairobi',
                '(GMT+03:00) Riyadh' => 'Asia/Riyadh',
                '(GMT+03:00) St. Petersburg' => 'Europe/Moscow',
                '(GMT+03:00) Volgograd' => 'Europe/Volgograd',
                '(GMT+03:30) Tehran' => 'Asia/Tehran',
                '(GMT+04:00) Abu Dhabi' => 'Asia/Muscat',
                '(GMT+04:00) Baku' => 'Asia/Baku',
                '(GMT+04:00) Muscat' => 'Asia/Muscat',
                '(GMT+04:00) Tbilisi' => 'Asia/Tbilisi',
                '(GMT+04:00) Yerevan' => 'Asia/Yerevan',
                '(GMT+04:30) Kabul' => 'Asia/Kabul',
                '(GMT+05:00) Ekaterinburg' => 'Asia/Yekaterinburg',
                '(GMT+05:00) Islamabad' => 'Asia/Karachi',
                '(GMT+05:00) Karachi' => 'Asia/Karachi',
                '(GMT+05:00) Tashkent' => 'Asia/Tashkent',
                '(GMT+05:30) Chennai' => 'Asia/Kolkata',
                '(GMT+05:30) Kolkata' => 'Asia/Kolkata',
                '(GMT+05:30) Mumbai' => 'Asia/Kolkata',
                '(GMT+05:30) New Delhi' => 'Asia/Kolkata',
                '(GMT+05:45) Kathmandu' => 'Asia/Kathmandu',
                '(GMT+06:00) Almaty' => 'Asia/Almaty',
                '(GMT+06:00) Astana' => 'Asia/Dhaka',
                '(GMT+06:00) Dhaka' => 'Asia/Dhaka',
                '(GMT+06:00) Novosibirsk' => 'Asia/Novosibirsk',
                '(GMT+06:00) Sri Jayawardenepura' => 'Asia/Colombo',
                '(GMT+06:30) Rangoon' => 'Asia/Rangoon',
                '(GMT+07:00) Bangkok' => 'Asia/Bangkok',
                '(GMT+07:00) Hanoi' => 'Asia/Bangkok',
                '(GMT+07:00) Jakarta' => 'Asia/Jakarta',
                '(GMT+07:00) Krasnoyarsk' => 'Asia/Krasnoyarsk',
                '(GMT+08:00) Beijing' => 'Asia/Hong_Kong',
                '(GMT+08:00) Chongqing' => 'Asia/Chongqing',
                '(GMT+08:00) Hong Kong' => 'Asia/Hong_Kong',
                '(GMT+08:00) Irkutsk' => 'Asia/Irkutsk',
                '(GMT+08:00) Kuala Lumpur' => 'Asia/Kuala_Lumpur',
                '(GMT+08:00) Perth' => 'Australia/Perth',
                '(GMT+08:00) Singapore' => 'Asia/Singapore',
                '(GMT+08:00) Taipei' => 'Asia/Taipei',
                '(GMT+08:00) Ulaan Bataar' => 'Asia/Irkutsk',
                '(GMT+08:00) Urumqi' => 'Asia/Urumqi',
                '(GMT+09:00) Osaka' => 'Asia/Tokyo',
                '(GMT+09:00) Sapporo' => 'Asia/Tokyo',
                '(GMT+09:00) Seoul' => 'Asia/Seoul',
                '(GMT+09:00) Tokyo' => 'Asia/Tokyo',
                '(GMT+09:00) Yakutsk' => 'Asia/Yakutsk',
                '(GMT+09:30) Adelaide' => 'Australia/Adelaide',
                '(GMT+09:30) Darwin' => 'Australia/Darwin',
                '(GMT+10:00) Brisbane' => 'Australia/Brisbane',
                '(GMT+10:00) Canberra' => 'Australia/Sydney',
                '(GMT+10:00) Guam' => 'Pacific/Guam',
                '(GMT+10:00) Hobart' => 'Australia/Hobart',
                '(GMT+10:00) Melbourne' => 'Australia/Melbourne',
                '(GMT+10:00) Port Moresby' => 'Pacific/Port_Moresby',
                '(GMT+10:00) Sydney' => 'Australia/Sydney',
                '(GMT+10:00) Vladivostok' => 'Asia/Vladivostok',
                '(GMT+11:00) Magadan' => 'Asia/Magadan',
                '(GMT+11:00) New Caledonia' => 'Asia/Magadan',
                '(GMT+11:00) Solomon Is.' => 'Asia/Magadan',
                '(GMT+12:00) Auckland' => 'Pacific/Auckland',
                '(GMT+12:00) Fiji' => 'Pacific/Fiji',
                '(GMT+12:00) Kamchatka' => 'Asia/Kamchatka',
                '(GMT+12:00) Marshall Is.' => 'Pacific/Fiji',
                '(GMT+12:00) Wellington' => 'Pacific/Auckland',
                '(GMT+13:00) Nuku\'alofa' => 'Pacific/Tongatapu'
        );
    
}

if (!function_exists('app_timezone')) {
    function app_timezone()
    {
        return config('app.timezone');
    }
}

if (!function_exists('api_asset')) {
    function api_asset($id)
    {
        $asset = \DB::table('uploads')->where('id',$id)->first();
        if ($asset) {
            return $asset->file_name;
        }
        return "";
    }
}

//return file uploaded via uploader
if (!function_exists('uploaded_asset')) {
    function uploaded_asset($id)
    {
        $asset = \DB::table('uploads')->where('id', $id)->first();
        if ($asset != null) {
            return my_asset($asset->file_name);
        }
        return null;
    }
}

if (!function_exists('my_asset')) {
    /**
     * Generate an asset path for the application.
     *
     * @param string $path
     * @param bool|null $secure
     * @return string
     */
    function my_asset($path, $secure = null)
    {
        if (env('FILESYSTEM_DRIVER') == 's3') {
            return Storage::disk('s3')->url($path);
        } else {
            //public/
            if(env('APP_LOC') == 'local'){
                return app('url')->asset('' . $path, $secure);
            } else {
                return app('url')->asset('public/' . $path, $secure);
            }

           // return app('url')->asset('' . $path, $secure);
        }
    }
}

if (!function_exists('static_asset')) {
    /**
     * Generate an asset path for the application.
     *
     * @param string $path
     * @param bool|null $secure
     * @return string
     */
    function static_asset($path, $secure = null)
    {

        if(env('APP_LOC') == 'local'){
                return app('url')->asset('' . $path, $secure);
            } else {
                return app('url')->asset('public/' . $path, $secure);
            }
        //public/
        //return app('url')->asset('' . $path, $secure);
    }
}


// if (!function_exists('isHttps')) {
//     function isHttps()
//     {
//         return !empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS']);
//     }
// }

if (!function_exists('getBaseURL')) {
    function getBaseURL()
    {
        $root = '//' . $_SERVER['HTTP_HOST'];
        $root .= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);

        return $root;
    }
}


if (!function_exists('getFileBaseURL')) {
    function getFileBaseURL()
    {
        if (env('FILESYSTEM_DRIVER') == 's3') {
            return env('AWS_URL') . '/';
        } elseif(env('APP_LOC') == 'local') {
            return getBaseURL() . '/';
        } else {
            return getBaseURL() . 'public/';
        }
    }
}


if (!function_exists('isUnique')) {
    /**
     * Generate an asset path for the application.
     *
     * @param string $path
     * @param bool|null $secure
     * @return string
     */
    function isUnique($email)
    {
        $user = \App\User::where('email', $email)->first();

        if ($user == null) {
            return '1'; // $user = null means we did not get any match with the email provided by the user inside the database
        } else {
            return '0';
        }
    }
}

if (!function_exists('get_setting')) {
    function get_setting($key, $default = null, $lang = false)
    {
        // $settings = Cache::remember('business_settings', 86400, function () {
        //     return BusinessSetting::all();
        // });

        $settings = BusinessSetting::all();

        if ($lang == false) {
            $setting = $settings->where('type', $key)->first();
        } else {
            $setting = $settings->where('type', $key)->where('lang', $lang)->first();
            $setting = !$setting ? $settings->where('type', $key)->first() : $setting;
        }
        return $setting == null ? $default : $setting->value;
    }
}

if (!function_exists('get_file_system')) {
    function get_file_system($key, $default = null)
    {
        $settings = \App\Models\FileSystem::all();

        $setting = $settings->where('name', $key)->first();

        return $setting == null ? $default : $setting->value;
    }
}

function convertHexToRgba($color, $opacity = false){
        $default = 'rgb(230,46,4)';
        //Return default if no color provided
        if(empty($color))
              return $default;

        //Sanitize $color if "#" is provided
        if ($color[0] == '#' ) {
            $color = substr( $color, 1 );
        }

        //Check if color has 6 or 3 characters and get values
        if (strlen($color) == 6) {
            $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
        } elseif ( strlen( $color ) == 3 ) {
            $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
        } else {
            return $default;
        }

        //Convert hexadec to rgb
        $rgb = array_map('hexdec', $hex);

        //Check if opacity is set(rgba or rgb)
        if($opacity){
            if(abs($opacity) > 1)
                $opacity = 1.0;
            $output = 'rgba('.implode(",",$rgb).','.$opacity.')';
        } else {
            $output = 'rgb('.implode(",",$rgb).')';
        }

        //Return rgb(a) color string
        return $output;
    }

function hex2rgba($color, $opacity = false)
{
    return convertHexToRgba($color, $opacity);
}

if (!function_exists('isAdmin')) {
    function isAdmin()
    {
        if (Auth::check() && (Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff')) {
            return true;
        }
        return false;
    }
}

if (!function_exists('isSeller')) {
    function isSeller()
    {
        if (Auth::check() && Auth::user()->user_type == 'seller') {

            if( isSingleStoreActivated() ) {
                abort(404);
            }

            return true;
        }
        return false;
    }
}

if (!function_exists('isCustomer')) {
    function isCustomer()
    {
        if (Auth::check() && Auth::user()->user_type == 'customer') {
            return true;
        }
        return false;
    }
}

if (!function_exists('formatBytes')) {
    function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        // Uncomment one of the following alternatives
        $bytes /= pow(1024, $pow);
        // $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}

// duplicates m$ excel's ceiling function
if (!function_exists('ceiling')) {
    function ceiling($number, $significance = 1)
    {
        return (is_numeric($number) && is_numeric($significance)) ? (ceil($number / $significance) * $significance) : false;
    }
}

if (!function_exists('get_images')) {
    function get_images($given_ids, $with_trashed = false)
    {
        if (is_array($given_ids)) {
            $ids = $given_ids;
        } elseif ($given_ids == null) {
            $ids = [];
        } else {
            $ids = explode(",", $given_ids);
        }


        return $with_trashed
            ? Upload::withTrashed()->whereIn('id', $ids)->get()
            : Upload::whereIn('id', $ids)->get();
    }
}

//for api
if (!function_exists('get_images_path')) {
    function get_images_path($given_ids, $with_trashed = false)
    {
        $paths = [];
        $images = get_images($given_ids, $with_trashed);
        if (!$images->isEmpty()) {
            foreach ($images as $image) {
                $paths[] = !is_null($image) ? $image->file_name : "";
            }
        }

        return $paths;

    }
}

//for order api 

if (!function_exists('order_create')) {
    function order_create($user_id, $seller_id,$payment_type){
        $cartItems = Cart::where('user_id', $user_id)->get();

        if ($cartItems->isEmpty()) {
            return $order_id = 0;
        }

        $user = User::find($user_id);

        $address = Address::where('id', $cartItems->first()->address_id)->first();
        $shippingAddress = [];
        if ($address != null) {
            $shippingAddress['name']        = $user->name;
            $shippingAddress['email']       = $user->email;
            $shippingAddress['address']     = $address->address;
            $shippingAddress['country']     = $address->country;
            $shippingAddress['city']        = $address->city;
            $shippingAddress['postal_code'] = $address->postal_code;
            $shippingAddress['phone']       = $address->phone;
            if($address->latitude || $address->longitude) {
                $shippingAddress['lat_lang'] = $address->latitude.','.$address->longitude;
            }
        }

        $subtotal = 0;
        $tax = 0;
        $shipping = 0;

        $order_ids = [];

        foreach ($cartItems as $key => $cartItem) {
            $product = Product::find($cartItem->product_id);
            $request = new Request;
            $order = new Order;
            $order->user_id = $user_id; 
            $order->seller_id = $product->user_id;
            $order->shipping_address = json_encode($shippingAddress);
            $order->payment_type = $payment_type;
            $order->payment_status = 'paid'; 
            $order->shipping_method = get_setting('shipping_type');
            $order->delivery_viewed = '0';
            $order->payment_status_viewed = '0';
            $order->code = date('Ymd-His') . rand(10, 99);
            $order->date = strtotime('now');
            $order->save();

            $order_ids[$key] = $order->id;

            if (!$product) {
                return response()->json([
                    'result' => false,
                    'message' => trans('messages.api.product_not_found')
                ]);
            }

            $subtotal = $cartItem->price * $cartItem->quantity;
            $tax = $cartItem->tax * $cartItem->quantity;
            $product_variation = $cartItem->variation;

            $product_stocks = $product->stocks->where('variant', $cartItem->variation)->first();
            $product_stocks->qty -= $cartItem->quantity;
            $product_stocks->save();

            $order_detail = new OrderDetail;
            $order_detail->order_id = $order->id;
            $order_detail->seller_id = $product->user_id;
            $order_detail->product_id = $product->id;
            $order_detail->variation = $product_variation;
            $order_detail->price = $cartItem->price * $cartItem->quantity;
            $order_detail->tax = $cartItem->tax * $cartItem->quantity;
            $order_detail->shipping_type = $cartItem->shipping_type;
            $order_detail->product_referral_code = $cartItem->product_referral_code;
            $order_detail->shipping_cost = $cartItem->shipping_cost;
            $order_detail->quantity = $cartItem->quantity;
            $order_detail->payment_status = 'paid';

            $shipping = $order_detail->shipping_cost;
            if ($cartItem->shipping_type == 'pickup_point') {
                $order_detail->pickup_point_id = $cartItem->pickup_point;
            }
            $order_detail->save();

            $product->update([
                'num_of_sale' => \DB::raw('num_of_sale + ' . $cartItem->quantity)
            ]);

            $order->grand_total = $subtotal + $tax + $shipping;

            if($cartItem->coupon_code != '') {
                $order->grand_total -= $cartItem->discount;
                if ($order->grand_total < 0) {
                    $order->grand_total = 0;
                    $order->payment_status = 'paid';
                }
                if (Session::has('club_point')) {
                    $order->club_point = Session::get('club_point');
                }
                $order->coupon_discount = $cartItem->discount;

                $coupon_usage = new CouponUsage;
                $coupon_usage->user_id = $user_id;
                $coupon_usage->coupon_id = $cartItem->coupon_code;
                $coupon_usage->save();
            }

            $order->save();

            if (\App\Addon::where('unique_identifier', 'otp_system')->first() != null &&
            \App\Addon::where('unique_identifier', 'otp_system')->first()->activated &&
            SmsTemplate::where('identifier', 'order_placement')->first()->status == 1) {
                try {
                    $otpController = new OTPVerificationController;
                    $otpController->send_order_code($order);
                } catch (\Exception $e) {

                }
            }

            //sends Notifications to user
            send_notification($order, 'placed');
            if (get_setting('google_firebase') == 1 && $order->user->device_token != null) {
                $request->device_token = $order->user->device_token;
                $request->title = "Order placed !";
                $request->text = " An order {$order->code} has been placed";

                $request->type = "order";
                $request->id = $order->id;
                $request->user_id = $order->user->id;

                send_firebase_notification($request);
            }

            $array['view'] = 'emails.invoice';
            $array['subject'] = translate('Your order has been placed') . ' - ' . $order->code;
            $array['from'] = get_setting('mail_from_address');
            $array['order'] = $order;

            //sends email to customer with the invoice pdf attached
            if (get_setting('mail_username') != null) {
                try {
                    \Mail::to($order->user->email)->queue(new InvoiceEmailManager($array));
                    \Mail::to(User::where('user_type', 'admin')->first()->email)->queue(new InvoiceEmailManager($array));
                } catch (\Exception $e) {
                    logger('mail error - '. $e->getMessage());
                }
            }

            if ($product->added_by != 'admin') {
                try {
                    \Mail::to(\App\User::find($product->user_id)->email)->queue(new InvoiceEmailManager($array));
                } catch (\Exception $e) {
                    logger('mail error - '. $e->getMessage());
                }
            }
        }

        Cart::where('user_id', $user_id)->delete();

        return $order_ids;
    }
}

//for api
if (!function_exists('checkout_done')) {
    function checkout_done($order_ids, $payment)
    {
        foreach($order_ids as $order_id) {
            $order = Order::findOrFail($order_id);
            $order->payment_status = 'paid';
            $order->payment_details = $payment;
            $order->save();

            send_notification($order, 'placed');

            if (\App\Addon::where('unique_identifier', 'affiliate_system')->first() != null && \App\Addon::where('unique_identifier', 'affiliate_system')->first()->activated) {
                $affiliateController = new AffiliateController;
                $affiliateController->processAffiliatePoints($order);
            }

            if (\App\Addon::where('unique_identifier', 'club_point')->first() != null && \App\Addon::where('unique_identifier', 'club_point')->first()->activated) {
                if (Auth::check()) {
                    $clubpointController = new ClubPointController;
                    $clubpointController->processClubPoints($order);
                }
            }
            $vendor_commission_activation = true;
            if (\App\Addon::where('unique_identifier', 'seller_subscription')->first() != null
                && \App\Addon::where('unique_identifier', 'seller_subscription')->first()->activated
                && !get_setting('vendor_commission_activation')) {
                $vendor_commission_activation = false;
            }

            if ($vendor_commission_activation) {
                if (BusinessSetting::where('type', 'category_wise_commission')->first()->value != 1) {
                    $commission_percentage = BusinessSetting::where('type', 'vendor_commission')->first()->value;
                    foreach ($order->orderDetails as $key => $orderDetail) {
                        $orderDetail->payment_status = 'paid';
                        $orderDetail->save();
                        if ($orderDetail->product->user->user_type == 'seller') {
                            $seller = $orderDetail->product->user->seller;
                            $seller->admin_to_pay = $seller->admin_to_pay + ($orderDetail->price * (100 - $commission_percentage)) / 100 + $orderDetail->tax + $orderDetail->shipping_cost;
                            $seller->save();
                        }
                    }
                } else {
                    foreach ($order->orderDetails as $key => $orderDetail) {
                        $orderDetail->payment_status = 'paid';
                        $orderDetail->save();
                        if ($orderDetail->product->user->user_type == 'seller') {
                            $commission_percentage = $orderDetail->product->category->commision_rate;
                            $seller = $orderDetail->product->user->seller;
                            $seller->admin_to_pay = $seller->admin_to_pay + ($orderDetail->price * (100 - $commission_percentage)) / 100 + $orderDetail->tax + $orderDetail->shipping_cost;
                            $seller->save();
                        }
                    }
                }
            } else {
                foreach ($order->orderDetails as $key => $orderDetail) {
                    $orderDetail->payment_status = 'paid';
                    $orderDetail->save();
                    if ($orderDetail->product->user->user_type == 'seller') {
                        $seller = $orderDetail->product->user->seller;
                        $seller->admin_to_pay = $seller->admin_to_pay + $orderDetail->price + $orderDetail->tax + $orderDetail->shipping_cost;
                        $seller->save();
                    }
                }
            }

            $order->commission_calculated = 1;
            $order->save();
        }
    }
}

//for api
if (!function_exists('wallet_payment_done')) {
    function wallet_payment_done($user_id, $amount, $payment_method, $payment_details)
    {
        $user = \App\User::find($user_id);
        $user->balance = $user->balance + $amount;
        $user->save();

        $wallet = new Wallet;
        $wallet->user_id = $user->id;
        $wallet->amount = $amount;
        $wallet->payment_method = $payment_method;
        $wallet->payment_details = $payment_details;
        $wallet->save();

    }
}

if (!function_exists('purchase_payment_done')) {
    function purchase_payment_done($user_id, $package_id)
    {
        $user = User::findOrFail($user_id);
        $user->customer_package_id = $package_id;
        $customer_package = CustomerPackage::findOrFail($package_id);
        $user->remaining_uploads += $customer_package->product_upload;
        $user->save();

        return 'success';

    }
}

//Commission Calculation
if (!function_exists('commission_calculation')) {
    function commission_calculation($order)
    {
        $vendor_commission_activation = true;
        if (\App\Addon::where('unique_identifier', 'seller_subscription')->first() != null
            && \App\Addon::where('unique_identifier', 'seller_subscription')->first()->activated
            && !get_setting('vendor_commission_activation')) {
            $vendor_commission_activation = false;
        }

        if ($vendor_commission_activation) {    
            if ($order->payment_type == 'cash_on_delivery') {
                foreach ($order->orderDetails as $key => $orderDetail) {
                    $orderDetail->payment_status = 'paid';
                    $orderDetail->save();
                    $commission_percentage = 0;
                    if (get_setting('category_wise_commission') != 1) {
                        $commission_percentage = get_setting('vendor_commission');
                    } else if ($orderDetail->product->user->user_type == 'seller') {
                        $commission_percentage = $orderDetail->product->category->commision_rate;
                    }
                    if ($orderDetail->product->user->user_type == 'seller') {
                        $seller = $orderDetail->product->user->seller;
                        $admin_commission = ($orderDetail->price * $commission_percentage) / 100;
                        
                        if (get_setting('product_manage_by_admin') == 1) {
                            $seller_earning = ($orderDetail->tax + $orderDetail->price) - $admin_commission;
                            // $seller->admin_to_pay += $seller_earning;
                        } else {
                            $seller_earning = ($orderDetail->tax + $orderDetail->shipping_cost + $orderDetail->price) - $admin_commission;
                            // $seller->admin_to_pay += $seller_earning;
                        }

                        $seller->save();

                        $commission_history = new CommissionHistory;
                        $commission_history->order_id = $order->id;
                        $commission_history->order_detail_id = $orderDetail->id;
                        $commission_history->seller_id = $orderDetail->seller_id;
                        $commission_history->admin_commission = $admin_commission;
                        $commission_history->seller_earning = $seller_earning;

                        $commission_history->save();

                        $owe_amount = OweAmount::where(['order_id'=>$order->id, 'status'=>'Pending']);

                        if(!$owe_amount->count()) {
                            $owe_amount = new OweAmount;
                            $owe_amount->order_id = $order->id;
                            $owe_amount->seller_id = $orderDetail->seller_id;
                            $owe_amount->total_amount = $admin_commission;
                            $owe_amount->remain_amount = $admin_commission;
                            $owe_amount->paid_amount = 0;
                            $owe_amount->status = 'Pending';
                            $owe_amount->currency = currency_code();
                            $owe_amount->save();
                        }

                        $settings = BusinessSetting::where('type','max_owe_amount')->first();
                        $owe_amount = OweAmount::where(['seller_id'=>Auth::user()->id, 'status' => 'Pending'])->get()->sum('convert_remain_amount');

                        if($settings->convert_max_amount < $owe_amount) {
                            Product::where('user_id', Auth::user()->id)->update(['published' => 0,'featured' => 0]);
                        }
                    }
                }
            } elseif ($order->manual_payment || $order->payment_type!='cash_on_delivery') {
                foreach ($order->orderDetails as $key => $orderDetail) {
                    $orderDetail->payment_status = 'paid';
                    $orderDetail->save();
                    $commission_percentage = 0;
                    if (get_setting('category_wise_commission') != 1) {
                        $commission_percentage = BusinessSetting::where('type', 'vendor_commission')->first()->value;
                    } else if ($orderDetail->product->user->user_type == 'seller') {
                        $commission_percentage = $orderDetail->product->category->commision_rate;
                    }
                    if ($orderDetail->product->user->user_type == 'seller') {
                        $seller = $orderDetail->product->user->seller;
                        // $admin_commission = ($orderDetail->price * $commission_percentage) / 100;
                        $admin_commission = (($orderDetail->price + $orderDetail->tax + $orderDetail->shipping_cost) * $commission_percentage) / 100;

                        if (get_setting('product_manage_by_admin') == 1) {
                            $seller_earning = ($orderDetail->tax + $orderDetail->price + $orderDetail->shipping_cost) - $admin_commission;
                            $seller->admin_to_pay += $seller_earning;
                        } else {
                            $seller_earning = ($orderDetail->tax + $orderDetail->shipping_cost + $orderDetail->price) - $admin_commission;
                            $seller->admin_to_pay += $seller_earning;
                        }

                        $seller->save();

                        $commission_history = new CommissionHistory;
                        $commission_history->order_id = $order->id;
                        $commission_history->order_detail_id = $orderDetail->id;
                        $commission_history->seller_id = $orderDetail->seller_id;
                        $commission_history->admin_commission = $admin_commission;
                        $commission_history->seller_earning = $seller_earning;

                        $commission_history->save();
                    }
                }
            }
        }

        if (\App\Addon::where('unique_identifier', 'affiliate_system')->first() != null && \App\Addon::where('unique_identifier', 'affiliate_system')->first()->activated) {
            $affiliateController = new AffiliateController;
            $affiliateController->processAffiliatePoints($order);
        }

        if (\App\Addon::where('unique_identifier', 'club_point')->first() != null && \App\Addon::where('unique_identifier', 'club_point')->first()->activated) {
            if ($order->user != null) {
                $clubpointController = new ClubPointController;
                $clubpointController->processClubPoints($order);
            }
        }
    }
}

//Send Notification
if (!function_exists('send_notification')) {
    function send_notification($order, $order_status)
    {
        if ($order->seller_id == \App\User::where('user_type', 'admin')->first()->id) {
            $users = User::findMany([$order->user->id, $order->seller_id]);
        } else {
            $users = User::findMany([$order->user->id, $order->seller_id, \App\User::where('user_type', 'admin')->first()->id]);
        }

        $order_notification = array();
        $order_notification['order_id'] = $order->id;
        $order_notification['order_code'] = $order->code;
        $order_notification['user_id'] = $order->user_id;
        $order_notification['seller_id'] = $order->seller_id;
        $order_notification['status'] = $order_status;
       
        Notification::send($users, new OrderNotification($order_notification));
    }
}

if (!function_exists('send_firebase_notification')) {
    function send_firebase_notification($req)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';


        $fields = array
        (
            'to' => $req->device_token,
            'notification' => [
                'body' => $req->text,
                'title' => $req->title,
                'sound' => 'default' /*Default sound*/
            ],
            'data' => [
                'item_type' => $req->type,
                'item_type_id' => $req->id,
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK'
            ]
        );

        //$fields = json_encode($arrayToSend);
        $headers = array(
            'Authorization: key=' . env('FCM_SERVER_KEY'),
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        $result = curl_exec($ch);
//        var_dump($result);
        curl_close($ch);
//        return $result;

        $firebase_notification = new FirebaseNotification;
        $firebase_notification->title = $req->title;
        $firebase_notification->text = $req->text;
        $firebase_notification->item_type = $req->type;
        $firebase_notification->item_type_id = $req->id;
        $firebase_notification->receiver_id = $req->user_id;

        $firebase_notification->save();
    }
}

// Addon Activation Check
if (!function_exists('addon_activated')) {
    function addon_activated($identifier, $default = null)
    {
        $activation = Addon::where('unique_identifier', $identifier)->where('activated',1)->first();
        return $activation == null ? false : true;
    }
}

// is Single Store Activated
if (!function_exists('isSingleStoreActivated')) {
    function isSingleStoreActivated()
    {
        return get_setting('vendor_system_activation') != 1 ? 1 : 0;
    }
}

/**
 * Do CURL With POST
 *
 * @param  String $url  Url
 * @param  Array $params  Url Parameters
 * @return string $data Response of URL
 */
if (!function_exists('curlPost')) {

    function curlPost($url,$params)
    {
        $ch = curl_init();

        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_HEADER, false); 
        curl_setopt($ch,CURLOPT_POST, count($params));
        curl_setopt($ch,CURLOPT_POSTFIELDS, http_build_query($params));    
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'User-Agent: curl',
        ]);
        $output = curl_exec($ch);

        curl_close($ch);
        return json_decode($output,true);
    }
}

/**
 * Get a Apple Login URL
 *
 * @return URL from Apple API
 */
if (!function_exists('getAppleLoginUrl')) {
    function getAppleLoginUrl()
    {
        $params = [
            'response_type'     => 'code',
            'response_mode'     => 'form_post',
            'client_id'         => get_setting('apple_service_id'),
            'redirect_uri'      => url('apple_callback'),
            'state'             => bin2hex(random_bytes(5)),
            'scope'             => 'name email',
        ];
        $authorize_url = 'https://appleid.apple.com/auth/authorize?'.http_build_query($params);

        return $authorize_url;
    }
}

/**
 * Get a Apple Login URL
 *
 * @return URL from Apple API
 */
if (!function_exists('getAppleApiLoginUrl')) {
    function getAppleApiLoginUrl()
    {
        $params = [
            'response_type'     => 'code',
            'response_mode'     => 'form_post',
            'client_id'         => get_setting('apple_service_id'),
            'redirect_uri'      => url('api/v2/auth/apple_callback'),
            'state'             => bin2hex(random_bytes(5)),
            'scope'             => 'name email',
        ];
        $authorize_url = 'https://appleid.apple.com/auth/authorize?'.http_build_query($params);

        return $authorize_url;
    }
}

/**
 * Generate Apple Client Secret
 *
 * @return String $token
 */
if (!function_exists('getAppleClientSecret')) {
    function getAppleClientSecret()
    {
        $key_file = base_path().get_setting('apple_key_file');

        $algorithmManager = new AlgorithmManager([new ES256()]);
        $jwsBuilder = new JWSBuilder($algorithmManager);
        $jws = $jwsBuilder
            ->create()
            ->withPayload(json_encode([
                'iat' => time(),
                'exp' => time() + 86400*180,
                'iss' => get_setting('apple_team_id'),
                'aud' => 'https://appleid.apple.com',
                'sub' => get_setting('apple_service_id'),
            ]))
            ->addSignature(JWKFactory::createFromKeyFile($key_file), [
                'alg' => 'ES256',
                'kid' => get_setting('apple_key_id')
            ])
            ->build();

        $serializer = new CompactSerializer();
        $token = $serializer->serialize($jws, 0);
        
        return $token;
    }
}

/**
 * Check Current Environment
 *
 * @return Boolean true or false
 */
if (!function_exists('isLiveEnv')) {
    function isLiveEnv($environments = [])
    {
        if(count($environments) > 0) {
            array_push($environments, 'live');
            return in_array(env('APP_ENV'),$environments);
        }
        return env('APP_ENV') == 'live';
    }
}

/**
 * Check Current Environment
 *
 * @return Boolean true or false
 */
if (!function_exists('canDisplayCredentials')) {
    function canDisplayCredentials()
    {
        // return env('APP_ENV') == 'live';
        return env('SHOW_CREDENTIALS','false') == 'true';
    }
}

if ( ! function_exists('updateEnvConfig'))
{
    function updateEnvConfig($envKey, $envValue)
    {
        $envFile = app()->environmentFilePath();
        $str = file_get_contents($envFile);

        try {
            $str .= "\n";
            $keyPosition = strpos($str, "{$envKey}=");
            $endOfLinePosition = strpos($str, "\n", $keyPosition);
            $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);

            if(!$keyPosition || !$endOfLinePosition || !$oldLine) {
                $str .= "{$envKey}={$envValue}\n";
            } else {

                if($envKey=='IP_ADDRESS') {
                    if($envValue=='delete') {
                        $envValue = '';
                    } else {

                        $oldValue = substr(strrchr($oldLine, '='), 1);

                        if(!$oldValue) {
                            $oldValues = array();
                        } else {
                            $oldValues = explode(',', $oldValue);
                        }

                        $envValue_delete = explode('_', $envValue);

                        if(count($envValue_delete)>1 && $envValue_delete[1]=='delete') {
                            $oldKey = array_search($envValue_delete[0], $oldValues);
                            if($oldKey!==false) {
                                unset($oldValues[$oldKey]);
                            }
                            if(count($oldValues)) {
                                $envValue = implode(',', $oldValues);
                            } else {
                                $envValue = '';
                            }
                        } else {

                            $envValue = filter_var($envValue, FILTER_VALIDATE_IP);

                            if($envValue && !in_array($envValue, $oldValues)) {
                                $oldValues[count($oldValues)] = $envValue;
                                if(count($oldValues)>1) {
                                    $envValue = implode(',', $oldValues);
                                }
                            } else {
                                $envValue = $oldValue;
                            }   
                        }
                    }
                }
                //logger($envKey.' - '.$envValue);
                $str = str_replace($oldLine, "{$envKey}={$envValue}", $str);
            }
            $str = substr($str, 0, -1);
            // dd($str);
            file_put_contents($envFile, $str);
        } catch(\Exception $e) {
            \Log::error($e->getMessage());
        }
    }
}

/**
 * get protected String or normal based on env
 *
 * @param {string} $str
 *
 * @return {string}
 */
if (!function_exists('protectedString')) {
    
    function protectedString($str) {
        if(isLiveEnv()) {
            return substr($str, 0, 1) . '****' . substr($str,  -4);
        }
        return $str;
    }
}

if (!function_exists('protectedEmail')) {
    
    function protectedEmail($email) {
        $exp = explode('@', $email);

        $end = end($exp);
        unset( $exp[ count($exp) - 1 ] );

        $join = implode('@', $exp);
        return substr($join, 0, 1) . '****@'.$end;
    }
}

if (!function_exists('is_wished')) {
    
    function is_wished($pid, $uid=null) {
        $uid = (Auth::user()) ? Auth::user()->id : $uid;
        $wishlist = Wishlist::where('user_id', $uid)->where('product_id', $pid)->first();
        return ($wishlist) ? 1 : 0;
    }
}

if(!function_exists('get_converted_time')) {
    function get_converted_time($datetime, $format = 'd-m-Y h:i A') {
        $timezone = (env('APP_TIMEZONE')) ? env('APP_TIMEZONE') : 'America/New_York';
        $dt = new DateTime("now", new DateTimeZone($timezone));
        $dt->setTimestamp($datetime);
        return $dt->format($format);
    }
}

if(!function_exists('get_least_status')) {
    function get_least_status($order, $type) {
        $status_array = $type == 'payment' ? collect(['unpaid', 'paid']) : collect(['pending', 'confirmed', 'on_the_way', 'delivered']);

        $order_status = $type == 'payment' ? $order->orderDetails->pluck('payment_status') : $order->orderDetails->pluck('delivery_status');

        foreach($status_array as $status) {
            if($order_status->contains($status)) {
                return $status;
                break;
            }
        }
    }
}

/**
 * Do CURL With POST for SHIP ENGINE API
 *
 * @param  String $url  Url
 * @param  Array $params  Url Parameters
 * @return string $data Response of URL
 */
if (!function_exists('ShipEnginecurl')) {

    function ShipEnginecurl($url, $params, $method = 'POST')
    {
        logger('ShipEnginecurl params-'. json_encode($params));
        $ch = curl_init();

        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_ENCODING, ''); 
        curl_setopt($ch,CURLOPT_MAXREDIRS, 10); 
        curl_setopt($ch,CURLOPT_TIMEOUT, 0); 
        curl_setopt($ch,CURLOPT_FOLLOWLOCATION, true); 
        curl_setopt($ch,CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1); 
        curl_setopt($ch,CURLOPT_CUSTOMREQUEST, $method);
        if($method == 'POST') {
            curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode($params));    
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Host: api.shipengine.com',
            'API-Key: '. get_setting('ship_engine_api_key') .'',
            'Content-Type: application/json'
        ]);

        $output = curl_exec($ch);

        curl_close($ch);
        return json_decode($output,true);
    }
}

/**
 * Check if user is connected to shipengine carrier
 *
 * @param  String $carrier  carrier
 * @return interger $count Response of URL
 */
if (!function_exists('check_if_user_connected_to_carrier')) {

    function check_if_user_connected_to_carrier($carrier, $user_id)
    {
        $carrier_count = \DB::table('user_ship_engine_settings')->where(['user_id' => $user_id, 'carrier_name' => $carrier])->count();

        return $carrier_count;
    }
}

/**
 * Check if user is connected to shipengine carrier
 *
 * @param  String $carrier  carrier
 * @return interger $count Response of URL
 */
if (!function_exists('ship_estimate')) {

    function ship_estimate($req_data)
    {
        try {
            $product = Product::find($req_data['product_id']);
            $product_user = User::find($product->user_id);
            if(request()->segment(1) == 'api') {
                $user = User::find($req_data['user_id']);
                $carrier_id = UserShipEngineSettings::where('user_id', $product->user_id)->first()->carrier_id;
            } else {
                $user = \Auth::user();
                $carrier_id = $req_data['carrier_id'];
            }

            $product_user_address = $product_user->addresses->where('set_default', 1)->first();
            $user_address = $user->addresses->where('set_default', 1)->first();

            $ship_detail['rate_options']['carrier_ids'] = [$carrier_id];
            $ship_detail['shipment']['validate_address'] = 'no_validation';
            $ship_detail1['customs_items']['quantity'] = (int) $req_data['ship_quantity'];
            $ship_detail1['customs_items']['description'] = 'sample';

            $unit_per_carton  =   $product->unit_per_carton;
            $package_weight   =   $product->package_weight;
            $package_unit     =   $product->package_unit;
            if(env('SHIP_ENGINE') == 'live') {
                $package_count  = ceil($req_data['ship_quantity']/$unit_per_carton);
               
                $per_pic_weight = $package_weight/$unit_per_carton;
                $total_weight   = ($per_pic_weight * $req_data['ship_quantity']);

                for($i=0; $i< $package_count;$i++){
                    if($total_weight < $package_weight){
                        $package_weight = $total_weight;
                    }
                    else {
                        $total_weight = $total_weight - $package_weight;
                    }

                    if(isset($req_data['count'])) {
                        // this if statement works only for merchant side
                        for($i=0; $i< $req_data['count']; $i++){
                            $ship_detail['shipment']['packages'][$i]['weight']['value']= $req_data['package_data']['package_weight'][$i];
                            $ship_detail['shipment']['packages'][$i]['weight']['unit']= $req_data['package_data']['package_unit'][$i];
                            $ship_detail['shipment']['packages'][$i]['dimensions']['unit']= $req_data['package_data']['dimension_unit'][$i];
                            $ship_detail['shipment']['packages'][$i]['dimensions']['length']= $req_data['package_data']['package_length'][$i];
                            $ship_detail['shipment']['packages'][$i]['dimensions']['width']= $req_data['package_data']['dimension_width'][$i];
                            $ship_detail['shipment']['packages'][$i]['dimensions']['height']= $req_data['package_data']['dimension_height'][$i];          
                        }
                    } else {
                        $ship_detail['shipment']['packages'][$i]['weight']['value']= $package_weight;
                        $ship_detail['shipment']['packages'][$i]['weight']['unit']= $package_unit;       
                    }
                }
            } else {
                if(isset($req_data['count'])) {
                    // this if statement works only for merchant side
                    for($i=0; $i< $req_data['count']; $i++){
                        $ship_detail['shipment']['packages'][$i]['weight']['value']= $req_data['package_data']['package_weight'][$i];
                        $ship_detail['shipment']['packages'][$i]['weight']['unit']= $req_data['package_data']['package_unit'][$i];
                        $ship_detail['shipment']['packages'][$i]['dimensions']['unit']= $req_data['package_data']['dimension_unit'][$i];
                        $ship_detail['shipment']['packages'][$i]['dimensions']['length']= $req_data['package_data']['package_length'][$i];
                        $ship_detail['shipment']['packages'][$i]['dimensions']['width']= $req_data['package_data']['dimension_width'][$i];
                        $ship_detail['shipment']['packages'][$i]['dimensions']['height']= $req_data['package_data']['dimension_height'][$i];          
                    }
                } else {
                    $ship_detail['shipment']['packages'][0]['weight']['value']= $package_weight;
                    $ship_detail['shipment']['packages'][0]['weight']['unit']= $package_unit;
                }
            }

            $ship_detail['shipment']['ship_to']['name']           = $user->name;
            $ship_detail['shipment']['ship_to']['phone']          = $user_address->phone;
            $ship_detail['shipment']['ship_to']['address_line1']  = $user_address->address;
            $ship_detail['shipment']['ship_to']['city_locality']  = $user_address->city;
            $ship_detail['shipment']['ship_to']['state_province'] = \DB::table('states')->where('name', $user_address->state)->first()->short_name;
            $ship_detail['shipment']['ship_to']['postal_code']    = $req_data['from_zip_code'];
            $ship_detail['shipment']['ship_to']['country_code']   = \DB::table('countries')->where('name', $user_address->country)->first()->code;

            $ship_detail['shipment']['customs']['contents']  = "documents";
            $ship_detail['shipment']['customs']['customs_items'][] = $ship_detail1['customs_items'];
            $ship_detail['shipment']['customs']['non_delivery'] = "treat_as_abandoned";

            $ship_detail['shipment']['ship_from']['name']           = $product_user->name;
            $ship_detail['shipment']['ship_from']['phone']          = $product_user_address->phone;
            $ship_detail['shipment']['ship_from']['address_line1']  = $product_user_address->address;
            $ship_detail['shipment']['ship_from']['city_locality']  = $product_user_address->city;
            $ship_detail['shipment']['ship_from']['state_province'] = \DB::table('states')->where('name', $product_user_address->state)->first()->short_name;
            $ship_detail['shipment']['ship_from']['postal_code']    = explode('-', $product_user_address->postal_code)[0];
            $ship_detail['shipment']['ship_from']['country_code']   = \DB::table('countries')->where('name', $product_user_address->country)->first()->code;

            $url = 'https://api.shipengine.com/v1/rates';
            $curl_res = ShipEnginecurl($url, $ship_detail, 'POST');
        } catch(\Exception $e) {
            logger('ship estimate error - '. $e->getMessage());
            return ['status' => false, 'message' => $e->getMessage()];
        }

        $estimate_details = json_decode(json_encode($curl_res));

        if(isset($estimate_details->errors)) {
            return [
                'status' => false,
                'message' => $estimate_details->errors[0]->message,
                'destination_zip_code' => $req_data['from_zip_code'],
                'ship_quantity' => $req_data['ship_quantity'],
                'data' => [],
            ];
        }

        if(isset($estimate_details->rate_response) && count($estimate_details->rate_response->errors)) {
            return [
                'status' => false,
                'message' => $estimate_details->rate_response->errors[0]->message,
                'destination_zip_code' => $req_data['from_zip_code'],
                'ship_quantity' => $req_data['ship_quantity'],
                'data' => [],
            ];
        }

        if(isset($estimate_details->rate_response->invalid_rates) || isset($estimate_details->rate_response->rates)){

            $estimate_array = $estimate_details->rate_response->invalid_rates;
            if(count($estimate_details->rate_response->rates) > 0 ){
              $estimate_array = $estimate_details->rate_response->rates;
            }

            $i=0;
            $a=collect($estimate_array);
            $estimate_array=$a->sortBy('shipping_amount.amount');

            foreach ($estimate_array as $key => $value) {
                $estimates[$i]['delivery_days'] =(($value->delivery_days != '' || $value->delivery_days != null) ?$value->delivery_days : '-');
                $estimates[$i]['rate_id'] = $value->rate_id;
                $estimates[$i]['carrier_id'] = $value->carrier_id;
                $estimates[$i]['service_code'] = $value->service_code;
                $estimates[$i]['service_type'] = $value->service_type;
                $estimates[$i]['carrier_code'] = $value->carrier_code;
                $estimates[$i]['package_type'] = ucwords(str_replace('_', ' ', $value->package_type));

                $total_day=(int)$estimates[$i]['delivery_days']+1;
                date_default_timezone_set("America/Los_Angeles");   

                $today_time=date("H:i:s");
                $days=$total_day;
                if($today_time>='16.00.00'){
                    $days=$total_day+1;
                }

                $format="Y-m-d H:i:s";
                for($current_days=0;$current_days<$days;$current_days++){
                    $day = date('N',strtotime("+".($current_days+1)."day"));
                    if($day>5)
                        $days++;
                }

                $orignal_date=date($format,strtotime("+$current_days day"));
                $date = strtotime($orignal_date);
                $get_day=date('d', $date);

                if($get_day<=9){
                    $get_day=substr($get_day,1);                  
                }

                $estimates[$i]['shipping_amount']['currency']        = "USD";
                $estimates[$i]['shipping_amount']['original_amount'] = $value->shipping_amount->amount;
                $estimates[$i]['shipping_amount']['amount']          = single_price($value->shipping_amount->amount);
                $i++;
            }
        }

        return [
            'status' => true,
            'destination_zip_code' => $req_data['from_zip_code'],
            'ship_quantity' => $req_data['ship_quantity'],
            'data' => $estimates,
        ];
    }
}

if (!function_exists('get_cart_return_data')) {

    function get_cart_return_data()
    {
        $return_data = [];
        if(Auth::check()) {
            $carts = Cart::where('user_id', Auth::user()->id)->get();
            foreach($carts as $key => $cart) {
                $return_data[$cart->id]['id'] = $cart->id;
                $return_data[$cart->id]['price'] = single_price($cart->price);
                $return_data[$cart->id]['quantity'] = $cart->quantity;
                $return_data[$cart->id]['shipping_cost'] = single_price($cart->shipping_cost);
                $return_data[$cart->id]['tax'] = single_price($cart->tax * $cart->quantity);
                $return_data[$cart->id]['total'] = single_price(($cart->price * $cart->quantity) + $cart->shipping_cost + $cart->tax);
            }
        }

        return $return_data;
    }
}
?>
