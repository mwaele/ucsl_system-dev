<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Payment Receipt</title>
    <style>
        /* Screen base */
        html,
        body {
            margin: 0;
            padding: 0;
            background: #fff;
            font-family: "Courier New", monospace;
            font-size: 12px;
        }

        /* Center the receipt on the page without flex */
        .page {
            width: 100%;
        }

        .receipt-container {
            width: 58mm;
            /* Kenyan ETR thermal width */
            padding: 5mm 3mm;
            /* small safe padding */
            margin: 12px auto;
            /* <-- centers the receipt */
            box-sizing: border-box;
        }

        .header {
            text-align: center;
            margin-bottom: 6px;
            margin-top: 10px;
        }

        .header h2 {
            margin: 0;
            font-size: 14px;
        }

        .line {
            border-top: 1px dashed #000;
            margin: 6px 0;
        }

        .details table {
            width: 100%;
            border-collapse: collapse;
        }

        .details th,
        .details td {
            text-align: left;
            padding: 2px 0;
            vertical-align: top;
            line-height: 1.2;
        }

        .details th {
            width: 48%;
            font-weight: normal;
        }

        .amount {
            font-size: 13px;
            font-weight: bold;
            text-align: center;
            margin-top: 6px;
        }

        .footer {
            margin-top: 8px;
            text-align: center;
            font-size: 11px;
        }

        .status {
            font-weight: bold;
        }

        /* Print rules */
        @page {
            size: 58mm auto;
            /* thermal roll width */
            margin: 0;
            /* printers add their own gaps */
        }

        @media print {

            html,
            body {
                margin: 0;
                padding: 0;
            }

            .page {
                width: 100%;
            }

            .receipt-container {
                width: 58mm;
                margin: 0 auto;
                /* keep centered on print */
                padding: 5mm 3mm;
            }
        }
    </style>
</head>

<body>
    <div class="page">
        <div class="receipt-container">
            <div style="text-align: right; vertical-align: top; marign-bottom:20px">
                <img src="{{ public_path('images/UCSLogo1.png') }}" alt="Logo" style="height: 70px;">
            </div>

            <div class="header">
                <h2>PAYMENT RECEIPT</h2>
                <p><strong>RCT-{{ str_pad($payments[0]->id, 6, '0', STR_PAD_LEFT) }}</strong></p>
            </div>

            <div class="line"></div>

            <div class="details">
                <table>
                    <tr>
                        <th>Reference</th>
                        <td>{{ $payments[0]->reference_no }}</td>
                    </tr>
                    <tr>
                        <th>Type</th>
                        <td>{{ $payments[0]->type }}</td>
                    </tr>
                    <tr>
                        <th>Date</th>
                        <td>{{ \Carbon\Carbon::parse($payments[0]->date_paid)->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Paid By</th>
                        <td>{{ $payments[0]->paidBy->name }}</td>
                    </tr>
                    <tr>
                        <th>Received By</th>
                        <td>{{ $payments[0]->user->name }}</td>
                    </tr>
                </table>
            </div>

            <div class="line"></div>
            <div class="amount">
                Amount Paid: KES {{ number_format($payments[0]->amount, 2) }}
            </div>
            <div class="line"></div>

            <div class="footer">
                <p>Thank you for your payment!</p>
                <p>Generated on {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
                <p>Served By: {{ Auth::user()->name }}</p>
            </div>
        </div>
    </div>
</body>

</html>
