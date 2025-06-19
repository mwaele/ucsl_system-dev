<!DOCTYPE html>
<html>
<head>
    <title>Client Requests PDF</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; }

        /* Only apply borders to explicitly marked table */
        table.bordered th, table.bordered td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        th { background-color: #f2f2f2; }
        h2 { margin-bottom: 0; }
    </style>
</head>

<body>
    <!-- No border table for heading -->
    <table class="no-border" style="width: 100%; margin-top: 10px;">
        <tr>
            <td style="text-align: left;">
                <h2>Client Requests Report</h2>
                @if ($station)
                    <p><strong>Station:</strong> {{ $station }}</p>
                @endif
                @if ($status)
                    <p><strong>Status:</strong> {{ ucfirst($status) }}</p>
                @endif
            </td>
            <td style="text-align: right; vertical-align: top;">
                <img src="{{ public_path('images/UCSLogo1.png') }}" alt="Logo" style="height: 70;">
            </td>
        </tr>
    </table>

    <!-- Add `bordered` class explicitly -->
    <table class="bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Request ID</th>
                <th>Client</th>
                <th>Status</th>
                <th>Created By</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($client_requests as $i => $request)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $request->requestId }}</td>
                    <td>{{ $request->client->name ?? '-' }}</td>
                    <td>{{ ucfirst($request->status) }}</td>
                    <td>{{ $request->createdBy->name ?? '-' }}</td>
                    <td>{{ $request->created_at->format('F j, Y \a\t g:i A') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
