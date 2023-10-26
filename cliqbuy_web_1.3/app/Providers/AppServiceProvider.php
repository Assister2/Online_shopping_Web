<?php

namespace App\Providers;

use Laravel\Passport\Console\ClientCommand;
use Laravel\Passport\Console\InstallCommand;
use Laravel\Passport\Console\KeysCommand;
use Laravel\Passport\Passport;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Validator;
use App\FlashDeal;
class AppServiceProvider extends ServiceProvider
{
  /**
   * Bootstrap any application services.
   *
   * @return void
   */
  public function boot()
  {
    date_default_timezone_set("Asia/Kolkata");
    $this->app['request']->server->set('HTTPS', true);
    $this->bindModels();
//Passport::routes();

    /*ADD THIS LINES*/
    $this->commands([
        InstallCommand::class,
        ClientCommand::class,
        KeysCommand::class,
    ]);

    if (Schema::hasTable('flash_deals')) 
    {
        $this->flash_deals();
    }

    logger('requested URL : '.request()->fullUrl());
    if(request()->isMethod('POST')) {
        logger('Post Method Params : '.json_encode(request()->post()));
    }
    
    Schema::defaultStringLength(191);

    if (Schema::hasTable('business_settings')) 
    {
        \Config::set('mail.host', get_setting('mail_host'));
        \Config::set('mail.username',get_setting('mail_username'));
        \Config::set('mail.password', get_setting('mail_password'));
        \Config::set('mail.port', get_setting('mail_port'));

        \Config::set(['services.facebook' => [
            'client_id' => get_setting('facebook_client_id'),
            'client_secret' => get_setting('facebook_client_secret'),
            'redirect' => url('/social-login/facebook/callback'),
        ]]);

        \Config::set(['services.google' => [
            'client_id' => get_setting('google_client_id'),
            'client_secret' => get_setting('google_client_secret'),
            'redirect' => url('/social-login/google/callback'),
        ]]);

        \Config::set(['services.google' => [
            'service_id' => get_setting('apple_service_id'),
            'team_id' => get_setting('apple_team_id'),
            'key_id' => get_setting('apple_key_id'),
            'key_file' => get_setting('apple_key_file'),
            'redirect' => url('/social-login/apple/callback'),
        ]]);
    }    


    // Custom Validation for File Extension
        \Validator::extend('valid_extensions', function($attribute, $value, $parameters) 
        {
            if(count($parameters) == 0) {
                return false;
            }
            $ext = strtolower($value->getClientOriginalExtension());
            
            return in_array($ext,$parameters);
        });

    // Custom Validation for CreditCard is Expired or Not
        Validator::extend('expires', function($attribute, $value, $parameters, $validator) 
        {
            $input    = $validator->getData();

            $expiryDate = gmdate('Ym', gmmktime(0, 0, 0, (int) \Arr::get($input, $parameters[0]), 1, (int) \Arr::get($input, $parameters[1])));
            
            return ($expiryDate >= gmdate('Ym')) ? true : false;
        });

        // Custom Validation for CreditCard is Valid or Not
        Validator::extend('validateluhn', function($attribute, $value, $parameters) 
        {
            if((is_numeric($value))) {
                $str = '';
                foreach (array_reverse(str_split($value)) as $i => $c) {
                    $str .= $i % 2 ? $c * 2 : $c;

                }

                return array_sum(str_split($str)) % 10 === 0;
            }
            return false;            
        });

  }

    public function flash_deals()
    {
        $flash_deals = FlashDeal::where('status', 1)->where('end_date', '<', strtotime(date('d-m-Y H:i:s')))->pluck('id')->toArray();
        if(count($flash_deals)) {
            foreach ($flash_deals as $key => $value) {
                $flash_deal = FlashDeal::where('id',$value)->first();
                $flash_deal->status = 0;
                $flash_deal->save();
            }
        }
    }

    protected function bindModels()
    {
        $this->app->bind('App\Contracts\ImageHandlerInterface','App\Services\LocalImageHandler');
    }
  /**
   * Register any application services.
   *
   * @return void
   */
  public function register()
  {
    
  }
}
