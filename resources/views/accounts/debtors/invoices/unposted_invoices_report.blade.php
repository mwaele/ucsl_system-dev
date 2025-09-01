<!DOCTYPE html>
<html>

<head>
    <title>Unposted Invoices Report PDF</title>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            margin-left: 50px;
            margin-right: 20px;
            padding: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table.bordered th,
        table.bordered td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        h2 {
            margin-bottom: 0;
        }

        @page {
            margin: 50px 30px;
        }

        .content {
            margin-bottom: 50px;
        }
    </style>
</head>

<body>
    <div class="content">
        <!-- Report Header -->
        <table>
            <tr>
                <td style="text-align: left;">
                    <h1><strong>Report for all Unposted Invoices </strong></h1>
                    <p><strong>Generated on:</strong> {{ \Carbon\Carbon::now()->format('F j, Y \a\t g:i A') }}</p>
                </td>
                <td style="text-align: right; vertical-align: top;">
                    <img src="{{ public_path('images/UCSLogo1.png') }}" alt="Logo" style="height: 70px;">
                </td>
            </tr>
        </table>

        <!-- Main Table -->
        <table class="bordered">
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 10%;">W/Bill Date</th>
                    <th style="width: 10%;">Client</th>
                    <th style="width: 10%;">Request ID</th>
                    <th style="width: 10%;">W/Bill No</th>
                    <th style="width: 10%;">Dispatch Batch No</th>
                    <th style="width: 10%;">INV No</th>
                    <th style="width: 15%;">Parcel Desc.</th>
                    <th style="width: 12%;">INV Amt.</th>
                    <th style="width: 12%;">VAT Amt.</th>
                    <th style="width: 12%;">Total Amt.</th>
                    <th style="width: 10%;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoices as $invoice)
                    <tr>
                        <td> {{ $loop->iteration }}. </td>
                        <td> {{ \Carbon\Carbon::parse($invoice->shipment_collection->verified_at)->format('M d, Y') ?? null }} </td>
                        <td> {{ $invoice->shipment_collection->sender_name }} </td>
                        <td> {{ $invoice->shipment_collection->requestId }} </td>
                        <td> {{ $invoice->shipment_collection->waybill_no }} </td>
                        <td>
                            @if(optional(optional($invoice->loading_sheet_waybills)->loading_sheet)->batch_no_formatted)
                                {{ $invoice->loading_sheet_waybills->loading_sheet->batch_no_formatted }}
                            @else
                                <span class="badge bg-warning text-white p-2">Pending dispatch</span>
                            @endif
                        </td>
                        <td> {{ $invoice->invoice_no }} </td>
                        <td> {!! $invoice->shipment_collection->items->pluck('item_name')->join('<br>') !!} </td>
                        <td> {{ number_format($invoice->shipment_collection->actual_cost, 2) }}</td>
                        <td> {{ number_format($invoice->shipment_collection->actual_vat, 2) }}</td>
                        <td> {{ number_format($invoice->amount, 2) }}</td>
                        <td>
                            @if(strtolower(str_replace(' ', '', $invoice->status)) === 'unpaid')
                                <span class="badge bg-info text-white p-2">Unposted</span>
                            @else
                                <span class="badge bg-success text-white p-2">Posted</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <!-- Totals row -->
                <tr class="font-weight-bold bg-light">
                    <td colspan="7"></td>
                    <td><strong>Totals:</strong></td>
                    <td><strong>Ksh {{ number_format($invoices->sum(fn($i) => $i->shipment_collection->actual_cost), 2) }}</strong></td>
                    <td><strong>Ksh {{ number_format($invoices->sum(fn($i) => $i->shipment_collection->actual_vat), 2) }}</strong></td>
                    <td><strong>Ksh {{ number_format($invoices->sum('amount'), 2) }}</strong></td>
                    <td colspan="1"></td>
                </tr>

                <!-- Original footer headers (useful if you're using DataTables) -->
                <tr>
                    <th>#</th>
                    <th>W/Bill Date</th>
                    <th>Client Name</th>
                    <th>Request ID</th>
                    <th>W/Bill No</th>
                    <th>Dispatch Batch No</th>
                    <th>INV. No</th>
                    <th>Desc.</th>
                    <th>INV Amt.</th>
                    <th>VAT</th>
                    <th>Total Amt.</th>
                    <th>Status</th>
                </tr>
            </tfoot>
        </table>
    </div>
</body>

</html>
