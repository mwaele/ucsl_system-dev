<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dispatch Processing</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            background-color: #fff;
            font-size: 12px;
            margin: 20px;
        }

        h4 {
            color: #14489f;
            font-weight: bold;
            margin-bottom: 5px;
        }

        h2 {
            color: #14489f;
        }

        .timeline-with-icons {
            border-left: 2px solid #f3f2f2;
            list-style: none;
            padding-left: 20px;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 5px;
            padding-left: 20px;
        }

        .timeline-icon {
            background-color: #f57f3f;
            color: #fff;
            border-radius: 50%;
            height: 24px;
            width: 24px;
            text-align: center;
            line-height: 24px;
            position: absolute;
            left: -33px;
            top: 0;
            font-size: 14px;
            font-weight: bold;
        }

        thead th {
            text-align: left !important;
        }


        .text-muted {
            color: #000;
        }

        .fw-bold {
            font-weight: bold;
            margin: 0;
            padding: 0;
        }

        .format {
            margin: 0 !important;
            padding: 0 !important;
        }

        .mb-4 {
            margin-bottom: 0.5rem;
        }

        .h2 {
            font-size: 14px;
        }

        .text-primary {
            color: #14489f;
        }

        .mb-2 {
            margin-bottom: 0.5rem;
        }

        .mt-2 {
            margin-top: 0.5rem;
        }

        .py-2 {
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
        }

        .header,
        .head {
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .row {
            display: block;
            clear: both;
        }

        .col-md-6 {
            display: inline-block;
            width: 49%;
            vertical-align: top;
            margin-right: 1%;
        }

        .p-3 {
            padding: 16px;
            border: 1px solid #ccc;
            /* instead of box-shadow */
        }

        /* Margins and text styles */
        .mb-1 {
            margin-bottom: 4px;
        }

        .text-success {
            color: #f57f3f;
        }

        .text-danger {
            color: red;
        }

        .text-dark {
            color: #212529;
        }

        .p {
            color: #14489f;
            margin-bottom: 2px;
            font-weight: bold;
        }

        .td {
            padding-left: 10;
            padding-bottom: 10;
            margin: 0;
            width: 50%;
            border: 1px solid #ccc;
            vertical-align: top;
        }

        .td-new {
            width: 100%;
            padding-left: 10;
            padding-bottom: 10;
        }

        .pp {
            margin-bottom: 0;
        }

        .signature-row {
            white-space: nowrap;
        }

        .signature-text,
        .signature-img {
            display: inline-block;
            vertical-align: middle;
            margin: 0;
        }

        .signature-img {
            height: 15px;
            max-width: 100%;
        }

        .top {
            margin-bottom: 7px;
        }

        .bordered-table {
            border: 1px solid #000;
            border-collapse: collapse;
        }

        .bordered-table th,
        .bordered-table td {
            border: 1px solid #a8a5a5;
            padding: 4px;
            /* optional spacing inside cells */
        }

        .text {
            font-size: 12px;
            color: #000;
            margin: 0;
            font-family: Arial;
        }

        .border {
            border-bottom: 1px solid #000 !important;
        }
    </style>
</head>

<body>
    <table width="100%" style="border-collapse: collapse;">
        <tr>
            <td style="width: 50%;">
                <img src="{{ public_path('images/UCSLogo1.png') }}" height="100" style="width: auto;" alt="Logo">
            </td>

            <td style="width: 50%; text-align: right; vertical-align: middle;">
                <div class="top">
                    <p class="text">
                        Pokomo Road, Industrial Area</strong>
                    </p>
                    <p class="text">
                        P.O. Box 43357 - 00100, Nairobi
                    </p>
                    <p class="text">
                        Tel: +254 756 504 560 0202592118 </p>
                </div>
                <div class="bottom">
                    <p class="text">
                        Jomo Kenyatta Avenue - Sparki Area</strong>
                    </p>
                    <p style="font-size: 12px;  #000; margin: 0;">
                        P.O. Box 980 - 80100 Mombasa
                    </p>
                    <p class="text">
                        Tel: +254 751 505 560 +254 104 100 101 </p>
                </div>

            </td>
        </tr>
    </table>
    <div class="head">
        <h2 style="text-align: center">Loading Manifest <strong>From</strong> {{ $loading_sheet->office->name }}
            <strong>To </strong>
            {{ optional($loading_sheet->office)->name === optional($destination)->destination
                ? 'Various'
                : optional($destination)->destination ?? 'N/A' }}

        </h2>
        <h1 class="text-danger mb-3 " style="text-align: right">
            <strong>No. {{ str_pad($loading_sheet->batch_no, 4, '0', STR_PAD_LEFT) }}
            </strong>
        </h1>
    </div>
    <section class="mb-3">
        <table width="100%" cellpadding="1" cellspacing="0"
            style=" font-family: sans-serif; margin-top:10px; margin-bottom:10px" class="bordered-table">
            <thead>
                <tr>

                    <th>OFFICE OF ORIGIN</th>
                    <th>DESTINATION</th>
                    <th>DISPATCH DATE</th>
                    <th>ARRIVAL DATE</th>
                </tr>
            </thead>
            <tbody>
                <tr>

                    <td>{{ $loading_sheet->office->name }} </td>
                    <td>{{ $destination->destination }}
                    </td>
                    <td>{{ $loading_sheet->dispatch_date ?? 'Pending Dispatch' }} </td>
                    <td>{{ $loading_sheet->received_date ?? 'Pending Arrival' }} </td>
                </tr>
            </tbody>

        </table>
        <table width="100%" style=" font-family: sans-serif margin-top:10px; margin-bottom:10px"
            class="bordered-table">
            <thead>
                <tr>
                    <th>WAYBILL NO.</th>
                    <th>CLIENT</th>
                    <th>DESCRIPTION</th>
                    <th>DESTINATION</th>
                    <th>QTY</th>
                    <th>WEIGHT</th>
                    <th>AMOUNT</th>
                    <th>ACCOUNT</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $item)
                    <tr>
                        <td>{{ $item->waybill_no }}</td>
                        <td>{{ $item->client_name }}</td>
                        <td>{{ $item->item_names }}</td>
                        <td>{{ $item->destination ?? '' }}</td>
                        <td style="text-align:right">{{ $item->total_quantity }}</td>
                        <td style="text-align:right">{{ $item->total_weight }}</td>
                        <td style="text-align:right"> {{ number_format($item->total_cost, 2) }}</td>
                        <td>
                            @if ($item->payment_mode === 'Invoice')
                                {{ 'Account' }}
                            @elseif ($item->payment_mode === 'MPESA')
                                {{ 'Cash' }}
                            @else
                                {{ $item->payment_mode }}
                            @endif

                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="4" style="text-align: right;font-weight:bold;">TOTAL</td>
                    <td style="text-align:right">{{ $totals->total_quantity_sum }}</td>
                    <td style="text-align:right">{{ $totals->total_weight_sum }}</td>
                    <td style="text-align:right">{{ number_format($totals->total_cost_sum, 2) }}</td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </section>

    <section class="">

        <table width="100%" style=" font-family: sans-serif;">

            <tr width="100%" style="padding-bottom:0; margin-top:0;margin-bottom:0; padding-bottom:0">
                <td style="padding-bottom:0; margin-top:0;margin-bottom:0; padding-bottom:0" class="border"
                    colspan="2">
                    <h3 class="text-primary" style="padding-bottom:0; margin-top:0;margin-bottom:0; padding-bottom:3">
                        <strong>DISPATCHER DETAILS</strong>
                    </h3>

                </td>

            </tr>

            <td width="59%">

                <p>NAME: {{ $loading_sheet->dispatcher->name }}</p>

                <p>ID NUMBER: {{ $loading_sheet->dispatcher->id_no }}</p>
            </td>
            <td width="41%">

                <p>PHONE NUMBER: {{ $loading_sheet->dispatcher->phone_no }}</p>

                <div class="signature-row">
                    <p class="signature-text">SIGNATURE:</p>
                    @php
                        $signaturePath = public_path('storage/' . $loading_sheet->dispatcher->signature);
                    @endphp

                    @if (file_exists($signaturePath) && is_file($signaturePath))
                        <img class="signature-img" src="{{ $signaturePath }}">
                    @endif
                </div>


            </td>
        </table>
        <table width="100%" style=" font-family: sans-serif;">
            <tr width="100%">
                <td class="border" colspan="2">
                    <h3 class="text-primary"
                        style="padding-bottom:0; margin-top:0;margin-bottom:0; padding-bottom:3px; ">
                        <strong>TRANSPORTER DETAILS</strong>
                    </h3>


                </td>
            </tr>


            <td width="59%">

                <p>NAME: {{ $loading_sheet->transporter->name }}</p>

                <p>DRIVER PHONE NUMBER: {{ $loading_sheet->transporter_truck->driver_contact }}</p>
                <div class="signature-row">
                    <p class="signature-text">SIGNATURE:</p>
                    @php
                        $signaturePath2 = public_path('storage/' . $loading_sheet->transporter->signature);
                    @endphp

                    @if (file_exists($signaturePath2) && is_file($signaturePath2))
                        <img class="signature-img" src="{{ $signaturePath2 }}">
                    @endif
                </div>
            </td>


            <td width="41%">
                <p>VEHICLE REG. NO: {{ $loading_sheet->transporter_truck->reg_no }}</p>
                <p>DRIVER NAME: {{ $loading_sheet->transporter_truck->driver_name }}</p>
                <p>DRIVER ID NO: {{ $loading_sheet->transporter_truck->driver_id_no }}</p>


            </td>
        </table>
        <table width="100%" style=" font-family: sans-serif;">
            <tr width="100%"
                style="padding-bottom:0 !important; margin-top:0 !important;margin-bottom:0 !important; padding-bottom:0 !important">
                <td class="border" colspan="2">
                    <h3 class="text-primary"
                        style="padding-bottom:0 !important; margin-top:0 !important;margin-bottom:0 !important; padding-bottom:3px !important">
                        <strong>RECEIVER/AGENT DETAILS</strong>
                    </h3>
                </td>
            </tr>
            <td width="65%"
                style="padding-bottom:0 !important; margin-top:0 !important;margin-bottom:0 !important; padding-bottom:0 !important">

                <p>NAME: ______________________</p>
                <p>ID NUMBER: _________________</p>
                <p>SIGNATURE:</p>
            </td>
            <td width="45%">
                <p>PHONE NUMBER: ________________________</p>
                <p>DATE: __________________________________</p>
                <p>OFFICE: ________________________________</p>
            </td>


        </table>

    </section>
</body>

</html>
