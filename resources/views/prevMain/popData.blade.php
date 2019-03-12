@extends('layouts.master')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="text-center">
                <h1 class="display-6">Data Preventive Maintenance</h1>
            </div>
            <div class="table table-responsive table-hover" >
            <table class="table-bordered table-striped" width="100%" style="text-align: center;">
                <thead>
                    <th>Kode WO</th>
                    <th>Kode Asset</th>
                    <th>Region</th>
                    <th>Type PM</th>
                </thead>
                <tbody>
                    @foreach ($datas as $d)
                        <tr>
                            <td>{{$d->wo_code}}</td>
                            <td>{{$d->asset_code}}</td>
                            <td>{{$d->region}}</td>
                            <td>{{$d->type}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>
@endsection