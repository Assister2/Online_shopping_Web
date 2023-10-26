<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserShipEngineSettings extends Model
{
    use HasFactory;

    protected $table = 'user_ship_engine_settings';

    protected $guarded = [];
}
