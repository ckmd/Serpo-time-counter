@section('footer')
    <script>
        $('#delete').on('show.bs.modal', function(event){
            var button = $(event.relatedTarget)
            var gang_id = button.data('gangid')
            var modal = $(this)
            modal.find('.modal-body #gang_id').val(gang_id);
        })
    </script>
@endsection

@extends('layouts.master')

@section('content')
    <h3>Halaman Gangguan</h3>
<!-- Table Start Here -->
    <table class="table table-responsive">
        <thead>
            <tr>
                <th>No</th>
                <th>Kategori Gangguan</th>
                <th>Parameter</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php $id = 1;?>
            @foreach($gangguans as $g)
            <tr>
                <th>{{$id}}</th>
                <td>{{$g->kategori_gangguan}}</td>
                <td>{{$g->parameter}}</td>
                <td>
                    <!-- <a href="#" class="btn btn-secondary">Update</a> -->
                    <button data-gangid="{{$g->id}}" class="btn btn-danger" data-toggle="modal" data-target="#delete">Delete</button>
                </td>
            </tr>
            <?php $id++; ?>
            @endforeach
        </tbody>
    </table>
<!-- Table Ends Here -->
<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
  Tambah Gangguan
</button>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Data Gangguan</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <form action="{{route('gangguan.store')}}" method="post">
      {{csrf_field()}}
        <div class="modal-body">
                    @include('gangguan.addform')
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save</button>
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
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Delete Confirmation</h4>
      </div>
      <form action="{{route('gangguan.destroy','test')}}" method="post">
      {{method_field('delete')}}
      {{csrf_field()}}
        <div class="modal-body">
        <p>
            Do you want to delete the data ?
        </p>
            <input type="hidden" name="gangguan_id" id="gang_id" value="">
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">No, Cancel</button>
            <button type="submit" class="btn btn-danger">Yes, Delete</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection