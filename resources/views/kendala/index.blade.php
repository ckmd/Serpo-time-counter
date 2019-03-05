@section('footer')
    <script>
        $('#delete').on('show.bs.modal', function(event){
            var button = $(event.relatedTarget)
            var ken_id = button.data('kenid')
            var modal = $(this)
            modal.find('.modal-body #ken_id').val(ken_id);
        })
    </script>
@endsection

@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="text-center">
                <h3>Halaman kendala</h3>
            </div>
            <!-- Table Start Here -->
            <table class="table table-striped table-hover table-bordered" >
                <thead class="thead-light" style="text-align: center;">
                    <tr>
                        <th>No</th>
                        <th>Kategori Kendala</th>
                        <th>Parameter</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $id = $kendalas->firstItem();?>
                    @foreach($kendalas as $k)
                    <tr>
                        <th>{{$id}}</th>
                        <td>{{$k->kategori_kendala}}</td>
                        <td>{{$k->parameter}}</td>
                        <td>
                            <!-- <a href="#" class="btn btn-secondary">Update</a> -->
                            <button data-kenid="{{$k->id}}" class="btn btn-danger" data-toggle="modal" data-target="#delete">Hapus</button>
                        </td>
                    </tr>
                    <?php $id++; ?>
                    @endforeach
                </tbody>
            </table>
            {{$kendalas->links()}}
            <!-- Table Ends Here -->
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
        Tambah Kendala
        </button>
        <a href="{{url('refresh')}}" class="btn btn-success">Refresh Data</a>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Tambah Kendala</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <form action="{{route('kendala.store')}}" method="post">
      {{csrf_field()}}
        <div class="modal-body">
                    @include('kendala.addForm')
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Hapus Kendala</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <form action="{{route('kendala.destroy','test')}}" method="post">
      {{method_field('delete')}}
      {{csrf_field()}}
        <div class="modal-body">
        <p>
            Apakah anda ingin menghapus Kendala ?
        </p>
            <input type="hidden" name="kendala_id" id="ken_id" value="">
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">Kembali</button>
            <button type="submit" class="btn btn-danger">Hapus</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection