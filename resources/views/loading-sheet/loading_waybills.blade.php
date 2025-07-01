@extends('layouts.custom')

@section('content')
    <style>
        .btn-group>.multiselect {
            width: 100% !important;
        }

        .multiselect-container {
            width: 100% !important;
        }
    </style>
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
                <div class="row mb-3 bg-primary p-3">
                    <div class="col-md-3 ">
                        <label for="batch_no" class="text-white">Batch No.</label>
                        <input type="text" value="{{ str_pad($loadingSheet->batch_no, 5, '0', STR_PAD_LEFT) }}
"
                            class="form-control" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="text-white" for="reg_no">Reg No.</label>
                        <input type="text" value="{{ $loadingSheet->reg_no }}" class="form-control" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="text-white" for="origin">Origin</label>
                        <input type="text" value="{{ $loading_sheet->office->name }}" class="form-control" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="text-white" for="destination">Destination</label>
                        <input type="text"
                            value="{{ $loading_sheet->rate->destination ?? '' }} @if ($loading_sheet->destination_id == '0') {{ 'Various' }} @endif"
                            class="form-control" readonly>
                    </div>
                </div>
                <div class="row bg-success mb-3 p-3">
                    @csrf
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="text-white">Select Waybills (Select Single or Multiple) <span
                                    class="text-white">*</span></label>
                            <select name="waybill_no[]" id="categories-multiselect" class="multiselect" multiple>
                                @foreach ($shipment_collections as $shipment_collection)
                                    <option value="{{ $shipment_collection->id }}">{{ $shipment_collection->waybill_no }}
                                    </option>
                                @endforeach
                            </select>
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
                            <i class="fas fa-fan text-white"></i>
                            Process</button>
                    </div>
                </div>
            </form>
        </div>
        <br>
    </div>
    <script>
        $(document).ready(function() {
            $('#categories-multiselect').multiselect({
                includeSelectAllOption: true,
                enableFiltering: true,
                buttonWidth: '100%'
            });
            // Fetch and load new shipment items
            // $('#categories-multiselect').on('change', function() {
            //     const selectedIds = ($(this).val() || []).map(String); // Normalize to string
            //     console.log("Selected IDs:", selectedIds);

            //     $.ajax({
            //         url: '/get-shipment-items',
            //         method: 'POST',
            //         data: {
            //             _token: '{{ csrf_token() }}',
            //             ids: selectedIds
            //         },
            //         success: function(items) {
            //             $('#shipment-item-table tbody').empty();

            //             items.forEach(function(item) {
            //                 let shipmentId = String(item.shipment_id);
            //                 let row = `
        //                 <tr data-shipment-id="${shipmentId}">
        //                     <td>${item.waybill_no}</td>
        //                     <td>${item.item_name}</td>
        //                     <td>${item.packages_no}</td>
        //                     <td>${item.actual_quantity}</td>
        //                     <td>${item.actual_weight}</td>
        //                     <td><button type="button" class="btn btn-danger btn-sm remove_row">Remove</button></td>
        //                 </tr>`;
            //                 $('#shipment-item-table tbody').append(row);
            //             });
            //         }
            //     });
            // });

            // // Remove a row and auto-uncheck waybill if no items remain
            // $('#shipment-item-table').on('click', '.remove_row', function() {
            //     const row = $(this).closest('tr');
            //     const shipmentId = String(row.data('shipment-id'));
            //     row.remove();

            //     const remaining = $(`#shipment-item-table tbody tr[data-shipment-id="${shipmentId}"]`)
            //         .length;
            //     console.log(`Remaining rows for shipment ID ${shipmentId}:`, remaining);

            //     if (remaining === 0) {
            //         // Uncheck from the select dropdown
            //         const option = $(`#categories-multiselect option[value="${shipmentId}"]`);
            //         option.prop('selected', false);
            //         console.log(`Unselecting shipment ID ${shipmentId}`);
            //         $('#categories-multiselect').trigger('change');
            //     }
            // });

            // When categories are selected/unselected
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

            // When "Remove" button is clicked
            $('#shipment-item-table').on('click', '.remove_row', function() {
                const row = $(this).closest('tr');
                const shipmentId = String(row.data('shipment-id'));

                row.remove();

                const remaining = $(`#shipment-item-table tbody tr[data-shipment-id="${shipmentId}"]`)
                    .length;
                console.log(`Remaining rows for shipment ID ${shipmentId}:`, remaining);

                if (remaining === 0) {
                    // Remove the option from selection
                    const select = $('#categories-multiselect');
                    let values = (select.val() || []).map(String); // Current selected values
                    values = values.filter(id => id !== shipmentId); // Remove the current ID

                    select.val(values); // Set the new values
                    select.trigger('change'); // Trigger change to reload shipment items
                    console.log(`Unselected shipment ID ${shipmentId}`);
                }
            });

        });
    </script>
@endsection
