@section('footer')
<script>
    $(".clickable-row").click(function() {
        window.location = $(this).data("gangguanhref");
    });

    $(".kendala-row").click(function() {
        window.location = $(this).data("kendalahref");
    });
</script>
@endsection

@extends('layouts.master')

@section('header')
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<script>
window.onload = function() {
    var chart = new CanvasJS.Chart("chartContainer", {
        theme: "light2", // "light1", "dark1", "dark2"
        animationEnabled: true, 		
        title:{
            text: "Trend Keaktifan Serpo Menggunakan Aplikasi FSM"
        },
        data: [{
            type: "line",
            yValueFormatString: "#,##0.00\"%\"",
            dataPoints: <?php echo json_encode($chartArray, JSON_NUMERIC_CHECK); ?>
        }]
    });
    chart.render();

    var rootCauseChart = new CanvasJS.Chart("rootCauseChart", {
        theme: "light2", // "light1", "dark1", "dark2"
        animationEnabled: true, 		
        title:{
            text: "Kategori Gangguan"
        },
        data: [{
            type: "pie",
            yValueFormatString: "#,##0.00\"%\"",
            indexLabel: "{label} ({y})",
            dataPoints: <?php echo json_encode($urcdArray, JSON_NUMERIC_CHECK); ?>
        }]
    });
    rootCauseChart.render();

    var kendalaChart = new CanvasJS.Chart("kendalaChart", {
        theme: "light2", // "light1", "dark1", "dark2"
        animationEnabled: true, 		
        title:{
            text: "Kategori Kendala"
        },
        data: [{
            type: "pie",
            yValueFormatString: "#,##0.00\"%\"",
            indexLabel: "{label} ({y})",
            dataPoints: <?php echo json_encode($ukArray, JSON_NUMERIC_CHECK); ?>
        }]
    });
    kendalaChart.render();
}
</script>
@endsection

@if($dbAvgExcel!=null)
    @section('card')
    <div class="card-deck">
        <div class="card text-white bg-primary p-0">
            <div class="card-body">
                <h5 class="card-title">{{$cardArray['regionSum']}}</h5>
                <p class="card-text">Work Order</p>
            </div>
        </div>
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title">{{$cardArray['avgTotalDurasi']}} Menit</h5>
                <p class="card-text-small">Total Durasi</p>
            </div>
        </div>
        <div class="card text-white bg-info">
            <div class="card-body">
                <h5 class="card-title">{{$cardArray['avgRSPS']*100}} %</h5>
                <p class="card-text">RSPS</p>
            </div>
        </div>
    </div>
    <br>
    <div class="card-deck">
        <div class="card text-white bg-secondary">
            <div class="card-body">
                <h5 class="card-title">{{$cardArray['avgDurasiSBU']}} Menit</h5>
                <p class="card-text">Durasi SBU</p>
            </div>
        </div>
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title">{{$cardArray['avgPrepTime']}} Menit</h5>
                <p class="card-text">Preparation Time</p>
            </div>
        </div>
        <div class="card text-white bg-danger">
            <div class="card-body">
                <h5 class="card-title">{{$cardArray['avgTravelTime']}} Menit</h5>
                <p class="card-text">Travel Time</p>
            </div>
        </div>
        <div class="card text-white bg-yellow">
            <div class="card-body">
                <h5 class="card-title">{{$cardArray['avgWorkTime']}} Menit</h5>
                <p class="card-text">Work Time</p>
            </div>
        </div>
    </div>
    <br>
    @endsection
    @section('chart')
    <div id="chartContainer" style="height: 300px; width: 100%;"></div>
    <br>
    <?php
    $awal = $pAwal;
    $akhir = $pAkhir;
    if($pAkhir==null && $pAwal==null){
        $awal = '*';
        $akhir = '*';
    }else if($pAkhir==null){
        $akhir = '*';
    }else if($pAwal==null){
        $awal = '*';
    }
    ?>
    <div class="table table-responsive table-hover" >
        <table style="float: left" width="45%">
            <thead class="thead-dark">
                <tr>
                    <th>Kategori Gangguan (RSPS = 100)</th>
                    <th>Total</th>
                    <th>Rataan Durasi (Menit)</th>
                </tr>
            </thead>
            @foreach($urcdArray as $urcda)
            <tr class='clickable-row' data-gangguanhref="gangguan-data/{{$urcda['label']}}/{{$regionName}}/{{$awal}}/{{$akhir}}">
                <td>{{$urcda['label']}}</td>
                <td>{{$urcda['total']}}</td>
                <td>{{$urcda['durasi']}}</td>
            </tr>
            @endforeach
        </table>
        <table style="float: right" width="45%">
            <thead class="thead-dark">
                <tr>
                <th>Kategori Kendala</th>
                <th>Total</th>
                </tr>
            </thead>
            @foreach($ukArray as $uka)
            <tr class='kendala-row' data-kendalahref="kendala-data/{{$uka['label']}}/{{$regionName}}/{{$awal}}/{{$akhir}}">
                <td>{{$uka['label']}}</td>
                <td>{{$uka['total']}}</td>
            </tr>
            @endforeach
        </table>
    </div>
    @if(count($urcArray)!=0)
    <div class="table table-responsive table-hover" >
        <table style="float: left" width="45%">
            <thead class="thead-dark">
                <tr>
                    <th>Kategori Gangguan (RSPS < 100)</th>
                    <th>Total</th>
                </tr>
            </thead>
            @foreach($urcArray as $urca)
            <tr>
                <td>{{$urca['label']}}</td>
                <td>{{$urca['y']}}</td>
            </tr>
            @endforeach
        </table>
    </div>
    @endif
    <table style="align: center; width: 100%;">
        <tr>
            <tbody>
                <tr>
                    <td>
                    <div id="rootCauseChart" style="height: 300px;width: 100%;"></div>
                    </td>
                    <td>
                    <div id="kendalaChart" style="height: 300px;width: 100%;"></div>
                    </td>
                </tr>
            </tbody>
        </tr>
    </table>

    @endsection
@endif

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="text-center">
                <h1 class="display-6">Performa Rata - Rata Serpo Filtered By Region</h1>
            </div>
            <form method="post" action="{{route('home')}}">
            {{csrf_field()}}
                <div class="row">
                    <div class="col">
                    <label for="reg">Region / Wilayah</label>
                    <select name="region" class="form-control" id="reg">
                        <option value="">-- Pilih Region --</option>
                        @foreach($unique as $u)
                            <option value="{{$u}}">{{$u}}</option>
                        @endforeach
                    </select>
                    </div>
                    <div class="col">
                        <label for="awal">Periode Awal</label>
                        <input type="date" class="form-control" id="awal" name="pawal">
                    </div>
                    <div class="col">
                        <label for="akhir">Periode Akhir</label>
                        <input type="date" class="form-control" id="akhir" name="pakhir">
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-filter"></i><span> Filter</span></button>
                    </div>
                        </form>
                        <br>
                        @if($dbAvgExcel!=null)
                </div>
        </div>
    </div>
</div>

                <blockquote class="blockquote text-center">
                <h3>
                    <small class="text-muted">Filtered by </small>
                Region {{$regionName}}
                </h3>
                @if(($pAwal==null) && ($pAkhir==null))
                    <p class="mb-0">Data All Time</p>
                @elseif($pAwal==null)
                    <p class="mb-0">Data Sampai Dengan {{$pAkhir}}</p>
                @elseif($pAkhir==null)
                    <p class="mb-0">Data Mulai Dari {{$pAwal}}</p>
                @else
                    <p class="mb-0">Periode {{$pAwal}} s.d. {{$pAkhir}}</p>
                @endif
                <!-- <footer class="blockquote-footer">avg (rata rata) waktu dalam satuan menit</footer> -->
            </blockquote>
            <br>
            @yield('card')
            @yield('chart')
            <br>
            <div class="text-center">
                <h3>Data Performa Rata - Rata Serpo Berdasarkan Region {{$regionName}}</h3>
            </div>
            <form method="post" action="{{route('allData.store')}}">
                    {{csrf_field()}}
                        <input type="hidden" name="awal" value="{{$pAwal}}">
                        <input type="hidden" name="akhir" value="{{$pAkhir}}">
                        <input type="hidden" name="region" value="{{$regionName}}">
                        <button type="submit" class="btn btn-success"><i class="fa fa-download"></i><span> Download</span></button>
            </form>
            <br>
            <table class="table table-responsive table-striped table-hover table-bordered" >
                <thead class="thead-light" style="text-align: center;">
                    <tr valign="top" >
                        <th rowspan="2">No</th>
                        <th rowspan="2">Basecamp</th>
                        <th rowspan="2">Service_Point</th>
                        <th rowspan="2">Jumlah_WO</th>
                        <th colspan="6">Average (Dalam Satuan Menit)</th>
                    </tr>
                    <tr>
                        <th>Total_Durasi_(A+B+C+D)</th>
                        <th>A.Durasi_SBU</th>
                        <th>B.Preparation_Time</th>
                        <th>C.Travel_Time</th>
                        <th>D.Working_Time</th>
                        <th>RSPS</th>
                    </tr>
                </thead>
                <tbody style="text-align: center;">
                <?php $i = 1; ?>
            @foreach($dbAvgExcel as $data)
                    <tr>
                        <th>{{$i}}</th>
                        <td>{{$data->basecamp}}</td>
                        <td>{{$data->serpo}}</td>
                        <td>{{$data->jumlah_wo}}</td>
                        @if($data->total_durasi!=null)
                        <td>{{round($data->total_durasi,2)}}</td>
                        @else
                        <td>n.a</td>
                        @endif
                        <td>{{round($data->durasi_sbu,2)}}</td>
                        @if($data->prep_time!=null)
                        <td>{{round($data->prep_time,2)}}</td>
                        @else
                        <td>n.a</td>
                        @endif
                        @if($data->travel_time!=null)
                        <td>{{round($data->travel_time,2)}}</td>
                        @else
                        <td>n.a</td>
                        @endif
                        @if($data->work_time!=null)
                        <td>{{round($data->work_time,2)}}</td>
                        @else
                        <td>n.a</td>
                        @endif
                        <td>{{$data->rsps*100}}%</td>
                    </tr>
                    <?php $i++; ?>
            @endforeach
                </tbody>
            </table>
            @endif
@endsection