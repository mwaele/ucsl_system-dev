@extends('layouts.custom')

@section('content')
    <div class="card mb-3">
        <div class="card-header pt-4">
            <div class="d-sm-flex align-items-center justify-content-between">
                <h4 class="m-0 font-weight-bold text-success">Loading Sheet Waybills </h4>

                <a href="{{ route('loading_sheets.index') }}" class="btn btn-success"><i class="fas fa-bars"></i>
                    Loading Sheets</a>
            </div>

        </div>
        <div class="card-body">
            <form action="  {{ route('loading_sheet_waybills.store') }} " method="post">

                <div class="row">
                    @csrf
                    <div class="col-md-12">
                        <div class="form-group"><label>Select Waybills (Select single or multiple) <span
                                    class="text-danger">*</span></label>
                            <div class="form-group col-md-6">
                                <select name="waybill_no[]" class="form-control" id="categories-multiselect" multiple>
                                    @foreach ($shipment_collections as $shipment_collection)
                                        <option value="{{ $shipment_collection->id }}">
                                            {{ $shipment_collection->waybill_no }}
                                        </option>
                                    @endforeach
                                </select>

                            </div>
                            <span id="waybill_no_feedback"></span>
                        </div>
                    </div>
                </div>

                <table class="table mt-3" id="shipment-item-table">
                    <thead>
                        <tr>
                            <th>Waybill No</th>
                            <th>Item Name</th>
                            <th>Packages</th>
                            <th>Quantity</th>
                            <th>Weight</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>


                <div class="row">
                    <div class="col-md-4 pt-2">
                        <input type="hidden" name="loading_sheet_id" value="{{ $ls_id }}">

                        <label for=""></label>
                        <button type="submit" id='submit-btn' class="form-control btn btn-primary btn-sm submit">
                            <i class="fas fa-save text-white"></i>
                            Save</button>
                    </div>
                </div>
            </form>
        </div>
        <br>
    </div>
    <script>
        $(document).ready(function() {
            // Fetch and load new shipment items
            $('#categories-multiselect').on('change', function() {
                const selectedIds = ($(this).val() || []).map(String); // Normalize to string
                console.log("Selected IDs:", selectedIds);

                $.ajax({
                    url: '/get-shipment-items',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        ids: selectedIds
                    },
                    success: function(items) {
                        $('#shipment-item-table tbody').empty();

                        items.forEach(function(item) {
                            let shipmentId = String(item.shipment_id);
                            let row = `
                            <tr data-shipment-id="${shipmentId}">
                                <td>${item.waybill_no}</td>
                                <td>${item.item_name}</td>
                                <td>${item.packages_no}</td>
                                <td>${item.actual_quantity}</td>
                                <td>${item.actual_weight}</td>
                                <td><button type="button" class="btn btn-danger btn-sm remove_row">Remove</button></td>
                            </tr>`;
                            $('#shipment-item-table tbody').append(row);
                        });
                    }
                });
            });

            // Remove a row and auto-uncheck waybill if no items remain
            $('#shipment-item-table').on('click', '.remove_row', function() {
                const row = $(this).closest('tr');
                const shipmentId = String(row.data('shipment-id'));
                row.remove();

                const remaining = $(`#shipment-item-table tbody tr[data-shipment-id="${shipmentId}"]`)
                    .length;
                console.log(`Remaining rows for shipment ID ${shipmentId}:`, remaining);

                if (remaining === 0) {
                    // Uncheck from the select dropdown
                    const option = $(`#categories-multiselect option[value="${shipmentId}"]`);
                    option.prop('selected', false);
                    console.log(`Unselecting shipment ID ${shipmentId}`);
                    $('#categories-multiselect').trigger('change');
                }
            });
        });
    </script>
@endsection
