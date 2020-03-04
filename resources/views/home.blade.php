@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col">
        <div class="row">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
        @endif
        </div>
            <div class="row  mb-4">

                <form name="filter" class="col  d-flex justify-content-around">
                        <div class="form-froup">
                        <select name="mark"  class="form-control">
                            <option disabled selected >Markani secin</option>
                            @foreach($marks as $mark)
                                <option value="{{$mark->id}}"
                                        @if ($mark->id == request()->input('mark'))
                                            selected="selected"
                                        @endif
                                >{{$mark->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-froup">
                        <select class="form-control" name="model">

                            <option disabled selected >Modeli secin</option>
                        </select>
                    </div>
                    <div class="form-froup">
                        <input type="text" value="{{request()->input('year' , '')}}"  class="form-control" pattern="[0-9]{4}" name="year" placeholder="Ili secin">
                    </div>
                    <div class="form-froup">
                        <input type="submit"  class="form-control" value="Filter et">
                    </div>
                    <div class="form-froup">
                        <input name="zero" type="button"  class="form-control" value="Sıfırla">
                    </div>

                </form>
            </div>
            <div class="row meta my-2 d-flex ">
                <div class="count mr-2">Sayı : {{$cars->total()}}</div>
                <div class="avg-price">Ortalama qiyməti : {{$cars->avg('price')}} AZN </div>
{{--                                <div class="avg-price">Ortalama qiyməti : {{$avg->avg}} AZN </div>--}}
            </div>
            <div class="row d-flex justify-content-around">
                    @foreach($cars as $index => $car)
                        <div class="card car" style="width: 20%; margin: 10px">
                            <div class="card-body">
                                <h5 class="card-title">{{$car->model->mark->name .  " " . $car->model->name}}</h5>
                                <p>Qiymət : {{$car->price}} AZN</p>
                                <p>il : {{$car->year}}</p>
                                <p>şəhər  : {{$car->city}}</p>
                            </div>
                        </div>
                    @endforeach
            </div>
            {{$cars->links()}}
        </div>
    </div>
</div>
@foreach($models as $model)
        <option style="display: none;"
                @if ($model->id == request()->input('model'))
                    selected="selected"
                @endif
                value="{{$model->id}}" class="{{$model->car_mark_id}}">{{$model->name}}</option>
@endforeach


@endsection
