@extends('layouts.master')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="table table-responsive" >
            <h4>Detail Asset</h4>
                <table style="float: left">
                    <tr>
                        <th>Site ID</th>
                        <td>{{$title->site_id}}</td>
                    </tr>
                    <tr>
                        <th>Site Name</th>
                        <td>{{$title->site}}</td>
                    </tr>
                    <tr>
                        <th>Kota</th>
                        <td>{{$title->kota}}</td>
                    </tr>
                    <tr>
                        <th>Propinsi</th>
                        <td>{{$title->propinsi}}</td>
                    </tr>
                    <tr>
                        <th>SBU</th>
                        <td>{{$title->sbu}}</td>
                    </tr>
                    <tr>
                        <th></th>
                    </tr>
                </table>
                <table style="float: left">
                    <tr>
                        <th>Model</th>
                        <td>{{$title->model}}</td>
                    </tr>
                    <tr>
                        <th>Type</th>
                        <td>{{$title->type}}</td>
                    </tr>
                    <tr>
                        <th>Updated at</th>
                        <td>{{$title->updated_time}}</td>
                    </tr>
                    <tr>
                        <th>Updated By</th>
                        <td>{{$title->updated_by}}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>{{$title->status}}</td>
                    </tr>
                </table>
            </div>
            <div class="text-center">
                <h4>Daftar PM terhadap Asset</h4>
            </div>
            <div class="table table-responsive table-hover" >
            <table class="table-bordered table-striped" width="100%" style="text-align: center;">
                <thead>
                    <th>No</th>
                    <th>WO_Code</th>
                    <th>WO_Date</th>
                    <th>Basecamp</th>
                    <th>Serpo</th>
                    <th>Category_PM</th>
                    <th>Category_POP</th>
                </thead>
                <tbody>
                    <?php $id = 1; ?>
                    @foreach ($datas as $d)
                        <tr>
                            <th>{{$id}}</th>
                            <td>{{$d->wo_code}}</td>
                            <td>{{$d->wo_date}}</td>
                            <td>{{$d->basecamp}}</td>
                            <td>{{$d->serpo}}</td>
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