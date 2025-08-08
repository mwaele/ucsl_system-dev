<!DOCTYPE html>
<html>

<head>
    <title>Clients Report PDF</title>
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
                    <h1><strong>Report for all shipment payments </strong></h1>
                    <p><strong>Reporting Period:</strong> {{ \Carbon\Carbon::now()->format('F j, Y \a\t g:i A') }}</p>
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
                    <th>#</th>
                    <th>Client</th>
                    <th>Request Id</th>
                    <th>Waybill No</th>
                    <th>Amount To Pay</th>
                    <th>Amount Paid</th>
                    <th>Ref. No</th>
                    <th>Balance</th>
                    <th>Date Paid</th>
                    <th>Received By</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($payments as $payment)
                    <tr>
                        <td> {{ $loop->iteration }}. </td>
                        <td> {{ $payment->client->name }} </td>
                        <td> {{ $payment->shipment_collection->requestId }} </td>

                        <td> {{ $payment->shipment_collection->waybill_no }} </td>
                        <td> {{ $payment->shipment_collection->total_cost }} </td>
                        <td> {{ $payment->amount }} </td>
                        <td> {{ $payment->reference_no }} </td>
                        <td> {{ $payment->shipment_collection->total_cost - $payment->amount }} </td>
                        <td> {{ $payment->date_paid }} </td>
                        <td> {{ $payment->user->name }} </td>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
