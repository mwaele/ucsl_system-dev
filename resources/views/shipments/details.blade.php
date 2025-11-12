<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipment Details - {{ $track->requestId ?? 'N/A' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .timeline-with-icons {
            border-left: 3px solid #007bff;
            position: relative;
            list-style: none;
            margin-left: 20px;
            padding-left: 20px;
        }

        .timeline-with-icons .timeline-item {
            position: relative;
        }

        .timeline-with-icons .timeline-icon {
            position: absolute;
            left: -26px;
            background-color: #007bff;
            color: #fff;
            border-radius: 50%;
            padding: 6px;
            top: 2px;
        }
    </style>
    <style>
        .results tr[visible='false'],
        .no-result {
            display: none;
        }

        .results tr[visible='true'] {
            display: table-row;
        }

        .counter {
            padding: 8px;
            color: #ccc;
        }

        /* Highlight the active link background */
        .nav-item.active>.nav-link,
        .collapse-item.active {
            background-color: #f57f3f;
            ;
            /* Example: Bootstrap primary */
            color: #fff !important;
        }

        .bg-success {
            background-color: #f57f3f !important
        }

        /* Optional: icon and text inside nav-link */
        .nav-item.active i,
        .nav-item.active span {
            color: #fff !important;
        }

        label {
            font-size: 20px;
            color: black;
        }

        .table .text-primary {
            color: #14489f !important;
        }

        .text-primary {
            color: #14489f !important;
        }

        .text-success {
            color: #f57f3f !important;
        }

        sidebar-divide {
            border: 2px solid #14489f !important;
        }

        /* Highlight collapsed child item (e.g., inside dropdown) */
        .collapse-item.active {
            font-weight: bold;
            border-left: 3px solid #f57f3f;
            ;
        }

        .sized {
            font-size: 18px !important;
        }
    </style>
</head>

<body class="bg-light">

    <div class="container py-5">
        <div class="card shadow-sm p-4 bg-white">
            <h2 class="text-dark mb-3">
                Shipment Details RequestId: <strong>{{ $track->requestId ?? 'N/A' }}</strong>
                for <strong>{{ $track->client->name ?? 'N/A' }}</strong>
            </h2>

            {{-- <section class="mb-3">
                <div class="row">
                    <div class="col-md-6">
                        <div class="shadow-sm p-3 bg-white rounded">
                            <p class="text-success mb-1">
                                Origin: <span class="text-dark">{{ $track->origin_office ?? 'N/A' }}</span>
                            </p>
                            <p class="text-success mb-1">
                                Destination: <span class="text-dark">{{ $track->destination_name ?? 'N/A' }}</span>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="shadow-sm p-3 bg-white rounded">
                            <p class="text-success mb-1">
                                Sender: <span class="text-dark">{{ $track->sender_name ?? 'N/A' }}</span>
                            </p>
                            <p class="text-success mb-1">
                                Receiver: <span class="text-dark">{{ $track->receiver_name ?? 'N/A' }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </section> --}}

            <section>
                <ul class="timeline-with-icons">
                    @forelse($track->trackingInfos as $index => $info)
                        @php $isLast = $loop->last; @endphp
                        <li class="timeline-item mb-3">
                            <span class="timeline-icon">
                                <i class="fas fa-check text-white fa-sm fa-fw"></i>
                            </span>
                            <h6 class="fw-bold text-primary"><strong>{{ $info->details ?? '' }}</strong></h6>
                            <p class="text-dark mb-1 fw-bold">
                                {{ \Carbon\Carbon::parse($info->date)->format('F j, Y g:i A') }}
                            </p>
                            {{-- <p class="text-dark mb-1">{{ $info->remarks ?? '' }}</p> --}}
                            @if ($isLast)
                                <p class="text-success p-0 m-0 fst-italic">
                                    <strong>Current Status: {{ $track->current_status ?? 'N/A' }}</strong>
                                </p>
                            @endif
                        </li>
                    @empty
                        <li class="timeline-item mb-3">
                            <p class="text-danger">No tracking updates available.</p>
                        </li>
                    @endforelse
                </ul>
            </section>

            @if (!empty($track->shipment_items))
                {{-- <section class="mt-4">
                    <h6 class="fw-bold text-primary mb-2">Shipment Items</h6>
                    <table class="table table-bordered table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Description</th>
                                <th>Quantity</th>
                                <th>Weight</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($track->shipment_items as $i => $item)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $item->description ?? 'N/A' }}</td>
                                    <td>{{ $item->quantity ?? 'N/A' }}</td>
                                    <td>{{ $item->weight ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </section> --}}
            @endif
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>

</html>
