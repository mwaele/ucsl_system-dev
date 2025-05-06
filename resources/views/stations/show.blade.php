@extends('layouts.admin_app')

@section('content')

    <div class="card mb-3">
      <div class="card-header">
        <div class="row">
            <div class="col-sm-4">
        <i class="fas fa-table text-success"></i>
        View</div>
      </div>
      </div>
      <div class="card-body">
        @foreach($floors as $floor)
            <div class="row">
            <div class="col-md-6">
            <div class="form-group"><label>Room Category Name.</label>
                <input type="text" name="floor_name" value="{{ $floor->floor_name}}"  class="form-control" required="" disabled>
              </div>
            </div>
            <div class="col-md-6">
            <label><i class="fa fa-double-check"></i></label>
            <a href="{{route('floors.index')}}">
              <button type="submit"   class="form-control  btn btn-danger btn-sm submit">
                 <i class="fa fa-backward text-white"></i>
              Back</button>
</a>
            </div>
          </div>
          @endforeach
          </div>
          <br>
      </div>

      @endsection