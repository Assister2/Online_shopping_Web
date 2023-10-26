<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipEngine extends Model
{
    use HasFactory;

    protected $table = 'ship_engines';
    
    protected $guarded = [];
}
