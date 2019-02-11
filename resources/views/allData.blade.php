@extends('layouts.master')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="text-center">
                <h1 class="display-6">All Data from Database</h1>
            </div>
            <button class="btn btn-success" href="{{route('excel.create')}}">Download xlsx</button>
            <table class="table-responsive table-hover table-bordered">
                <thead>
                    <tr>
                        <th>id</th>
                        <!-- <th>AR ID</th>
                        <th>Prob ID</th> -->
                        <th>kode_wo</th>
                        <th>region</th>
                        <th>basecamp</th>
                        <th>serpo</th>
                        <th>durasi SBU</th>
                        <th>preparation time</th>
                        <th>travel time</th>
                        <th>working time</th>
                        <th>stopclock time</th>
                        <th>complete time</th>
                        <th>rsps</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($datas as $cat)
                    <tr>
                        <td>{{$cat->id}}</td>
                        <!-- <td>{{$cat->ar_id}}</td>
                        <td>{{$cat->prob_id}}</td> -->
                        <td>{{$cat->kode_wo}}</td>
                        <td>{{$cat->region}}</td>
                        <td>{{$cat->basecamp}}</td>
                        <td>{{$cat->serpo}}</td>
                        <td>{{$cat->durasi_sbu}}</td>
                        <td>{{$cat->prep_time}}</td>
                        <td>{{$cat->travel_time}}</td>
                        <td>{{$cat->work_time}}</td>
                        <td>{{$cat->sc_time}}</td>
                        <td>{{$cat->complete_time}}</td>
                        <td>{{ round((float)$cat->rsps * 100 ) }}%</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection