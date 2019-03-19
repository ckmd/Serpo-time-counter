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
            <div class="text-center">
                <h1 class="display-6">Report PM POP Region {{$region}}</h1>
            </div>
            <div class="table table-responsive table-hover" >
            <h4>Total PM : {{$totalPM}}</h4>
            <table class="table-bordered table-striped" width="100%" style="text-align: center;">
                <thead>
                    <th>No</th>
                    <th>Site ID</th>
                    <th>Site Name</th>
                    <th>Category</th>
                    <th>Jumlah PM</th>
                </thead>
                <tbody>
                    <?php $id = 1; ?>
                    @foreach ($arrayRegion as $d)
                        <tr class='reportsite-row' data-reportsitehref="report-data-site/{{$d['site_id']}}">
                            <td>{{$id}}</td>
                            <td>{{$d['site_id']}}</td>
                            <td>{{$d['site_name']}}</td>
                            <td>{{$d['category']}}</td>
                            <td>{{$d['pm']}}</td>
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