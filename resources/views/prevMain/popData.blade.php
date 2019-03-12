@extends('layouts.master')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="text-center">
                <h1 class="display-6">Report Preventive Maintenance</h1>
            </div>
            <div class="table table-responsive table-hover" >
            <table class="table-bordered table-striped" width="100%" style="text-align: center;">
                <thead>
                    <th>No</th>
                    <th>Region</th>
                    <th>Total WO</th>
                    <th>Total PM POP</th>
                    <th>POP Distribution</th>
                    <th>POP Superbackbone</th>
                    <th>POP Backbone</th>
                </thead>
                <tbody>
                <?php $id = 1;?>
                    @foreach ($arrayPOP as $d)
                        <tr>
                            <th>{{$id}}</th>
                            <td>{{$d['region']}}</td>
                            <td>{{$d['total_wo']}}</td>
                            <td>{{$d['total_pop']}}</td>
                            <td>{{$d['POP D']*100}}%</td>
                            <td>{{$d['POP SB']*100}}%</td>
                            <td>{{$d['POP B']*100}}%</td>
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