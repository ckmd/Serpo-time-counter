@extends ('layouts.master')

@section('content')
<!-- Upload Files Start Here -->
<!-- <form action="/excel" method="POST" enctype="multipart/form-data"> -->
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="text-center">

                <h3>Upload Excel File</h3>
                <form action="{{route('excel.store')}}" method="POST" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <input type="file" name="excelFile">
                    <input type="submit" value="submit">
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Upload Files Ends Here -->
<!-- PHP for uploading Files -->
@endsection