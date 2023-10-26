<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Request;
use App;

class SubscriptionPlan extends Model
{
    use Translatable;
    
    protected $table = 'subscription_plan';

    public $timestamps = false;

    public $translatedAttributes = ['name', 'description' ,'tagline'];


    public function getTranslation($field = '', $lang = false) {
        // return $this->$field;
        $lang = $lang == false ? App::getLocale() : $lang;
        $subscription_translations = $this->hasMany(SubscriptionPlanTranslations::class)->where('locale', $lang)->first();
        return $subscription_translations != null ? $subscription_translations->$field : $this->$field;
    }

     public function product_translations() {
        return $this->hasMany('App\Models\SubscriptionPlanTranslations', 'subscription_plan_id', 'id');
    }

    // public function __construct(array $attributes = [])
    // {
        // parent::__construct($attributes);
        
        // if(Request::segment(1) == ADMIN_URL) {
        //     $this->defaultLocale = 'en';
        // }
        // else {
        //     $this->defaultLocale = Session::get('language');
        // }
    // }

}
