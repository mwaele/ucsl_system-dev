@extends('layouts.custom')

@section('content')
    <!-- DataTales Example -->
    <div class="card">
        <div class="card-header py-3">
            <div class="d-sm-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-danger">Parcel collections</h6>

                <div class="d-flex align-items-center">
                    <!-- Counter positioned just before the search input -->
                    <span class="text-primary counter mr-2"></span>

                    <div class="input-group input-group-sm mr-2" style="width: 250px;">
                        <input type="text" id="tableSearch" class="form-control" placeholder="Search client requests...">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fa fa-search"></i></span>
                        </div>
                    </div>

                    <button type="button" class="btn btn-sm btn-primary shadow-sm" data-toggle="modal"
                        data-target="#createClientRequest">
                        <i class="fas fa-plus fa-sm text-white"></i> Create Client Request
                    </button>
                </div>
            </div>

            <!-- Modal -->
            <form action="{{ route('clientRequests.store') }}" method="POST">
                @csrf
                <div class="modal fade" id="createClientRequest" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document"> <!-- Added modal-lg for wider layout -->
                        <div class="modal-content">
                            <div class="modal-header bg-gradient-primary">
                                <h5 class="modal-title text-white" id="exampleModalLabel">Create Client Request</h5>
                                <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
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
                <table class="table table-bordered text-primary results" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Ref</th>
                            <th>Parcel Details</th>
                            <th>Qty</th>
                            <th>Wght</th>
                            <th>Volume</th>
                            <th>Sender</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        <tr class="table-warning no-result text-center" style="display: none;">
                            <td colspan="10">
                                <i class="fa fa-warning text-danger"></i> No result found.
                            </td>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>RequestID</th>
                            <th>Parcel Details</th>
                            <th>Qty</th>
                            <th>Wght</th>
                            <th>Volume</th>
                            <th>Sender</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($collections as $collection)
                            <tr>
                                <td> {{ $loop->iteration }} </td>
                                <td> {{ \Carbon\Carbon::parse($collection->request->dateRequested)->format('Y-m-d') }} </td>
                                <td> {{ \Carbon\Carbon::parse($collection->request->dateRequested)->format('H:i') }} </td>
                                <td> {{ $collection->requestId }} </td>
                                <td> {{ $collection->item->item_name }} </td>
                                <td> {{ $collection->item->packages_no }} </td>
                                <td> {{ $collection->item->weight }} </td>
                                <td> {{ $collection->item->Volume }} </td>
                                <td> {{ $collection->request->clientId->name }} </td>
                                <td> {{ $collection->request->collectionLocation }} </td>
                                <td>
                                    <p
                                        class="badge
                                            @if ($request->status == 'pending collection') bg-secondary
                                            @elseif ($request->status == 'collected')
                                                bg-warning
                                            @elseif ($request->status == 'received')
                                                bg-primary @endif
                                            fs-5 text-white">
                                        {{ \Illuminate\Support\Str::title($collection->shipmentCollection->status) }}
                                    </p>
                                </td>
                                <td class="d-flex pl-4">
                                    <button class="btn btn-sm btn-info mr-1" title="Edit Client Request" data-toggle="modal"
                                        data-target="#editClientRequest-{{ $request->requestId }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <!-- Edit Client Request Modal -->
                                    <div class="modal fade" id="editClientRequest-{{ $request->requestId }}" tabindex="-1"
                                        role="dialog"
                                        aria-labelledby="editClientRequestModalLabel-{{ $request->requestId }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <form action="{{ route('clientRequests.update', $request->requestId) }}"
                                                method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-content">
                                                    <div class="modal-header bg-gradient-primary">
                                                        <h5 class="modal-title text-white"
                                                            id="editClientRequestModalLabel-{{ $request->requestId }}">Edit
                                                            Client Request</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <!-- Form Fields -->
                                                        <div class="form-group">
                                                            <label class="text-primary">Request ID</label>
                                                            <input type="text" name="requestId" class="form-control"
                                                                value="{{ $request->requestId }}" readonly>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="text-primary">Client</label>
                                                            <select name="clientId" class="form-control">
                                                                @foreach ($clients as $client)
                                                                    <option value="{{ $client->id }}"
                                                                        {{ $client->id == $request->clientId ? 'selected' : '' }}>
                                                                        {{ $client->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="text-primary">Pick-up Location</label>
                                                            <input type="text" name="collectionLocation"
                                                                class="form-control"
                                                                value="{{ $request->collectionLocation }}">
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="text-primary">Date Requested</label>
                                                            <div class="input-group">
                                                                <input type="datetime-local" name="dateRequested"
                                                                    id="datetime" class="form-control"
                                                                    value="{{ \Carbon\Carbon::parse($request->dateRequested)->format('Y-m-d\TH:i') }}">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text"
                                                                        style="cursor: pointer;"
                                                                        onclick="document.getElementById('datetime').focus()">
                                                                        <i class="fa fa-calendar"></i>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="text-primary">Rider</label>
                                                            <select name="userId" class="form-control rider-select"
                                                                data-modal="{{ $request->requestId }}">
                                                                <option value="">Select Rider</option>
                                                                @foreach ($drivers as $driver)
                                                                    @php
                                                                        $assignedVehicle = $vehicles->firstWhere(
                                                                            'user_id',
                                                                            $driver->id,
                                                                        );
                                                                    @endphp
                                                                    <option value="{{ $driver->id }}"
                                                                        data-vehicle="{{ $assignedVehicle ? $assignedVehicle->regNo : '' }}"
                                                                        {{ $driver->id == $request->userId ? 'selected' : '' }}>
                                                                        {{ $driver->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="text-primary">Vehicle</label>
                                                            <input type="text" class="form-control vehicle-input"
                                                                name="vehicleDisplay"
                                                                value="{{ $request->vehicle->regNo }}" readonly>
                                                            <input type="hidden" name="vehicleId"
                                                                class="vehicle-id-hidden"
                                                                value="{{ $request->vehicleId }}">
                                                        </div>

                                                        <script>
                                                            document.addEventListener('DOMContentLoaded', function() {
                                                                const selects = document.querySelectorAll('.rider-select');

                                                                selects.forEach(select => {
                                                                    select.addEventListener('change', function() {
                                                                        const modalId = this.dataset.modal;
                                                                        const selectedOption = this.options[this.selectedIndex];
                                                                        const vehicleRegNo = selectedOption.dataset.vehicle || '';

                                                                        const modal = document.getElementById('editClientRequest-' + modalId);
                                                                        const vehicleInput = modal.querySelector('.vehicle-input');
                                                                        const hiddenVehicleId = modal.querySelector('.vehicle-id-hidden');

                                                                        vehicleInput.value = vehicleRegNo;

                                                                        const allVehicles = @json($vehicles);

                                                                        allVehicles.forEach(vehicle => {
                                                                            if (vehicle.regNo === vehicleRegNo) {
                                                                                hiddenVehicleId.value = vehicle.id;
                                                                            }
                                                                        });
                                                                    });
                                                                });
                                                            });
                                                        </script>

                                                        <div class="form-group">
                                                            <label class="text-primary">Description of Goods</label>
                                                            <textarea name="parcelDetails" class="form-control">{{ $request->parcelDetails }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary">Update</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <button class="btn btn-sm btn-warning mr-1" title="View Client Request">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    @if ($request->status === 'collected')
                                        <button class="btn btn-sm btn-primary mr-1" title="Verify Collected Parcel"
                                            data-toggle="modal"
                                            data-target="#verifyCollectedParcel-{{ $request->requestId }}">
                                            <i class="fas fa-clipboard-check"></i>
                                        </button>
                                    @endif
                                    <!-- Verify Collected Parcel Modal -->
                                    <div class="modal fade" id="verifyCollectedParcel-{{ $request->requestId }}"
                                        tabindex="-1" role="dialog"
                                        aria-labelledby="verifyCollectedParcelModalLabel-{{ $request->requestId }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <form action="{{ route('clientRequests.update', $request->requestId) }}"
                                                method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-content">
                                                    <div class="modal-header bg-gradient-primary">
                                                        <h5 class="modal-title text-white"
                                                            id="verifyCollectedParcelModalLabel-{{ $request->requestId }}">
                                                            Verify Details of Collected Parcel </h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <!-- Form Fields -->
                                                        <div class="row">
                                                            <div class="form-group col-md-3">
                                                                <label class="text-primary">Request ID</label>
                                                                <input type="text" name="requestId"
                                                                    class="form-control"
                                                                    value="{{ $request->requestId }}" readonly>
                                                            </div>

                                                            <div class="form-group col-md-3">
                                                                <label class="text-primary">Rider</label>
                                                                <input type="text" name="userId" class="form-control"
                                                                    value="{{ $driver->name }}" readonly>
                                                            </div>

                                                            <div class="form-group col-md-3">
                                                                <label class="text-primary">Vehicle</label>
                                                                <input type="text" class="form-control vehicle-input"
                                                                    name="vehicleDisplay"
                                                                    value="{{ $request->vehicle->regNo }}" readonly>
                                                            </div>

                                                            <div class="form-group col-md-3">
                                                                <label class="text-primary">Date Requested</label>
                                                                <input type="datetime-local" name="dateRequested"
                                                                    id="datetime" class="form-control"
                                                                    value="{{ \Carbon\Carbon::parse($request->dateRequested)->format('Y-m-d\TH:i') }}"
                                                                    readonly>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="text-primary">Client</label>
                                                            <select name="clientId" class="form-control">
                                                                @foreach ($clients as $client)
                                                                    <option value="{{ $client->id }}"
                                                                        {{ $client->id == $request->clientId ? 'selected' : '' }}>
                                                                        {{ $client->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="text-primary">Pick-up Location</label>
                                                            <input type="text" name="collectionLocation"
                                                                class="form-control"
                                                                value="{{ $request->collectionLocation }}">
                                                        </div>
                                                        <script>
                                                            document.addEventListener('DOMContentLoaded', function() {
                                                                const selects = document.querySelectorAll('.rider-select');

                                                                selects.forEach(select => {
                                                                    select.addEventListener('change', function() {
                                                                        const modalId = this.dataset.modal;
                                                                        const selectedOption = this.options[this.selectedIndex];
                                                                        const vehicleRegNo = selectedOption.dataset.vehicle || '';

                                                                        const modal = document.getElementById('editClientRequest-' + modalId);
                                                                        const vehicleInput = modal.querySelector('.vehicle-input');
                                                                        const hiddenVehicleId = modal.querySelector('.vehicle-id-hidden');

                                                                        vehicleInput.value = vehicleRegNo;

                                                                        const allVehicles = @json($vehicles);

                                                                        allVehicles.forEach(vehicle => {
                                                                            if (vehicle.regNo === vehicleRegNo) {
                                                                                hiddenVehicleId.value = vehicle.id;
                                                                            }
                                                                        });
                                                                    });
                                                                });
                                                            });
                                                        </script>

                                                        <div class="form-group">
                                                            <label class="text-primary">Description of Goods</label>
                                                            <textarea name="parcelDetails" class="form-control">{{ $request->parcelDetails }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary">Update</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>



                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        const searchInput = document.getElementById("tableSearch");
                        const tableRows = document.querySelectorAll(".results tbody tr");
                        const counter = document.querySelector(".counter"); // optional
                        const noResult = document.querySelector(".no-result"); // optional

                        searchInput.addEventListener("input", function() {
                            const searchTerm = searchInput.value.toLowerCase().trim();
                            let matchCount = 0;

                            tableRows.forEach(row => {
                                const rowText = row.textContent.toLowerCase().replace(/\s+/g, " ");
                                const terms = searchTerm.split(" ");
                                const matched = terms.every(term => rowText.includes(term));

                                if (matched) {
                                    row.style.display = "";
                                    row.setAttribute("visible", "true");
                                    matchCount++;
                                } else {
                                    row.style.display = "none";
                                    row.setAttribute("visible", "false");
                                }
                            });

                            if (counter) {
                                counter.textContent = matchCount + " item" + (matchCount !== 1 ? "s" : "");
                            }

                            if (noResult) {
                                noResult.style.display = matchCount === 0 ? "block" : "none";
                            }
                        });
                    });
                </script>
            </div>
        </div>
    </div>
@endsection
