@extends('layouts.master')

@section('content')
    <h3>Preventive Maintenance</h3>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="text-center">
                    <img src="{{asset('images/excel.png')}}"  alt="Upload Excel File">

                    <h3>Upload Preventive Maintenance File</h3>
                    <form action="{{route('prevMain.store')}}" method="POST" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <input type="file" class="btn btn-primary btn-md" name="excelFile">
                        <input type="submit" class="btn btn-primary" value="submit">
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection