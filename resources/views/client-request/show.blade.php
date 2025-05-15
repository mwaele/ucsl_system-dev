@extends('layouts.custom')

@section('content')
    <!-- DataTales Example -->
    <div class="card">
        <div class="card-header py-3">
            <div class="d-sm-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-danger">My Collections List</h6>
                <a href="/collections_report" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm"><i
                        class="fas fa-download fa-sm text-white"></i> Generate Report</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>

                            <th>Req ID</th>
                            <th>Client Name</th>
                            <th>Telephone Number</th>
                            <th>Date Allocated</th>
                            <th>Physical Address</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>

                            <th>Req ID</th>
                            <th>Client Name</th>
                            <th>Client Telephone Number</th>
                            <th>Date Allocated</th>
                            <th>Physical Address</th>
                            <th>Status</th>
                            <th>Action</th>

                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($collections as $collection)
                            <tr>
                                <td>{{ $collection->requestId }}</td>
                                <td> {{ $collection->client->name }} </td>
                                <td> {{ $collection->client->contactPersonPhone }} </td>
                                <td> {{ $collection->created_at }} </td>
                                <td> {{ $collection->client->address }} </td>
                                <td>
                                    <p
                                        class="badge
                                            @if ($collection->status == 'pending collection') bg-secondary
                                            @elseif ($collection->status == 'collected')
                                                bg-warning
                                            @elseif ($collection->status == 'Delayed')
                                                bg-primary @endif
                                            fs-5 text-white">
                                        {{ \Illuminate\Support\Str::title($collection->status) }}
                                    </p>
                                </td>
                                <td class="row pl-4">
                                    <a href="#">
                                        <button class="btn btn-sm btn-info mr-1" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </a>
                                    <a href="#">
                                        <button class="btn btn-sm btn-warning" data-toggle="modal" title="Collect parcels"
                                            data-target="#collect-{{ $collection->id }}"><i class="fas fa-box"></i>
                                        </button>
                                    </a>
                                    {{-- <a href="#">
                                        <button class="btn btn-sm btn-warning mr-1" title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </a>
                                    <a href="#">
                                        <button class="btn btn-sm btn-success mr-1" title="PDF Download">
                                            <i class="fas fa-file-pdf"></i>
                                        </button>
                                    </a> --}}
                                    {{-- <button type="button" class="btn btn-sm btn-danger" data-toggle="modal"
                                        data-target="#delete_floor-{{ $collection->id }}"><i
                                            class="fas fa-trash"></i></button> --}}
                                    <!-- Logout Modal-->
                                    <div class="modal fade" id="delete_floor-{{ $collection->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <p>Are you sure you want to delete {{ $collection->client_name }}.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <form action ="#" method = "POST">
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


                                    <div class="modal fade" id="collect-{{ $collection->id }}" tabindex="-1" role="dialog"
                                        aria-labelledby="collectionModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-warning">
                                                    <h5 class="modal-title text-dark" id="exampleModalLabel">Collection of
                                                        {{ $collection->parcelDetails }}. Request ID
                                                        {{ $collection->requestId }}
                                                        for
                                                        {{ $collection->client->name }}</h5>
                                                    <button type="button" class="text-white close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">





                                                    <form action="{{ route('shipment_collections.store') }}"
                                                        method="POST">

                                                        @csrf

                                                        <!-- Radio Buttons -->
                                                        <div class="form-group mb-3">
                                                            <label class="form-label text-dark">Sender Type</label><br>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio"
                                                                    name="sender_type" id="clientRadio" value="client">
                                                                <label class="form-check-label"
                                                                    for="clientRadio">Client</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio"
                                                                    name="sender_type" id="agentRadio" value="agent">
                                                                <label class="form-check-label"
                                                                    for="agentRadio">Agent</label>
                                                            </div>
                                                        </div>

                                                        <!-- Sender and Receiver in same row -->

                                                        <!-- Sender Panel -->
                                                        <div class="col-md-12">
                                                            <div class="card shadow-sm mb-4" id="senderForm"
                                                                style="display: none;">
                                                                <div class="card-header bg-primary text-white">Sender
                                                                    Details</div>
                                                                <!-- SENDER DETAILS -->
                                                                <div class="card-body">
                                                                    <!-- Sender Details Form (Initially Hidden) -->
                                                                    <div>
                                                                        <div class="form-row">
                                                                            <div class="form-group col-md-12">
                                                                                <label class="form-label text-dark">Sender
                                                                                    Name <span
                                                                                        class="text-danger">*</span></label>
                                                                                <input type="text" class="form-control"
                                                                                    name="sender_name" id="sender_name">
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-row">
                                                                            <div class="form-group col-md-6">
                                                                                <label class="form-label text-dark">ID
                                                                                    Number <span
                                                                                        class="text-danger">*</span></label>
                                                                                <input type="text" class="form-control"
                                                                                    name="sender_id_no" id="sender_id_no">
                                                                            </div>
                                                                            <div class="form-group col-md-6">
                                                                                <label class="form-label text-dark">Phone
                                                                                    <span
                                                                                        class="text-danger">*</span></label>
                                                                                <input type="text" class="form-control"
                                                                                    name="sender_contact"
                                                                                    id="sender_contact">
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-row">
                                                                            <div class="form-group col-md-6">
                                                                                <label class="form-label text-dark">Town
                                                                                    <span
                                                                                        class="text-danger">*</span></label>
                                                                                <input type="text" class="form-control"
                                                                                    name="sender_town" id="sender_town">
                                                                            </div>
                                                                            <div class="form-group col-md-6">
                                                                                <label class="form-label text-dark">Address
                                                                                    <span
                                                                                        class="text-danger">*</span></label>
                                                                                <input type="text" class="form-control"
                                                                                    name="sender_address"
                                                                                    id="sender_address">
                                                                            </div>
                                                                        </div>
                                                                    </div>



                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- Receiver Panel -->
                                                        <div class="col-md-12">
                                                            <div class="card shadow-sm mb-4">
                                                                <div class="card-header bg-primary text-white">Receiver
                                                                    Details</div>
                                                                <!-- RECEIVER DETAILS -->
                                                                <div class="card-body">
                                                                    <div class="form-row">
                                                                        <div class="form-group col-md-12">
                                                                            <label class="form-label text-dark">Receiver
                                                                                Name <span class="text-danger">*</span>
                                                                            </label>
                                                                            <input type="text" class="form-control"
                                                                                name="receiverContactPerson" required>
                                                                            <input type="hidden" name='client_id'
                                                                                value="{{ $collection->client->id }}">
                                                                            <input type="hidden" name="requestId"
                                                                                value="{{ $collection->requestId }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-row">
                                                                        <div class="form-group col-md-6">
                                                                            <label class="form-label text-dark">ID
                                                                                Number <span class="text-danger">*</span>
                                                                            </label>
                                                                            <input type="text" class="form-control"
                                                                                name="receiverIdNo" required>
                                                                        </div>
                                                                        <div class="form-group col-md-6">
                                                                            <label class="form-label text-dark">Phone
                                                                                Number
                                                                                <span class="text-danger">*</span>
                                                                            </label>
                                                                            <input type="text" class="form-control"
                                                                                name="receiverPhone" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-row">
                                                                        <div class="form-group col-md-6">
                                                                            <label class="form-label text-dark">Address
                                                                                <span class="text-danger">*</span>
                                                                            </label>
                                                                            <input type="text" class="form-control"
                                                                                name="receiverAddress" required>
                                                                        </div>
                                                                        <div class="form-group col-md-6">
                                                                            <label class="form-label text-dark">Town
                                                                                <span class="text-danger">*</span>
                                                                            </label>
                                                                            <input type="text" class="form-control"
                                                                                name="receiverTown" required>
                                                                        </div>
                                                                    </div>


                                                                </div>
                                                            </div>
                                                        </div>


                                                        <!-- Description of Goods -->
                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <label class="form-label text-dark">Origin <span
                                                                        class="text-danger">*</span> </label>
                                                                <select name="origin" id="origin"
                                                                    class="form-control origin-dropdown" required>
                                                                    <option value="">Select</option>
                                                                    @foreach ($offices as $office)
                                                                        <option value="{{ $office->id }}">
                                                                            {{ $office->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label class="form-label text-dark">Destination <span
                                                                        class="text-danger">*</span> </label>
                                                                <select name="destination" id="destination"
                                                                    class="form-control destination-dropdown" required>
                                                                    <option value="">Select</option>
                                                                </select>
                                                            </div>
                                                            <input type="hidden" name='destination_id'
                                                                id="destination_id">
                                                        </div>

                                                        <!-- Shipment Info Table -->
                                                        {{-- <div class="section-title"><b class="text-dark">
                                                Shipment Information</b></div> --}}

                                                        <div class="table-responsive mt-3">
                                                            <table class="table table-bordered shipmentTable"
                                                                id="shipmentTable">
                                                                <thead class="thead-success">
                                                                    <tr>
                                                                        <th>Item Name</th>
                                                                        <th>Packages #</th>
                                                                        <th>Weight (kg)</th>
                                                                        <th>Length (cm)</th>
                                                                        <th>Width (cm)</th>
                                                                        <th>Height (cm)</th>
                                                                        <th>Volume (cm<sup>3</sup>)</th>
                                                                        <th>Act</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td><input type="text" class="form-control"
                                                                                name="item[]"></td>
                                                                        <td><input type="number" min="0"
                                                                                max="100" class="form-control"
                                                                                name="packages[]"></td>
                                                                        <td><input type="number" min="0"
                                                                                max="100" class="form-control"
                                                                                name="weight[]"></td>
                                                                        <td><input type="number" min="0"
                                                                                max="100" class="form-control"
                                                                                name="length[]"></td>
                                                                        <td><input type="number" min="0"
                                                                                max="100" class="form-control"
                                                                                name="width[]"></td>
                                                                        <td><input type="number" min="0"
                                                                                max="100" class="form-control"
                                                                                name="height[]"></td>
                                                                        <td class="volume-display text-muted"><input
                                                                                type="number" min="0"
                                                                                class="form-control" name="volume[]"
                                                                                readonly></td>
                                                                        <td><button type="button"
                                                                                class="btn btn-danger btn-sm remove-row"
                                                                                title="Delete Row"><i
                                                                                    class="fas fa-trash"></i></button>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>

                                                        <button type="button" class="btn btn-primary mb-3 addRowBtn"
                                                            id="addRowBtn">Add
                                                            Row</button>


                                                        <!-- Service Level -->
                                                        <div class="section-title"></div>
                                                        <div class="form-row">
                                                            {{-- <div class="form-group col-md-6">
                                                                <label class="form-label text-dark">Select Service <span
                                                                        class="text-danger">*</span> </label>
                                                                <select class="form-control" name="service" required>
                                                                    <option value="">-- Select Service --</option>
                                                                    <option value="standard">Standard</option>
                                                                    <option value="express">Express</option>
                                                                    <option value="overnight">Overnight</option>
                                                                </select>
                                                            </div> --}}
                                                            <div class="form-row">
                                                                <div class="form-group col-md-4">
                                                                    <label class="form-label text-dark">Cost <span
                                                                            class="text-danger">*</span>
                                                                    </label>
                                                                    <input type="number" min="0"
                                                                        class="form-control" name="cost" required
                                                                        readonly>
                                                                </div>
                                                                <input type="hidden" name="base_cost" value="">

                                                                <div class="form-group col-md-4">
                                                                    <label class="form-label text-dark">Tax (16%) <span
                                                                            class="text-danger">*</span>
                                                                    </label>
                                                                    <input type="number" min="0"
                                                                        class="form-control" name="vat" required
                                                                        readonly>
                                                                </div>
                                                                <div class="form-group col-md-4">
                                                                    <label class="form-label text-dark">Total Cost <span
                                                                            class="text-danger">*</span>
                                                                    </label>
                                                                    <input type="number" min="0"
                                                                        class="form-control" name="total_cost" required
                                                                        readonly>
                                                                </div>
                                                            </div>

                                                            <!-- Submit -->


                                                            {{-- <button type="submit" class="btn btn-sm btn-danger" title="Delete"
                                                                value="DELETE">YES DELETE <i class="fas fa-trash"></i> </button> --}}


                                                        </div>
                                                        <div class="modal-footer d-flex justify-content-between p-0">
                                                            <button type="submit"
                                                                class="btn btn-success text-dark">Submit
                                                                Collection</button>
                                                            <button type="button" class="btn btn-danger"
                                                                data-dismiss="modal">Cancel</button>
                                                        </div>

                                                    </form>
                                                </div>


                                            </div>
                                        </div>

                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>


            <!-- JavaScript to toggle and populate form -->

            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const clientRadio = document.getElementById('clientRadio');
                    const agentRadio = document.getElementById('agentRadio');
                    const senderForm = document.getElementById('senderForm');

                    const sender_name = document.getElementById('sender_name');
                    const sender_id_no = document.getElementById('sender_id_no');
                    const sender_contact = document.getElementById('sender_contact');
                    const sender_town = document.getElementById('sender_town');
                    const sender_address = document.getElementById('sender_address');

                    function clearForm() {
                        sender_name.value = '';
                        sender_id_no.value = '';
                        sender_contact.value = '';
                        sender_town.value = '';
                        sender_address.value = '';
                    }

                    clientRadio.addEventListener('change', () => {
                        if (clientRadio.checked) {
                            senderForm.style.display = 'block';

                            fetch('/clientData') // Adjust this URL as needed
                                .then(response => {
                                    if (!response.ok) {
                                        throw new Error('Network response was not ok');
                                    }
                                    return response.json();
                                })
                                .then(data => {
                                    // Use the first client for demo purposes
                                    if (data.length > 0) {
                                        const client = data[0];
                                        sender_name.value = client.name || '';
                                        sender_id_no.value = client.kraPin || '';
                                        sender_contact.value = client.contact || '';
                                        sender_town.value = client.city || '';
                                        sender_address.value = client.address || '';
                                    } else {
                                        alert('No clients found.');
                                    }
                                })
                                .catch(error => {
                                    console.error('Error fetching client data:', error);
                                    alert('Failed to fetch client data.');
                                });
                        }
                    });

                    agentRadio.addEventListener('change', () => {
                        if (agentRadio.checked) {
                            senderForm.style.display = 'block';
                            clearForm(); // Allow fresh entry
                        }
                    });
                });
            </script>



        </div>
    </div>
@endsection
