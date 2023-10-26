<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlanTranslations extends Model
{
    protected $table = 'subscription_plan_translations';

    public $timestamps = false;

    public function language() {
        return $this->belongsTo('App\Models\Language','locale','code');
    }
}
