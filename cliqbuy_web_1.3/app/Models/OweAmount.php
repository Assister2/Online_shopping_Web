<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OweAmount extends Model
{
    use HasFactory;
    protected $table = 'owe_amounts';
    protected $appends = ['convert_remain_amount', 'convert_total_amount','convert_paid_amount'];
    
    public function users() {
        return $this->belongsTo('App\User', 'seller_id', 'id')->withTrashed();
    }

    public function orders() {
        return $this->belongsTo('\App\Order', 'order_id', 'id');
    }

    public function getConvertRemainAmountAttribute() {
        return ceil(currencyConvert($this->attributes['currency'],'',$this->attributes['remain_amount']));
    }
    public function getConvertTotalAmountAttribute() {
        return ceil(currencyConvert($this->attributes['currency'],'',$this->attributes['total_amount']));
    }
    public function getConvertPaidAmountAttribute() {
        return ceil(currencyConvert($this->attributes['currency'],'',$this->attributes['paid_amount']));
    }        
 }