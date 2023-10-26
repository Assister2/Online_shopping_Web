<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Currency;
use App\BusinessSetting;
use App\Product;
use App\Models\SubscriptionPlan;
use App\Models\FileSystem;
use App\Models\UserSubscriptionRenewal;
use App\Models\UserSubscriptionPlan;
use App\Models\Language;
use App\Http\SubscriptionHelper;
use Validator;
use Artisan;

use Illuminate\Support\Facades\Artisan as FacadesArtisan;

class BusinessSettingsController extends Controller
{   

    public function __construct(SubscriptionHelper $subscription)
     {
        $this->subscription_helper = $subscription;
     }
    public function general_setting(Request $request)
    {        
    	return view('backend.setup_configurations.general_settings');
    }

    public function activation(Request $request)
    {
        $data['subscription_plan'] = SubscriptionPlan::where('status','Active')->first();
        $data['user_subscription_plan'] = UserSubscriptionPlan::where('plan_type','!=','Custom')->where('status','Active')->first();
    	return view('backend.setup_configurations.activation',$data);
    }

    public function social_login(Request $request)
    {
        
        return view('backend.setup_configurations.social_login');
    }

    public function smtp_settings(Request $request)
    {
        
        return view('backend.setup_configurations.smtp_settings');
    }

    public function google_analytics(Request $request)
    {
        
        return view('backend.setup_configurations.google_configuration.google_analytics');
    }

    public function google_recaptcha(Request $request)
    {
        
        return view('backend.setup_configurations.google_configuration.google_recaptcha');
    }
    
    public function google_map(Request $request) {
        
        return view('backend.setup_configurations.google_configuration.google_map');
    }
    
    public function google_firebase(Request $request) {
        
        return view('backend.setup_configurations.google_configuration.google_firebase');
    }

    public function facebook_chat(Request $request)
    {
        
        return view('backend.setup_configurations.facebook_chat');
    }

    public function facebook_comment(Request $request)
    {
        
        return view('backend.setup_configurations.facebook_configuration.facebook_comment');
    }

    public function payment_method(Request $request)
    {
        
        return view('backend.setup_configurations.payment_method');
    }

    public function file_system(Request $request)
    {
        return view('backend.setup_configurations.file_system');
    }

    /**
     * Update the API key's for payment methods.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function payment_method_update(Request $request)
    {
        if ($request->paypal_client_id || $request->paypal_client_secret || $request->paypal_webhook_key) 
        {
            BusinessSetting::where(['type' => 'paypal_client_id'])->update(['value' => $request->paypal_client_id]);
            BusinessSetting::where(['type' => 'paypal_client_secret'])->update(['value' => $request->paypal_client_secret]);
            BusinessSetting::where(['type' => 'paypal_webhook_key'])->update(['value' => $request->paypal_webhook_key]);
        }
        elseif ($request->stripe_key || $request->stripe_secret || $request->stripe_webhook_secret_key) 
        {
            BusinessSetting::where(['type' => 'stripe_key'])->update(['value' => $request->stripe_key]);
            BusinessSetting::where(['type' => 'stripe_secret'])->update(['value' => $request->stripe_secret]);
            BusinessSetting::where(['type' => 'stripe_webhook_secret_key'])->update(['value' => $request->stripe_webhook_secret_key]);
        }

        $business_settings = BusinessSetting::where('type', $request->payment_method.'_sandbox')->first();
        if($business_settings != null){
            if ($request->has($request->payment_method.'_sandbox')) {
                $business_settings->value = 1;
                $business_settings->save();
            }
            else{
                $business_settings->value = 0;
                $business_settings->save();
            }
        }

        Artisan::call('cache:clear');

        flash(translate("settings_updated_successfully"))->success();
        return back();
    }

    /**
     * Update the API key's for GOOGLE analytics.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function google_analytics_update(Request $request)
    {
        foreach ($request->types as $key => $type) {
                $this->overWriteEnvFile($type, $request[$type]);
        }

        $business_settings = BusinessSetting::where('type', 'google_analytics')->first();

        if ($request->has('google_analytics')) {
            $business_settings->value = 1;
            $business_settings->save();
        }
        else{
            $business_settings->value = 0;
            $business_settings->save();
        }

        Artisan::call('cache:clear');

        flash(translate("settings_updated_successfully"))->success();
        return back();
    }

    public function google_recaptcha_update(Request $request)
    {
        if ($request->google_recaptcha) {
            BusinessSetting::where(['type' => 'google_recaptcha_value'])->update(['value' => $request->google_recaptcha_value]);
        }

        $business_settings = BusinessSetting::where('type', 'google_recaptcha')->first();

        if ($request->has('google_recaptcha')) {
            $business_settings->value = 1;
            $business_settings->save();
        }
        else{
            $business_settings->value = 0;
            $business_settings->save();
        }

        Artisan::call('cache:clear');

        flash(translate("settings_updated_successfully"))->success();
        return back();
    }

    public function ship_engine_update(Request $request)
    {
        $where = ['type' => 'ship_engine_api_key'];
        $data = ['value' => $request->ship_engine_api_key];

        BusinessSetting::updateOrCreate($where, $data);

        Artisan::call('cache:clear');

        flash(translate("settings_updated_successfully"))->success();
        return back();
    }

    public function google_map_update(Request $request)
    {
        foreach ($request->types as $key => $type) {
            $this->overWriteEnvFile($type, $request[$type]);
        }

        $business_settings = BusinessSetting::where('type', 'google_map')->first();

        if ($request->has('google_map')) {
            $business_settings->value = 1;
            $business_settings->save();
        }
        else{
            $business_settings->value = 0;
            $business_settings->save();
        }

        Artisan::call('cache:clear');

        flash(translate("settings_updated_successfully"))->success();
        return back();
    }

    public function google_firebase_update(Request $request)
    {
        foreach ($request->types as $key => $type) {
            $this->overWriteEnvFile($type, $request[$type]);
        }

        $business_settings = BusinessSetting::where('type', 'google_firebase')->first();

        if ($request->has('google_firebase')) {
            $business_settings->value = 1;
            $business_settings->save();
        }
        else{
            $business_settings->value = 0;
            $business_settings->save();
        }

        Artisan::call('cache:clear');

        flash(translate("settings_updated_successfully"))->success();
        return back();
    }


    /**
     * Update the API key's for GOOGLE analytics.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function facebook_chat_update(Request $request)
    {
        foreach ($request->types as $key => $type) {
                $this->overWriteEnvFile($type, $request[$type]);
        }

        $business_settings = BusinessSetting::where('type', 'facebook_chat')->first();

        if ($request->has('facebook_chat')) {
            $business_settings->value = 1;
            $business_settings->save();
        }
        else{
            $business_settings->value = 0;
            $business_settings->save();
        }

        Artisan::call('cache:clear');

        flash(translate("settings_updated_successfully"))->success();
        return back();
    }

    public function facebook_comment_update(Request $request)
    {
        foreach ($request->types as $key => $type) {
            $this->overWriteEnvFile($type, $request[$type]);
        }

        $business_settings = BusinessSetting::where('type', 'facebook_comment')->first();
        if(!$business_settings) {
            $business_settings = new BusinessSetting;
            $business_settings->type = 'facebook_comment';
        }

        $business_settings->value = 0;
        if ($request->facebook_comment) {
            $business_settings->value = 1;
        }

        $business_settings->save();

        Artisan::call('cache:clear');

        flash(translate("settings_updated_successfully"))->success();
        return back();
    }

    public function facebook_pixel_update(Request $request)
    {
        foreach ($request->types as $key => $type) {
                $this->overWriteEnvFile($type, $request[$type]);
        }

        $business_settings = BusinessSetting::where('type', 'facebook_pixel')->first();

        if ($request->has('facebook_pixel')) {
            $business_settings->value = 1;
            $business_settings->save();
        }
        else{
            $business_settings->value = 0;
            $business_settings->save();
        }

        Artisan::call('cache:clear');

        flash(translate("settings_updated_successfully"))->success();
        return back();
    }

    /**
     * Update the API key's for other methods.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function env_key_update(Request $request)
    {
        $rules = array(
            'apple_key_file'        => 'valid_extensions:p8',
        );

        $messages = [
            'apple_key_file' => 'Apple Key File is invalid',
        ];

        $validator = Validator::make($request->all(), $rules, [], $messages);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $image_uploader = resolve('App\Contracts\ImageHandlerInterface');
        if ($request->hasFile('apple_key_file')) {
            $key_file = $request->file('apple_key_file');
            $dir_name = base_path();
            $target_dir = '/public';
            $file_name = "key.txt";
            $extensions = ['txt','p8'];
            $options = compact('dir_name','target_dir','file_name','extensions');

            $upload_result = $image_uploader->upload($key_file,$options);
            if(!$upload_result['status']) {
                flashMessage('danger', $upload_result['status_message']);
                return back();
            }
            $file_name = $dir_name.$target_dir.'/'.$file_name;
            $file_name = str_replace(base_path(),"",$file_name);
        }

        if ($request->google_client_id || $request->google_client_secret) 
        {
            BusinessSetting::where(['type' => 'google_client_id'])->update(['value' => $request->google_client_id]);
            BusinessSetting::where(['type' => 'google_client_secret'])->update(['value' => $request->google_client_secret]);
        }
        elseif($request->facebook_client_id || $request->facebook_client_secret)
        {
            BusinessSetting::where(['type' => 'facebook_client_id'])->update(['value' => $request->facebook_client_id]);
            BusinessSetting::where(['type' => 'facebook_client_secret'])->update(['value' => $request->facebook_client_secret]);
        }
        elseif($request->apple_service_id || $request->apple_team_id || $request->apple_key_id)
        {
            BusinessSetting::where(['type' => 'apple_service_id'])->update(['value' => $request->apple_service_id]);
            BusinessSetting::where(['type' => 'apple_team_id'])->update(['value' => $request->apple_team_id]);
            BusinessSetting::where(['type' => 'apple_key_id'])->update(['value' => $request->apple_key_id]);
        }
        elseif($request->mail_driver || $request->mail_host || $request->mail_port || $request->mail_username || $request->mail_password || $request->mail_encryption || $request->mail_from_address || $request->mail_from_name)
        {
            BusinessSetting::where(['type' => 'mail_driver'])->update(['value' => $request->mail_driver]);
            BusinessSetting::where(['type' => 'mail_host'])->update(['value' => $request->mail_host]);
            BusinessSetting::where(['type' => 'mail_port'])->update(['value' => $request->mail_port]);
            BusinessSetting::where(['type' => 'mail_username'])->update(['value' => $request->mail_username]);
            BusinessSetting::where(['type' => 'mail_password'])->update(['value' => $request->mail_password]);
            BusinessSetting::where(['type' => 'mail_encryption'])->update(['value' => $request->mail_encryption]);
            BusinessSetting::where(['type' => 'mail_from_address'])->update(['value' => $request->mail_from_address]);
            BusinessSetting::where(['type' => 'mail_from_name'])->update(['value' => $request->mail_from_name]);
        } else if($request->DEFAULT_LANGUAGE) {
            Language::where('default_language',1)->update(['default_language'=>0]);
            Language::where('code', $request->DEFAULT_LANGUAGE)->update(['default_language'=>1]);       
                
        }
        Artisan::call('cache:clear');

        flash(translate("settings_updated_successfully"))->success();
        return back();
    }

    /**
     * overWrite the Env File values.
     * @param  String type
     * @param  String value
     * @return \Illuminate\Http\Response
     */
    public function overWriteEnvFile($type, $val)
    {
        if(env('DEMO_MODE') != 'On' || env('APP_ENV')!='live'){
            updateEnvConfig($type, $val);
            // $path = base_path('.env');
            // if (file_exists($path)) {
            //     $val = '"'.trim($val).'"';
            //     if(is_numeric(strpos(file_get_contents($path), $type)) && strpos(file_get_contents($path), $type) >= 0){
            //         file_put_contents($path, str_replace(
            //             $type.'="'.env($type).'"', $type.'='.$val, file_get_contents($path)
            //         ));
            //     }
            //     else{
            //         file_put_contents($path, file_get_contents($path)."\r\n".$type.'='.$val);
            //     }
            // }
        }
    }

    public function seller_verification_form(Request $request)
    {
    	return view('backend.sellers.seller_verification_form.index');
    }

    /**
     * Update sell verification form.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function seller_verification_form_update(Request $request)
    {
        $form = array();
        $select_types = ['select', 'multi_select', 'radio'];
        $j = 0;
        for ($i=0; $i < count($request->type); $i++) {
            $item['type'] = $request->type[$i];
            $item['label'] = $request->label[$i];
            if(in_array($request->type[$i], $select_types)){
                $item['options'] = json_encode($request['options_'.$request->option[$j]]);
                $j++;
            }
            array_push($form, $item);
        }
        $business_settings = BusinessSetting::where('type', 'verification_form')->first();
        $business_settings->value = json_encode($form);
        if($business_settings->save()){
            Artisan::call('cache:clear');
            
            flash(translate("verification_form_updated_successfully"))->success();
            return back();
        }
    }

    public function update(Request $request)
    {
        if($request->max_owe_amount) {
            $rules = array(
                "max_owe_amount" => 'required|numeric|min:1'
            );
            $niceNames = array(
                "max_owe_amount" => "Maximum Owe Amount"
            );
            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($niceNames); 
            if($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }
            else {
                BusinessSetting::where(['type' => 'max_owe_amount'])->update(['value' => $request->max_owe_amount]);
            }
        }

        if ($request->home_slider_images) {
            if(count(array_filter($request->home_slider_images)) != count($request->home_slider_images)) {
                flash(translate("need_home_slider"))->error();
                return back();
            }   
        }

        if ($request->home_banner1_images) {
            if(count(array_filter($request->home_banner1_images)) != count($request->home_banner1_images)) {
                flash(translate("need_home_banner"))->error();
                return back();
            }   
        }

        if ($request->home_banner2_images) {
            if(count(array_filter($request->home_banner2_images)) != count($request->home_banner2_images)) {
                flash(translate("need_home_banner2"))->error();
                return back();
            }   
        }

        if ($request->home_banner3_images) {
            if(count(array_filter($request->home_banner3_images)) != count($request->home_banner3_images)) {
                flash(translate("need_home_banner3"))->error();
                return back();
            }   
        }

        foreach ($request->types as $key => $type) {
            if($type == 'site_name'){

                $this->overWriteEnvFile('APP_NAME', str_replace(" ","",$request[$type]));
            }
            if($type == 'timezone'){
                $this->overWriteEnvFile('APP_TIMEZONE', $request[$type]);
            }
            else {
                $lang = null;
                if(gettype($type) == 'array'){
                    $lang = array_key_first($type);
                    $type = $type[$lang];
                    $business_settings = BusinessSetting::where('type', $type)->where('lang',$lang)->first();
                }else{
                    $business_settings = BusinessSetting::where('type', $type)->first();
                }

                if($business_settings!=null){
                    if(gettype($request[$type]) == 'array'){
                        $business_settings->value = json_encode($request[$type]);
                    }
                    else {
                        $business_settings->value = $request[$type];
                    }
                    $business_settings->lang = $lang;
                    $business_settings->save();
                }
                else{
                    $business_settings = new BusinessSetting;
                    $business_settings->type = $type;
                    if(gettype($request[$type]) == 'array'){
                        $business_settings->value = json_encode($request[$type]);
                    }
                    else {
                        $business_settings->value = $request[$type];
                    }
                    $business_settings->lang = $lang;
                    $business_settings->save();
                }
            }
        }

        Artisan::call('cache:clear');

        flash(translate("settings_updated_successfully"))->success();
        return back();
    }

    public function updateActivationSettings(Request $request)
    {
        $env_changes = ['FORCE_HTTPS', 'FILESYSTEM_DRIVER'];
        if (in_array($request->type, $env_changes)) {

            return $this->updateActivationSettingsInEnv($request);
        }

        $paypal_payment = get_setting('paypal_payment');
        $stripe_payment = get_setting('stripe_payment');
        $cash_payment = get_setting('cash_payment');
        
        if ($request->type == 'cash_payment' || $request->type == 'stripe_payment' || $request->type == 'paypal_payment') {
            if ($request->value == '' || $request->value == null) {
                $payment_count = $paypal_payment + $stripe_payment + $cash_payment;
                if ($payment_count == 1) {
                    flash(translate("need_atlease_one_payment"))->error();
                    return 'fail';
                 } 
            }
        }

        if ($request->type == 'pickup_point') {
            $ship_engine = get_setting('ship_engine');

            if($ship_engine) {
                flash(translates("cannot_activate_pickup_point"))->error();
                return 'fail';
            }
        }

        $business_settings = BusinessSetting::where('type', $request->type)->first();


        $request->merge([
            'value' => $request->value ?? '0'
        ]);
            
        if($business_settings!=null) {

            if ($request->type == 'maintenance_mode' && $request->value == '1') {
                if(env('DEMO_MODE') != 'On'){
                    Artisan::call('down');
                }
            }
            elseif ($request->type == 'maintenance_mode' && $request->value == '0') {
                if(env('DEMO_MODE') != 'On') {
                    Artisan::call('up');
                }
            } elseif ($request->type == 'ship_engine' && ($request->value == '1' || $request->value == '0')) {
                // dd($request->value);
                if($request->value == '1') {
                    // update pickup point based on business settings
                    \DB::table('business_settings')->where('type', 'pickup_point')->update(['value' => '0']);
                    
                    // update shipping configuration to product_wise_shipping if ship engine is turned on
                    \DB::table('business_settings')->where('type', 'shipping_type')->update(['value' => 'product_wise_shipping']);
                } else {
                    \DB::statement("TRUNCATE TABLE `addresses`");
                    \DB::statement("TRUNCATE TABLE `carts`");
                }
            }

            $business_settings->value = $request->value;
            $business_settings->save();
        }
        else{
            $business_settings = new BusinessSetting;
            $business_settings->type = $request->type;
            $business_settings->value = $request->value;
            $business_settings->save();
        }
        $active_subscription = UserSubscriptionPlan::where('status','Active')->first();
        if($request->type=='subscription' && $request->value == '1'){
            Product::where('id','!=','0')->where('added_by','!=','admin')->update(['published'=>0]);
        }
        if($request->type=='subscription' && $request->value == '0' && $active_subscription){
            $user_subscription = UserSubscriptionRenewal::with('user_subscription_plan')->where('cancelled',0)->get();
            UserSubscriptionPlan::where('id','!=','0')->update(['status'=>'Inactive']);
            foreach ($user_subscription as $key => $value) {
                $customer_id = $value->user_subscription_plan->customer_id??'';
                $cancel = $this->subscription_helper->cancel_subscription($value->subscription_id,$customer_id);
               $value->cancelled=1;
               $value->save();

               $value->user_subscription_plan->customer_id=Null;
               $value->user_subscription_plan->save();
               
            }
        }

        Artisan::call('cache:clear');
        return '1';
    }

    public function updateActivationSettingsInEnv($request)
    {
        if ($request->type == 'FORCE_HTTPS' && $request->value == '1') {
            $this->overWriteEnvFile($request->type, 'On');

            if(strpos(env('APP_URL'), 'http:') !== FALSE) {
                $this->overWriteEnvFile('APP_URL', str_replace("http:", "https:", env('APP_URL')));
            }

        }
        elseif ($request->type == 'FORCE_HTTPS' && $request->value == '0') {
            $this->overWriteEnvFile($request->type, 'Off');
            if(strpos(env('APP_URL'), 'https:') !== FALSE) {
                $this->overWriteEnvFile('APP_URL', str_replace("https:", "http:", env('APP_URL')));
            }

        }
        elseif ($request->type == 'FILESYSTEM_DRIVER' && $request->value == '1') {
            $this->overWriteEnvFile($request->type, 's3');
        }
        elseif ($request->type == 'FILESYSTEM_DRIVER' && $request->value == '0') {
            $this->overWriteEnvFile($request->type, 'local');
        }

        return '1';
    }

    public function vendor_commission(Request $request)
    {
        return view('backend.sellers.seller_commission.index');
    }

    public function vendor_commission_update(Request $request){
        foreach ($request->types as $key => $type) {
            $business_settings = BusinessSetting::where('type', $type)->first();
            if($business_settings!=null){
                $business_settings->value = $request[$type];
                $business_settings->save();
            }
            else{
                $business_settings = new BusinessSetting;
                $business_settings->type = $type;
                $business_settings->value = $request[$type];
                $business_settings->save();
            }
        }

        Artisan::call('cache:clear');

        flash(translate('merchant_commission_updated'))->success();
        return back();
    }

    public function shipping_configuration(Request $request){
        return view('backend.setup_configurations.shipping_configuration.index');
    }

    public function shipping_configuration_update(Request $request){
        if(get_setting('ship_engine')) {
            flash(translates('shipping_not_updated'))->warning();
            return back();
        }
        
        $business_settings = BusinessSetting::where('type', $request->type)->first();
        $business_settings->value = $request[$request->type];
        $business_settings->save();

        Artisan::call('cache:clear');

        flash(translate('shipping_updated'))->success();
        return back();
    }

    public function file_system_update(Request $request)
    {
        foreach($request->types as $type) {
            $exists = FileSystem::where('name', $type);

            if($exists->count()) {
                $exists->update(['value' => $request[$type]]);
            } else {
                $filesys = new FileSystem;
                $filesys->name = $type;
                $filesys->value = $request[$type];
                $filesys->save();
            }
        }

        Artisan::call('cache:clear');

        flash(translates('file_sys_updated'))->success();
        return back();
    }
}
