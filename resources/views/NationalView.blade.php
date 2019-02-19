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
        subtitles: [{
	    	text: {{$totalWO}}
            }],
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
            text: "Average RSPS"
        },
        data: [{
            type: "column",
            yValueFormatString: "#,##0.00\"%\"",
            dataPoints: <?php echo json_encode($rspsArray, JSON_NUMERIC_CHECK); ?>
        }]
    });
    rspsChart.render();
}
</script>
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
@endif
@endsection