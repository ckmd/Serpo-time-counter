@extends('layouts.master')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="text-center">
                <h1 class="display-6">Daftar Asset</h1>
            </div>
            <div class="table table-responsive table-hover" >
            <table class="table-bordered table-striped" width="100%" style="text-align: center;">
                <thead>
                    <th>No</th>
                    <th>Site ID</th>
                    <th>Site</th>
                    <th>Kota / Kab</th>
                    <th>Propinsi</th>
                    <th>SBU</th>
                    <th>Model</th>
                    <th>Type</th>
                </thead>
                <tbody>
                <?php $id = $datas->firstItem(); ?>
                    @foreach ($datas as $d)
                        <tr>
                            <th>{{$id}}</th>
                            <td>{{$d->site_id}}</td>
                            <td>{{$d->site}}</td>
                            <td>{{$d->kota}}</td>
                            <td>{{$d->propinsi}}</td>
                            <td>{{$d->sbu}}</td>
                            <td>{{$d->model}}</td>
                            <td>{{$d->type}}</td>
                        </tr>
                        <?php $id++ ?>
                    @endforeach
                </tbody>
            </table>
            </div>
            {{$datas->links()}}
        </div>
    </div>
</div>
@endsection