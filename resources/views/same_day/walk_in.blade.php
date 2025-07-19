@extends('layouts.custom')

@section('content')
    <div class="card">

        <div class="card-header py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Same Day - Walk-in Parcels</h5>

                <div class="d-flex gap-2 ms-auto">
                    <a href="/sameday_walkin_report" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm mr-2">
                        <i class="fas fa-download fa text-white"></i> Generate Report
                    </a>

                    <button type="button" class="btn btn-sm btn-primary shadow-sm" data-toggle="modal"
                        data-target="#registerParcel">
                        <i class="fas fa-plus fa-sm text-white"></i> Register parcel
                    </button>
                    <form action="{{ route('shipment-collections.create') }}" method="POST">
                        @csrf
                        <div class="modal fade" id="registerParcel" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-warning">
                                        <h5 class="modal-title text-white" id="exampleModalLabel">Register Same Day Walk-in Parcel</h5>
                                        <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="modal-header bg-primary">
                                            <h5 class="text-white">Fill in the Client details.</h5>
                                        </div>

                                        <div class="row mt-3 mb-3">
                                            <div class="col-md-2">
                                                <h6 for="requestId" class="text-muted text-dark">Request ID</h6>
                                                <input type="text" value="{{ $request_id }}" name="requestId"
                                                    class="form-control" id="request_id" readonly>
                                            </div>
                                            <input type="hidden" value="{{ $consignment_no }}" name="consignment_no">
                                            <div class="col-md-2">
                                                <h6 for="clientId" class="text-muted text-primary">Client</h6>
                                                <select class="form-control" id="clientId" name="clientId">
                                                    <option value="">Select Client</option>
                                                    @foreach ($walkInClients as $client)
                                                        <option value="{{ $client->id }}">{{ $client->name }}
                                                            ({{ $client->accountNo }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <h6 for="clientCategories" class="text-muted text-primary">Client Categories</h6>
                                                <select class="form-control" id="clientCategories" name="category_id">
                                                    <option value="">Select Client Categories</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <h6 for="subCategories" class="text-muted text-primary">Service Level</h6>
                                                <!-- Readonly input to display the name -->
                                                <input type="text" class="form-control" value="{{ $sub_category->sub_category_name }}" readonly>

                                                <!-- Hidden input to store the ID -->
                                                <input type="hidden" name="sub_category_id" value="{{ $sub_category->id }}">
                                            </div>
                                            <div class="col-md-2">
                                                <h6 for="collectionLocation" class="text-muted text-primary">From</h6>
                                                <select name="origin_id" id="origin_id" class="form-control origin-dropdown"
                                                    required>
                                                    <option value="">Select</option>
                                                    @foreach ($offices as $office)
                                                        <option value="{{ $office->id }}">
                                                            {{ $office->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <h6 for="collectionLocation" class="text-muted text-primary">To</h6>
                                                <select name="destination" class="form-control destination-dropdown">
                                                    <option value="{{ $office->id }}" data-id="{{ $office->id }}">
                                                        {{ $office->name }}</option>
                                                </select>
                                            </div>
                                            <input type="hidden" name='destination_id' id="destination_id">
                                        </div>

                                        <div class="modal-header bg-primary">
                                            <h5 class=" text-white">Fill in the Receiver details.</h5>
                                        </div>

                                        <div class="row mt-3">
                                            <div class="col-md-2 mb-3">
                                                <h6 for="receiverContactPerson" class="text-muted text-primary">Name</h6>
                                                <input type="text" id="receiverContactPerson" class="form-control"
                                                    name="receiverContactPerson">
                                            </div>

                                            <div class="col-md-2">
                                                <h6 for="receiverIdNo" class="text-muted text-primary">ID Number</h6>
                                                <input type="text" id="receiverIdNo" class="form-control" name="receiverIdNo">
                                            </div>

                                            <div class="col-md-2">
                                                <h6 for="receiverPhone" class="text-muted text-primary">Phone Number</h6>
                                                <input type="text" id="receiverPhone" class="form-control" name="receiverPhone">
                                            </div>

                                            <div class="col-md-2">
                                                <h6 for="receiverEmail" class="text-muted text-primary">Email</h6>
                                                <input type="text" id="receiverEmail" class="form-control" name="receiverEmail">
                                            </div>

                                            <div class="col-md-2">
                                                <h6 for="receiverAddress" class="text-muted text-primary">Address</h6>
                                                <input type="text" id="receiverAddress" class="form-control"
                                                    name="receiverAddress">
                                            </div>

                                            <div class="col-md-2">
                                                <h6 for="receiverTown" class="text-muted text-primary">Town</h6>
                                                <input type="text" id="receiverTown" class="form-control"
                                                    name="receiverTown">
                                            </div>
                                        </div>

                                        <div class="modal-header bg-primary">
                                            <h5 class=" text-white">Fill in the Parcel details.</h5>
                                        </div>

                                        <table class="table table-bordered" id="shipmentTable">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Item Name</th>
                                                    <th>Package No</th>
                                                    <th>Weight (Kg)</th>
                                                    <th>Length (cm)</th>
                                                    <th>Width (cm)</th>
                                                    <th>Height (cm)</th>
                                                    <th>Vol (cm<sup>3</sup>)</th>
                                                    <th>Remarks</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="items-table-body">
                                                <!-- First item row -->
                                                <tr>
                                                    <td>1</td>
                                                    <td><input type="text" name="item_name[]" class="form-control" required>
                                                    </td>
                                                    <td><input type="number" name="packages[]" class="form-control" required>
                                                    </td>
                                                    <td><input type="number" step="0.01" name="weight[]"
                                                            class="form-control" required></td>
                                                    <td><input type="number" name="length[]" class="form-control"
                                                            onchange="calculateVolume(this)"></td>
                                                    <td><input type="number" name="width[]" class="form-control"
                                                            onchange="calculateVolume(this)"></td>
                                                    <td><input type="number" name="height[]" class="form-control"
                                                            onchange="calculateVolume(this)"></td>
                                                    <td>
                                                        <input type="number" name="volume[]" class="form-control" readonly>
                                                    </td>
                                                    <td><input type="text" name="remarks[]" class="form-control"></td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                                            onclick="addSubItem(0)">+Subitem</button>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td colspan="10">
                                                        <div id="sub-items-wrapper-0" style="display: none;">
                                                            <table class="table table-sm table-bordered mt-1">
                                                                <thead class="thead-light">
                                                                    <tr>
                                                                        <th>Sub Item Name</th>
                                                                        <th>Quantity</th>
                                                                        <th>Weight (Kg)</th>
                                                                        <th>Remarks</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="sub-items-0">
                                                                    <!-- JS adds sub-items here -->
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addItem()">+ Add
                                            Another Item</button><br>

                                        <label class="form-label text-primary mt-4">Cost Summary</label>

                                        <div class="row mb-3">
                                            <div class="col-md-2">
                                                <h6 class="text-muted text-primary">Total Weight (Kg)</h6>
                                                <input type="number" min="0" class="form-control" name="total_weight"
                                                    readonly>
                                            </div>
                                            <div class="col-md-2">
                                                <h6 for="itemCost" class="text-muted text-primary">Item Cost (KES)</h6>
                                                <input type="number" min="0" class="form-control" name="cost"
                                                    required readonly>
                                            </div>
                                            <input type="hidden" name="base_cost" value="0">
                                            <div class="col-md-2">
                                                <h6 for="vatAmount" class="text-muted text-primary">Tax (16%)</h6>
                                                <input type="number" min="0" class="form-control" name="vat"
                                                    required readonly>
                                            </div>
                                            <div class="col-md-2">
                                                <h6 for="totalCost" class="text-muted text-primary">Total Cost (KES)</h6>
                                                <input type="number" min="0" class="form-control" name="total_cost"
                                                    required readonly>
                                            </div>
                                            <div class="col-md-2">
                                                <h6 for="billing_party" class="text-muted text-primary">Billing Party</h6>
                                                <select name="billing_party" class="form-control">
                                                    <option value="" selected>-- Select --</option>
                                                    <option value="Sender">Sender</option>
                                                    <option value="Receiver">Receiver</option>
                                                </select>
                                            </div>

                                            <div class="col-md-2">
                                                <h6 for="payment_mode" class="text-muted text-primary">Payment Mode</h6>
                                                <select name="payment_mode" class="form-control">
                                                    <option value="" selected>-- Select --</option>
                                                    <option value="M-Pesa">M-Pesa</option>
                                                    <option value="Cash">Cash</option>
                                                    <option value="Cheque">Cheque</option>
                                                    <option value="Invoice">Invoice</option>
                                                </select>
                                            </div>

                                            <div class=" mt-2 col-md-2">
                                                <h6 for="reference" class="text-muted text-primary">Reference</h6>
                                                <input type="text" name="reference" class="form-control" placeholder="e.g. MPESA123XYZ">
                                            </div>
                                        </div>

                                        @push('scripts')
                                            <script>
                                                document.addEventListener('input', function(e) {
                                                    if (
                                                        e.target.matches('[name="weight[]"]') ||
                                                        e.target.matches('[name="packages[]"]')
                                                    ) {
                                                        recalculateCosts();
                                                    }
                                                });

                                                let itemCount = 1;

                                                function addItem() {
                                                    const tbody = document.getElementById('items-table-body');

                                                    const itemRow = document.createElement('tr');
                                                    itemRow.innerHTML = `
                                                    <td>${itemCount + 1}</td>
                                                    <td><input type="text" name="item_name[]" class="form-control" required></td>
                                                    <td><input type="number" name="packages[]" class="form-control" required></td>
                                                    <td><input type="number" step="0.01" name="weight[]" class="form-control" required></td>
                                                    <td><input type="number" name="length[]" class="form-control" onchange="calculateVolume(this)"></td>
                                                    <td><input type="number" name="width[]" class="form-control" onchange="calculateVolume(this)"></td>
                                                    <td><input type="number" name="height[]" class="form-control" onchange="calculateVolume(this)"></td>
                                                    <td><input type="number" name="volume[]" class="form-control" readonly></td>
                                                    <td><input type="text" name="remarks[]" class="form-control"></td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addSubItem(${itemCount})">+Subitem</button>
                                                    </td>
                                                `;

                                                    const subItemRow = document.createElement('tr');
                                                    subItemRow.innerHTML = `
                                                    <td colspan="10">
                                                        <div id="sub-items-wrapper-${itemCount}" style="display: none;">
                                                            <table class="table table-sm table-bordered mt-2">
                                                                <thead class="thead-light">
                                                                    <tr>
                                                                        <th>Sub Item Name</th>
                                                                        <th>Quantity</th>
                                                                        <th>Weight (Kg)</th>
                                                                        <th>Remarks</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="sub-items-${itemCount}">
                                                                    <!-- Sub-items will go here -->
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </td>
                                                `;

                                                    tbody.appendChild(itemRow);
                                                    tbody.appendChild(subItemRow);

                                                    itemCount++;
                                                }

                                                function addSubItem(parentIndex) {
                                                    const wrapper = document.getElementById(`sub-items-wrapper-${parentIndex}`);
                                                    wrapper.style.display = 'block'; // Show the table

                                                    const container = document.getElementById(`sub-items-${parentIndex}`);
                                                    const subItemCount = container.children.length;

                                                    const row = document.createElement('tr');
                                                    row.innerHTML = `
                                                    <td><input type="text" name="items[${parentIndex}][sub_items][${subItemCount}][name]" class="form-control" required></td>
                                                    <td><input type="number" name="items[${parentIndex}][sub_items][${subItemCount}][quantity]" class="form-control" required></td>
                                                    <td><input type="number" step="0.01" name="items[${parentIndex}][sub_items][${subItemCount}][weight]" class="form-control" required></td>
                                                    <td><input type="text" name="items[${parentIndex}][sub_items][${subItemCount}][remarks]" class="form-control"></td>
                                                    <td><button type="button" class="btn btn-sm btn-danger" onclick="removeSubItem(this, ${parentIndex})">Remove</button></td>
                                                `;
                                                    container.appendChild(row);
                                                }

                                                function calculateVolume(el) {
                                                    const row = el.closest('tr');
                                                    const length = parseFloat(row.querySelector('[name="length[]"]').value) || 0;
                                                    const width = parseFloat(row.querySelector('[name="width[]"]').value) || 0;
                                                    const height = parseFloat(row.querySelector('[name="height[]"]').value) || 0;
                                                    const volume = length * width * height;
                                                    row.querySelector('[name="volume[]"]').value = volume;

                                                    // Recalculate totals
                                                    recalculateCosts();
                                                }

                                                function removeSubItem(button, parentIndex) {
                                                    const row = button.closest('tr');
                                                    row.remove();

                                                    const container = document.getElementById(`sub-items-${parentIndex}`);
                                                    const wrapper = document.getElementById(`sub-items-wrapper-${parentIndex}`);

                                                    if (container.children.length === 0) {
                                                        wrapper.style.display = 'none';
                                                    }
                                                }
                                            </script>
                                        @endpush
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
            </div>
        </div>



        <div class="card-body">
            <div class="table-responsive">
                <table class="table text-primary table-bordered table-striped table-hover" id="ucsl-table" width="100%"
                    cellspacing="0" style="font-size: 14px;">
                    <thead>
                        <tr class="text-success">
                            <th>#</th>
                            <th>Request ID</th>
                            <th>Client</th>
                            <th>Date</th>
                            <th>Origin</th>
                            <th>Destination</th>
                            <th>Service Level</th>
                            <th>Received By</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Request ID</th>
                            <th>Client</th>
                            <th>Date</th>
                            <th>Origin</th>
                            <th>Destination</th>
                            <th>Service Level</th>
                            <th>Received By</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($clientRequests as $request)
                            <tr>
                                <td> {{ $loop->iteration }}. </td>
                                <td> {{ $request->requestId }} </td>
                                <td> {{ $request->client->name }} </td>
                                <td> {{ \Carbon\Carbon::parse($request->shipmentCollection->created_at)->format('F j, Y \a\t g:i A') }}
                                </td>
                                <td> {{ $request->shipmentCollection->office->name }} </td>
                                <td> {{ $request->shipmentCollection->destination->destination }} </td> 
                                <td> {{ $request->shipmentCollection->clientRequestById->serviceLevel->sub_category_name }} </td> 
                                <td> {{ $request->shipmentCollection->collectedBy->name ?? 'user' }} </td>
                                <td>
                                    @php
                                        $status = $request->shipmentCollection->clientRequestById->status ?? null;
                                    @endphp

                                    @if ($status)
                                        <span class="badge p-2
                                            @if ($status === 'pending collection') bg-secondary
                                            @elseif ($status === 'collected') bg-warning
                                            @elseif ($status === 'verified') bg-primary
                                            @else bg-dark
                                            @endif
                                            fs-5 text-white">
                                            {{ \Illuminate\Support\Str::title($status) }}
                                        </span>
                                    @else
                                        <span class="badge p-2 bg-light text-muted fs-5">No Status</span>
                                    @endif
                                </td>
                                <td class="d-flex pl-2">
                                    <button class="btn btn-sm btn-info mr-1" data-toggle="modal"
                                        data-target="#editUserModal-{{ $request->id }}">
                                        Edit
                                    </button>
                                    <button class="btn btn-sm btn-danger mr-1" title="Delete Client Request"
                                        data-toggle="modal" data-target="#deleteUser-{{ $request->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <!-- Delete Modal-->
                                    <div class="modal fade" id="deleteUser-{{ $request->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <p>Are you sure you want to delete {{ $request->name }}?
                                                    </p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-sm btn-secondary"
                                                        data-dismiss="modal">Cancel</button>
                                                    <form action =" {{ route('user.destroy', $request->id) }}"
                                                        method = "POST">
                                                        @method('DELETE')
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete"
                                                            value="DELETE">YES DELETE <i class="fas fa-trash"></i> </button>
                                                    </form>
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
        </div>
    </div>
@endsection
