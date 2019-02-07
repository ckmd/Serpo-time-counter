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
            @if($dataArray!=null)
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
                <footer class="blockquote-footer">avg (rata rata) waktu dalam satuan menit</footer>
            </blockquote>
            <table class="table table-hover">
                <thead class="thead-light">
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
