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
// Chart for Wo per Region
    var woChart = new CanvasJS.Chart("woChart", {
        theme: "light2",
        animationEnabled: true,
        title: {
            text: "Total WO"
        },
        data: [{
            type: "pie",
            yValueFormatString: "#,##0.00\"%\"",
            indexLabel: "{label} ({y})",
            dataPoints: <?php echo json_encode($woArray, JSON_NUMERIC_CHECK); ?>
        }]
    });
    woChart.render();
// Chart for Average RSPS
    var rspsChart = new CanvasJS.Chart("rspsChart", {
        theme: "light2",
    	animationEnabled: true,
        title: {
            text: "Keaktifan Serpo Menggunakan Aplikasi FSM"
        },
        data: [{
            type: "column",
            yValueFormatString: "#,##0.00\"%\"",
            dataPoints: <?php echo json_encode($rspsArray, JSON_NUMERIC_CHECK); ?>
        }]
    });
    rspsChart.render();

    var trendChart = new CanvasJS.Chart("chartContainer", {
        theme: "light2", // "light1", "dark1", "dark2"
        animationEnabled: true, 		
        title:{
            text: "Trend Keaktifan Serpo Menggunakan Aplikasi FSM"
        },
        data: [{
            type: "line",
            yValueFormatString: "#,##0.00\"%\"",
            indexLabel: "{label} ({y})",
            dataPoints: <?php echo json_encode($chartArray, JSON_NUMERIC_CHECK); ?>
        }]
    });
    trendChart.render();

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

@section('card')
<div class="card-deck">
        <div class="card text-white bg-primary text-center">
            <div class="card-body">
                <span class="info-box-text">Work Order</span>
                <h3>{{$cardArray['regionSum']}}</h3>
                <!-- <p class="card-text">Work Order</p> -->
            </div>
        </div>
        <div class="card text-white bg-primary">
            <div class="card-body">
                <span class="info-box-text">Total Durasi WO (A+B+C+D)</span>
                <h3>{{$cardArray['totalDurasiWO']}}</h3>
                <span class="info-box-text">Menit</span>
                <!-- <p>Menit</p> -->
            </div>
        </div>
        <div class="card text-white bg-primary">
            <div class="card-body">
                <span class="info-box-text">Total Durasi Serpo (B+C+D)</span>
                <h3>{{$cardArray['avgTotalDurasi']}}</h3>
                <span class="info-box-text">Menit</span>
                <!-- <p>Menit</p> -->
            </div>
        </div>
        <div class="card text-white bg-primary">
            <div class="card-body">
                <span class="info-box-text">Performa RSPS</span>
                <!-- <h3 class="info-box-number">{{$cardArray['avgRSPS']*100}} %</h3> -->
                <!-- <p class="card-text">Performa RSPS</p>-->
                <h3>{{$cardArray['avgRSPS']*100}} %</h3> 
            </div>
        </div>
    </div>
    <br>
    <div class="card-deck text-center">
        <div class="card text-white bg-secondary">
            <div class="card-body">
                <span class="info-box-text">A. Durasi SBU</span>
                <!-- <span class="info-box-number">{{$cardArray['avgDurasiSBU']}}</span> -->
                <h3>{{$cardArray['avgDurasiSBU']}}</h3>
                <span class="info-box-text">Menit</span>
                <!-- <p class="card-text">A.Durasi SBU</p>
                <p>(Menit)</p> -->
            </div>
        </div>
        <div class="card text-white bg-success">
            <div class="card-body">
                <span class="info-box-text">B. Preparation Time</span>
                <h3>{{$cardArray['avgPrepTime']}}</h3>
                <!-- <span class="info-box-number">{{$cardArray['avgPrepTime']}}</span> -->
                <span class="info-box-text">Menit</span>
                <!-- <p class="card-text">B.Preparation Time</p>
                <p class="card-text">Menit</p> -->
            </div>
        </div>
        <div class="card text-white bg-danger">
            <div class="card-body">
                <span class="info-box-text">C. Travel Time</span>
                <h3 >{{$cardArray['avgTravelTime']}}</h3>
                <span class="info-box-text">Menit</span>
                <!-- <h5 class="card-title"> Menit</h5>
                <p class="card-text"></p> -->
            </div>
        </div>
        <div class="card text-white bg-yellow">
            <div class="card-body">
                <span class="info-box-text">D. Work Time</span>
                <h3>{{$cardArray['avgWorkTime']}}</h3>
                <span class="info-box-text">Menit</span>             
                <!-- <h5 class="card-title">{{$cardArray['avgWorkTime']}} Menit</h5>
                <p class="card-text">Work Time</p> -->
            </div>
        </div>
    </div>
    <br>
@endsection

@section('chart')
<table style="align: center; width: 100%;">
    <tr>
        <tbody>
            <tr>
                <td>
                <div id="woChart" style="height: 300px; width: 100%;"></div>
                </td>
                <td>
                <div id="rspsChart" style="height: 300px; width: 100%;"></div>
                </td>
            </tr>
        </tbody>
    </tr>
</table>
<br>
<div id="chartContainer" style="height: 300px; width: 100%;"></div>
<br>
@endsection

@section('pieChart')
<div class="table table-responsive table-hover" >
    @if($urcdArray!=null)
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
    <table style="float: left" width="45%">
        <thead class="thead-dark">
            <tr>
                <th>Kategori Gangguan (RSPS = 100)</th>
                <th>Total</th>
                <th>Rataan Durasi (Menit)</th>
            </tr>
        </thead>
        @foreach($urcdArray as $urcda)
        <tr class='clickable-row' data-gangguanhref="gangguan-data/{{$urcda['label']}}/national/{{$awal}}/{{$akhir}}">
            <td>{{$urcda['label']}}</td>
            <td>{{$urcda['total']}}</td>
            <td>{{$urcda['durasi']}}</td>
        </tr>
        @endforeach
    </table>
    @endif
    @if($ukArray!=null)
    <table style="float: right" width="45%">
        <thead class="thead-dark">
            <tr>
            <th>Kategori Kendala</th>
            <th>Total</th>
            </tr>
        </thead>
        @foreach($ukArray as $uka)
        <tr class='kendala-row' data-kendalahref="kendala-data/{{$uka['label']}}/national/{{$awal}}/{{$akhir}}">
            <td>{{$uka['label']}}</td>
            <td>{{$uka['total']}}</td>
        </tr>
        @endforeach
    </table>
    @endif
</div>
<div class="table table-responsive table-hover" >
@if($urcArray!=null)
    <table style="float: left" width="45%">
        <thead class="thead-dark">
            <tr>
                <th>Kategori Gangguan (RSPS < 100)</th>
                <th>Total</th>
                <th>Durasi</th>
            </tr>
        </thead>
        @foreach($urcArray as $urca)
        <tr>
            <td>{{$urca['label']}}</td>
            <td>{{$urca['y']}}</td>
            <td>n.a</td>
        </tr>
        @endforeach
    </table>
@endif
</div>
<table style="align: center; width: 100%;">
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
</table>
@endsection
@section('content')
<blockquote class="blockquote text-center">
    <h3>
        <small class="text-muted">Filtered </small>
    Nasional
    </h3>
</blockquote>
<form method="post" action="{{route('national.store')}}">
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
@if($nationalDataForView!=null)
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
@yield('card')
@yield('chart')
@yield('pieChart')
<br>
<div class="text-center">
    <h4>Rata - Rata Data Nasional Berdasarkan Regional (Semua WO)</h4>
</div>
<a href="{{route('national.create')}}" class="btn btn-success"><i class="fa fa-download"></i><span> Download</span></a>
<br>
<br>

<table class="table table-bordered table-striped table-hover" style="text-align: center;">
    <thead class="thead-dark">
        <tr valign="top" >
            <th rowspan="2">Region</th>
            <th rowspan="2">Jumlah WO</th>
            <th colspan="6">Average (Dalam Satuan Menit)</th>
        </tr>
        <tr>
            <th>Durasi SBU</th>
            <th>Total Durasi Serpo (A+B+C)</th>
            <th>A.Preparation Time</th>
            <th>B.Travel Time</th>
            <th>C.Working Time</th>
            <th>RSPS</th>
        </tr>
    </thead>
    <tbody>
        @foreach($nationalDataForView as $data)
        <tr>
            <td>{{$data->region}}</td>
            <td>{{$data->jumlah_wo}}</td>
            <td>{{$data->durasi_sbu}}</td>
            <td>{{$data->total_durasi}}</td>
            <td>{{$data->prep_time}}</td>
            <td>{{$data->travel_time}}</td>
            <td>{{$data->work_time}}</td>
            <td>{{$data->rsps*100}}%</td>
        </tr>
        @endforeach
    </tbody>
</table>
<!-- Table for RSPS 100 -->
<br>
<div class="text-center">
    <h4>Rata - Rata Data Nasional Berdasarkan Regional (RSPS = 100)</h4>
</div>

<table class="table table-bordered table-striped table-hover" style="text-align: center;">
    <thead class="thead-dark">
        <tr valign="top" >
            <th rowspan="2">Region</th>
            <th rowspan="2">Jumlah WO</th>
            <th colspan="6">Average (Dalam Satuan Menit)</th>
        </tr>
        <tr>
            <th>Durasi SBU</th>
            <th>Total Durasi Serpo (A+B+C)</th>
            <th>A.Preparation Time</th>
            <th>B.Travel Time</th>
            <th>C.Working Time</th>
            <th>RSPS</th>
        </tr>
    </thead>
    <tbody>
        @foreach($nationalDataRsps1 as $n)
        <tr>
            <td>{{$n['region']}}</td>
            <td>{{$n['jumlah_wo']}}</td>
            <td>{{$n['durasi_sbu']}}</td>
            <td>{{$n['total_durasi']}}</td>
            <td>{{$n['prep_time']}}</td>
            <td>{{$n['travel_time']}}</td>
            <td>{{$n['work_time']}}</td>
            <td>{{$n['rsps']*100}}%</td>
        </tr>
        @endforeach
    </tbody>
</table>

@endif
@endsection