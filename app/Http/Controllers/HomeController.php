<?php

namespace App\Http\Controllers;

use App\Car;
use App\CarMark;
use App\CarModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index(Request $request)
    {
        $this->validate($request , [
            'year' => ['nullable' , 'regex:/[0-9]{4}/'],
            'mark' => ['nullable' , 'integer'],
            'model' => ['nullable' , 'integer'],
        ]);

        $marks = CarMark::all();
        $models = CarModel::all();

        $cars = Car::with(['model' , 'model.mark' ])
            ->orderBy('id' , 'desc');

        if ($request->has('year') and $request->year) $cars->where('year' , $request->year);
        if ($request->has('mark')) $cars->whereHas('model' , function ($q) use ($request) {
            $q->where('car_mark_id' , $request->mark);
        });
        if ($request->has('model')) $cars->where('car_model_id', $request->model);


        /**
         * for save filter data
         */
//        $avg = $this->getAVGOfPriceByCurrency(clone $cars);


        $cars = $cars->paginate(16);
        return view('home' , compact('cars'  , 'marks' , 'models'));
    }


    public function getAVGOfPriceByCurrency($obj){
        //        SELECT avg (
        //           CASE
        //                  when currency ="$" then  price *1.7
        //                  when currency ="€" then  price *1.7
        //                  when currency = "AZN"  then  price * 1.9
        //              END
        //          ) as avg from cars
        /**
         * use variable instead value
         */
        return $obj->first(DB::raw('
                 avg (
                CASE
                    when currency ="$" then  price *1.7
                     when currency ="€" then  price *1.9
                    when currency = "AZN"  then  price 
                END 
            ) as avg
        '));
    }
}
