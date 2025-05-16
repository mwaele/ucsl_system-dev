@extends('layouts.custom')

@section('content')
    <!-- DataTables Example -->
    <div class="card mb-4">
        <div class="card-header py-3">
            <div class="d-sm-flex align-items-center justify-content-between"> 
                <h6 class="m-0 font-weight-bold text-primary">Collected Parcel Details</h6>

                <div class="d-flex align-items-center"> 
                    <!-- Counter positioned just before the search input -->
                    <span class="text-primary counter mr-2"></span>

                    <div class="input-group input-group-sm mr-2" style="width: 250px;">
                        <input type="text" id="tableSearch" class="form-control" placeholder="Search collected parcels...">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fa fa-search"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered results" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Rider</th>
                            <th>Vehicle</th>
                            <th>Date of Request</th>
                            <th>No. of Parcels</th>
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
                            <th>Rider</th>
                            <th>Vehicle</th>
                            <th>Date of Request</th>
                            <th>No. of Parcels</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($ridersWithPendingVerification as $entry)
                            <tr>
                                <td> {{ $loop->iteration }} </td>
                                <td> {{ $entry->user->name ?? '—' }} </td>
                                <td> {{ $entry->vehicle->regNo }} </td>
                                <td> {{ \Carbon\Carbon::parse($entry->dateRequested)->format('d M Y H:i') }} </td>
                                <td> {{ $entry->parcel_count }} </td>
                                <td>
                                    <p class="badge 
                                        @if ($entry->status == 'collected')
                                            bg-warning
                                        @elseif ($entry->status == 'verified')
                                            bg-primary
                                        @endif
                                        fs-5 text-white">
                                        {{ \Illuminate\Support\Str::title($entry->status) }}
                                    </p>
                                </td>
                                <td class="d-flex pl-4">
                                    <button 
                                        class="btn btn-sm btn-info mr-1" 
                                        title="Proceed to Verify Collection" 
                                        data-toggle="modal" 
                                        data-target="#verifyModal-{{ $entry->user->id }}">
                                        <i class="fas fa-clipboard-check"></i>
                                    </button>

                                    <!-- Proceed to Verify Collection Modal -->
                                    <div class="modal fade" id="verifyModal-{{ $entry->user->id }}" tabindex="-1" role="dialog" aria-labelledby="verifyModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-gradient-info">
                                                    <h5 class="modal-title text-white" id="verifyModalLabel">Verify Parcels for {{ $entry->user->name }}</h5>
                                                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <ul class="list-group">
                                                        @php
                                                            $userRequests = $requestsToVerify[$entry->user->id] ?? collect();
                                                        @endphp

                                                        @forelse ($userRequests as $request)
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                <div>
                                                                    <strong>{{ $request->requestId }}</strong> - {{ $request->client->name }}
                                                                </div>
                                                                <button 
                                                                    class="btn btn-sm btn-primary" 
                                                                    data-toggle="modal" 
                                                                    data-target="#verifyRequestModal-{{ $request->id }}" 
                                                                    onclick="$('#verifyModal-{{ $entry->user->id }}').modal('hide')"
                                                                    title="Verify Now">
                                                                    <i class="fas fa-arrow-right"></i>
                                                                </button>
                                                            </li>
                                                        @empty
                                                            <li class="list-group-item text-muted">No collected parcels to verify for this rider.</li>
                                                        @endforelse
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <button class="btn btn-sm btn-warning mr-1" title="View Collection">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        @foreach ($requestsToVerify as $userRequests)
                            @foreach ($userRequests as $request)
                                <div class="modal fade" id="verifyRequestModal-{{ $request->id }}" tabindex="-1" role="dialog" aria-labelledby="verifyRequestModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl" role="document">
                                        <form method="POST" action="">
                                            @csrf
                                            <div class="modal-content">
                                                <div class="modal-header bg-gradient-info">
                                                    <h5 class="modal-title text-white">
                                                        Verify Parcels - {{ $request->requestId }} | {{ $request->client->name }}
                                                    </h5>
                                                    <button type="button" class="close text-white" data-dismiss="modal">
                                                        <span>&times;</span>
                                                    </button>
                                                </div>

                                                <div class="modal-body">
                                                    @if ($request->shipmentCollection && $request->shipmentCollection->items->isNotEmpty())
                                                        @foreach ($request->shipmentCollection->items as $item)                                                     
                                                            <div class="border p-2 mb-3">
                                                                <h6>Item {{ $index + 1 }} - {{ $item->description ?? 'No description' }}</h6>
                                                                <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id }}">

                                                                <div class="form-row">
                                                                    <div class="col">
                                                                        <label>Item name</label>
                                                                        <input type="text" name="items[{{ $index }}][item_name]" class="form-control" value="{{ $item->item_name }}" required>
                                                                    </div>
                                                                    <div class="col">
                                                                        <label>Quantity</label>
                                                                        <input type="number" name="items[{{ $index }}][packages_no]" class="form-control" value="{{ $item->packages_no }}" required>
                                                                    </div>
                                                                    <div class="col">
                                                                        <label>Weight (kg)</label>
                                                                        <input type="number" step="0.01" name="items[{{ $index }}][weight]" class="form-control" value="{{ $item->weight }}" required>
                                                                    </div>
                                                                    <div class="col">
                                                                        <label>Length (cm)</label>
                                                                        <input type="number" name="items[{{ $index }}][length]" class="form-control" value="{{ $item->length }}" required>
                                                                    </div>
                                                                    <div class="col">
                                                                        <label>Width (cm)</label>
                                                                        <input type="number" name="items[{{ $index }}][width]" class="form-control" value="{{ $item->width }}" required>
                                                                    </div>
                                                                    <div class="col">
                                                                        <label>Height (cm)</label>
                                                                        <input type="number" name="items[{{ $index }}][height]" class="form-control" value="{{ $item->height }}" required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <div class="text-muted">No items found for this request.</div>
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-success">
                                                        <i class="fas fa-check-circle"></i> Submit Verification
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        @endforeach          
                    </tbody>
                </table>

                <script>
                document.addEventListener("DOMContentLoaded", function () {
                    const searchInput = document.getElementById("tableSearch");
                    const tableRows = document.querySelectorAll(".results tbody tr");
                    const counter = document.querySelector(".counter"); // optional
                    const noResult = document.querySelector(".no-result"); // optional

                    searchInput.addEventListener("input", function () {
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

    <div class="card">
        <div class="card-header py-3">
            <div class="d-sm-flex align-items-center justify-content-between"> 
                <h6 class="m-0 font-weight-bold text-primary">Verified Parcel Details</h6>

                <div class="d-flex align-items-center"> 
                    <!-- Counter positioned just before the search input -->
                    <span class="text-primary counter mr-2"></span>

                    <div class="input-group input-group-sm mr-2" style="width: 250px;">
                        <input type="text" id="tableSearch" class="form-control" placeholder="Search verified parcels...">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fa fa-search"></i></span>
                        </div>
                    </div>
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
                <table class="table table-bordered results" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Rider</th>
                            <th>Vehicle</th>
                            <th>Date of Request</th>
                            <th>No. of Parcels</th>
                            <th>Verified By</th>
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
                            <th>Rider</th>
                            <th>Vehicle</th>
                            <th>Date of Request</th>
                            <th>No. of Parcels</th>
                            <th>Verified By</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($groupedVerifiedParcels as $date => $entry)
                            <tr>
                                <td> {{ $loop->iteration }} </td>
                                <td> {{ $entry->user->name ?? '—' }} </td>
                                <td> {{ $entry->vehicle->regNo }} </td>
                                <td> {{ \Carbon\Carbon::parse($entry->dateRequested)->format('d M Y H:i') }} </td>
                                <td> {{ $entry->parcel_count }} </td>
                                <td> <p class="badge
                                            @if ($entry->status == 'pending collection')
                                                bg-secondary
                                            @elseif ($entry->status == 'collected')
                                                bg-warning
                                            @elseif ($entry->status == 'received')
                                                bg-primary
                                            @endif
                                            fs-5 text-white"
                                           >
                                        {{ \Illuminate\Support\Str::title($entry->status) }}
                                    </p>
                                </td>
                                <td class="d-flex pl-4">
                                    <button class="btn btn-sm btn-warning mr-1" title="View Collection">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach          
                    </tbody>
                </table>
                <script>
                document.addEventListener("DOMContentLoaded", function () {
                    const searchInput = document.getElementById("tableSearch");
                    const tableRows = document.querySelectorAll(".results tbody tr");
                    const counter = document.querySelector(".counter"); // optional
                    const noResult = document.querySelector(".no-result"); // optional

                    searchInput.addEventListener("input", function () {
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
