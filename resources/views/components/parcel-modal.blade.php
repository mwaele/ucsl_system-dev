@props([
    'modalId',
    'title',
    'actionRoute',
    'data', // either $collection or $arrival
    'requiresApproval' => false,
    'approvalStatuses' => [],
])

<div class="modal fade" id="{{ $modalId }}" tabindex="-1" role="dialog"
    aria-labelledby="{{ $modalId }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">{{ $title }} – {{ $data->parcelDetails }} (Request ID: {{ $data->requestId }})</h5>
                <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ $actionRoute }}">
                    @csrf

                    <!-- Delivery / Issue Type -->
                    <div class="form-group">
                        <label class="text-primary">Select Type:</label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio"
                                name="type" id="select_receiver_{{ $modalId }}"
                                value="receiver">
                            <label class="form-check-label"
                                for="select_receiver_{{ $modalId }}">Receiver</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio"
                                name="type" id="select_agent_{{ $modalId }}"
                                value="agent">
                            <label class="form-check-label"
                                for="select_agent_{{ $modalId }}">Agent</label>
                        </div>
                    </div>

                    @php
                        $isApproved = $requiresApproval
                            ? ($approvalStatuses[$data->requestId] ?? false)
                            : true;
                    @endphp

                    <!-- Receiver Panel -->
                    <div class="col-md-12" id="receiver_panel_{{ $modalId }}" style="display: none;">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-info text-white">Receiver Details</div>
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Receiver Name<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="receiver_name"
                                            value="{{ $data->shipmentCollection->receiver_name ?? '' }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Phone<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="receiver_phone"
                                            value="{{ $data->shipmentCollection->receiver_phone ?? '' }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>ID Number<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="receiver_id_no" maxlength="8"
                                            value="{{ $data->shipmentCollection->receiver_id_no ?? '' }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Agent Panel -->
                    <div class="col-md-12" id="agent_panel_{{ $modalId }}" style="display: none;">
                        <div class="card shadow-sm mb-4">
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

                    @if ($requiresApproval && !$isApproved)
                        <div class="alert alert-warning">
                            Agent collection requires front-office approval.
                        </div>
                    @endif

                    <input type="hidden" name="client_id" value="{{ $data->client->id }}">
                    <input type="hidden" name="requestId" value="{{ $data->requestId }}">

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const receiverRadio = document.getElementById('select_receiver_{{ $modalId }}');
    const agentRadio = document.getElementById('select_agent_{{ $modalId }}');
    const receiverPanel = document.getElementById('receiver_panel_{{ $modalId }}');
    const agentPanel = document.getElementById('agent_panel_{{ $modalId }}');

    function togglePanels() {
        receiverPanel.style.display = receiverRadio.checked ? 'block' : 'none';
        agentPanel.style.display = agentRadio.checked ? 'block' : 'none';
    }

    receiverRadio.addEventListener('change', togglePanels);
    agentRadio.addEventListener('change', togglePanels);
});
</script>
