<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Invoice</title>
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


        .head {
            margin: 0 !important;
            padding: 0 !important;
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

        .bg-secondary {
            background-color: #f3e0d6;
        }

        .text-danger {
            color: red;
            margin: 0 !important;
            padding: 0 !important;
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

        .text-right {
            text-align: right !important;
        }

        /* .footer {
            position: fixed;
            bottom: -20px;
            left: 0;
            right: 0;
            height: 60px;
            font-size: 16px;
            text-align: center;
            border-top: 3px solid #f57f3f;
            padding: 15px;
            color: #000;
        } */

        .footer {
            position: fixed;
            bottom: 20px;
            padding: 15px;
            left: 0;
            right: 0;
            height: 60px;
            border-top: 3px solid #f57f3f;
            text-align: center;
            font-size: 16px;
        }

        .footer .page:after {
            content: "Page " counter(page) " of " counter(pages);
        }

        /* Fix for counter(pages) being 0 */
        .only-page .footer .page-number:after {
            content: "Page 1 of 1";
        }

        /* To prevent content from being covered by the footer */
        .content {
            padding-bottom: 100px;
        }
    </style>
</head>

<body class="content  {{ $onlyOnePage ? 'only-page' : '' }}">
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

    <table style="width:100%;">
        <tr>
            <!-- Empty left spacer -->
            <td style="width:33%;"></td>

            <!-- Centered INVOICE -->
            <td style="width:34%; text-align:center;">
                <h1 class="text-primary" style="margin:0;">
                    <strong>INVOICE</strong>
                </h1>
            </td>

            <!-- Right aligned REF -->
            <td style="width:33%; text-align:right; white-space:nowrap;">
                <h2 class="text-primary" style="margin:0;">
                    <strong>Ref: {{ $invoice->invoice_no ?? '' }}</strong>
                </h2>
            </td>
        </tr>
    </table>





    <section class="mb-3">
        <table width="100%" class="bg-secondary" cellpadding="1" cellspacing="0"
            style=" font-family: sans-serif; margin-top:10px; margin-bottom:20px; border: 1px solid rgb(238, 237, 237);  padding:3px">
            <thead>
                <th style="border: none; padding-left:5px" width="35%">Bill To</th>
                <th style="border: none;" width="30%"></th>
                <th style="border: none;" width="35%"></th>
            </thead>
            <tbody>
                <tr>
                    <td style="padding-left:5px; !important">{{ $invoice->name }} <br>
                        {{ $invoice->address }} <br>
                        {{-- {{ $invoice->city }} <br> --}}
                        {{ 'PIN: ' . $invoice->kraPin }}

                    </td>
                    <td class="text-center" style="padding-right:5px!important;">
                        <p style="padding:3px!important; margin:0 !important"> <strong>Sender:
                            </strong>{{ $invoice->sender_name }}
                        </p>
                        <p style="padding:3px!important; margin:0 !important"> <strong>Receiver: </strong>
                            {{ $invoice->receiver_name }}</p>

                    </td>
                    <td class="text-right" style="padding-right:5px!important;">
                        <p style="padding:3px!important; margin:0 !important"> <strong>Due Date: </strong>
                            {{ \Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y h:i A') }}
                        </p>
                        <p style="padding:3px!important; margin:0 !important"> <strong>Generated on :</strong>
                            {{ now()->format('d/m/Y') . ' at ' . now()->format('h:i A') }}
                        </p>


                    </td>
                    {{-- <td>{{ $invoice-> }} </td>
                    <td>{{ $invoice-> }}
                    </td> --}}
                </tr>
            </tbody>

        </table>
        <table width="100%" cellpadding="1" cellspacing="0"
            style=" font-family: sans-serif; margin-top:10px; margin-bottom:10px" class="bordered-table">
            <tr>
                <th style="text-align: left">Waybill Number</th>
                <th style="text-align: left">Request ID</th>
                <th style="text-align: left">Status</th>
                <th style="text-align: left">Payment Due in</th>
            </tr>
            <tr>
                <td>{{ $invoice->waybill_no }}</td>
                <td>{{ $invoice->requestId }}</td>
                <td>{{ $invoice->invoice_status }}</td>
                <td>
                    @php
                        $dueDate = \Carbon\Carbon::parse($invoice->due_date);
                        $remainingDays = now()->floatDiffInDays($dueDate, false); // returns decimal days
                        $remainingDaysRounded = round($remainingDays); // round to nearest whole day
                    @endphp
                    @if ($remainingDaysRounded > 0)
                        ({{ $remainingDaysRounded }} days )
                    @elseif ($remainingDaysRounded === 0)
                        (Due today)
                    @else
                        (Overdue by {{ abs($remainingDaysRounded) }} days)
                    @endif
                </td>
            </tr>
        </table>

        <!-- Main Table -->
        <table width="100%" cellpadding="1" cellspacing="0"
            style=" font-family: sans-serif; margin-top:10px; margin-bottom:10px" class="bordered-table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Item Details</th>
                    <th class="text-center" style="text-align: center !important">Quantity</th>
                    <th class="text-right">Weight (Kgs) </th>
                    <th class="text-right">Amount (KES.)</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $subTotal = $invoice->actual_cost + ($invoice->last_mile_delivery_charges ?? 0);
                    $vat = $invoice->actual_vat;
                    $grandTotal = $subTotal + $vat;
                @endphp
                {{-- @foreach ($shipmentItems as $item) --}}
                <tr>
                    <td> {{ '1' }}. </td>
                    <td>Shipment from {{ $invoice->routeFrom }} </td>
                    <td class="text-center" style="text-align: center"> {{ $shipmentItems->count() }} </td>
                    <td class="text-right"> {{ $totalWeight }} </td>
                    <td class="text-right">{{ number_format($subTotal, 2) }} </td>
                </tr>
                {{-- @endforeach --}}

                @php
                    $itemCount = $shipmentItems->count();
                @endphp

                @if (!empty($invoice->last_mile_delivery_charges) && $invoice->last_mile_delivery_charges > 0)
                    <tr>
                        <td> {{ $itemCount + 1 }}. </td>
                        <td colspan="3" class="text-left">Last Mile Delivery Charges</td>
                        <td class="text-right">{{ number_format($invoice->last_mile_delivery_charges, 2) }}</td>
                    </tr>
                @endif



                <tr style="margin-top:20px">
                    <td colspan="4" class="text-right"><strong>SUB TOTAL</strong></td>
                    <td class="text-right">{{ number_format($subTotal, 2) }}</td>
                </tr>
                <tr>
                    <td colspan="4" class="text-right"><strong>VAT</strong></td>
                    <td class="text-right">{{ number_format($vat, 2) }}</td>
                </tr>
                <tr>
                    <td colspan="4" class="text-right"><strong>TOTAL</strong></td>
                    <td class="text-right">{{ number_format($grandTotal, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Authorization Table -->
        <table style="width: 100%; border-collapse: collapse; ">

            <tr>
                <td style="">
                    <p>Prepared By: {{ Auth::user()->name }} </p>

                </td>
                <td style="">
                    <p>Checked By:________________ </p>
                    < </td>
                <td style="">
                    <p>Approved By:_______________ </p>
                    < </td>
            </tr>
            <tr>
                <td colspan="1" style=" padding:2px">
                    <p>Date: {{ now()->format('jS F, Y') }}</p>


                </td>
                <td style=" padding:2px">
                    <p>Date:__________________ </p>
                </td>
                <td style=" padding:2px">
                    <p>Date:__________________ </p>
                </td>
            </tr>
        </table>

        {{-- @for ($i = 1; $i <= 100; $i++)
            <p>Test line {{ $i }}</p>
        @endfor --}}

        {{-- <div class="footer text-warning">

        </div> --}}
        <div class="footer">
            <p class="text-primary" style="padding-top:0; margin-top:0">
                Ufanisi Courier Services Limited - Fast | Reliable | Secure
            </p>
            <span class="page-number"></span>
        </div>

</body>

</html>

@if (isset($pdf))
    @php
        $pdf->page_script('
            if ($PAGE_COUNT > 0) {
                $font = $fontMetrics->getFont("Helvetica", "normal");
                $size = 10;
                $text = "Page " . $PAGE_NUM . " of " . $PAGE_COUNT;
                $pdf->text(500, 820, $text, $font, $size);
            }
        ');
    @endphp
@endif
