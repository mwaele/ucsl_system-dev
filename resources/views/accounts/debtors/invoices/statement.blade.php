<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Client Statement</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h2, h4 { margin: 0; text-align: center; }
        .header { text-align: center; margin-bottom: 20px; }
        .client-info { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background: #f0f0f0; }
        .totals td { font-weight: bold; }
        .note { margin-top: 20px; font-size: 11px; }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <img src="{{ public_path('images/UCSLogo1.png') }}" alt="Logo" style="height: 70px;"><br>
        <h3>UFANISI COURIER SERVICES LIMITED</h3>
        <h4>CLIENT INVOICE STATEMENT</h4>
    </div>

    <!-- Client Info -->
    <div class="client-info">
        <p><strong>CLIENT NAME:</strong> {{ $client->name }}</p>
        <p><strong>CLIENT CODE:</strong> {{ $client->accountNo}}</p>
        <p><strong>PRINTED ON:</strong> {{ $printedOn }}</p>
    </div>

    <!-- Transactions Table -->
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Date</th>
                <th>Description</th>
                <th>Reference</th>
                <th>Debit</th>
                <th>Credit</th>
            </tr>
        </thead>
        <tbody>
            @php
                $debitTotal = 0;
                $creditTotal = 0;
            @endphp

            @foreach($transactions as $i => $txn)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($txn->date)->format('d-M-Y') }}</td>
                    <td>{{ $txn->details }}</td>
                    <td>{{ $txn->reference }}</td>
                    <td>{{ number_format($txn->dr, 2) }}</td>
                    <td>{{ number_format($txn->cr, 2) }}</td>
                </tr>
                @php
                    $debitTotal += $txn->dr;
                    $creditTotal += $txn->cr;
                @endphp
            @endforeach

            <!-- Totals -->
            <tr class="totals">
                <td colspan="4">TOTAL</td>
                <td>{{ number_format($debitTotal, 2) }}</td>
                <td>{{ number_format($creditTotal, 2) }}</td>
            </tr>

            <!-- Balance -->
            <tr class="totals">
                <td colspan="4">BALANCE</td>
                <td colspan="2">{{ number_format($debitTotal - $creditTotal, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Note -->
    <p class="note">
        Please settle any outstanding balances within the agreed credit terms.<br>
        For queries, contact our Accounts Department.
    </p>
</body>
</html>
