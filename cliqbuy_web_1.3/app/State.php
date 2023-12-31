<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App;

class State extends Model
{
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function cities() {
        return $this->hasMany(City::class);
    }

    public function getTranslation($field = '', $lang = false){
        $lang = $lang == false ? App::getLocale() : $lang;
        $state_translation = $this->hasMany(StateTranslation::class)->where('lang', $lang)->first();
        return $state_translation != null ? $state_translation->$field : $this->$field;
    }

    public function state_translations(){
      return $this->hasMany(StateTranslation::class);
    }
}
