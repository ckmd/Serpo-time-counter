@extends('layouts.master')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="text-center">
                <h4 class="display-6">Calculated Raw Data Data</h4>
                <footer class="blockquote-footer">*Durasi dalam satuan menit</footer>
            </div>
            <a href="{{route('excel.create')}}" class="btn btn-success"><i class="fa fa-download"></i><span> Download</span></a>
            <a href="deleteExcel" class="btn btn-danger"><i class="fa fa-trash"></i><span> Hapus Data</span></a>
            <br>
            <br>
            <table class="table table-responsive table-hover table-bordered table-striped" style="text-align: center;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <!-- <th>AR ID</th>
                        <th>Prob ID</th> -->
                        <th>Kode_WO</th>
                        <th>WO_Date</th>
                        <th>Region</th>
                        <th>Basecamp</th>
                        <th>Service_Point</th>
                        <th>Durasi_SBU</th>
                        <th>Preparation_Time</th>
                        <th>Travel_Time</th>
                        <th>Working_Time</th>
                        <th>RSPS</th>
                        <th>Category</th>
                        <th>Root_Cause</th>
                        <th>Kendala</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $id = $datas->firstItem()?>
                    @foreach($datas as $cat)
                    <tr>
                        <th>{{$id}}</th>
                        <!-- <td>{{$cat->ar_id}}</td>
                        <td>{{$cat->prob_id}}</td> -->
                        <td>{{$cat->kode_wo}}</td>
                        <td nowrap="nowrap">{{$cat->wo_date}}</td>
                        <td>{{$cat->region}}</td>
                        <td nowrap="nowrap" class="text-left">{{$cat->basecamp}}</td>
                        <td nowrap="nowrap" class="text-left">{{$cat->serpo}}</td>
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
                        <td>{{$cat->rsps*100}}%</td>
                        @if($cat->category!=null)
                        <td nowrap="nowrap">{{$cat->category}}</td>
                        @else
                        <td>n.a</td>
                        @endif
                        @if($cat->root_cause!=null)
                        <td nowrap="nowrap">{{$cat->root_cause}}</td>
                        @else
                        <td>n.a</td>
                        @endif
                        @if($cat->kendala!=null)
                        <td nowrap="nowrap">{{$cat->kendala}}</td>
                        @else
                        <td>n.a</td>
                        @endif
                    </tr>
                    <?php $id++?>
                    @endforeach
                </tbody>
            </table>
            {{$datas->links()}}
        </div>
    </div>
</div>

@endsection