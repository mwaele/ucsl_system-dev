@extends('layouts.custom')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="text-primary mb-0">Delivery Metrics</h4>
        <a href="{{ route('dashboard') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <div class="row">

        <!-- Undelivered Parcels -->
        <div class="col-xl-2 col-md-6 mb-4">
            <div class="card bg-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-xs font-weight-bold text-white text-uppercase mb-1">Undelivered</div>
                            <div class="h5 mb-0 font-weight-bold text-white">{{ $undeliveredParcels }}</div>
                        </div>
                        <i class="fas fa-truck-loading fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- On Transit -->
        <div class="col-xl-2 col-md-6 mb-4">
            <div class="card bg-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-xs font-weight-bold text-white text-uppercase mb-1">On Transit</div>
                            <div class="h5 mb-0 font-weight-bold text-white">{{ $onTransitParcels }}</div>
                        </div>
                        <i class="fas fa-shipping-fast fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delayed -->
        <div class="col-xl-2 col-md-6 mb-4">
            <div class="card bg-warning shadow h-100 py-2 border-left-danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-xs font-weight-bold text-white text-uppercase mb-1">Delayed</div>
                            <div class="h5 mb-0 font-weight-bold text-white">{{ $delayedDeliveries }}</div>
                        </div>
                        <i class="fas fa-hourglass-half fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Failed -->
        <div class="col-xl-2 col-md-6 mb-4">
            <div class="card bg-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-xs font-weight-bold text-white text-uppercase mb-1">Failed</div>
                            <div class="h5 mb-0 font-weight-bold text-white">{{ $failedDeliveries }}</div>
                        </div>
                        <i class="fas fa-times-circle fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Successful -->
        <div class="col-xl-2 col-md-6 mb-4">
            <div class="card bg-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-xs font-weight-bold text-white text-uppercase mb-1">Delivered</div>
                            <div class="h5 mb-0 font-weight-bold text-white">{{ $successfulDeliveries }}</div>
                        </div>
                        <i class="fas fa-box fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
