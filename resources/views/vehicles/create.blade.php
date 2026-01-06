@extends('layouts.custom')

@section('content')
    <div class="card mb-3">
        <div class="card-header pt-4">
            <div class="row pt-2">
                <div class="col-sm-4">
                    <i class="fas fa-table text-success"></i>
                    Add Vehicle
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="  {{ route('vehicles.store') }} " method="post">

                <div class="row">
                    @csrf
                    <div class="col-md-4">
                        <div class="form-group"><label>Reg No.</label>
                            <input type="text" name="regNo" class="form-control" required="">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group"><label>Type</label>
                            <input type="text" name="type" class="form-control" required="">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group"><label>Color</label>
                            <input type="text" name="color" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group"><label>Tonnage</label>
                            <input type="text" name="tonnage" class="form-control" required>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group"><label>status</label>
                            <select name="status" class="form-control">
                                <option value="">Select Status</option>
                                <option value="available">Available</option>
                                <option value="intransit">In Transit</option>
                                <option value="garage">Garage</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="description">Description</label>
                            <input type="text" name="description" class="form-control">
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group"><label>Default Driver</label>
                            <select name="user_id" class="form-control">
                                <option value="">Select Driver</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="ownedBy">OwnedBy</label>
                            <select name="ownedBy" class="form-control">
                                <option value="">Select Company</option>
                                @foreach ($companies as $company)
                                    <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-4 pt-3">
                        <button type="submit" class="form-control btn btn-primary btn-sm submit">
                            <i class="fas fa-save text-white"></i>
                            Save</button>
                    </div>
                </div>
            </form>
        </div>
        <br>
    </div>
@endsection
