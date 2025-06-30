@extends('layouts.custom')

@section('content')
    <style>
        tr,
        th,
        td,
        {
        color: #14489f !important
        }

        .p {
            text-align: right;
            padding: 0;
            margin: 0;
        }
    </style>
    <!-- DataTales Example -->
    <div class="card">
        {{-- Success Message --}}

        <div class="card-header py-3">
            <div class="d-sm-flex align-items-center justify-content-between">
                <a href="{{ route('loading_sheets.index') }}" class="btn btn-success"><i class="fas fa-bars"></i>
                    All Loading Sheets</a>
                <h4 class="m-0 font-weight-bold text-success">Loading Sheet Details</h4>


                <a href="/generate_loading_sheet/{{ $loading_sheet->id }}"
                    class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm"><i
                        class="fas fa-download fa-sm text-white"></i> Generate Report</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-2 p-2">
                <div class="col-md-6">
                    <img src="{{ asset('images/UCSLogo1.png') }}" height="100px" width="auto" alt="UCS Logo"
                        class="img-fluid">
                </div>
                <div class="col-md-6 d-flex flex-column align-items-end justify-content-end">
                    <div class="mb-2 ">
                        <p class="p">Pokomo Road, Industrial Area</p>
                        <p class="p">P.O. Box 43357 - 00100, Nairobi</p>
                        <p class="p">Tel: +254 756 504 560 0202592118</p>
                        <p class="p">Email: enquiries@ufanisicourier.co.ke</p>
                    </div>
                    <div class="mb-2 ">
                        <p class="p">Jomo Kenyatta Avenue - Sparki Area</p>
                        <p class="p">P.O. Box 980 - 80100 Mombasa</p>
                        <p class="p">Tel: +254 751 505 560 +254 104 100 101</p>
                    </div>
                </div>
            </div>

            <h4 class="text-danger mb-3" style="text-align: right">
                <strong>No. {{ str_pad($loading_sheet->batch_no, 4, '0', STR_PAD_LEFT) }}
                </strong>
            </h4>

            <div class="table-responsive">
                <table class="table table-bordered text-primary" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>DISPATCH DATE</th>
                            <th>OFFICE OF ORIGIN</th>
                            <th>DESTINATION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $loading_sheet->dispatch_date ?? 'Pending Dispatch' }} </td>
                            <td>{{ $loading_sheet->office->name }} </td>
                            <td>{{ $destination->destination }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered text-primary" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>WAYBILL NO.</th>
                            <th>DESCRIPTION</th>
                            <th>DESTINATION</th>
                            <th>QTY</th>
                            <th>WEIGHT</th>
                            <th>AMOUNT</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $item)
                            <tr>
                                <td>{{ $item->waybill_no }}</td>
                                <td>{{ $item->item_names }} - {{ $item->client_name }}</td>
                                <td>{{ $item->destination ?? '' }}</td>
                                <td>{{ $item->total_quantity }}</td>
                                <td>{{ $item->total_weight }}</td>
                                <td>{{ number_format($item->total_cost, 2) }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="3" style="text-align: right;font-weight:bold;">TOTAL</td>
                            <td>{{ $totals->total_quantity_sum }}</td>
                            <td>{{ $totals->total_weight_sum }}</td>
                            <td>{{ number_format($totals->total_cost_sum, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <h4><strong>DISPATCHER</strong></h4>
            <div class="row mt-2 p-2">

                <div class="col-md-6">
                    <p>NAME: {{ $loading_sheet->dispatcher->name }}</p>
                </div>
                <div class="col-md-6">
                    <p>ID NUMBER: {{ $loading_sheet->dispatcher->id_no }}</p>
                </div>
                <div class="col-md-6">
                    <p>PHONE NUMBER: {{ $loading_sheet->dispatcher->phone_no }}</p>
                </div>
                <div class="col-md-6">
                    <p>SIGNATURE: <img class="img-fluid" style="height: 20px"
                            src="{{ asset('storage/' . $loading_sheet->dispatcher->signature) }}"
                            alt="Dispatcher Signature"></p>


                </div>
            </div>
            <h4><strong>TRANSPORTER</strong></h4>
            <div class="row mt-2 p-2">

                <div class="col-md-6">
                    <p>NAME: {{ $loading_sheet->transporter->name }}</p>
                </div>

                <div class="col-md-6">
                    <p>PHONE NUMBER: {{ $loading_sheet->transporter->phone_no }}</p>
                </div>
                <div class="col-md-6">
                    <p>REG. DETAILS: {{ $loading_sheet->transporter->reg_details }}</p>
                </div>
                <div class="col-md-6">
                    <p>SIGNATURE: <img class="img-fluid" style="height: 20px"
                            src="{{ asset('storage/' . $loading_sheet->transporter->signature) }}"></p>
                    </p>


                </div>
            </div>
            <h4><strong>RECEIVER/AGENT</strong></h4>
            <div class="row mt-2 p-2">

                <div class="col-md-6">
                    <p>NAME:</p>
                </div>
                <div class="col-md-6">
                    <p>ID NUMBER:</p>
                </div>
                <div class="col-md-6">
                    <p>PHONE NUMBER:</p>
                </div>
                <div class="col-md-6">
                    <P>DATE:</P>
                </div>
                <div class="col-md-6">
                    <P>SIGNATURE:</P>
                </div>
            </div>
        </div>
    </div>
@endsection
