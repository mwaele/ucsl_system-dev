@extends('layouts.custom')

@section('content')
    <div class="card">

        <div class="card-header py-3">
            <div class="d-flex justify-content-between align-items-center">
                <a href="/sameday_account_report" class="d-none d-sm-inline-block btn  btn-danger shadow-sm mr-2">
                    <i class="fas fa-download fa text-white"></i> Generate Report
                </a>
                <h4 class="mb-0 text-warning"><strong> Same Day - On-Account Parcels</strong></h4>

                <div class="d-flex gap-2 ms-auto">
                    
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createParcelModal">
                        + Create Parcel
                    </button>

                    <form action="{{ route('clientRequestSameDay.store') }}" method="POST">
                        @csrf
                        <div class="modal fade" id="createParcelModal" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document"> <!-- Added modal-lg for wider layout -->
                                <div class="modal-content">
                                    <div class="modal-header bg-gradient-primary">
                                        <h3 class="modal-title text-white" id="exampleModalLabel">Create Same Day
                                            On-account Request</h3>
                                        <button type="button" class="text-white close" data-dismiss="modal"
                                            aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form>
                                            <h6 class="text-primary">Fill in the client details.</h6>

                                            <div class="row mb-3">
                                                <div class="col-md-6">

                                                    <label for="clientId" class="form-label text-primary">Client</label>
                                                    <select class="form-control" id="clientId" name="clientId">
                                                        <option value="">Select Client</option>
                                                        @foreach ($clients as $client)
                                                            <option value="{{ $client->id }}">{{ $client->name }}
                                                                ({{ $client->accountNo }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="collectionLocation" class="form-label text-primary">Pickup
                                                        Location</label>
                                                    <select name="collectionLocation" id="collectionLocation"
                                                        class="form-control selectpicker" data-live-search="true">
                                                        <option value="">-- Select Location --</option>
                                                        @foreach ($locations as $location)
                                                            <option value="{{ $location->destination }}"
                                                                data-id="{{ $location->id }}">
                                                                {{ $location->destination }}</option>
                                                        @endforeach
                                                    </select>
                                                    <input type="hidden" name='rate_id' id="rate_id" value="">


                                                    {{-- <input type="text" class="form-control" name="collectionLocation"
                                                        id="collectionLocation" autocomplete="off">
                                                    <div id="locationSuggestions"
                                                        class="list-group bg-white position-absolute w-80"
                                                        style="background-color: white;z-index: 1000;"></div> --}}

                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-6">

                                                    <label for="clientCategories" class="form-label text-primary">Client
                                                        Categories</label>
                                                    <!-- Client's Categories -->
                                                    <select class="form-control mt-1" id="clientCategories"
                                                        name="category_id">
                                                        <option value="">Select Client Categories</option>
                                                    </select>

                                                </div>

                                                <div class="col-md-6">
                                                    <label for="subCategories" class="form-label text-primary">Service Type
                                                    </label>
                                                    <!-- Readonly input to display the name -->
                                                    <input type="text" class="form-control"
                                                        value="{{ $sub_category->sub_category_name }}" readonly>

                                                    <!-- Hidden input to store the ID -->
                                                    <input type="hidden" name="sub_category_id"
                                                        value="{{ $sub_category->id }}">
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label for="parcelDetails" class="form-label fw-medium text-primary">Parcel
                                                    Details</label>
                                                <textarea class="form-control" id="parcelDetails" name="parcelDetails" rows="3"
                                                    placeholder="Fill in the description of goods."></textarea>
                                            </div>

                                            <h6 class="text-muted text-primary"> Rider Details.</h6>
                                            <div class="row mb-2 bg-success">
                                                <div class="col-md-4">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="riderOption"
                                                            id="currentLocation" value="currentLocation">
                                                        <label class="form-check-label" for="allRiders"> Pickup
                                                            Location</label>

                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="riderOption"
                                                            id="unallocatedRiders" value="unallocated">
                                                        <label class="form-check-label" for="unallocatedRiders">Unallocated
                                                            Riders</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="riderOption"
                                                            id="allRiders" value="all">
                                                        <label class="form-check-label" for="allRiders">All Riders</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12 mb-3">
                                                    <label for="userId" class="form-label text-primary">Rider</label>
                                                    <select class="form-control" id="userId" name="userId">
                                                        <option value="">Select Rider</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row">

                                                <div class="col-md-4 mb-3">
                                                    <label for="vehicle" class="form-label text-primary">Vehicle</label>
                                                    <input type="text" id="vehicle" class="form-control"
                                                        name="vehicle_display" placeholder="Select rider to populate"
                                                        readonly>
                                                    <input type="hidden" id="vehicleId" name="vehicleId">
                                                </div>

                                                <div class="col-md-4 mb-3">
                                                    <label for="requestId" class="form-label text-primary">Request
                                                        ID</label>
                                                    <input type="text" value="{{ $request_id }}" name="requestId"
                                                        class="form-control" id="request_id" readonly>
                                                </div>
                                                <script>
                                                    const vehicleMap = {
                                                        @foreach ($vehicles as $vehicle)
                                                            "{{ $vehicle->user_id }}": {
                                                                id: "{{ $vehicle->id }}",
                                                                regNo: "{{ $vehicle->regNo ?? '-' }}",
                                                                status: "{{ $vehicle->status }}"
                                                            },
                                                        @endforeach
                                                    };

                                                    document.addEventListener('DOMContentLoaded', function() {
                                                        const userSelect = document.getElementById('userId');
                                                        const vehicleInput = document.getElementById('vehicle');
                                                        const vehicleIdInput = document.getElementById('vehicleId');

                                                        userSelect.addEventListener('change', function() {
                                                            const selectedUserId = this.value;
                                                            const vehicle = vehicleMap[selectedUserId];

                                                            if (vehicle) {
                                                                vehicleInput.value = `${vehicle.regNo ?? '-'} (${vehicle.status})`;
                                                                vehicleIdInput.value = vehicle.id;
                                                            } else {
                                                                vehicleInput.value = '';
                                                                vehicleIdInput.value = '';
                                                            }
                                                        });
                                                    });
                                                </script>

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

                                                    <script>
                                                        document.getElementById('calendar-trigger').addEventListener('click', function() {
                                                            document.getElementById('datetime').focus();
                                                        });
                                                    </script>
                                                </div>
                                            </div>
                                        </form>
                                        <hr>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="priority_level" class="form-label text-primary">Priority Level</label>
                                                <select class="form-control" name="priority_level" id="priority_level">
                                                    <option value="normal" selected>Normal</option>
                                                    <option value="high">High</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6" id="priority-deadline-group" style="display: none;">
                                                <label for="deadline_date" class="form-label text-primary">Deadline (If High Priority)</label>
                                                <input type="datetime-local" class="form-control" name="deadline_date" id="deadline_date">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer d-sm-flex align-items-center">
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
                            <th>Pick-up Location</th>
                            <th>Date Requested</th>
                            <th>Rider</th>
                            <th>Vehicle</th>
                            <th>Desc.</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($clientRequests as $request)
                            <tr>
                                <td> {{ $loop->iteration }}. </td>
                                <td> {{ $request->requestId }} </td>
                                <td> {{ $request->client->name }} </td>
                                <td> {{ $request->collectionLocation }} </td>
                                <td> {{ \Carbon\Carbon::parse($request->dateRequested)->format('F j, Y \a\t g:i A') }}
                                </td>
                                <td> {{ $request->user->name ?? '—' }} </td>
                                <td> {{ $request->vehicle->regNo ?? '—' }} </td>
                                <td> {{ $request->parcelDetails }} </td>
                                <td>
                                    <span
                                        class="badge p-2
                                    @if ($request->status == 'pending collection') bg-secondary
                                    @elseif ($request->status == 'collected')
                                        bg-warning
                                    @elseif ($request->status == 'delivered')
                                        bg-primary @endif
                                    fs-5 text-white">
                                        {{ \Illuminate\Support\Str::title($request->status) }}
                                    </span>
                                </td>
                                <td class="d-flex pl-2">
                                    @if ($request->status === 'pending collection')
                                        <button class="btn btn-sm btn-primary mr-1" title="Edit Client Request"
                                            data-toggle="modal"
                                            data-target="#editClientRequest-{{ $request->requestId }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    @endif
                                    <!-- Edit Client Request Modal -->
                                    <div class="modal fade" id="editClientRequest-{{ $request->requestId }}"
                                        tabindex="-1" role="dialog"
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
                                                            id="editClientRequestModalLabel-{{ $request->requestId }}">
                                                            Edit Client Request</h5>
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
                                                                    value="{{ \Carbon\Carbon::parse($request->dateRequested)->format('F j, Y \a\t g:iA') }}">
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
                                                                value="{{ $request->vehicle->regNo ?? '—' }}" readonly>
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

                                    @if ($request->status === 'pending collection')
                                        <button class="btn btn-sm btn-danger mr-1" title="Delete Client Request"
                                            data-toggle="modal"
                                            data-target="#deleteClientRequest-{{ $request->requestId }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endif

                                    <!-- Delete Modal-->
                                    <div class="modal fade" id="deleteClientRequest-{{ $request->requestId }}"
                                        tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                        aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <p>Are you sure you want to delete {{ $request->requestId }}?
                                                    </p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-sm btn-secondary"
                                                        data-dismiss="modal">Cancel</button>
                                                    <form
                                                        action =" {{ route('clientRequests.destroy', $request->requestId) }}"
                                                        method = "POST">
                                                        @method('DELETE')
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-danger"
                                                            title="Delete" value="DELETE">YES DELETE <i
                                                                class="fas fa-trash"></i> </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if (
                                        isset($request->shipmentCollection) &&
                                        !$request->shipmentCollection->agent_approved &&
                                        $request->shipmentCollection->agent_requested
                                    )
                                        <a 
                                            href="#" 
                                            class="btn btn-sm btn-primary mr-1" 
                                            title="Review Agent Pickup Request"
                                            data-toggle="modal"
                                            data-target="#agentRequestModal-{{ $request->requestId }}"
                                        >
                                            <i class="fas fa-user-check"></i> Review Agent Request
                                        </a>
                                    @endif

                                    <!-- Agent Request Modal -->
                                    <div class="modal fade" id="agentRequestModal-{{ $request->requestId }}" tabindex="-1" role="dialog" aria-labelledby="agentRequestModalLabel-{{ $request->requestId }}" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <form method="POST" action="{{ route('client-request.agent-approval') }}" onsubmit="return validateAgentApprovalForm({{ $request->requestId }})">
                                                @csrf
                                                <input type="hidden" name="request_id" value="{{ $request->requestId }}">
                                                <input type="hidden" id="action-{{ $request->requestId }}" name="action" value="approve">

                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Review Agent Request</h5>
                                                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <p>Are you sure you want to approve or decline the agent pickup request for this client?</p>

                                                        <label for="remarks-{{ $request->requestId }}" class="form-label">Remarks <span class="text-muted">(required only if declining)</span>:</label>
                                                        <textarea class="form-control" name="remarks" id="remarks-{{ $request->requestId }}" rows="3" placeholder="Add remarks if necessary..."></textarea>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-danger" onclick="setAgentAction({{ $request->requestId }}, 'decline')">Decline</button>

                                                        <button type="submit" class="btn btn-success d-inline" id="approve-btn-{{ $request->requestId }}" onclick="setAgentAction({{ $request->requestId }}, 'approve')">
                                                            Approve
                                                        </button>

                                                        <button type="submit" class="btn btn-warning d-none" id="confirm-decline-btn-{{ $request->requestId }}">
                                                            Confirm Decline
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    @if ($request->status === 'collected')
                                        <button class="btn btn-sm btn-primary mr-1" title="View Client Request">
                                            View <i class="fas fa-eye"></i>
                                        </button>
                                    @endif

                                    @if ($request->status === 'collected')
                                        <button class="btn btn-sm btn-info mr-1" title="Generate Waybill"
                                            data-toggle="modal" data-target="#waybillModal{{ $request->requestId }}">
                                            Waybill <i class="fas fa-file-invoice"></i> 
                                        </button>

                                        <div class="modal fade" id="waybillModal{{ $request->requestId }}"
                                            tabindex="-1" role="dialog" aria-labelledby="waybillLabel"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-xl" role="document" style="max-width: 850px;">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title text-primary">Waybill Preview</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body"
                                                        style="max-height: 80vh; overflow-y: auto; background: #f9f9f9;">
                                                        <iframe src="{{ route('waybill.preview', $request->requestId) }}"
                                                            width="100%" height="500" frameborder="0"></iframe>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Close</button>
                                                        <a href="{{ route('waybill.generate', $request->requestId) }}"
                                                            target="_blank" class="btn btn-primary">
                                                            Generate
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif


                                    @if ($request->status === 'delivered')
                                        <button class="btn btn-sm btn-warning mr-1" title="Dispatch parcel"
                                            data-toggle="modal" data-target="">
                                            <i class="fas fa-truck"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <script>
                    function setAgentAction(requestId, action) {
                        const actionField = document.getElementById(`action-${requestId}`);
                        const approveBtn = document.getElementById(`approve-btn-${requestId}`);
                        const confirmDeclineBtn = document.getElementById(`confirm-decline-btn-${requestId}`);

                        if (!actionField || !approveBtn || !confirmDeclineBtn) {
                            console.warn('Could not find required fields for request ID:', requestId);
                            return;
                        }

                        actionField.value = action;

                        if (action === 'decline') {
                            approveBtn.classList.add('d-none');
                            confirmDeclineBtn.classList.remove('d-none');
                        } else {
                            approveBtn.classList.remove('d-none');
                            confirmDeclineBtn.classList.add('d-none');
                        }
                    }

                    function validateAgentApprovalForm(requestId) {
                        const action = document.getElementById(`action-${requestId}`).value;
                        const remarks = document.getElementById(`remarks-${requestId}`).value.trim();

                        if (action === 'decline' && remarks === '') {
                            alert('Remarks are required when declining a request.');
                            return false;
                        }

                        return true;
                    }

                    document.addEventListener('DOMContentLoaded', function () {
                        // Bind all modal trigger buttons
                        const allModals = document.querySelectorAll('[id^="agentRequestModal-"]');

                        allModals.forEach(modal => {
                            modal.addEventListener('shown.bs.modal', function () {
                                // Reset modal state every time it opens
                                const id = modal.id.replace('agentRequestModal-', '');
                                setAgentAction(id, 'approve'); // Reset to default state
                            });
                        });
                    });
                </script>

                <script>
                    $(document).ready(function() {
                        $('#collectionLocation').on('change', function() {
                            // Get the selected option
                            const selectedOption = $(this).find('option:selected');

                            // Get the data-id attribute of the selected option
                            const rateId = selectedOption.data('id');

                            // Set the rate_id input value
                            $('#rate_id').val(rateId);
                        });
                    });
                </script>
            </div>
        </div>
    </div>
@endsection
