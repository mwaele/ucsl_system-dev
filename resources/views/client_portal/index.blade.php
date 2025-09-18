@extends('layouts.client_portal_custom')
<style>
    .chart-area,
    .chart-pie {
        height: 400px;
        /* same height for both */
    }

    canvas {
        max-height: 100% !important;
    }
</style>

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-primary">Client Portal Dashboard</h1>
    </div>
@endsection
