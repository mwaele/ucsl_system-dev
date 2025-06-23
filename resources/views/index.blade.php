@extends('layouts.custom')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-download fa-sm text-white-50"></i> Generate Reports </a>
    </div>

    <!-- Time Filter -->
    <form method="GET" action="{{ route('dashboard') }}" class="mb-4">
        <div class="form-inline">
            <label for="time" class="mr-2 font-weight-bold">Filter by Time:</label>
            <select name="time" id="time" class="form-control mr-2" onchange="this.form.submit()">
                <option value="all" {{ $timeFilter == 'all' ? 'selected' : '' }}>All</option>
                <option value="daily" {{ $timeFilter == 'daily' ? 'selected' : '' }}>Today</option>
                <option value="weekly" {{ $timeFilter == 'weekly' ? 'selected' : '' }}>This Week</option>
                <option value="biweekly" {{ $timeFilter == 'biweekly' ? 'selected' : '' }}>Last 14 Days</option>
                <option value="monthly" {{ $timeFilter == 'monthly' ? 'selected' : '' }}>This Month</option>
                <option value="yearly" {{ $timeFilter == 'yearly' ? 'selected' : '' }}>This Year</option>
            </select>
        </div>
    </form>

    <!-- Content Row -->
    <div class="row">

        <!-- Total Requests Card -->
        <div class="col-xl-2 col-md-6 mb-4">
            <a href="{{ route('client-requests.index') }}" title="View All Client Requests"
                class="text-decoration-none text-dark">
                <div class="card border-left-primary shadow h-100 py-2 hover-card">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">
                                    Total Requests
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $totalRequests }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Collected Requests Card -->
        <div class="col-xl-2 col-md-6 mb-4">
            <a href="{{ route('client-requests.index', ['status' => 'collected']) }}" title="View Collected Parcels"
                class="text-decoration-none text-dark">
                <div class="card border-left-success shadow h-100 py-2 hover-card">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Collected Requests
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $collected }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-box fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Verified Requests Card -->
        <div class="col-xl-2 col-md-6 mb-4">
            <a href="{{ route('client-requests.index', ['status' => 'verified']) }}" title="View Verified Collections"
                class="text-decoration-none text-dark">
                <div class="card border-left-info shadow h-100 py-2 hover-card">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Verified
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $verified }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Unverified Requests Card -->
        <div class="col-xl-2 col-md-6 mb-4">
            <a href="{{ route('client-requests.index', ['status' => 'collected']) }}" title="View Unverified Parcels"
                class="text-decoration-none text-dark">
                <div class="card border-left-success shadow h-100 py-2 hover-card">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Unverified Requests
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $collected }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-box fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Pending Collections Card -->
        <div class="col-xl-2 col-md-6 mb-4">
            <a href="{{ route('client-requests.index', ['status' => 'pending collection']) }}"
                title="View Pending Collections" class="text-decoration-none text-dark">
                <div class="card border-left-warning shadow h-100 py-2 hover-card">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Pending Collections
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $pendingCollection }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clock fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    @if ($stationStats)
        <div class="row mt-4">
            @foreach ($stationStats as $stationName => $stats)
                <div class="col-md-3 mb-3">
                    <a href="{{ route('client-requests.index', ['station' => $stationName]) }}" class="text-decoration-none text-dark">
                        <div class="card border-left-primary shadow h-100 py-2 hover-card">
                            <div class="card-body">
                                <h6 class="font-weight-bold text-primary text-uppercase mb-2">{{ $stationName }} Station</h6>
                                <p class="mb-1">Total: <strong>{{ $stats['total'] }}</strong></p>
                                <p class="mb-1 text-success">
                                    <a href="{{ route('client-requests.index', ['station' => $stationName, 'status' => 'collected', 'time' => $timeFilter]) }}"
                                    class="text-success text-decoration-none">
                                        Collected: <strong>{{ $stats['collected'] }}</strong>
                                    </a>
                                </p>
                                <p class="mb-1 text-info">
                                    <a href="{{ route('client-requests.index', ['station' => $stationName, 'status' => 'verified', 'time' => $timeFilter]) }}"
                                    class="text-info text-decoration-none">
                                        Verified: <strong>{{ $stats['verified'] }}</strong>
                                    </a>
                                </p>
                                <p class="mb-1 text-warning">
                                    <a href="{{ route('client-requests.index', ['station' => $stationName, 'status' => 'pending collection', 'time' => $timeFilter]) }}"
                                    class="text-warning text-decoration-none">
                                        Pending Collection: <strong>{{ $stats['pending'] }}</strong>
                                    </a>
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    @endif


    <!-- Content Row -->
    <div class="row">
        <!-- Area Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Earnings Overview
                    </h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                            aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Dropdown Header:</div>
                            <a class="dropdown-item" href="#">Action</a>
                            <a class="dropdown-item" href="#">Another action</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Something else here</a>
                        </div>
                    </div>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="myAreaChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pie Chart -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Revenue Sources
                    </h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                            aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Dropdown Header:</div>
                            <a class="dropdown-item" href="#">Action</a>
                            <a class="dropdown-item" href="#">Another action</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Something else here</a>
                        </div>
                    </div>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="myPieChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <span class="mr-2">
                            <i class="fas fa-circle text-primary"></i> Direct
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-success"></i> Social
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-info"></i> Referral
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
