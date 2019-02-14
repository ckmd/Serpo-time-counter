@extends('layouts.master')

@section('content')
<!-- <h2>National Data</h2> -->
<blockquote class="blockquote text-center">
    <h3>
        <small class="text-muted">Filtered </small>
    Nasional
    </h3>
    <footer class="blockquote-footer">avg (rata rata) waktu dalam satuan menit</footer>
</blockquote>
<table class="table table-bordered table-striped table-hover" style="text-align: center;">
    <thead class="thead-dark">
        <tr>
            <th>Region</th>
            <th>Jumlah WO</th>
            <th>Durasi SBU</th>
            <th>Preparation Time</th>
            <th>Travel Time</th>
            <th>Working Time</th>
            <th>RSPS</th>
        </tr>
    </thead>
    <tbody>
        @foreach($nationalDataForView as $data)
        <tr>
            <td>{{$data->region}}</td>
            <td>{{$data->jumlah_wo}}</td>
            <td>{{$data->durasi_sbu}}</td>
            <td>{{$data->prep_time}}</td>
            <td>{{$data->travel_time}}</td>
            <td>{{$data->work_time}}</td>
            <td>{{ round((float)$data->rsps * 100 ) }}%</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection