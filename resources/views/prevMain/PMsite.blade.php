@extends('layouts.master')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="text-center">
                <h1 class="display-6">Site Code {{$asset_code}}</h1>
            </div>
            <div class="table table-responsive table-hover" >
            <table class="table-bordered table-striped" width="100%" style="text-align: center;">
                <thead>
                    <th>No</th>
                    <th>WO Code</th>
                    <th>WO Date</th>
                    <th>Site ID</th>
                    <th>Region</th>
                    <th>Category PM</th>
                    <th>Category POP</th>
                </thead>
                <tbody>
                    <?php $id = 1; ?>
                    @foreach ($datas as $d)
                        <tr>
                            <th>{{$id}}</th>
                            <td>{{$d->wo_code}}</td>
                            <td>{{$d->wo_date}}</td>
                            <td>{{$d->asset_code}}</td>
                            <td>{{$d->region}}</td>
                            <td>{{$d->category_pm}}</td>
                            <td>{{$d->category_pop}}</td>     
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