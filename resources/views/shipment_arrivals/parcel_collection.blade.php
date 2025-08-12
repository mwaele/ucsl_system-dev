{{-- resources/views/shipment_arrivals/parcel_collection.blade.php --}}
@extends('layouts.custom')

@section('content')
<div class="card">

    <div class="card-header py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Parcel Collection</h5>

            <div class="d-flex gap-2 ms-auto">
                <a href="{{ url('/parcel-collection-report') }}" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm mr-2">
                    <i class="fas fa-download fa text-white"></i> Generate Report
                </a>
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
                        <th>Waybill No.</th>
                        <th>Verified By</th>
                        <th>Total Cost</th>
                        <th>Status</th>
                        <th>Paid</th>
                        <th>Vehicle Reg No</th>
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
                                <span class="badge badge-{{ strtolower($arrival->status) === 'delivered' ? 'success' : 'warning' }}">
                                    {{ ucfirst($arrival->status) }}
                                </span>
                            </td>
                            <td>{{ $arrival->payment?->amount }}</td>
                            <td>{{ $arrival->vehicle_reg_no }}</td>
                            <td>
                               <!-- Issue Button -->
                                @if ($arrival->status === 'received')
                                    <button class="btn btn-sm btn-primary" title="Issue Parcel" data-toggle="modal"
                                        data-target="#issueParcel-{{ $arrival->id }}">
                                        Issue <i class="fas fa-box-open"></i> <i class="fas fa-arrow-right"></i>
                                    </button>
                                @endif

                                <!-- Issue Parcel Modal -->
                                <div class="modal fade" id="issueParcel-{{ $arrival->id }}" tabindex="-1" role="dialog"
                                    aria-labelledby="issueParcelLabel-{{ $arrival->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title">Issue Parcel – {{ $arrival->parcelDetails }} (Request ID: {{ $arrival->requestId }})</h5>
                                                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST" action="">
                                                    @csrf
                                                    <div class="form-group">
                                                        <label class="text-primary">Select Issue Type:</label><br>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="issue_type"
                                                                id="issue_receiver_{{ $arrival->id }}" value="receiver">
                                                            <label class="form-check-label" for="issue_receiver_{{ $arrival->id }}">Receiver</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="issue_type"
                                                                id="issue_agent_{{ $arrival->id }}" value="agent">
                                                            <label class="form-check-label" for="issue_agent_{{ $arrival->id }}">Agent</label>
                                                        </div>
                                                    </div>

                                                    <!-- Receiver Panel -->
                                                    <div class="col-md-12" id="issue_receiver_panel_{{ $arrival->id }}" style="display:none;">
                                                        <div class="card shadow-sm mb-3">
                                                            <div class="card-header bg-info text-white">Receiver Details</div>
                                                            <div class="card-body">
                                                                <div class="form-row">
                                                                    <div class="form-group col-md-6">
                                                                        <label>Receiver Name<span class="text-danger">*</span></label>
                                                                        <input type="text" class="form-control" name="receiver_name">
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <label>Phone<span class="text-danger">*</span></label>
                                                                        <input type="text" class="form-control" name="receiver_phone">
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <label>ID Number<span class="text-danger">*</span></label>
                                                                        <input type="text" class="form-control" name="receiver_id_no" maxlength="8">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Agent Panel -->
                                                    <div class="col-md-12" id="issue_agent_panel_{{ $arrival->id }}" style="display:none;">
                                                        <div class="card shadow-sm mb-3">
                                                            <div class="card-header bg-info text-white">Agent Details</div>
                                                            <div class="card-body">
                                                                <div class="form-row">
                                                                    <div class="form-group col-md-6">
                                                                        <label>Agent Name</label>
                                                                        <input type="text" class="form-control" name="agent_name">
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <label>Agent Phone</label>
                                                                        <input type="text" class="form-control" name="agent_phone">
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <label>Agent ID Number</label>
                                                                        <input type="text" class="form-control" name="agent_id_no" maxlength="8">
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <label>Remarks<span class="text-danger">*</span></label>
                                                                        <input type="text" class="form-control" name="remarks">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <input type="hidden" name="requestId" value="{{ $arrival->requestId }}">
                                                    <input type="hidden" name="client_id" value="{{ $arrival->shipmentCollection->client_id }}">

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary">Submit</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function () {
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
