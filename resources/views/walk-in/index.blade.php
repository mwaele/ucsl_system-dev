@extends('layouts.custom')

@section('content')
    <!-- DataTables Example -->
    <div class="card mb-4">
        <div class="card-header py-3">
            <div class="d-sm-flex align-items-center justify-content-between"> 
                <h6 class="m-0 font-weight-bold text-primary">Walk-in Parcels</h6>
                <div class="d-flex align-items-center">
                    <!-- Counter positioned just before the search input -->
                    <span class="text-primary counter mr-2"></span>

                    <button type="button" class="btn btn-sm btn-primary shadow-sm" data-toggle="modal"
                        data-target="#registerParcel">
                        <i class="fas fa-plus fa-sm text-white"></i> Register parcel
                    </button>
                </div> 
            </div>
            <!-- Modal -->
            <form action="" method="POST">
                @csrf
                <div class="modal fade" id="registerParcel" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document"> <!-- Added modal-lg for wider layout -->
                        <div class="modal-content">
                            <div class="modal-header bg-gradient-primary">
                                <h5 class="modal-title text-white" id="exampleModalLabel">Register Parcel</h5>
                                <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form>
                                    <h6 class="text-muted text-primary">Fill in the client details</h6>

                                    <div class="row mb-3">
                                        <div class="col-md-3 mb-3">
                                            <label for="consignment_no" class="form-label text-primary">Request ID</label>
                                            <input type="text" value="{{ $consignment_no }}" name="consignment_no"
                                                class="form-control" id="consignment_no" readonly>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="clientId" class="form-label text-primary">Client</label>
                                            <select class="form-control" id="clientId" name="clientId">
                                                <option value="">Select Client</option>
                                                @foreach ($walkInClients as $client)
                                                    <option value="{{ $client->id }}">{{ $client->name }}
                                                        ({{ $client->accountNo }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="collectionLocation" class="form-label text-primary">From</label>
                                            <select name="origin_id" id="origin_id"
                                                class="form-control origin-dropdown" required>
                                                <option value="">Select</option>
                                                @foreach ($offices as $office)
                                                    <option value="{{ $office->id }}">
                                                        {{ $office->name }}</option>
                                                @endforeach
                                            </select> 
                                        </div>
                                        <div class="col-md-3">
                                            <label for="collectionLocation" class="form-label text-primary">To</label>
                                            <select name="destination"
                                                class="form-control destination-dropdown">
                                                <option value="">Select</option>
                                            </select>
                                        </div>
                                        <input type="hidden" name='destination_id' id="destination_id">
                                    </div>

                                    <h6 class="text-muted text-primary">Fill in the Receiver details.</h6>

                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="receiverContactPerson" class="form-label text-primary">Name</label>
                                            <select class="form-control" id="userId" name="receiverContactPerson">  
                                            </select>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label for="vehicle" class="form-label text-primary">Contact</label>
                                            <input type="text" id="vehicle" class="form-control" name="vehicle_display"
                                                placeholder="Select rider to populate" readonly>
                                            <input type="hidden" id="vehicleId" name="vehicleId">
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label for="datetime" class="text-primary">Date of Request</label>
                                            <div class="input-group">
                                                <input type="text" name="dateRequested" id="datetime"
                                                    class="form-control" placeholder="Select date & time">
                                                <div class="input-group-append">
                                                    <span class="input-group-text" id="calendar-trigger"
                                                        style="cursor: pointer;">
                                                        <i class="fa fa-calendar"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="parcelDetails" class="form-label fw-medium text-primary">Parcel
                                            Details</label>
                                        <textarea class="form-control" id="parcelDetails" name="parcelDetails" rows="3"
                                            placeholder="Fill in the description of goods."></textarea>
                                    </div>

                                    
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered results" id="ucsl-table" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Client</th>
                            <th>Consignment No</th>
                            <th>Date</th>
                            <th>Origin</th>
                            <th>Destination</th>
                            <th>Received By</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr> 
                    </thead>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Client</th>
                            <th>Consignment No</th>
                            <th>Date</th>
                            <th>Origin</th>
                            <th>Destination</th>
                            <th>Received By</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        
                    </tbody>  
                </table>
            </div>
        </div>
@endsection
