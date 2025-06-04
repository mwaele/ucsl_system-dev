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
            <form action="{{ route('shipment-collections.create') }}" method="POST">
                @csrf
                <div class="modal fade" id="registerParcel" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl" role="document"> 
                        <div class="modal-content">
                            <div class="modal-header bg-gradient-primary">
                                <h5 class="modal-title text-white" id="exampleModalLabel">Register Parcel</h5>
                                <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <label class="form-label text-primary">Fill in the client details.</label>

                                <div class="row mb-3">
                                    <div class="col-md-3 mb-3">
                                        <h6 for="requestId" class="text-muted text-primary">Request ID</h6>
                                        <input type="text" value="{{ $request_id }}" name="requestId"
                                            class="form-control" id="request_id" readonly>
                                    </div>
                                    <div class="col-md-3">
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
                                    <div class="col-md-3">
                                        <h6 for="collectionLocation" class="text-muted text-primary">From</h6>
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
                                        <h6 for="collectionLocation" class="text-muted text-primary">To</h6>
                                        <select name="destination"
                                            class="form-control destination-dropdown">
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                    <input type="hidden" name='destination_id' id="destination_id">
                                </div>

                                <label class="form-label text-primary">Fill in the Receiver details.</label>

                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <h6 for="receiverContactPerson" class="text-muted text-primary">Name</h6>
                                        <input type="text" id="receiverContactPerson" class="form-control" name="receiverContactPerson">
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <h6 for="receiverIdNo" class="text-muted text-primary">ID Number</h6>
                                        <input type="text" id="receiverIdNo" class="form-control" name="receiverIdNo">
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <h6 for="receiverPhone" class="text-muted text-primary">Phone Number</h6>
                                        <input type="text" id="receiverPhone" class="form-control" name="receiverPhone">
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <h6 for="receiverAddress" class="text-muted text-primary">Address</h6>
                                        <input type="text" id="receiverAddress" class="form-control" name="receiverAddress">
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <h6 for="receiverTown" class="text-muted text-primary">Town</h6>
                                        <input type="text" id="receiverTown" class="form-control" name="receiverTown">
                                    </div>
                                </div>

                                <label class="form-label text-primary">Fill in the Parcel details.</label>

                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Item No.</th>
                                            <th>Item Name</th>
                                            <th>Package No</th>
                                            <th>Weight (Kg)</th>
                                            <th>Length (cm)</th>
                                            <th>Width (cm)</th>
                                            <th>Height (cm)</th>
                                            <th>Volume (cm<sup>3</sup>)</th>
                                            <th>Remarks</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="items-table-body">
                                        <!-- First item row -->
                                        <tr>
                                            <td>1</td>
                                            <td><input type="text" name="items[0][item_name]" class="form-control" required></td>
                                            <td><input type="number" name="items[0][packages_no]" class="form-control" required></td>
                                            <td><input type="number" step="0.01" name="items[0][weight]" class="form-control" required></td>
                                            <td><input type="number" name="items[0][length]" class="form-control" onchange="calculateVolume(0)"></td>
                                            <td><input type="number" name="items[0][width]" class="form-control" onchange="calculateVolume(0)"></td>
                                            <td><input type="number" name="items[0][height]" class="form-control" onchange="calculateVolume(0)"></td>
                                            <td>
                                                <span id="volume-display-0">0</span>
                                                <input type="hidden" name="items[0][volume]" id="volume-input-0" value="0">
                                            </td>
                                            <td><input type="text" name="items[0][remarks]" class="form-control"></td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="addSubItem(0)">+ Sub Item</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="10">
                                                <div id="sub-items-wrapper-0" style="display: none;">
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
                                                        <tbody id="sub-items-0">
                                                            <!-- JS adds sub-items here -->
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="addItem()">+ Add Another Item</button><br>

                                <label class="form-label text-primary mt-4">Cost Summary</label>

                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <h6 for="itemCost" class="text-muted text-primary">Item Cost (KES)</h6>
                                        <input type="number" min="0" class="form-control" name="cost" required readonly>
                                    </div>
                                    <input type="hidden" name="base_cost" value="">
                                    <div class="col-md-3">
                                        <h6 for="vatAmount" class="text-muted text-primary">Tax (16%)</h6>
                                        <input type="number" min="0" class="form-control" name="vat" required readonly>
                                    </div>
                                    <div class="col-md-3">
                                        <h6 for="totalCost" class="text-muted text-primary">Total Cost (KES)</h6>
                                        <input type="number" min="0" class="form-control" name="total_cost" required readonly>
                                    </div>
                                </div>

                                @push('scripts')
                                <script>
                                    let itemCount = 1;

                                    function addItem() {
                                        const tbody = document.getElementById('items-table-body');

                                        const itemRow = document.createElement('tr');
                                        itemRow.innerHTML = `
                                            <td>${itemCount + 1}</td>
                                            <td><input type="text" name="items[${itemCount}][item_name]" class="form-control" required></td>
                                            <td><input type="number" name="items[${itemCount}][packages_no]" class="form-control" required></td>
                                            <td><input type="number" step="0.01" name="items[${itemCount}][weight]" class="form-control" required></td>
                                            <td><input type="number" name="items[${itemCount}][length]" class="form-control" onchange="calculateVolume(${itemCount})"></td>
                                            <td><input type="number" name="items[${itemCount}][width]" class="form-control" onchange="calculateVolume(${itemCount})"></td>
                                            <td><input type="number" name="items[${itemCount}][height]" class="form-control" onchange="calculateVolume(${itemCount})"></td>
                                            <td>
                                                <span id="volume-display-${itemCount}">0</span>
                                                <input type="hidden" name="items[${itemCount}][volume]" id="volume-input-${itemCount}" value="0">
                                            </td>
                                            <td><input type="text" name="items[${itemCount}][remarks]" class="form-control"></td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="addSubItem(${itemCount})">+ Sub Item</button>
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

                                    function calculateVolume(index) {
                                        const length = parseFloat(document.querySelector(`[name="items[${index}][length]"]`).value) || 0;
                                        const width = parseFloat(document.querySelector(`[name="items[${index}][width]"]`).value) || 0;
                                        const height = parseFloat(document.querySelector(`[name="items[${index}][height]"]`).value) || 0;
                                        const volume = length * width * height;

                                        document.getElementById(`volume-display-${index}`).innerText = volume;
                                        document.getElementById(`volume-input-${index}`).value = volume;
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
