@extends ('layouts.master')

@section('popTable')
<table class="table table-striped table-hover table-bordered">
    <thead>
        <tr>
            <th>No</th>
            <th>POP ID</th>
            <th>POP Name</th>
            <th>SBU</th>
            <th>Type</th>
            <th>Location</th>
        </tr>
    </thead>
    <tbody>
        <?php $id = $pop->firstItem()?>
        @foreach($pop as $p)
            <tr>
                <td>{{$id}}</td>
                <td>{{$p->pop_id}}</td>
                <td>{{$p->pop_name}}</td>
                <td>{{$p->sbu}}</td>
                <td>{{$p->type}}</td>
                <td>{{$p->location}}</td>
            </tr>
            <?php $id++?>
        @endforeach
    </tbody>
</table>
{{$pop->links()}}
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="text-center">
                <h3>Upload Raw Data Pop File</h3>
                <form action="{{route('pop.store')}}" method="POST" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <input type="file" class="btn btn-primary btn-md" name="popFile">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i><span> Upload</span></button>
                </form>
            </div>
        </div>
    </div>
</div>
@yield('popTable')

@endsection