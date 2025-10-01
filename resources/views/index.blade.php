@extends('layouts.custom')
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
                <div class="card border-left-info bg-primary shadow h-100 py-2 hover-card">
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

        <!-- Delivered Requests Card -->
        <div class="col-xl-2 col-md-6 mb-4">
            <a href="{{ route('client-requests.index', array_merge($queryParams, ['status' => 'delivered', 'time' => $timeFilter])) }}"
                title="View Unverified Parcels" class="text-decoration-none text-dark">
                <div class="card border-left-info bg-success shadow h-100 py-2 hover-card">
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

        <!-- Failed/Undelivered Requests Card -->
        <div class="col-xl-2 col-md-6 mb-4">
            <a href="{{ route('client-requests.index', array_merge($queryParams, ['status' => 'delivered', 'time' => $timeFilter])) }}"
                title="View Unverified Parcels" class="text-decoration-none text-dark">
                <div class="card border-left-info bg-danger shadow h-100 py-2 hover-card">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-white text-uppercase mb-1">
                                    Failed/Undelivered Requests
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-white">
                                    {{ $delivered }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
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
        <!-- Bar Graph -->
        <div class="col-xl-8 col-lg-7 d-flex">
            <div class="card shadow mb-4 w-100 h-100">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Earnings Overview</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-primary"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                            aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item" href="#" id="downloadChart">Download PNG</a>
                            <a class="dropdown-item" href="#" id="exportPDF">Export to PDF</a>

                            <form id="chartForm" action="{{ route('export.pdf') }}" method="POST"
                                style="display:none;">
                                @csrf
                                <input type="hidden" name="chartImage" id="chartImage">
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="myChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pie Chart -->
        <div class="col-xl-4 col-lg-5 d-flex">
            <div class="card shadow mb-4 w-100 h-100">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Requests Distribution</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie text-center">
                        <canvas id="pieChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Line Chart -->
        <div class="col-xl-8 col-lg-7 d-flex">
            <div class="card shadow mb-4 w-100 h-100">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Profitability</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie text-center">
                        <canvas id="lineChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Doughnut Graph -->
        <div class="col-xl-4 col-lg-5 d-flex">
            <div class="card shadow mb-4 w-100 h-100">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Clients</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie text-center">
                        <canvas id="doughnutChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Initialize chart and store the instance
        const ctx = document.getElementById('myChart').getContext('2d');
        const myChartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($labels),
                datasets: [{
                    label: 'Earnings',
                    data: @json($data),
                    borderWidth: 1,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(153, 102, 255, 0.6)',
                        'rgba(255, 159, 64, 0.6)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: {
                                size: 16, // 🔹 legend font size
                                weight: 'bold'
                            }
                        }
                    },
                    title: {
                        display: true,
                        text: 'Earnings Overview',
                        font: {
                            size: 20, // 🔹 chart title font size
                            weight: 'bold'
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            font: {
                                size: 14 // 🔹 x-axis labels font size
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            font: {
                                size: 14 // 🔹 y-axis labels font size
                            }
                        }
                    }
                }
            }
        });

        // Download as PNG
        document.getElementById('downloadChart').addEventListener('click', function(e) {
            e.preventDefault();
            const imageURL = myChartInstance.toBase64Image();
            const link = document.createElement('a');
            link.href = imageURL;
            link.download = "chart.png";
            link.click();
        });

        // Export to PDF
        document.getElementById('exportPDF').addEventListener('click', function() {
            const canvas = document.getElementById('myChart');
            const chartImage = canvas.toDataURL('image/png');

            fetch('/export-pdf', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        chartImage
                    })
                })
                .then(response => response.blob())
                .then(blob => {
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = 'report.pdf';
                    document.body.appendChild(a);
                    a.click();
                    a.remove();
                    window.URL.revokeObjectURL(url);
                })
                .catch(error => console.error('Error:', error));
        });
    </script>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('pieChart').getContext('2d');

            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: @json($labels),
                    datasets: [{
                        label: 'Requests',
                        data: @json($data),
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.6)',
                            'rgba(54, 162, 235, 0.6)',
                            'rgba(255, 206, 86, 0.6)',
                            'rgba(75, 192, 192, 0.6)',
                            'rgba(153, 102, 255, 0.6)',
                            'rgba(255, 159, 64, 0.6)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 20,
                                padding: 20,
                            },
                            // display: false //  Hide bottom legend
                        },
                        datalabels: {
                            color: '#fff',
                            font: {
                                weight: 'bold',
                                size: 11
                            },
                            formatter: (value, context) => {
                                const label = context.chart.data.labels[context.dataIndex];
                                return `${label}\n${value}`;
                            }
                        }
                    },
                    layout: {
                        padding: {
                            top: 20,
                            bottom: 20
                        }
                    }
                },
                plugins: [ChartDataLabels]
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById("lineChart").getContext("2d");

            new Chart(ctx, {
                type: "line",
                data: {
                    labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul"],
                    datasets: [{
                        label: "Earnings",
                        data: [1000, 1500, 1250, 2000, 1800, 2400, 2200],
                        borderColor: "rgba(78, 115, 223, 1)",
                        backgroundColor: "rgba(78, 115, 223, 0.05)",
                        tension: 0.3, // curve smoothness
                        fill: true,
                        pointBackgroundColor: "rgba(78, 115, 223, 1)",
                        pointBorderColor: "#fff",
                        pointHoverRadius: 6,
                        pointRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true
                        },
                        tooltip: {
                            mode: "index",
                            intersect: false
                        }
                    },
                    interaction: {
                        mode: "nearest",
                        axis: "x",
                        intersect: false
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: "rgba(234, 236, 244, 0.3)"
                            }
                        }
                    }
                }
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById("doughnutChart").getContext("2d");

            new Chart(ctx, {
                type: "doughnut", // changed from "pie"
                data: {
                    labels: ["On-Account", "Walkin"],
                    datasets: [{
                        data: [45, 75], // example data
                        backgroundColor: ["#4e73df", "#1cc88a"],
                        hoverBackgroundColor: ["#2e59d9", "#17a673"],
                        hoverBorderColor: "rgba(234, 236, 244, 1)",
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: "70%", // controls doughnut thickness
                    plugins: {
                        legend: {
                            display: true,
                            position: "bottom",
                        }
                    }
                }
            });
        });
    </script>

@endsection
