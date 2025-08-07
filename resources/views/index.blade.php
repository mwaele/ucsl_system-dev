@extends('layouts.custom')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-primary">Dashboard</h1>
    </div>

    <!-- Time Filter & Date Range Filter -->
    <form method="GET" action="{{ route('dashboard') }}" class="mb-4">
        <div class="form-row align-items-end">
            <div class="col-auto">
                <label for="time" class="font-weight-bold">Quick Filter:</label>
                <select name="time" id="time" class="form-control text-primary" onchange="this.form.submit()">
                    <option value="all" {{ $timeFilter == 'all' ? 'selected' : '' }}>All</option>
                    <option value="daily" {{ $timeFilter == 'daily' ? 'selected' : '' }}>Today</option>
                    <option value="weekly" {{ $timeFilter == 'weekly' ? 'selected' : '' }}>This Week</option>
                    <option value="biweekly" {{ $timeFilter == 'biweekly' ? 'selected' : '' }}>Last 14 Days</option>
                    <option value="monthly" {{ $timeFilter == 'monthly' ? 'selected' : '' }}>This Month</option>
                    <option value="yearly" {{ $timeFilter == 'yearly' ? 'selected' : '' }}>This Year</option>
                </select>
            </div>
            <div class="col-auto">
                <label for="start_date" class="font-weight-bold">Start Date:</label>
                <input type="date" name="start_date" id="start_date" class="form-control text-primary"
                    value="{{ request('start_date') }}">
            </div>
            <div class="col-auto">
                <label for="end_date" class="font-weight-bold">End Date:</label>
                <input type="date" name="end_date" id="end_date" class="form-control text-primary"
                    value="{{ request('end_date') }}">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Apply</button>
            </div>
            <div class="col-auto">
                <a href="{{ route('dashboard') }}" class="btn btn-warning">Clear</a>
            </div>
        </div>
    </form>


    <!-- Content Row -->
    @php
        $queryParams = ['time' => $timeFilter];

        if (request('start_date') && request('end_date')) {
            $queryParams['start_date'] = request('start_date');
            $queryParams['end_date'] = request('end_date');
        }
    @endphp

    <div class="row">

        <!-- Total Requests Card -->
        <div class="col-xl-2 col-md-6 mb-4">
            <a href="{{ route('client-requests.index', array_merge($queryParams, ['time' => $timeFilter])) }}"
                title="View All Client Requests" class="text-decoration-none text-dark">
                <div class="card border-left-info bg-success shadow h-100 py-2 hover-card">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-white text-uppercase mb-1">
                                    Total Requests
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-white">
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

        <!-- Delivered Requests Card -->
        <div class="col-xl-2 col-md-6 mb-4">
            <a href="{{ route('client-requests.index', array_merge($queryParams, ['status' => 'delivered', 'time' => $timeFilter])) }}"
                title="View Unverified Parcels" class="text-decoration-none text-dark">
                <div class="card border-left-info bg-primary shadow h-100 py-2 hover-card">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-white text-uppercase mb-1">
                                    Delivered Requests
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-white">
                                    {{ $delivered }}
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

        <!-- Collected Requests Card -->
        <div class="col-xl-2 col-md-6 mb-4">
            <a href="{{ route('client-requests.index', array_merge($queryParams, ['status' => 'collected', 'time' => $timeFilter])) }}"
                title="View Collected Parcels" class="text-decoration-none text-dark">
                <div class="card border-left-success bg-primary shadow h-100 py-2 hover-card">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-white text-uppercase mb-1">
                                    Collected Requests
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-white">
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
            <a href="{{ route('client-requests.index', array_merge($queryParams, ['status' => 'verified', 'time' => $timeFilter])) }}"
                title="View Verified Collections" class="text-decoration-none text-dark">
                <div class="card border-left-success bg-info shadow h-100 py-2 hover-card">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-white text-uppercase mb-1">
                                    Verified
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-white">
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

        <!-- Pending Collections Card -->
        <div class="col-xl-2 col-md-6 mb-4">
            <a href="{{ route('client-requests.index', array_merge($queryParams, ['status' => 'pending collection', 'time' => $timeFilter])) }}"
                title="View Pending Collections" class="text-decoration-none text-dark">
                <div class="card border-left-primary bg-warning shadow h-100 py-2 hover-card">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-white text-uppercase mb-1">
                                    Pending Collections
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-white">
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
                <div class="col-md-2 mb-3">
                    <a href="{{ route('client-requests.index', array_merge($queryParams, ['station' => $stationName, 'time' => $timeFilter])) }}"
                        class="text-decoration-none text-dark">
                        <div class="card border-left-primary shadow h-100 py-2 hover-card">
                            <div class="card-body">
                                <h6 class="font-weight-bold text-primary text-uppercase mb-2">{{ $stationName }}
                                </h6>
                                <p class="mb-1">Total: <strong>{{ $stats['total'] }}</strong></p>
                                <p class="mb-1 text-info">
                                    <a href="{{ route('client-requests.index', array_merge($queryParams, ['station' => $stationName, 'status' => 'delivered', 'time' => $timeFilter])) }}"
                                        class="text-primary text-decoration-none">
                                        Delivered: <strong>{{ $stats['delivered'] }}</strong>
                                    </a>
                                </p> 
                                <p class="mb-1 text-info">
                                    <a href="{{ route('client-requests.index', array_merge($queryParams, ['station' => $stationName, 'status' => 'verified', 'time' => $timeFilter])) }}"
                                        class="text-info text-decoration-none">
                                        Verified: <strong>{{ $stats['verified'] }}</strong>
                                    </a>
                                </p>
                                <p class="mb-1 text-success">
                                    <a href="{{ route('client-requests.index', array_merge($queryParams, ['station' => $stationName, 'status' => 'collected', 'time' => $timeFilter])) }}"
                                        class="text-success text-decoration-none">
                                        Collected: <strong>{{ $stats['collected'] }}</strong>
                                    </a>
                                </p>
                                <p class="mb-1 text-warning">
                                    <a href="{{ route('client-requests.index', array_merge($queryParams, ['station' => $stationName, 'status' => 'pending collection', 'time' => $timeFilter])) }}"
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
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-primary"></i>
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
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-primary"></i>
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
