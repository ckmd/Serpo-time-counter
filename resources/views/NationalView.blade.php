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
            dataPoints: <?php echo json_encode($chartArray, JSON_NUMERIC_CHECK); ?>
        }]
    });
    trendChart.render();

}
</script>
@endsection

@section('card')
    <div class="card-deck">
        <div class="card text-white bg-primary p-0">
            <div class="card-body">
                <h5 class="card-title">{{$cardArray['regionSum']}}</h5>
                <p class="card-text">Work Order</p>
            </div>
        </div>
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
        <div class="card text-white bg-info">
            <div class="card-body">
                <h5 class="card-title">{{$cardArray['avgRSPS']}} %</h5>
                <p class="card-text">RSPS</p>
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
            <input type="submit" class="btn btn-primary btn-lg" value="Filter">
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
<blockquote>
    <footer class="blockquote-footer">Data dalam satuan menit</footer>
</blockquote>
<table class="table table-bordered table-striped table-hover" style="text-align: center;">
    <thead class="thead-dark">
        <tr>
            <th>Region</th>
            <th>Jumlah WO</th>
            <th>Durasi SBU</th>
            <th>Preparation Time</th>
            <th>Travel Time</th>
            <th>Working Time</th>
            <th>RSPS</th>
        </tr>
    </thead>
    <tbody>
        @foreach($nationalDataForView as $data)
        <tr>
            <td>{{$data->region}}</td>
            <td>{{$data->jumlah_wo}}</td>
            <td>{{$data->durasi_sbu}}</td>
            <td>{{$data->prep_time}}</td>
            <td>{{$data->travel_time}}</td>
            <td>{{$data->work_time}}</td>
            <td>{{$data->rsps}}%</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif
@endsection