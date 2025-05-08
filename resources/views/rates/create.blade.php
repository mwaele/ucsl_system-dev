@extends('layouts.custom')

@section('content')
    <div class="card mb-3">
        <div class="card-header pt-4">
            <div class="row pt-2">
                <div class="col-sm-4">
                    <i class="fas fa-table text-success"></i>
                    Add Rate
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="  {{ route('rates.store') }} " method="post">

                <div class="row">
                    @csrf
                    <div class="col-md-4">
                        <div class="form-group"><label class="text-primary">Route From <span
                                    class="text-danger">*</span></label>
                            <select name="office_id" class="form-control" required="">
                                <option value="">Select</option>
                                @foreach ($offices as $office)
                                    <option value="{{ $office->id }}">{{ $office->name }}</option>
                                @endforeach

                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group"><label class="text-primary">Zone </label>
                            <select name="zone_id" class="form-control" required="">
                                <option value="">Select</option>
                                @foreach ($zones as $zone)
                                    <option value="{{ $zone->id }}">{{ $zone->zone_name }}</option>
                                @endforeach

                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group"><label class="text-primary">Destination </label>
                            <input type="text" name="destination" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">


                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="text-primary" for="longitude">Rate <span class="text-danger">*</span></label>
                            <input type="text" name="rate" required class="form-control">
                        </div>
                    </div>


                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="text-primary" for="applicableFrom">Applicable From</label>
                            <input type="date" name="applicableFrom" class="form-control">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="text-primary" for="applicableFrom">Applicable To</label>
                            <input type="date" name="applicableTo" class="form-control">
                        </div>
                    </div>

                </div>

                <div class="row">


                    <div class="col-md-4">
                        <div class="form-group"><label class="text-primary">Approval Status</label>
                            <select name="approvalStatus" class="form-control">
                                <option value="">Select Status</option>
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group"><label class="text-primary">Status</label>
                            <select name="status" class="form-control">
                                <option value="">Select Status</option>
                                <option value="active">Active</option>
                                <option value="closed">Closed</option>
                            </select>
                        </div>
                    </div>


                </div>


                <div class="row">



                    <div class="col-md-4 pt-2">
                        <label class="text-primary" for=""></label>
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
