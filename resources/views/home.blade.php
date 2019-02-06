@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <form method="post" action="{{route('home')}}">
            {{csrf_field()}}
                <div class="form-group">
                    <label for="region">Pilih Region </label>
                    <select name="region" id="">
                        <option value="">-- Pilih Region --</option>
                        @foreach($unique as $u)
                            <option value="{{$u}}">{{$u}}</option>
                        @endforeach
                    </select>
                </div>
                <input type="submit" class="btn btn-primary" value="Filter">
            </form>
            <!-- Refactoring Starts Here -->
            @if($filteredRegion!=NULL)
            <h2>filter region {{$regionName}}</h2>
            <table class="table table-responsive">
                <thead>
                    <tr>
                        <th>basecamp</th>
                        <th>serpo</th>
                        <th>durasi SBU</th>
                        <th>preparation time</th>
                        <th>travel time</th>
                        <th>working time</th>
                        <th>complete time</th>
                        <th>rsps</th>
                    </tr>
                </thead>
                <tbody>
            @foreach($filteredRegion as $cat)
                    <tr>
                        <td>{{$cat->basecamp}}</td>
                        <td>{{$cat->serpo}}</td>
                        <td>{{$cat->durasi_sbu}}</td>
                        <td>{{$cat->prep_time}}</td>
                        <td>{{$cat->travel_time}}</td>
                        <td>{{$cat->work_time}}</td>
                        <td>{{$cat->complete_time}}</td>
                        <td>{{ round((float)$cat->rsps * 100 ) }}%</td>
                    </tr>
            @endforeach
                </tbody>
            </table>
            @endif
            <!-- Refactoring Ends Here -->
            @if($dataArray!=null)
            <h2>filter region {{$regionName}}</h2>
            <h6>avg (rata rata) waktu dalam satuan menit</h6>
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
                        <td>{{round($data['avgPrepTime'])}}</td>
                        <td>{{round($data['avgTravelTime'])}}</td>
                        <td>{{round($data['avgWorkTime'])}}</td>
                        <td>{{round($data['avgCompleteTime'])}}</td>
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
