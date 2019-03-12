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
                    <th>No</th>
                    <th>Kode WO</th>
                    <th>Kode Asset</th>
                    <th>Region</th>
                    <th>Category PM</th>
                    <th>Category POP</th>
                </thead>
                <tbody>
                <?php $id = $datas->firstItem(); ?>
                    @foreach ($datas as $d)
                        <tr>
                            <th>{{$id}}</th>
                            <td>{{$d->wo_code}}</td>
                            <td>{{$d->asset_code}}</td>
                            <td>{{$d->region}}</td>
                            <td>{{$d->category_pm}}</td>
                            <td>{{$d->category_pop}}</td>
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