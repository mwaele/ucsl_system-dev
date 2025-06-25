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
                    @php
                        $stationName = (!empty($station) && strtolower($station) !== 'all') ? ucfirst($station) : 'All Stations';

                        $timeLabel = match($timeFilter) {
                            'daily' => 'Daily',
                            'weekly' => 'Weekly',
                            'biweekly' => 'Biweekly',
                            'monthly' => 'Monthly',
                            'yearly' => 'Yearly',
                            default => 'Comprehensive'
                        };

                        $showOfficeColumn = ($stationName === 'All Stations');
                    @endphp
                    <h2>{{ $timeLabel }} Report for {{ ucfirst($status ?? 'All') }} Client Requests in {{ $stationName }}</h2>
                    @if (!empty($timeFilter) && $timeFilter !== 'all' && !empty($reportingPeriod))
                        @php
                            [$start, $end] = $reportingPeriod;
                        @endphp

                        @if ($timeFilter === 'daily')
                            <p><strong>Reporting Period:</strong> {{ \Carbon\Carbon::parse($start)->format('F j, Y') }}</p>
                        @elseif ($timeFilter === 'yearly')
                            <p><strong>Reporting Period:</strong> Year {{ \Carbon\Carbon::parse($start)->format('Y') }}</p>
                        @else
                            <p><strong>Reporting Period:</strong> {{ \Carbon\Carbon::parse($start)->format('F j, Y') }} - {{ \Carbon\Carbon::parse($end)->format('F j, Y') }}</p>
                        @endif
                    @endif
                    <p><strong>As at:</strong> {{ \Carbon\Carbon::now()->format('F j, Y \a\t g:i A') }}</p>
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
                    <th style="width: 5%;">#</th>
                    <th style="width: 10%;">Request ID</th>
                    <th style="width: 15%;">Client</th>
                    <th style="width: 15%;">Status</th>
                    @if ($showOfficeColumn)
                        <th style="width: 15%;">Office</th>
                    @endif
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
                        @if ($showOfficeColumn)
                            <td>{{ $request->office->name ?? '-' }}</td>
                        @endif
                        <td>{{ $request->createdBy->name ?? '-' }}</td>
                        <td>{{ $request->created_at->format('F j, Y \a\t g:i A') }}</td>
                        <td></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $showOfficeColumn ? 8 : 7 }}" style="text-align: center;">
                            No client requests found for this filter.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>
