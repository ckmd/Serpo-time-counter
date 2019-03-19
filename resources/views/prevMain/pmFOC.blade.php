@extends('layouts.master')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="text-center">
                <h3>Daftar Asset PM FOC Region {{$region}}</h3>
            </div>
            <div class="table table-responsive table-hover" >
            <h5>Total PM FOC : {{$totalPMFOC}}</h5>
            <table class="table-bordered table-striped" width="100%" style="text-align: center;">
                <thead>
                    <th>No</th>
                    <th>Site ID</th>
                    <th>Site Name</th>
                    <th>Category</th>
                    <th>Jumlah PM</th>
                </thead>
                <tbody>
                    <?php $id = 1; ?>
                    @foreach ($arrayRegion as $d)
                        <tr>
                            <td>{{$id}}</td>
                            <td>{{$d['site_id']}}</td>
                            <td>{{$d['site_name']}}</td>
                            <td>{{$d['category']}}</td>
                            <td>{{$d['pm']}}</td>
                        </tr>
                    <?php $id++; ?>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>
@endsection