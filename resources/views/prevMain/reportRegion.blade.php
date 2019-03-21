@section('footer')
<script>
    $(".reportsite-row").click(function() {
        window.location = $(this).data("reportsitehref");
    });
</script>
@endsection

@extends('layouts.master')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
        <button class="btn btn-primary" onclick="history.go(-1)"><i class="fa fa-arrow-left"></i><span> Back</span></button>
            <div class="text-center">
                <h3>Daftar Asset PM POP Region {{$region}}</h3>
            </div>
            <div class="table table-responsive table-hover" >
            <h5>Total PM POP : {{$totalPM}}</h5>
            <table class="table-bordered table-striped" width="100%" style="text-align: center;">
                <thead>
                    <th>No</th>
                    <th>Site_ID</th>
                    <th>Site_Name</th>
                    <th>Category</th>
                    <th>Jumlah_PM</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    <?php $id = 1; ?>
                    @foreach ($arrayRegion as $d)
                        <tr>
                            <td>{{$id}}</td>
                            <td>{{$d['site_id']}}</td>
                            <td>{{$d['site_name']}}</td>
                            <td>{{$d['category']}}</td>
                            <td>{{$d['pm']}}</td>
                            <td><button class="btn btn-primary reportsite-row" data-reportsitehref="report-data-site/{{$d['site_id']}}">Detail</button></td>
                        </tr>
                    <?php $id++; ?>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>
@endsection