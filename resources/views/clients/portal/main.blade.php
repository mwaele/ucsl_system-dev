<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Client Portal</title>
    <link href="{{ asset('assets/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            background-color: #f4f6f9;
        }

        .sidebar {
            min-height: 100vh;
            background-color: #14489f;
        }

        .sidebar .nav-link {
            color: #fff;
        }

        .sidebar .nav-link.active {
            background-color: #f57f3f;
            color: #fff;
            font-weight: bold;
        }

        .content-area {
            padding: 20px;
        }
    </style>
</head>

<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <nav class="sidebar d-flex flex-column p-3">
            <a href="#" class="d-flex align-items-center mb-3 text-white text-decoration-none">
                <img src="{{ asset('images/UCSLogo1.png') }}" height="50" alt="Logo" class="me-2">
                <span class="fs-5 fw-bold">Client Portal</span>
            </a>
            <hr class="text-white">
            <ul class="nav nav-pills flex-column mb-auto">
                <li>
                    <a href="{{ route('client.parcel.create') }}" class="nav-link {{ request()->routeIs('client.parcel.create') ? 'active' : '' }}">
                        <i class="bi bi-box-seam me-2"></i> Create Parcel
                    </a>
                </li>
                <li>
                    <a href="{{ route('tracker') }}" class="nav-link {{ request()->routeIs('tracker') ? 'active' : '' }}">
                        <i class="bi bi-search me-2"></i> Track Parcel
                    </a>
                </li>
            </ul>
            <hr class="text-white">
            <button class="btn btn-danger w-100 logout">
                Logout: {{ auth('client')->user()->name ?? auth('guest')->user()->name }}
            </button>
        </nav>

        <!-- Main Content -->
        <div class="content-area flex-grow-1">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="text-primary">
                    @yield('page-title', 'Track Your Parcel')
                </h2>
                <h6 class="text-dark">
                    Tracking Date: <span id="liveDateTime"></span>
                </h6>
            </div>

            <!-- Page Content -->
            @yield('page-content')
        </div>
    </div>

    {{-- Scripts --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @stack('scripts')
    <script>
        // logout
        $('.logout').on('click', function(e) {
            e.preventDefault();
            if (!confirm("Are you sure you want to logout?")) return;
            $.ajax({
                url: "/client/logout",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function() {
                    localStorage.clear();
                    window.location.href = "/tracking";
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    alert('Logout failed. Try again.');
                }
            });
        });

        // live date/time
        function updateDateTime() {
            const now = new Date();
            document.getElementById('liveDateTime').textContent =
                now.toLocaleString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: 'numeric',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: true
                });
        }
        updateDateTime();
        setInterval(updateDateTime, 1000);
    </script>
</body>
</html>
