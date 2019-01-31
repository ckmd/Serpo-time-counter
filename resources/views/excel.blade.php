@extends ('layouts.master')

@section('content')
<!-- Upload Files Start Here -->
<!-- <form action="/excel" method="POST" enctype="multipart/form-data"> -->
<form action="{{route('excel.store')}}" method="POST" enctype="multipart/form-data">
    {{csrf_field()}}
    <input type="file" name="excelFile">
    <input type="submit" value="submit">
</form>
<!-- Upload Files Ends Here -->
<!-- PHP for uploading Files -->
@endsection