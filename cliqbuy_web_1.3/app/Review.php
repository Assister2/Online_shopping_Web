<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
  public function user(){
    return $this->belongsTo(User::class)->withTrashed();
  }

  public function product(){
    return $this->belongsTo(Product::class);
  }
}
