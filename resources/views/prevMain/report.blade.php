@section('footer')
<script>
    $(".report-row").click(function() {
        window.location = $(this).data("reporthref");
    });

    $(".report-pm-foc").click(function() {
        window.location = $(this).data("reportpmfoc");
    });

    $(".report-pm-lain").click(function() {
        window.location = $(this).data("reportpmlain");
    });

</script>
@endsection
@extends('layouts.master')
@section('content')
<div class="container">
    <!-- <div class="row justify-content-center"> -->
        <!-- <div class="col-md-12"> -->
            <div class="text-center">
                <h3>Report Preventive Maintenance</h3>
            </div>
            <a href="#" class="btn btn-success"><i class="fa fa-download"></i><span> Download</span></a>
            <br>
            <br>
            <table class="table table-responsive table-bordered table-striped" width="100%" style="text-align: center;">
                <thead class="thead-light">
                    <tr>
                        <th rowspan="2">No</th>
                        <th rowspan="2">Region</th>
                        <th rowspan="2">Action</th>
                        <th colspan="3">POP Keseluruhan</th>
                        <th colspan="3">POP Distribution</th>
                        <th colspan="3">POP Backbone</th>
                        <th colspan="3">POP Super Backbone</th>
                        <th colspan="4">PM NON POP</th>
                    </tr>
                    <tr>
                        <th>Total_POP_Asset</th>
                        <th>Total_PM_POP</th>
                        <th>Percentage</th>
                        <th>POP_D_Asset</th>
                        <th>POP_PM_D</th>
                        <th>Percent</th>
                        <th>POP_B_Asset</th>
                        <th>POP_PM_B</th>
                        <th>Percent</th>
                        <th>POP_SB_Asset</th>
                        <th>POP_PM_SB</th>
                        <th>Percent</th>
                        <th>PM_FOC</th>
                        <th>Action</th>
                        <th>PM_Lain</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php $id = 1;?>
                    @foreach ($arrayPOP as $d)
                        <tr>
                            <th>{{$id}}</th>
                            <td>{{$d['region']}}</td>
                            <td><button class='btn btn-primary report-row' data-reporthref="report-data/{{$d['region']}}">Detail</button></td>
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
                            <td>{{$d['pmFOC']}}</td>
                            <td><button class="btn btn-primary report-pm-foc" data-reportpmfoc="report-pm-foc/{{$d['region']}}">Detail</button></td>
                            <td>{{$d['pmLain']}}</td>
                            <td><button class="btn btn-primary report-pm-lain" data-reportpmlain="report-pm-lain/{{$d['region']}}">Detail</button></td>
                        </tr>
                        <?php $id++; ?>
                    @endforeach
                </tbody>
            </table>
    <!-- </div> -->
</div>
@endsection