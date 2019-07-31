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
            <form method="post" action="{{route('report.store')}}">
                {{csrf_field()}}
                <div class="row">
                    <div class="col-3">
                        <label for="awal">Periode Awal</label>
                        <input type="date" class="form-control" id="awal" name="pawal">
                    </div>
                    <div class="col-3">
                        <label for="akhir">Periode Akhir</label>
                        <input type="date" class="form-control" id="akhir" name="pakhir">
                    </div>
                    <div class="col">
                        <br>
                        <button type="submit" class="btn btn-primary btn-lg"><i class="fa fa-filter"></i><span> Filter</span></button>

                        <!-- <input type="submit" class="btn btn-primary btn-lg" value="Filter"> -->
                    </div>
                </div>
            </form>
            <br>
            @if($report!=null)
            <br>
            <blockquote class="blockquote text-center">
                @if(($pAwal==null) && ($pAkhir==null))
                    <p class="mb-0">Data All Time</p>
                @elseif($pAwal==null)
                    <p class="mb-0">Data Sampai Dengan {{$pAkhir}}</p>
                @elseif($pAkhir==null)
                    <p class="mb-0">Data Mulai Dari {{$pAwal}}</p>
                @else
                    <p class="mb-0">Periode {{$pAwal}} s.d. {{$pAkhir}}</p>
                @endif
            </blockquote>
            <form method="post" action="{{url('downloadReport')}}">
                {{csrf_field()}}
                <input type="hidden" name="awal" value="{{$pAwal}}">
                <input type="hidden" name="akhir" value="{{$pAkhir}}">
                <button type="submit" class="btn btn-success"><i class="fa fa-download"></i><span> Download</span></button>
            </form>
            <!-- <a href="{{route('prevMain.create')}}" class="btn btn-success"><i class="fa fa-download"></i><span> Download</span></a> -->
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
                    @foreach ($report as $d)
                        <tr>
                            <th>{{$id}}</th>
                            <td>{{$d->region}}</td>
                            <td><button class='btn btn-primary report-row' data-reporthref="report-data/{{$d['region']}}">Detail</button></td>
                            <td>{{$d->total_POP_asset}}</td>
                            <td>{{$d->total_PM_POP}}</td>
                            <td>{{$d->ratio_total*100}}%</td>
                            <td>{{$d->asset_POP_D}}</td>
                            <td>{{$d->PM_POP_D}}</td>
                            <td>{{$d->ratio_POP_D*100}}%</td>
                            <td>{{$d->asset_POP_B}}</td>
                            <td>{{$d->PM_POP_B}}</td>
                            <td>{{$d->ratio_POP_B*100}}%</td>
                            <td>{{$d->asset_POP_SB}}</td>
                            <td>{{$d->PM_POP_SB}}</td>
                            <td>{{$d->ratio_POP_SB*100}}%</td>
                            <td>{{$d->PM_FOC}}</td>
                            <td><button class="btn btn-primary report-pm-foc" data-reportpmfoc="report-pm-foc/{{$d['region']}}">Detail</button></td>
                            <td>{{$d->PM_lain}}</td>
                            <td><button class="btn btn-primary report-pm-lain" data-reportpmlain="report-pm-lain/{{$d['region']}}">Detail</button></td>
                        </tr>
                        <?php $id++; ?>
                    @endforeach
                </tbody>
            </table>
            @endif
    <!-- </div> -->
</div>
@endsection