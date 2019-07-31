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

    // var rootCauseChart = new CanvasJS.Chart("rootCauseChart", {
    //     theme: "light2", // "light1", "dark1", "dark2"
    //     animationEnabled: true, 		
    //     title:{
    //         text: "Kategori Gangguan"
    //     },
    //     data: [{
    //         type: "column",
    //         yValueFormatString: "#,##0.00\"%\"",
    //         // indexLabel: "{label} ({y})",
    //         dataPoints: <?php echo json_encode($urcdArray, JSON_NUMERIC_CHECK); ?>
    //     }]
    // });
    // rootCauseChart.render();

// Category
var catChart = new CanvasJS.Chart("catChart", {
        theme: "light2", // "light1", "dark1", "dark2"
        animationEnabled: true, 		
        title:{
            text: "Category"
        },
        axisX:{
            labelFontSize: 11,
            interval: 1,
            labelAngle: 0
        },
        data: [{
            indexLabelFontSize: 11,
            // showInLegend: "true",
			// legendText: "{label}",
            indexLabel: "{label} (#percent%)",
            type: "pie",
            dataPoints: <?php echo json_encode($category, JSON_NUMERIC_CHECK); ?>
        }]
    });
    catChart.render();

// Chart FOC
    var focChart = new CanvasJS.Chart("focChart", {
        theme: "light2", // "light1", "dark1", "dark2"
        animationEnabled: true, 		
        title:{
            text: "FOC"
        },
        axisX:{
            labelFontSize: 11,
            interval: 1,
            labelAngle: 0
        },
        data: [{
            indexLabelFontSize: 11,
            type: "column",
            dataPoints: <?php echo json_encode($arrayUrc["FOC"], JSON_NUMERIC_CHECK); ?>
        }]
    });
    focChart.render();

// Chart FOT
var fotChart = new CanvasJS.Chart("fotChart", {
        theme: "light2", // "light1", "dark1", "dark2"
        animationEnabled: true, 		
        title:{
            text: "FOT / Perangkat"
        },
        axisX:{
            labelFontSize: 11,
            interval: 1,
            labelAngle: 0
        },
        data: [{
            indexLabelFontSize: 11,
            type: "column",
            yValueFormatString: "#",
            dataPoints: <?php echo json_encode($arrayUrc["FOT/Perangkat"], JSON_NUMERIC_CHECK); ?>
        }]
    });
    fotChart.render();

// Chart Bukan Gangguan
    var bgChart = new CanvasJS.Chart("bgChart", {
        theme: "light2", // "light1", "dark1", "dark2"
        animationEnabled: true, 		
        title:{
            text: "Bukan Gangguan"
        },
        axisX:{
            labelFontSize: 11,
            interval: 1,
            labelAngle: 0
        },
        data: [{
            indexLabelFontSize: 11,
            type: "column",
            yValueFormatString: "#",
            dataPoints: <?php echo json_encode($arrayUrc["Bukan Gangguan"], JSON_NUMERIC_CHECK); ?>
        }]
    });
    bgChart.render();

// PS Chart
    var psChart = new CanvasJS.Chart("psChart", {
        theme: "light2", // "light1", "dark1", "dark2"
        animationEnabled: true, 		
        title:{
            text: "PS"
        },
        axisX:{
            labelFontSize: 11,
            interval: 1,
            labelAngle: 0
        },
        data: [{
            indexLabelFontSize: 11,
            type: "column",
            yValueFormatString: "#",
            dataPoints: <?php echo json_encode($arrayUrc["PS"], JSON_NUMERIC_CHECK); ?>
        }]
    });
    psChart.render();

// Software Chart
var swChart = new CanvasJS.Chart("swChart", {
        theme: "light2", // "light1", "dark1", "dark2"
        animationEnabled: true, 		
        title:{
            text: "Software"
        },
        axisX:{
            labelFontSize: 11,
            interval: 1,
            labelAngle: 0
        },
        data: [{
            indexLabelFontSize: 11,
            type: "column",
            yValueFormatString: "#",
            dataPoints: <?php echo json_encode($arrayUrc["Software"], JSON_NUMERIC_CHECK); ?>
        }]
    });
    swChart.render();

// TerminasiChart
var terminasiChart = new CanvasJS.Chart("terminasiChart", {
        theme: "light2", // "light1", "dark1", "dark2"
        animationEnabled: true, 		
        title:{
            text: "Top 10 Terminasi POP"
        },
        axisX:{
            labelFontSize: 11,
            interval: 1,
            labelAngle: 0
        },
        data: [{
            indexLabelFontSize: 11,
            type: "column",
            yValueFormatString: "#,##",
            indexLabel: "{y}/{presentase}%",
            dataPoints: <?php echo json_encode($countPop, JSON_NUMERIC_CHECK); ?>
        }]
    });
    terminasiChart.render();
    
// Kendala Chart
    var kendalaChart = new CanvasJS.Chart("kendalaChart", {
        theme: "light2", // "light1", "dark1", "dark2"
        animationEnabled: true, 		
        title:{
            text: "Kategori Kendala"
        },
        axisX:{
            labelFontSize: 11,
            interval: 1,
            labelAngle: 0
        },
        data: [{
            indexLabelFontSize: 11,
            type: "column",
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
<!-- 
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
                <td>{{$uka['y']}}</td>
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
    @endif  -->
    <!-- Chart untuk persebaran category -->
    <div class="table table-responsive table-hover" >
        <table style="float: left" width="45%">
            <thead class="thead-dark">
                <tr>
                    <th>Category Name</th>
                    <th>Total</th>
                    <th>Total Durasi (Menit)</th>
                </tr>
            </thead>
                @foreach($category as $c)
                <tr>
                    <td>{{$c['label']}}</td>
                    <td>{{$c['y']}}</td>
                    <td>{{$c['durasi']}}</td>
                </tr>
                @endforeach
        </table>
        <table style="float: right" width="50%">
            <tr><td>
                <div id="catChart" style="height: 280px;width: 100%;"></div>
            </td></tr>
        </table>
    </div>
    <div class="table table-responsive table-hover" >
        <table style="float: left" width="35%">
            <thead class="thead-dark">
                <tr>
                    <th>Top 10 Terminasi POP Name</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($countPop as $cp)
                    <tr>
                        <td>{{$cp['label']}}</td>
                        <td>{{$cp['y']}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <table style="float: right" width="65%">
            <tr><td>
                <div id="terminasiChart" style="height: 280px;width: 100%;"></div>
            </td></tr>
        </table>
    </div>
    <!-- Chart untuk menampilkan Root cause Gangguan dan Kendala -->
    <div id="focChart" style="height: 300px;width: 100%;"></div><br>
    <div id="fotChart" style="height: 300px;width: 100%;"></div><br>
    <div id="psChart" style="height: 300px;width: 100%;"></div><br>
    <div id="swChart" style="height: 300px;width: 100%;"></div><br>
    <div id="bgChart" style="height: 300px;width: 100%;"></div><br>
    <div id="kendalaChart" style="height: 300px;width: 100%;"></div>
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
                    <small class="text-muted">Filtered by SBU </small>
                Region {{$regionLongName}}
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
                <h3>Data Performa Rata - Rata Serpo Berdasarkan Region {{$regionLongName}}</h3>
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
                        <th>Durasi_SBU</th>
                        <th>Total_Durasi_Serpo (A+B+C)</th>
                        <th>A.Prep_Time</th>
                        <th>B.Travel_Time</th>
                        <th>C.Working_Time</th>
                        <th>RSPS</th>
                    </tr>
                </thead>
                <tbody style="text-align: center;">
                <?php $i = 1; ?>
            @foreach($dbAvgExcel as $data)
                    <tr>
                        <th>{{$i}}</th>
                        <td nowrap="nowrap" class="text-left">{{$data->basecamp}}</td>
                        <td nowrap="nowrap" class="text-left">{{$data->serpo}}</td>
                        <td>{{$data->jumlah_wo}}</td>
                        <td>{{round($data->durasi_sbu,2)}}</td>
                        @if($data->prep_time!=null)
                        <td>{{round($data->prep_time,2)}}</td>
                        @else
                        <td>n.a</td>
                        @endif
                        @if($data->total_durasi!=null)
                        <td>{{round($data->total_durasi,2)}}</td>
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