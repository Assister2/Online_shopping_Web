<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $guarded = [];

    protected $appends = ['order_total_amount'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function pickup_point()
    {
        return $this->belongsTo(PickupPoint::class);
    }

    public function refund_request()
    {
        return $this->hasOne(RefundRequest::class);
    }

    public function affiliate_log()
    {
        return $this->hasMany(AffiliateLog::class);
    }

    public function getOrderTotalAmountAttribute(){

        $TotalAmount =  $this->attributes['price'] + $this->attributes['tax'] + $this->attributes['shipping_cost'] - $this->attributes['coupon_amount'];
        return $TotalAmount<0?0.00:$TotalAmount;
    }
}
