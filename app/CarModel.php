<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CarModel extends Model
{
    protected $fillable = ['name' , 'id' , 'car_mark_id'];

    public $timestamps = false;


    public function mark(){
        return $this->belongsTo('App\CarMark' , 'car_mark_id' , 'id');
    }
}
