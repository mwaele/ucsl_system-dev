@extends('layouts.custom')

@section('content')
    <div class="card">

        <div class="card-header py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Overnight - On-account parcels</h5>
                <!-- Time Filter & Date Range Filter -->
                {{-- <form method="GET" action="{{ route('dashboard') }}" class="mb-4">
                    <div class="form-row align-items-end">
                        <div class="col-auto">
                            <label for="time" class="font-weight-bold">Quick Filter:</label>
                            <select name="time" id="time" class="form-control text-primary"
                                onchange="this.form.submit()">
                                <option value="all" {{ $timeFilter == 'all' ? 'selected' : '' }}>All</option>
                                <option value="daily" {{ $timeFilter == 'daily' ? 'selected' : '' }}>Today</option>
                                <option value="weekly" {{ $timeFilter == 'weekly' ? 'selected' : '' }}>This Week</option>
                                <option value="biweekly" {{ $timeFilter == 'biweekly' ? 'selected' : '' }}>Last 14 Days
                                </option>
                                <option value="monthly" {{ $timeFilter == 'monthly' ? 'selected' : '' }}>This Month</option>
                                <option value="yearly" {{ $timeFilter == 'yearly' ? 'selected' : '' }}>This Year</option>
                            </select>
                        </div>
                        <div class="col-auto">
                            <label for="start_date" class="font-weight-bold">Start Date:</label>
                            <input type="date" name="start_date" id="start_date" class="form-control text-primary"
                                value="{{ request('start_date') }}">
                        </div>
                        <div class="col-auto">
                            <label for="end_date" class="font-weight-bold">End Date:</label>
                            <input type="date" name="end_date" id="end_date" class="form-control text-primary"
                                value="{{ request('end_date') }}">
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary">Apply</button>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('dashboard') }}" class="btn btn-warning">Clear</a>
                        </div>
                    </div>
                </form> --}}


                {{-- <!-- Content Row -->
                @php
                    $queryParams = ['time' => $timeFilter];

                    if (request('start_date') && request('end_date')) {
                        $queryParams['start_date'] = request('start_date');
                        $queryParams['end_date'] = request('end_date');
                    }
                @endphp --}}

                <div class="d-flex gap-2 ms-auto">
                    <a href="/overnight_account_report" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm mr-2">
                        <i class="fas fa-download fa text-white"></i> Generate Report
                    </a>

                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createParcelModal">
                        + Create Parcel
                    </button>
                    <form action="{{ route('clientRequests.store') }}" method="POST">
                        @csrf
                        <div class="modal fade" id="createParcelModal" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document"> <!-- Added modal-lg for wider layout -->
                                <div class="modal-content">
                                    <div class="modal-header bg-gradient-primary">
                                        <h5 class="modal-title text-white" id="exampleModalLabel">Create Overnight
                                            On-account Request</h5>
                                        <button type="button" class="text-white close" data-dismiss="modal"
                                            aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form>
                                            <h6 class="text-muted text-primary">Fill in the client details.</h6>

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
                                                    <input type="text" class="form-control" name="collectionLocation"
                                                        id="collectionLocation" autocomplete="off">
                                                    <div id="locationSuggestions"
                                                        class="list-group bg-white position-absolute w-80"
                                                        style="background-color: white;z-index: 1000;"></div>

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
                                                <label for="subCategories" class="form-label text-primary">Service Level </label>
                                                <!-- Readonly input to display the name -->
                                                <input type="text" class="form-control" value="{{ $sub_category->sub_category_name }}" readonly>

                                                <!-- Hidden input to store the ID -->
                                                <input type="hidden" name="sub_category_id" value="{{ $sub_category->id }}">
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
                                                                regNo: "{{ $vehicle->regNo }}",
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
                                                                vehicleInput.value = `${vehicle.regNo} (${vehicle.status})`;
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
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Close</button>
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
                                    @elseif ($request->status == 'verified')
                                        bg-primary @endif
                                    fs-5 text-white">
                                        {{ \Illuminate\Support\Str::title($request->status) }}
                                    </span>
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
                                                        <button type="submit" class="btn btn-sm btn-danger"
                                                            title="Delete" value="DELETE">YES DELETE <i
                                                                class="fas fa-trash"></i> </button>
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
