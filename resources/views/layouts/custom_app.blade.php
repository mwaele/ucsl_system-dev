<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel Auth') }}</title>

    <!-- Bootstrap 5 CSS -->
    <link href="{{ asset('assets/css/sb-admin-2.min.css') }}" rel="stylesheet" />

    <!-- Optional: Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            background-image: url('{{ asset('images/Sys-0 WallPaper.jpg') }}');
            background-size: 100% 100%;
            /* Ensures the image always covers the screen */
            background-position: center;
            /* Keeps the focus centered */
            background-repeat: no-repeat;
            /* Prevents tiling */
            min-height: 100vh;
            /* Ensures full screen height */
            margin: 0;
            /* Removes default body margin */
        }


        .auth-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-card {
            width: 100%;
            max-width: 450px;
        }
    </style>
</head>

<body>
    <div class="auth-wrapper">
        <div class="auth-card">
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle (with Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
