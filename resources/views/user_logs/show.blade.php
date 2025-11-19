@extends('layouts.custom') {{-- or your main layout --}}

@section('title', 'User Log Details')
<style>
    .timeline {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .timeline-item {
        transition: transform 0.2s;
    }

    .timeline-item:hover {
        transform: translateX(5px);
    }

    .timeline-content {
        border-radius: 12px;
    }
</style>

@section('content')
    @php
        function getCardColor($index)
        {
            $colors = [
                '#4e73df', // Blue
                '#1cc88a', // Green
                '#36b9cc', // Teal
                '#f6c23e', // Yellow
                '#e74a3b', // Red
                '#858796', // Gray
            ];
            return $colors[$index % count($colors)];
        }
    @endphp

    <div class=" mt-5">
        <div class="card shadow-sm">
            <div class="card-header  text-primary">
                <h4 class="mb-0">{{ $logs->first()->name ?? 'User Logs' }} Activity timeline</h4>
            </div>
            <div class="card-body">
                @if ($logs->isEmpty())
                    <p class="text-muted">No logs found for this user.</p>
                @else
                    <div class="timeline">
                        @foreach ($logs as $index => $log)
                            <div class="timeline-item"
                                style="margin-left: {{ $index % 2 == 1 ? '30px' : '0px' }};
                                                           background-color: {{ getCardColor($index) }};">
                                <div class="timeline-content text-white p-3 shadow-sm rounded">
                                    <span
                                        class="fw-bold">{{ \Carbon\Carbon::parse($log->created_at)->format('M d, Y H:i') }}</span>
                                    <p class="mb-1">{{ $log->actions }}</p>
                                    <a href="{{ $log->url }}" target="_blank"
                                        class="text-white text-decoration-underline">
                                        {{ $log->url }}
                                    </a>
                                    {{-- <small class="text-light d-block mt-1">ID: {{ $log->id }}</small> --}}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <a href="{{ route('user_logs.index') }}" class="btn btn-success mt-4">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>


@endsection
