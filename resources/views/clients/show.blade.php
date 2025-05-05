@extends('layouts.custom')

@section('content')

    <div class="card mb-3">
      <div class="card-header">
        <div class="row">
            <div class="col-sm-4">
        <i class="fas fa-table text-success"></i>
        Client Details</div>
      </div>
      </div>
      <div class="card-body">
        @foreach($clients as $client)
            <div class="row">
              @csrf
              @method('PATCH')
            <div class="col-md-4">
            <div class="form-group"><label>CLient Name.</label>
                <input type="text" name="client_name" value="{{ $client->client_name}}"  class="form-control" required="" disabled>
              </div>
            </div>
            <div class="col-md-4">
            <div class="form-group"><label>Phone Number</label>
                <input type="text" name="tel_number" value="{{ $client->tel_number}}"  class="form-control" required="" disabled>
              </div>
            </div>
            <div class="col-md-4">
            <div class="form-group"><label>Email</label>
                <input type="text" name="email" value="{{ $client->email}}"  class="form-control" required="" disabled>
              </div>
            </div>
        </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group"><label>Physical Address</label>
                    <input type="text" name="physical_address" value="{{ $client->physical_address}}"  class="form-control" required="" disabled>
                </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group"><label>Postal Address</label>
                    <input type="text" name="postal_address" value="{{ $client->postal_address}}"  class="form-control" required="" disabled>
                </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group"><label>Postal Code</label>
                    <input type="text" name="postal_code" value="{{ $client->postal_code}}"  class="form-control" required="" disabled>
                </div>
              </div>
            </div>
            <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Full Name (Operations Person)</label>
                <input type="text" name="operations_person" class="form-control" value="{{$client->operations_person}}" disabled>
              </div>    
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="">Email</label>
                <input type="email" name="o_email" value="{{$client->o_email}}" class="form-control" disabled>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="">Phone Number</label>
                <input type="text" name="o_phone" class="form-control" value="{{$client->o_phone}}" disabled>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Full Name (Finance Person)</label>
                <input type="text" name="finance_person" class="form-control" value="{{$client->finance_person}}" disabled>

              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="">Email</label>
                <input type="email" name="f_email" class="form-control" value="{{$client->f_email}}" disabled>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="">Phone Number</label>
                <input type="text" name="f_phone" class="form-control" disabled value="{{$client->f_phone}}">
              </div>
            </div>
          </div>
          @endforeach
          </div>
          <br>
      </div>

      @endsection