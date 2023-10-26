<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BusinessSetting extends Model
{
    protected $guarded = [];
    protected $appends = ['convert_max_amount'];

    public function getConvertMaxAmountAttribute() {
        $currency = BusinessSetting::where('type', 'system_default_currency')->first()->value;
        $amount = BusinessSetting::where('type', 'max_owe_amount')->first()->value;
        return (currencyConvert(Currency::find($currency)->code,'',$amount));
    }
}
