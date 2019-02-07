@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="text-center">
                <h1 class="display-6">Performa Rata- Rata Serpo Filtered By Region</h1>
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
            <input type="submit" class="btn btn-primary" value="Filter">
            </form>
            <br>
            @if($dbAvgExcel!=null)
            <form method="post" action="{{route('db.store')}}">
            {{csrf_field()}}
                <input type="hidden" name="awal" value="{{$pAwal}}">
                <input type="hidden" name="akhir" value="{{$pAkhir}}">
                <input type="hidden" name="region" value="{{$regionName}}">
                <input type="submit" class="btn btn-success" value="download">
            </form>
            <blockquote class="blockquote text-center">
                <h3>
                    <small class="text-muted">Filtered by </small>
                Region {{$regionName}}
                </h3>
                @if(($pAwal==null) && ($pAkhir==null))
                    <p class="mb-0">Data All Time</p>
                @elseif($pAwal==null)
                    <p class="mb-0">Data sampai dengan {{$pAkhir}}</p>
                @elseif($pAkhir==null)
                    <p class="mb-0">Data Mulai dari {{$pAwal}}</p>
                @else
                    <p class="mb-0">periode {{$pAwal}} s.d. {{$pAkhir}}</p>
                @endif
                <!-- <footer class="blockquote-footer">avg (rata rata) waktu dalam satuan menit</footer> -->
            </blockquote>
            <table class="table table-hover table-bordered">
                <thead class="thead-light" style="text-align: center;">
                    <tr valign="top" >
                        <th rowspan="2" >basecamp</th>
                        <th rowspan="2">serpo</th>
                        <th colspan="6">Average (Dalam Satuan Menit)</th>
                    </tr>
                    <tr>
                        <th>durasi SBU</th>
                        <th>preparation time</th>
                        <th>travel time</th>
                        <th>working time</th>
                        <th>complete time</th>
                        <th>rsps</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                usort($dataArray, function($a, $b) {
                    return $a['basecamp'] <=> $b['basecamp'];
                });
                ?>
            @foreach($dbAvgExcel as $data)
                    <tr>
                        <td>{{$data->basecamp}}</td>
                        <td>{{$data->serpo}}</td>
                        <td>{{round($data->durasi_sbu,2)}}</td>
                        <td>{{round($data->prep_time,2)}}</td>
                        <td>{{round($data->travel_time,2)}}</td>
                        <td>{{round($data->work_time,2)}}</td>
                        <td>{{round($data->complete_time,2)}}</td>
                        <td>{{ round((float)$data->rsps * 100 ) }}%</td>
                    </tr>
            @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>
</div>
@endsection
