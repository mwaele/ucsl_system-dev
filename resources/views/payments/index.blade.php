@extends('layouts.custom')

@section('content')
    <!-- DataTales Example -->
    <div class="card">
        {{-- Success Message --}}

        <div class="card-header py-3">
            <div class="d-sm-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-danger">List of all Shipment Payments </h6>
                <a href="/payments_report" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm"><i
                        class="fas fa-download fa-sm text-white"></i> Generate Payment Report</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered text-primary" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Client</th>
                            <th>Request Id</th>
                            <th>Waybill No</th>
                            <th>Amount To Pay</th>
                            <th>Amount Paid</th>
                            <th>Ref. No</th>
                            <th>Balance</th>
                            <th>Date Paid</th>
                            <th>Received By</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Client</th>
                            <th>Request Id</th>
                            <th>Waybill No</th>
                            <th>Amount To Pay</th>
                            <th>Amount Paid</th>
                            <th>Ref. No</th>
                            <th>Balance</th>
                            <th>Date Paid</th>
                            <th>Received By</th>
                            <th>Action</th>

                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($payments as $payment)
                            <tr>
                                <td> {{ $payment->client->name }} </td>
                                <td> {{ $payment->shipment_collection->requestId }} </td>

                                <td> {{ $payment->shipment_collection->waybill_no }} </td>
                                <td> {{ $payment->shipment_collection->total_cost }} </td>
                                <td> {{ $payment->amount }} </td>
                                <td> {{ $payment->reference_no }} </td>
                                <td> {{ $payment->shipment_collection->total_cost - $payment->amount }} </td>
                                <td> {{ $payment->date_paid }} </td>
                                <td> {{ $payment->user->name }} </td>
                                <td class="row pl-4">
                                    <a href="{{ route('payments.show', $payment->id) }}">
                                        <button class="btn btn-sm btn-info mr-1" title="Receipt">
                                            <i class="fas fa-file"></i> Generate Receipt
                                        </button>
                                    </a>
                                    {{-- <button type="button" class="btn btn-sm btn-danger" data-toggle="modal"
                                        data-target="#delete_floor-{{ $payment->id }}"><i
                                            class="fas fa-trash"></i></button> --}}
                                    <!-- Logout Modal-->
                                    {{-- <div class="modal fade" id="delete_floor-{{ $payment->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <p>Are you sure you want to delete {{ $payment->reference_no }}.
                                                    </p>
                                                </div>
                                                <div class="modal-footer">
                                                    <form
                                                        action =" {{ route('payments.destroy', ['payment' => $payment->id]) }}"
                                                        method = "POST">
                                                        @method('DELETE')
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete"
                                                            value="DELETE">YES DELETE <i class="fas fa-trash"></i> </button>
                                                    </form>
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Cancel</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
