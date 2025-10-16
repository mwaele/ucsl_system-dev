<!DOCTYPE html>
<html>
<head>
    <title>Delivery Metrics PDF</title>
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
                    @if (!empty($reportingPeriod))
                        @php
                            [$start, $end] = $reportingPeriod;
                            $start = \Carbon\Carbon::parse($start);
                            $end = \Carbon\Carbon::parse($end);
                        @endphp

                        @if ($start->eq($end))
                            <p><strong>Reporting Date:</strong> {{ $start->format('F j, Y') }}</p>
                        @elseif ($timeFilter === 'yearly' && $start->isStartOfYear() && $end->isEndOfYear())
                            <p><strong>Reporting Period:</strong> Year {{ $start->format('Y') }}</p>
                        @else
                            <p><strong>Reporting Period:</strong> {{ $start->format('F j, Y') }} - {{ $end->format('F j, Y') }}</p>
                        @endif
                    @endif

                    <p><strong>As at:</strong> {{ \Carbon\Carbon::now()->format('F j, Y \a\t g:i A') }}</p>
                    <p><strong>Records:</strong> {{ $shipments->count() }}</p>
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
                    <th style="width: 15%;">Service Level</th>
                    <th style="width: 15%;">Client Type</th>
                    <th style="width: 30%;">Route</th>
                    <th style="width: 15%;">Date Requested</th>
                    <th style="width: 15%;">Rider</th>
                    <th style="width: 15%;">Vehicle</th>
                    <th style="width: 20%;">Status</th>
                    @if (isset($status) && strtolower($status) === 'delivery_failed')
                        <th style="width: 20%;">Reason</th>
                    @endif
                    <th style="width: 20%;">Remarks</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($shipments as $shipment)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $shipment->requestId }}</td>
                        <td>{{ $shipment->clientRequestById->client->name ?? 'N/A' }}</td>
                        <td>{{ $shipment->clientRequestById->serviceLevel->sub_category_name ?? 'N/A' }}</td>
                        <td>{{ \Illuminate\Support\Str::title($shipment->clientRequestById->client->type ?? '—') }}</td>
                        <td>
                            From: {{ $shipment->sender_address ?? '' }}, {{ $shipment->sender_town ?? '' }}<br>
                            To: {{ $shipment->receiver_address ?? '' }}, {{ $shipment->receiver_town ?? '' }}
                        </td>
                        <td>{{ \Carbon\Carbon::parse($shipment->created_at)->format('F j, Y') }}</td>
                        <td>{{ $shipment->collectedBy->name ?? '—' }}</td>
                        <td>{{ $shipment->clientRequestById->vehicle->regNo ?? '—' }}</td>
                        <td>
                            <span class="badge p-2 
                                @if ($shipment->status == 'arrived') bg-secondary
                                @elseif (in_array($shipment->status, ['Delivery Rider Allocated', 'delivery_rider_allocated'])) bg-warning
                                @elseif ($shipment->status == 'parcel_delivered') bg-success
                                @elseif ($shipment->status == 'delivery_failed') bg-danger
                                @else bg-dark @endif text-white">
                                {{ \Illuminate\Support\Str::title(str_replace('_', ' ', $shipment->status)) }}
                            </span>
                        </td>
                        {{-- Only show remarks if viewing delivery_failed shipments --}}
                        @if (isset($status) && strtolower($status) === 'delivery_failed')
                            <td>{{ $shipment->delivery_failure_remarks ?? '—' }}</td>
                        @endif
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
            <tfoot>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 10%;">Request ID</th>
                    <th style="width: 15%;">Client</th>
                    <th style="width: 15%;">Service Level</th>
                    <th style="width: 15%;">Client Type</th>
                    <th style="width: 30%;">Route</th>
                    <th style="width: 15%;">Date Requested</th>
                    <th style="width: 15%;">Rider</th>
                    <th style="width: 15%;">Vehicle</th>
                    <th style="width: 20%;">Status</th>
                    @if (isset($status) && strtolower($status) === 'delivery_failed')
                        <th style="width: 20%;">Reason</th>
                    @endif
                    <th style="width: 20%;">Remarks</th>
                </tr>
            </tfoot>
        </table>
    </div>
</body>
</html>
