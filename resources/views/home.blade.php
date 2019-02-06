@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="text-center">
                <h3>Performa Rata- Rata Serpo Filter By Region</h3>
            </div>
            <form method="post" action="{{route('home')}}">
            {{csrf_field()}}
                <div class="form-group">
                    <select name="region" class="form-control">
                        <option value="">-- Pilih Region --</option>
                        @foreach($unique as $u)
                            <option value="{{$u}}">{{$u}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="row">
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
            @if($dataArray!=null)
            <div class="text-center">
                <h3>Filter by Region {{$regionName}}</h3>
                @if(($pAwal==null) && ($pAkhir==null))
                    <h6>Data All Time</h6>
                @elseif($pAwal==null)
                    <h6>Data sampai dengan {{$pAkhir}}</h6>
                @elseif($pAkhir==null)
                    <h6>Data Mulai dari {{$pAwal}}</h6>
                @else
                    <h6>periode {{$pAwal}} s.d. {{$pAkhir}}</h6>
                @endif
                <p>avg (rata rata) waktu dalam satuan menit</p>
            </div>
            <table class="table table-responsive">
                <thead>
                    <tr>
                        <th>basecamp</th>
                        <th>serpo</th>
                        <th>avg durasi SBU</th>
                        <th>avg preparation time</th>
                        <th>avg travel time</th>
                        <th>avg working time</th>
                        <th>avg complete time</th>
                        <th>avg rsps</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                usort($dataArray, function($a, $b) {
                    return $a['basecamp'] <=> $b['basecamp'];
                });
                ?>
            @foreach($dataArray as $data)
                    <tr>
                        <td>{{$data['basecamp']}}</td>
                        <td>{{$data['serpo']}}</td>
                        <td>{{round($data['avgDurasiSBU'],2)}}</td>
                        <td>{{round($data['avgPrepTime'],2)}}</td>
                        <td>{{round($data['avgTravelTime'],2)}}</td>
                        <td>{{round($data['avgWorkTime'],2)}}</td>
                        <td>{{round($data['avgCompleteTime'],2)}}</td>
                        <td>{{ round((float)$data['avgRSPS'] * 100 ) }}%</td>
                    </tr>
            @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>
</div>
@endsection
