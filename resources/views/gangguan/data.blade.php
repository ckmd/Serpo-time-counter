@extends('layouts.master')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="text-center">
                <h1 class="display-6">Data Gangguan {{$label}} </h1>
            </div>
            <a href="{{route('gangguan.create')}}" class="btn btn-success">Download to .xlsx</a>
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
                        <th>rsps</th>
                        <th>Root Cause</th>
                        <th>Kendala</th>
                    </tr>
                </thead>
                <tbody>
                <?php $id = $dataGangguan->firstItem(); ?>
                    @foreach($dataGangguan as $cat)
                    <tr>
                        <th>{{$id}}</th>
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
                        <td>{{$cat->rsps * 100}}%</td>
                        @if($cat->root_cause!=null)
                        <td>{{$cat->root_cause}}</td>
                        @else
                        <td>n.a</td>
                        @endif
                        @if($cat->kendala!=null)
                        <td>{{$cat->kendala}}</td>
                        @else
                        <td>n.a</td>
                        @endif
                    </tr>
                    <?php $id++; ?>
                    @endforeach
                </tbody>
            </table>
            {{$dataGangguan->links()}}
        </div>
    </div>
</div>

@endsection