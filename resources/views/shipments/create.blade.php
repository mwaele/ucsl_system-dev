@extends('layouts.custom')

@section('content')
    <div class="card shadow-sm">
        <div class="card-header pt-4">
            <div class="row pt-2">
                <div class="col-sm-4">
                    <i class="fas fa-table text-success"></i>
                    Add New Shipment
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
                                        <label>Sender Name</label>
                                        <input type="text" class="form-control" name="sender_name" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Address</label>
                                        <input type="text" class="form-control" name="sender_address" required>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>City</label>
                                        <input type="text" class="form-control" name="sender_city" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Contact Person</label>
                                        <input type="text" class="form-control" name="sender_contact" required>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>ID Number</label>
                                        <input type="text" class="form-control" name="sender_id" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Telephone</label>
                                        <input type="text" class="form-control" name="sender_phone" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Date & Time</label>
                                    <input type="text" class="form-control datetime" name="sender_datetime" required>
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
                                        <label>Receiver Name</label>
                                        <input type="text" class="form-control" name="receiver_name" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Address</label>
                                        <input type="text" class="form-control" name="receiver_address" required>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>City</label>
                                        <input type="text" class="form-control" name="receiver_city" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Contact Person</label>
                                        <input type="text" class="form-control" name="receiver_contact" required>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>ID Number</label>
                                        <input type="text" class="form-control" name="receiver_id" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Telephone</label>
                                        <input type="text" class="form-control" name="receiver_phone" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Date & Time</label>
                                    <input type="text" class="form-control datetime" name="receiver_datetime" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description of Goods -->
                <div class="section-title">3. Description of Goods</div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>Description</label>
                        <input type="text" class="form-control" name="goods_description" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Origin</label>
                        <input type="text" class="form-control" name="goods_origin" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Destination</label>
                        <input type="text" class="form-control" name="goods_destination" required>
                    </div>
                </div>

                <!-- Shipment Info Table -->
                <div class="section-title">4. Shipment Information</div>
                <table class="table table-bordered" id="shipmentTable">
                    <thead class="thead-light">
                        <tr>
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
                            <td><input type="number" class="form-control" name="packages[]"></td>
                            <td><input type="number" class="form-control" name="weight[]"></td>
                            <td><input type="number" class="form-control" name="length[]"></td>
                            <td><input type="number" class="form-control" name="width[]"></td>
                            <td><input type="number" class="form-control" name="height[]"></td>
                            <td><button type="button" class="btn btn-danger btn-sm remove-row">Remove</button></td>
                        </tr>
                    </tbody>
                </table>
                <button type="button" class="btn btn-primary mb-3" id="addRowBtn">Add Row</button>

                <!-- Service Level -->
                <div class="section-title">5. Service Level</div>
                <div class="form-group">
                    <label>Select Service</label>
                    <select class="form-control" name="service_level" required>
                        <option value="">-- Select Service --</option>
                        <option value="standard">Standard</option>
                        <option value="express">Express</option>
                        <option value="overnight">Overnight</option>
                    </select>
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
                    <i class="fas fa-table text-success"></i>
                    Add New Client
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="  {{ route('shipments.store') }} " method="post">

                <div class="row">
                    @csrf
                    <div class="col-md-4">
                        <div class="form-group"><label>Client Name</label>
                            <input type="text" name="client_name" class="form-control" required="">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group"><label>Phone Number</label>
                            <input type="text" name="tel_number" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group"><label>Email</label>
                            <input type="text" name="email" class="form-control">
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group"><label>Physical Address</label>
                            <input type="text" name="physical_address" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group"><label>Postal Address</label>
                            <input type="text" name="postal_address" class="form-control" required="">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group"><label>Postal Code</label>
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
