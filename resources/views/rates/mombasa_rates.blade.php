@extends('layouts.custom')

@section('content')
    <!-- DataTales Example -->
    <div class="card">
        {{-- Success Message --}}

        <div class="card-header py-3">
            <div class="d-sm-flex align-items-center justify-content-between">
                <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#add"> Add
                    Rate <i class="fas fa-plus"></i></button>
                <h4 class="m-0 font-weight-bold text-danger">Overnight Rates From Mombasa to Other Destinations</h4>

                <a href="/msa_rates_report" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm"><i
                        class="fas fa-download fa-sm text-white"></i> Generate Report</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered text-primary" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Origin</th>
                            <th>Destination</th>
                            <th>Zone</th>
                            <th>Rate</th>
                            <th>Type</th>
                            <th>Approval Status</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Origin</th>
                            <th>Destination</th>
                            <th>Zone</th>
                            <th>Rate</th>
                            <th>Type</th>
                            <th>Approval Status</th>
                            <th>Status</th>
                            <th>Action</th>

                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($rates as $rate)
                            <tr>
                                <td> {{ $loop->iteration }}.</td>
                                <td> {{ $rate->office->name }} </td>

                                <td> {{ $rate->destination }} </td>
                                <td> {{ $rate->zone_name->zone_name }} </td>
                                <td> {{ $rate->rate }} </td>
                                <td> {{ ucfirst($rate->type) }}</td>
                                <td> {{ ucfirst($rate->approvalStatus) }} </td>
                                <td> {{ $rate->status }} </td>
                                <td class="row pl-4">
                                    <!-- Edit Button-->
                                    <button class="btn btn-sm btn-primary mr-1" data-toggle="modal" data-target="#editMombasaRate{{ $rate->id }}">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>

                                    <!-- Delete Button-->
                                    <!-- <button type="button" class="btn btn-sm btn-danger" data-toggle="modal"
                                        data-target="#delete_floor-{{ $rate->id }}"><i
                                            class="fas fa-trash"></i> Delete
                                        </button> -->

                                    <!-- Edit Mombasa Rate Modal -->
                                    <div class="modal fade" id="editMombasaRate{{ $rate->id }}" tabindex="-1" role="dialog" aria-labelledby="editMombasaRateLabel{{ $rate->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-xl" role="document"> 
                                            <div class="modal-content">
                                                <div class="modal-header bg-warning">
                                                    <h5 class="modal-title text-white" id="editMombasaRateLabel{{ $rate->id }}">
                                                        <strong>Edit Mombasa Rate</strong>
                                                    </h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>

                                                <div class="modal-body">
                                                    <form action="{{ route('rates.edit', $rate->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')

                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="text-primary">Route From <span class="text-danger">*</span></label>
                                                                    <select name="office_id" class="form-control" required readonly>
                                                                        @foreach ($offices as $office)
                                                                            <option value="{{ $office->id }}" {{ $office->id == $rate->office_id ? 'selected' : '' }}>
                                                                                {{ $office->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="text-primary">Zone</label>
                                                                    <select name="zone_id" class="form-control" required>
                                                                        <option value="">Select</option>
                                                                        @foreach ($zones as $zone)
                                                                            <option value="{{ $zone->id }}" {{ $zone->id == $rate->zone_id ? 'selected' : '' }}>
                                                                                {{ $zone->zone_name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="text-primary">Destination</label>
                                                                    <input type="text" name="destination" class="form-control" value="{{ $rate->destination }}">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="text-primary">Rate <span class="text-danger">*</span></label>
                                                                    <input type="text" name="rate" required class="form-control" value="{{ $rate->rate }}">
                                                                </div>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="text-primary">Applicable From</label>
                                                                    <input type="date" name="applicableFrom" class="form-control" value="{{ $rate->applicableFrom }}">
                                                                </div>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="text-primary">Applicable To</label>
                                                                    <input type="date" name="applicableTo" class="form-control" value="{{ $rate->applicableTo }}">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="text-primary">Approval Status</label>
                                                                    <select name="approvalStatus" class="form-control">
                                                                        <option value="">Select Status</option>
                                                                        <option value="pending" {{ $rate->approvalStatus == 'pending' ? 'selected' : '' }}>Pending</option>
                                                                        <option value="approved" {{ $rate->approvalStatus == 'approved' ? 'selected' : '' }}>Approved</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="text-primary">Status</label>
                                                                    <select name="status" class="form-control">
                                                                        <option value="">Select Status</option>
                                                                        <option value="active" {{ $rate->status == 'active' ? 'selected' : '' }}>Active</option>
                                                                        <option value="closed" {{ $rate->status == 'closed' ? 'selected' : '' }}>Closed</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="text-primary">Type</label>
                                                                    <select name="type" class="form-control">
                                                                        <option value="">Select rate type</option>
                                                                        <option value="normal" {{ $rate->type == 'normal' ? 'selected' : '' }}>Normal</option>
                                                                        <option value="pharmaceutical" {{ $rate->type == 'pharmaceutical' ? 'selected' : '' }}>Pharmaceutical</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-4 pt-2">
                                                                <button type="submit" class="form-control btn btn-warning btn-sm">
                                                                    <i class="fas fa-save text-white"></i> Update
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Delete Modal-->
                                    <div class="modal fade" id="delete_floor-{{ $rate->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <p>Are you sure you want to delete {{ $rate->regNo }}.
                                                    </p>
                                                </div>
                                                <div class="modal-footer">
                                                    <form action =" {{ route('rates.destroy', ['rate' => $rate->id]) }}"
                                                        method = "POST">
                                                        @method('DELETE')
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete"
                                                            value="DELETE">YES DELETE <i class="fas fa-trash"></i> </button>
                                                    </form>
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Cancel</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="add" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-xl" role="document"> <!-- Added modal-lg for wider layout -->
                    <div class="modal-content">
                        <div class="modal-header bg-success">

                            <h5 class="modal-title text-white" id="exampleModalLabel"> <strong>Add Mombasa Rate</strong>
                            </h5>

                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="  {{ route('rates.store') }} " method="post">

                                <div class="row">
                                    @csrf
                                    <div class="col-md-4">
                                        <div class="form-group"><label class="text-primary">Route From <span
                                                    class="text-danger">*</span></label>
                                            <select name="office_id" class="form-control" required="" readonly>
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
                                            <label class="text-primary" for="longitude">Rate <span
                                                    class="text-danger">*</span></label>
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
                                    <div class="col-md-4">
                                        <div class="form-group"><label class="text-primary">Type</label>
                                            <select name="type" class="form-control">
                                                <option value="">Select rate type</option>
                                                <option value="normal">Normal</option>
                                                <option value="pharmaceutical">Pharmaceutical</option>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
