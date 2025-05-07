@extends('layouts.custom')

@section('content')
    <div class="card shadow-sm">
        <div class="card-header pt-4">
            <div class="row pt-2">
                <div class="col-sm-4">
                    <i class="fas fa-table text-success"></i>
                    <b class="text-dark">Add New Shipment</b>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form>

                <!-- Sender and Receiver in same row -->

                <div class="row">
                    <!-- Sender Panel -->
                    <div class="col-md-6">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-primary text-white">Sender Details</div>
                            <!-- SENDER DETAILS -->
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label class="form-label text-dark">Sender Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="senderName" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label text-dark">Address <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control" name="senderAddress" required>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label class="form-label text-dark">Town <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control" name="senderTown" required>
                                    </div>
                                    {{-- <div class="form-group col-md-6">
                                        <label class="form-label text-dark">Date <span class="text-danger">*</span>
                                        </label>
                                        <input type="date" class="form-control datetime" name="sender_datetime" required>
                                    </div> --}}
                                    {{-- <div class="form-group col-md-6">
                                        <label class="form-label text-dark">Contact Person <span
                                                class="text-danger">*</span> </label>
                                        <input type="text" class="form-control" name="sender_contact" required>
                                    </div> --}}
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label class="form-label text-dark">ID Number <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control" name="senderIdNo" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label text-dark">Telephone <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control" name="senderPhone" required>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Receiver Panel -->
                    <div class="col-md-6">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-success text-white">Receiver Details</div>
                            <!-- RECEIVER DETAILS -->
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label class="form-label text-dark">Receiver Name <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control" name="receiverContactPerson" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label text-dark">Address <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control" name="receiverAddress" required>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label class="form-label text-dark">Town <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control" name="receiverTown" required>
                                    </div>
                                    {{-- <div class="form-group col-md-6">
                                        <label class="form-label text-dark">Contact Person <span
                                                class="text-danger">*</span> </label>
                                        <input type="text" class="form-control" name="receiver_contact" required>
                                    </div> --}}
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label class="form-label text-dark">ID Number <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control" name="receiverIdNo" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label text-dark">Telephone <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control" name="receiverPhone" required>
                                    </div>
                                </div>
                                {{-- <div class="form-group">
                                    <label class="form-label text-dark">Date
                                    </label>
                                    <input type="date" class="form-control datetime" name="receiver_datetime"
                                        required>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description of Goods -->
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label class="form-label text-dark">Description <span class="text-danger">*</span> </label>
                        <input type="text" class="form-control" name="goods_description" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label class="form-label text-dark">Origin <span class="text-danger">*</span> </label>
                        <select name="origin" id="" class="form-control" required>
                            <option value="">Select</option>
                            @foreach ($stations as $station)
                                <option value="{{ $station->station_name }}">{{ $station->station_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label class="form-label text-dark">Destination <span class="text-danger">*</span> </label>
                        <select name="destination" id="" class="form-control" required>
                            <option value="">Select</option>
                            @foreach ($stations as $station)
                                <option value="{{ $station->station_name }}">{{ $station->station_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Shipment Info Table -->
                {{-- <div class="section-title"><b class="text-dark">
                        Shipment Information</b></div> --}}

                <div class="table-responsive mt-3">
                    <table class="table table-bordered" id="shipmentTable">
                        <thead class="thead-success">
                            <tr>
                                <th>Item Name</th>
                                <th>No. of Packages</th>
                                <th>Weight (kg)</th>
                                <th>Length (cm)</th>
                                <th>Width (cm)</th>
                                <th>Height (cm)</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input type="text" class="form-control" name="item[]"></td>
                                <td><input type="number" min="0" max="100" class="form-control"
                                        name="packages[]"></td>
                                <td><input type="number" min="0" max="100" class="form-control"
                                        name="weight[]"></td>
                                <td><input type="number" min="0" max="100" class="form-control"
                                        name="length[]"></td>
                                <td><input type="number" min="0" max="100" class="form-control"
                                        name="width[]"></td>
                                <td><input type="number" min="0" max="100" class="form-control"
                                        name="height[]"></td>
                                <td><button type="button" class="btn btn-danger btn-sm remove-row">Remove</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <button type="button" class="btn btn-primary mb-3" id="addRowBtn">Add Row</button>


                <!-- Service Level -->
                <div class="section-title"></div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label class="form-label text-dark">Select Service <span class="text-danger">*</span> </label>
                        <select class="form-control" name="service" required>
                            <option value="">-- Select Service --</option>
                            <option value="standard">Standard</option>
                            <option value="express">Express</option>
                            <option value="overnight">Overnight</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="form-label text-dark">Cost <span class="text-danger">*</span>
                        </label>
                        <input type="number" min="0" class="form-control" name="cost" required>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="btn btn-success">Submit Shipment</button>
            </form>
        </div>


    </div>
    </div>
    {{-- <div class="card mb-3">
        <div class="card-header pt-4">
            <div class="row pt-2">
                <div class="col-sm-4">
                    <i class="fas fa-table text-dark"></i>
                    Add New Client
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="  {{ route('shipments.store') }} " method="post">

                <div class="row">
                    @csrf
                    <div class="col-md-4">
                        <div class="form-group"><label class="form-label text-dark">Client Name <span class="text-danger">*</span> </label>
                            <input type="text" name="client_name" class="form-control" required="">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group"><label class="form-label text-dark">Phone Number <span class="text-danger">*</span> </label>
                            <input type="text" name="tel_number" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group"><label class="form-label text-dark">Email <span class="text-danger">*</span> </label>
                            <input type="text" name="email" class="form-control">
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group"><label class="form-label text-dark">Physical Address <span class="text-danger">*</span> </label>
                            <input type="text" name="physical_address" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group"><label class="form-label text-dark">Postal Address <span class="text-danger">*</span> </label>
                            <input type="text" name="postal_address" class="form-control" required="">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group"><label class="form-label text-dark">Postal Code <span class="text-danger">*</span> </label>
                            <input type="text" name="postal_code" class="form-control" required="">
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-4 pt-3">
                        <button type="submit" class="form-control btn btn-primary btn-sm submit">

                            <strong> SAVE CLIENT </strong> <i class="fas fa-arrow-right text-white"></i></button>
                    </div>
                </div>
            </form>
        </div>
        <br>
    </div> --}}
@endsection
