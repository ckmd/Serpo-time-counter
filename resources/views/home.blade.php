@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!
                </div>
            </div> -->
            <form method="post" action="{{route('home')}}">
            {{csrf_field()}}
                <div class="form-group">
                    <label for="region">Pilih Region </label>
                    <select name="region" id="">
                        <option value="">-- Pilih Region --</option>
                        @foreach($unique as $u)
                            <option value="{{$u}}">{{$u}}</option>
                        @endforeach
                    </select>
                </div>
                <input type="submit" class="btn btn-primary" value="Filter">
            </form>
            @if($filteredRegion!=NULL)
            <h2>filter region {{$regionName}}</h2>
            <table class="table table-responsive">
                <thead>
                    <tr>
                        <th>basecamp</th>
                        <th>serpo</th>
                        <th>durasi SBU</th>
                        <th>preparation time</th>
                        <th>travel time</th>
                        <th>working time</th>
                        <th>complete time</th>
                        <th>rsps</th>
                    </tr>
                </thead>
                <tbody>
            @foreach($filteredRegion as $cat)
                    <tr>
                        <td>{{$cat->basecamp}}</td>
                        <td>{{$cat->serpo}}</td>
                        <td>{{$cat->durasi_sbu}}</td>
                        <td>{{$cat->prep_time}}</td>
                        <td>{{$cat->travel_time}}</td>
                        <td>{{$cat->work_time}}</td>
                        <td>{{$cat->complete_time}}</td>
                        <td>{{ round((float)$cat->rsps * 100 ) }}%</td>
                    </tr>
            @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>
</div>
@endsection
