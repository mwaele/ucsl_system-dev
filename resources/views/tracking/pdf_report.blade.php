<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Tracking PDF</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            background-color: #f4f6f9;
            font-size: 12px;
            margin: 20px;
        }

        h4 {
            color: #0d6efd;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .timeline-with-icons {
            border-left: 2px solid #ccc;
            list-style: none;
            padding-left: 20px;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 10px;
            padding-left: 20px;
        }

        .timeline-icon {
            background-color: #d1e7dd;
            color: #0f5132;
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


        .text-muted {
            color: #6c757d;
        }

        .fw-bold {
            font-weight: bold;
        }

        .mb-4 {
            margin-bottom: 1.5rem;
        }

        .mb-2 {
            margin-bottom: 0.5rem;
        }

        .py-2 {
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
        }
    </style>
</head>

<body>
    <h2>
        &#128640; Tracking Results For <strong>{{ $trackingData['requestId'] }}</strong>
        Client: <strong>{{ $trackingData['client']['name'] ?? 'N/A' }}</strong>
    </h2>

    <section class="py-2">
        <ul class="timeline-with-icons">
            @foreach ($trackingData['tracking_infos'] as $info)
                <li class="timeline-item">
                    <span class="timeline-icon">&#10003;</span>
                    <h5 class="fw-bold">{{ $info['details'] }}</h5>
                    <p class="text-muted mb-2 fw-bold">{{ \Carbon\Carbon::parse($info['date'])->format('d M Y, H:i') }}
                    </p>
                    @if (!empty($info['remarks']))
                        <p class="text-muted">{{ $info['remarks'] }}</p>
                    @endif
                </li>
            @endforeach
        </ul>

    </section>
</body>

</html>
