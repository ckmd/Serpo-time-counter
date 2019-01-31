@extends('layouts.master')

@section('content')
    <h2>Success</h2>
    <button class="btn btn-primary">download</button>
    <table class="table table-responsive">
        <thead>
            <tr>
                <th>id</th>
                <th>AR ID</th>
                <th>Prob ID</th>
                <th>kode_wo</th>
                <th>region</th>
                <th>basecamp</th>
                <th>serpo</th>
                <th>durasi SBU</th>
                <th>preparation time</th>
                <th>travel time</th>
                <th>working time</th>
                <th>complete time</th>
            </tr>
        </thead>
        <tbody>
    @foreach($datas as $cat)
            <tr>
                <td>{{$cat->id}}</td>
                <td>{{$cat->ar_id}}</td>
                <td>{{$cat->prob_id}}</td>
                <td>{{$cat->kode_wo}}</td>
                <td>{{$cat->region}}</td>
                <td>{{$cat->basecamp}}</td>
                <td>{{$cat->serpo}}</td>
                <td>{{$cat->durasi_sbu}}</td>
                <td>{{$cat->prep_time}}</td>
                <td>{{$cat->travel_time}}</td>
                <td>{{$cat->work_time}}</td>
                <td>{{$cat->complete_time}}</td>
            </tr>
    @endforeach
        </tbody>
    </table>
@endsection