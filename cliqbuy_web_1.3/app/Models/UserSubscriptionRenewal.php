<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSubscriptionRenewal extends Model
{
    protected $table = 'user_subscription_renewal';

    protected $appends = ['next_renewel_date','created_at_date'];

    public function user_subscription_plan() {
        return $this->belongsTo('App\Models\UserSubscriptionPlan', 'user_plan_id', 'id');
    }

     public function user_details() {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function getNextRenewelDateAttribute()
    {
        return date('d/m/Y', strtotime($this->attributes['created_at']. ' + 28 days'));
    }
    public function getCreatedAtDateAttribute()
    {
        return date('d/m/Y', strtotime($this->attributes['created_at']));
    }

}
