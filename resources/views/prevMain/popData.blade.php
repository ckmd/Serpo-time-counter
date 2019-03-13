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
                <thead class="thead-light">
                    <tr>
                        <th rowspan="2">No</th>
                        <th rowspan="2">Region</th>
                        <th colspan="3">POP Keseluruhan</th>
                        <th colspan="3">POP Distribution</th>
                        <th colspan="3">POP Backbone</th>
                        <th colspan="3">POP Super Backbone</th>
                    </tr>
                    <th>Total POP Asset</th>
                    <th>Total PM POP</th>
                    <th>Percentage</th>
                    <th>POP D Asset</th>
                    <th>POP PM D</th>
                    <th>Percent</th>
                    <th>POP B Asset</th>
                    <th>POP PM B</th>
                    <th>Percent</th>
                    <th>POP SB Asset</th>
                    <th>POP PM SB</th>
                    <th>Percent</th>
                </thead>
                <tbody>
                <?php $id = 1;?>
                    @foreach ($arrayPOP as $d)
                        <tr>
                            <th>{{$id}}</th>
                            <td>{{$d['region']}}</td>
                            <td>{{$d['total_wo']}}</td>
                            <td>{{$d['total_pop']}}</td>
                            <td>{{$d['percentageAll']*100}}%</td>
                            <td>{{$d['assetPOPD']}}</td>
                            <td>{{$d['POPD']}}</td>
                            <td>{{$d['percentagePOPD']*100}}%</td>
                            <td>{{$d['assetPOPB']}}</td>
                            <td>{{$d['POPB']}}</td>
                            <td>{{$d['percentagePOPB']*100}}%</td>
                            <td>{{$d['assetPOPSB']}}</td>
                            <td>{{$d['POPSB']}}</td>
                            <td>{{$d['percentagePOPSB']*100}}%</td>
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