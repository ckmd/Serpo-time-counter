@extends('layouts.master')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="text-center">
                <h1 class="display-6">All Data from Database</h1>
                <footer class="blockquote-footer">*Durasi dalam satuan menit</footer>
            </div>
            <a href="{{route('excel.create')}}" class="btn btn-success">Download to .xlsx</a>
            <table class="table-responsive table-hover table-bordered table-striped" style="text-align: center;">
                <thead>
                    <tr>
                        <th>id</th>
                        <!-- <th>AR ID</th>
                        <th>Prob ID</th> -->
                        <th>kode_wo</th>
                        <th>region</th>
                        <th>basecamp</th>
                        <th>serpo</th>
                        <th>durasi SBU</th>
                        <th>preparation time</th>
                        <th>travel time</th>
                        <th>working time</th>
                        <th>stopclock time</th>
                        <th>complete time</th>
                        <th>rsps</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($datas as $cat)
                    <tr>
                        <th>{{$cat->id}}</th>
                        <!-- <td>{{$cat->ar_id}}</td>
                        <td>{{$cat->prob_id}}</td> -->
                        <td>{{$cat->kode_wo}}</td>
                        <td>{{$cat->region}}</td>
                        <td>{{$cat->basecamp}}</td>
                        <td>{{$cat->serpo}}</td>
                        <td>{{$cat->durasi_sbu}}</td>
                        @if($cat->prep_time!=null)
                        <td>{{$cat->prep_time}}</td>
                        @else
                        <td>n.a</td>
                        @endif
                        @if($cat->travel_time!=null)
                        <td>{{$cat->travel_time}}</td>
                        @else
                        <td>n.a</td>
                        @endif
                        @if($cat->work_time!=null)
                        <td>{{$cat->work_time}}</td>
                        @else
                        <td>n.a</td>
                        @endif
                        @if($cat->sc_time!=null)
                        <td>{{$cat->sc_time}}</td>
                        @else
                        <td>n.a</td>
                        @endif
                        @if($cat->complete_time)
                        <td>{{$cat->complete_time}}</td>
                        @else
                        <td>n.a</td>
                        @endif
                        <td>{{ round((float)$cat->rsps * 100 ) }}%</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection