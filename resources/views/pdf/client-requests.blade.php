<!DOCTYPE html>
<html>
<head>
    <title>Client Requests PDF</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; margin: 0; padding: 0; }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; }

        table.bordered th, table.bordered td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        th { background-color: #f2f2f2; }
        h2 { margin-bottom: 0; }

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
                    <h2>Client Requests Report</h2>
                    @if (!empty($station))
                        <p><strong>Station:</strong> {{ $station }}</p>
                    @endif
                    @if (!empty($status))
                        <p><strong>Status:</strong> {{ ucfirst($status) }}</p>
                    @endif
                    @if (!empty($timeFilter) && $timeFilter !== 'all')
                        <p><strong>Time Filter:</strong> {{ ucfirst($timeFilter) }}</p>
                    @endif
                    <p><strong>Generated:</strong> {{ \Carbon\Carbon::now()->format('F j, Y \a\t g:i A') }}</p>
                    <p><strong>Records:</strong> {{ $client_requests->count() }}</p>
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
                    <th style="width: 3%;">#</th>
                    <th style="width: 10%;">Request ID</th>
                    <th style="width: 15%;">Client</th>
                    <th style="width: 12%;">Status</th>
                    <th style="width: 15%;">Created By</th>
                    <th style="width: 20%;">Created At</th>
                    <th style="width: 25%;">Remarks</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($client_requests as $i => $request)
                    <tr>
                        <td>{{ $loop->iteration }}.</td>
                        <td>{{ $request->requestId }}</td>
                        <td>{{ $request->client->name ?? '-' }}</td>
                        <td>{{ ucfirst($request->status) }}</td>
                        <td>{{ $request->createdBy->name ?? '-' }}</td>
                        <td>{{ $request->created_at->format('F j, Y \a\t g:i A') }}</td>
                        <td>
                            
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center;">No client requests found for this filter.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>
