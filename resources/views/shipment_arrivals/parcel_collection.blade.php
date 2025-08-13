{{-- resources/views/shipment_arrivals/parcel_collection.blade.php --}}
@extends('layouts.custom')

@section('content')
    <div class="card">

        <div class="card-header py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Parcel Collection</h5>

                <div class="d-flex gap-2 ms-auto">
                    <a href="{{ url('/parcel-collection-report') }}"
                        class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm mr-2">
                        <i class="fas fa-download fa text-white"></i> Generate Report
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table text-primary table-bordered table-striped table-hover" id="dataTable" width="100%"
                    cellspacing="0" style="font-size: 14px;">
                    <thead>
                        <tr class="text-success">
                            <th>#</th>
                            <th>Request ID</th>
                            <th>Waybill No.</th>
                            <th>Verified By</th>
                            <th>Total Cost</th>
                            <th>Status</th>
                            <th>Paid</th>
                            <th>Vehicle Reg No</th>
                            {{-- <th>Transporter</th> --}}
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($shipmentArrivals as $index => $arrival)
                            <tr>
                                <td>{{ $loop->iteration }}.</td>
                                <td>{{ $arrival->requestId }}</td>
                                <td>{{ $arrival->shipmentCollection->waybill_no }}</td>
                                <td>{{ $arrival->verifiedBy->name ?? null }}</td>
                                <td>Ksh. {{ number_format($arrival->total_cost) }}</td>
                                <td>
                                    <span
                                        class="badge badge-{{ strtolower($arrival->status) === 'delivered' ? 'success' : 'warning' }}">
                                        {{ ucfirst($arrival->status) }}
                                    </span>
                                </td>
                                <td>{{ $arrival->payment?->amount }}</td>
                                <td>{{ $arrival->transporter_truck->reg_no ?? '' }}</td>
                                {{-- <td>{{ $arrival->transporter->name ?? '' }}</td> --}}
                                <td>
                                    <!-- Issue Button -->
                                    @if ($arrival->status === 'Verified')
                                        <button class="btn btn-sm btn-primary" title="Issue Parcel" data-toggle="modal"
                                            data-target="#issueParcel-{{ $arrival->id }}">
                                            Issue <i class="fas fa-box-open"></i> <i class="fas fa-arrow-right"></i>
                                        </button>
                                        @if ($arrival->delivery_rider_status != 'Allocated')
                                            <button class="btn btn-sm btn-warning" title="Delivery" data-toggle="modal"
                                                data-target="#allocateRider-{{ $arrival->id }}">
                                                Allocate Rider <i class="fas fa-box-open"></i> <i
                                                    class="fas fa-arrow-right"></i>
                                            </button>
                                        @endif
                                        @if ($arrival->delivery_rider_status == 'Allocated')
                                            <button class="btn btn-info">Rider
                                                {{ $arrival->delivery_rider_status }}</button>
                                        @endif
                                    @endif

                                    <div class="modal fade" id="issueParcel-{{ $arrival->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="issueParcelLabel-{{ $arrival->id }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary text-white">
                                                    <h5 class="modal-title">
                                                        Issue Parcel – {{ $arrival->parcelDetails }} (Request ID:
                                                        {{ $arrival->requestId }})
                                                    </h5>
                                                    <button type="button" class="close text-white"
                                                        data-dismiss="modal">&times;</button>
                                                </div>

                                                <div class="modal-body">
                                                    {{-- Issue Form --}}
                                                    <form method="POST"
                                                        action="{{ route('arrivals.issue', $arrival->id) }}">
                                                        @csrf

                                                        @php
                                                            $hasPayment = $arrival->payment !== null;
                                                            $balance = $hasPayment ? $arrival->payment->balance : null;
                                                        @endphp

                                                        {{-- Payment Section (Only if unpaid or has balance) --}}
                                                        @if (!$hasPayment || $balance > 0)
                                                            <div class="mb-3">
                                                                @if (!$hasPayment)
                                                                    <span class="badge bg-danger text-white">
                                                                        Unpaid – To pay Ksh.
                                                                        {{ number_format($arrival->shipmentCollection->total_cost, 0) }}
                                                                    </span>
                                                                @else
                                                                    <span class="badge bg-info text-white">
                                                                        Paid:
                                                                        {{ $arrival->shipmentCollection->payment_mode }}
                                                                    </span>
                                                                    <span class="badge bg-primary text-white">
                                                                        Ksh.
                                                                        {{ number_format($arrival->payment->amount, 0) }}
                                                                    </span>
                                                                    <span class="badge bg-warning text-white">
                                                                        Balance: Ksh. {{ number_format($balance, 0) }}
                                                                    </span>
                                                                @endif
                                                            </div>

                                                            {{-- Record Payment --}}
                                                            <div class="card shadow-sm border-primary mb-3">
                                                                <div class="card-header bg-primary text-white">
                                                                    Record Payment
                                                                </div>
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        {{-- Payment Mode --}}
                                                                        <div class="col-md-4">
                                                                            <label for="payment_mode" class="text-primary">
                                                                                <h6>Payment Mode</h6>
                                                                            </label>
                                                                            <select id="payment_mode" name="payment_mode"
                                                                                class="form-control" required>
                                                                                <option value="" selected>-- Select --
                                                                                </option>
                                                                                <option value="M-Pesa">M-Pesa</option>
                                                                                <option value="Cash">Cash</option>
                                                                                <option value="Cheque">Cheque</option>
                                                                                <option value="Invoice">Invoice</option>
                                                                            </select>
                                                                        </div>

                                                                        {{-- Reference --}}
                                                                        <div class="col-md-4">
                                                                            <label for="reference" class="text-primary">
                                                                                <h6>Reference</h6>
                                                                            </label>
                                                                            <input type="text" id="reference"
                                                                                name="reference"
                                                                                class="form-control text-uppercase"
                                                                                placeholder="e.g. TH647CDTNA" maxlength="10"
                                                                                pattern="[A-Z0-9]{10}"
                                                                                title="Enter a 10-character M-Pesa code in capital letters with no spaces or special characters"
                                                                                oninput="this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '').slice(0,10)"
                                                                                required>
                                                                        </div>

                                                                        {{-- Amount --}}
                                                                        <div class="col-md-4">
                                                                            <label for="amount_paid" class="text-primary">
                                                                                <h6>Amount</h6>
                                                                            </label>
                                                                            <input type="number" id="amount_paid"
                                                                                name="amount_paid" class="form-control"
                                                                                placeholder="Enter amount paid" required>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif

                                                        {{-- Issue Type Selection --}}
                                                        <div class="form-group">
                                                            <label class="text-primary">Select Issue Type:</label><br>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio"
                                                                    name="issue_type"
                                                                    id="issue_receiver_{{ $arrival->id }}"
                                                                    value="receiver">
                                                                <label class="form-check-label"
                                                                    for="issue_receiver_{{ $arrival->id }}">Receiver</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio"
                                                                    name="issue_type"
                                                                    id="issue_agent_{{ $arrival->id }}" value="agent">
                                                                <label class="form-check-label"
                                                                    for="issue_agent_{{ $arrival->id }}">Agent</label>
                                                            </div>
                                                        </div>

                                                        {{-- Receiver Panel --}}
                                                        <div class="col-md-12"
                                                            id="issue_receiver_panel_{{ $arrival->id }}"
                                                            style="display:none;">
                                                            <div class="card shadow-sm mb-3">
                                                                <div class="card-header bg-info text-white">Receiver
                                                                    Details</div>
                                                                <div class="card-body">
                                                                    <div class="form-row">
                                                                        <div class="form-group col-md-6">
                                                                            <label>Receiver Name<span
                                                                                    class="text-danger">*</span></label>
                                                                            <input type="text" class="form-control"
                                                                                name="receiver_name">
                                                                        </div>
                                                                        <div class="form-group col-md-6">
                                                                            <label>Phone<span
                                                                                    class="text-danger">*</span></label>
                                                                            <input type="text" class="form-control"
                                                                                name="receiver_phone">
                                                                        </div>
                                                                        <div class="form-group col-md-6">
                                                                            <label>ID Number<span
                                                                                    class="text-danger">*</span></label>
                                                                            <input type="text" class="form-control"
                                                                                name="receiver_id_no" maxlength="8">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        {{-- Agent Panel --}}
                                                        <div class="col-md-12" id="issue_agent_panel_{{ $arrival->id }}"
                                                            style="display:none;">
                                                            <div class="card shadow-sm mb-3">
                                                                <div class="card-header bg-info text-white">Agent Details
                                                                </div>
                                                                <div class="card-body">
                                                                    <div class="form-row">
                                                                        <div class="form-group col-md-6">
                                                                            <label>Agent Name</label>
                                                                            <input type="text" class="form-control"
                                                                                name="agent_name">
                                                                        </div>
                                                                        <div class="form-group col-md-6">
                                                                            <label>Agent Phone</label>
                                                                            <input type="text" class="form-control"
                                                                                name="agent_phone">
                                                                        </div>
                                                                        <div class="form-group col-md-6">
                                                                            <label>Agent ID Number</label>
                                                                            <input type="text" class="form-control"
                                                                                name="agent_id_no" maxlength="8">
                                                                        </div>
                                                                        <div class="form-group col-md-6">
                                                                            <label>Remarks<span
                                                                                    class="text-danger">*</span></label>
                                                                            <input type="text" class="form-control"
                                                                                name="remarks">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        {{-- Hidden Fields --}}
                                                        <input type="hidden" name="requestId"
                                                            value="{{ $arrival->requestId }}">
                                                        <input type="hidden" name="client_id"
                                                            value="{{ $arrival->shipmentCollection->client_id }}">

                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-primary">
                                                                Submit
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <script>
                                            document.addEventListener('DOMContentLoaded', function() {
                                                const receiverRadio = document.getElementById('issue_receiver_{{ $arrival->id }}');
                                                const agentRadio = document.getElementById('issue_agent_{{ $arrival->id }}');
                                                const receiverPanel = document.getElementById('issue_receiver_panel_{{ $arrival->id }}');
                                                const agentPanel = document.getElementById('issue_agent_panel_{{ $arrival->id }}');

                                                function togglePanels() {
                                                    receiverPanel.style.display = receiverRadio.checked ? 'block' : 'none';
                                                    agentPanel.style.display = agentRadio.checked ? 'block' : 'none';
                                                }

                                                receiverRadio.addEventListener('change', togglePanels);
                                                agentRadio.addEventListener('change', togglePanels);
                                            });
                                        </script>
                                    </div>

                                    {{-- Allocate Rider Modal --}}

                                    <div class="modal fade" id="allocateRider-{{ $arrival->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="allocateRiderLabel-{{ $arrival->id }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-xl" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-warning text-white">
                                                    <h5 class="modal-title">
                                                        Allocate Rider to deliver {{ $arrival->parcelDetails }} (Request
                                                        ID:
                                                        {{ $arrival->requestId }})
                                                    </h5>
                                                    <button type="button" class="close text-white"
                                                        data-dismiss="modal">&times;</button>
                                                </div>

                                                <div class="modal-body">
                                                    {{-- Issue Form --}}
                                                    <form method="POST" id="allocateRider"
                                                        action="{{ route('shipment-arrivals.update', $arrival->id) }}">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="row">
                                                            {{-- Payment Mode --}}
                                                            <div class="col-md-12">
                                                                <label for="rider_delivery" class="text-primary">
                                                                    <h6>Select Delivery Rider</h6>
                                                                </label>
                                                                <select id="delivery_rider" name="delivery_rider"
                                                                    class="form-control" required>
                                                                    <option value="" selected>-- Select
                                                                        --
                                                                    </option>
                                                                    @foreach ($riders as $rider)
                                                                        <option value="{{ $rider->id }}">
                                                                            {{ $rider->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>




                                                        {{-- Hidden Fields --}}
                                                        {{-- <input type="hidden" name="requestId"
                                                            value="{{ $arrival->requestId }}">
                                                        <input type="hidden" name="client_id"
                                                            value="{{ $arrival->shipmentCollection->client_id }}"> --}}

                                                        <div
                                                            class="modal-footer d-flex justify-content-between mt-2 shadow-sm">
                                                            <button type="button" class="btn btn-danger"
                                                                data-dismiss="modal">Cancel X</button>
                                                            <button type="submit" class="btn btn-primary">
                                                                Submit
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <script>
                                            document.addEventListener('DOMContentLoaded', function() {
                                                const receiverRadio = document.getElementById('issue_receiver_{{ $arrival->id }}');
                                                const agentRadio = document.getElementById('issue_agent_{{ $arrival->id }}');
                                                const receiverPanel = document.getElementById('issue_receiver_panel_{{ $arrival->id }}');
                                                const agentPanel = document.getElementById('issue_agent_panel_{{ $arrival->id }}');

                                                function togglePanels() {
                                                    receiverPanel.style.display = receiverRadio.checked ? 'block' : 'none';
                                                    agentPanel.style.display = agentRadio.checked ? 'block' : 'none';
                                                }

                                                receiverRadio.addEventListener('change', togglePanels);
                                                agentRadio.addEventListener('change', togglePanels);
                                            });
                                        </script>
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
