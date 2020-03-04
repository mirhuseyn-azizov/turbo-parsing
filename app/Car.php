<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public function model(){
        return $this->belongsTo('App\CarModel' , 'car_model_id' , 'id');
    }


    public function getPriceAttribute($value){
        if ($this->currency == "$")
            return $value * 1.7;
        else if ($this->currency == "€")
            return $value * 1.9;
        return $value;
    }
}
