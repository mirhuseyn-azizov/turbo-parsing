<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CarMark extends Model
{
    protected $fillable = ['name' , 'id' ];

    public $timestamps = false;


    public function models(){
        return $this->hasMany('App\CarModel' , 'car_mark_id' , 'id');
    }

}
