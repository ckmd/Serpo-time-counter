@extends('layouts.master')

@section('content')
    <h3>Karyawan Pages</h3>
<!-- Table Start Here -->
    <table class="table table-responsive">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Deskripsi</th>
                <th>Start Travel</th>
                <th>Start Work</th>
                <th>Request Complete</th>
                <th>Travel Time</th>
                <th>Work Time</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $cat)
            <?php
                $travel = new DateTime($cat->start_travel);
                $work = new DateTime($cat->start_work);
                $complete = new DateTime($cat->req_complete);
                $traveltime = date_diff($work, $travel);
                $worktime = date_diff($complete, $work);
            ?>
            <tr>
                <td>{{$cat->title}}</td>
                <td>{{$cat->description}}</td>
                <td>{{$cat->start_travel}}</td>
                <td>{{$cat->start_work}}</td>
                <td>{{$cat->req_complete}}</td>
                <?php
                    if(($traveltime->d==0) && ($traveltime->h==0)){
                        ?>
                <td>{{$traveltime->format('%i minutes, %s seconds')}}</td>
                <?php
                    }else if($traveltime->d==0){?>
                        <td>{{$traveltime->format('%h hours, %i minutes, %s seconds')}}</td>
                        <?php
                    }else{?>
                        <td>{{$traveltime->format('%d days, %h hours, %i minutes, %s seconds')}}</td>
                        <?php
                    }
                ?>
                <?php
                    if(($worktime->d==0) && ($worktime->h==0)){
                        ?>
                <td>{{$worktime->format('%i minutes, %s seconds')}}</td>
                <?php
                    }else if($worktime->d==0){?>
                        <td>{{$worktime->format('%h hours, %i minutes, %s seconds')}}</td>
                        <?php
                    }else{?>
                        <td>{{$worktime->format('%d days, %h hours, %i minutes, %s seconds')}}</td>
                        <?php
                    }
                ?>
            </tr>
            @endforeach
        </tbody>
    </table>
<!-- Table Ends Here -->
<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
  Tambah Karyawan
</button>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Data Karyawan</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <form action="{{route('category.store')}}" method="post">
      {{csrf_field()}}
        <div class="modal-body">
            <div class="form-group">
                <label for="title">Nama</label>
                <input type="text" class="form-control" name="title" id="title">
            </div>
            <div class="form-group">
                <label for="des">Deskripsi</label>
                <textarea class="form-control" name="description" id="des" cols="20" rows="5"></textarea>
            </div>
            <div class="form-group">
                <label for="travel">Start Travel</label>
                <input type="datetime-local" class="form-control" name="start_travel" id="travel" step="1">
            </div>
            <div class="form-group">
                <label for="work">Start Work</label>
                <input type="datetime-local" class="form-control" name="start_work" id="work" step="1">
            </div>
            <div class="form-group">
                <label for="complete">Request Complete</label>
                <input type="datetime-local" class="form-control" name="req_complete" id="complete" step="1">
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection