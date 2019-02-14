@extends('layouts.master')

@section('header')
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<script>
window.onload = function() {
// Chart for Wo per Region
    var woChart = new CanvasJS.Chart("woChart", {
        animationEnabled: true,
        title: {
            text: "WO Regional"
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
        title: {
            text: "Average RSPS"
        },
        data: [{
            type: "line",
            yValueFormatString: "#,##0\"%\"",
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
    <footer class="blockquote-footer">avg (rata rata) waktu dalam satuan menit</footer>
</blockquote>
<form method="post" action="{{route('home')}}">
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
            <td>{{ round((float)$data->rsps * 100 ) }}%</td>
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
@endsection