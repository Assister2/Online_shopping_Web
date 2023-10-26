<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSubscriptionPlan extends Model
{
    protected $table = 'user_subscription_plan';   

    protected $appends = ['next_renewel_date','next_renewel_date_convert'];

    // Join with user_subscription_renewal table
    public function subscription_renewal() {
        return $this->hasMany('App\Models\UserSubscriptionRenewal', 'user_id', 'user_id')->orderBy('id','DESC');
    }

    public function getNextRenewelDateAttribute()
    {   
        if($this->attributes['plan_type']!='Custom'){
        $count=$this->attributes['duration']*28;
        return date('d/m/Y', strtotime($this->attributes['updated_at']. ' + '.$count.' days'));
        }else
        return date('d/m/Y');
    }
    public function getNextRenewelDateConvertAttribute()
    {   if($this->attributes['plan_type']!='Custom'){
            $count=$this->attributes['duration']*28;
            // $count=1;
            return date('Y-m-d', strtotime($this->attributes['updated_at']. ' + '.$count.' days'));       
        }else
        return date('Y-m-d');
    }

    // Join with users table
    public function users() {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
