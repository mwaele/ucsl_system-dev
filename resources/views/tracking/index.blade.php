<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Tracking Status</title>
    <link href="{{ asset('assets/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">


    <style>
        body {
            background-color: #f4f6f9;
        }

        .timeline-with-icons {
            border-left: 1px solid hsl(0, 0%, 90%);
            position: relative;
            list-style: none;
        }

        .timeline-with-icons .timeline-item {
            position: relative;
        }

        .timeline-with-icons .timeline-item:after {
            position: absolute;
            display: block;
            top: 0;
        }

        .timeline-with-icons .timeline-icon {
            position: absolute;
            left: -56px;
            background-color: #f57f3f;
            color: #14489f;
            border-radius: 50%;
            height: 31px;
            width: 31px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>

<body class="p-4">

    <div class="container vh-100 d-flex flex-column justify-content-center">
        <div class="row justify-content-center align-items-center mb-4">
            <div class="text-center">
                <img src="{{ asset('images/UCSLogo1.png') }}" height="100" alt="Logo" class="mb-3">
                <h2 class="text-primary fw-bold"><strong>Parcel Tracking</strong></h2>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-lg rounded-4 border-0">
                    <div class="card-body p-5">
                        <h3 class="text-center text-success mb-4">Client Sign In</h3>
                        <form id="clientLoginForm">
                            @csrf
                            <div class="mb-3">
                                <label class="text-dark">Email</label>
                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}" required autofocus>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="text-dark">Password</label>
                                <input type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Sign In</button>
                            <a href="{{ route('guest') }}" class="btn btn-link w-100 mt-3">Continue as Guest</a>
                        </form>

                        <div id="loginError" class="text-danger mt-2"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('clientLoginForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const form = e.target;
            const formData = {
                email: form.email.value,
                password: form.password.value
            };

            fetch('/api/client/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        // 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), // Only needed for web routes
                    },
                    body: JSON.stringify(formData)
                })
                .then(async res => {
                    const data = await res.json();
                    if (!res.ok) throw data;

                    // ✅ Save token to localStorage/sessionStorage
                    localStorage.setItem('client_token', data.access_token);

                    // ✅ Redirect to tracker
                    window.location.href = '/tracker';
                })
                .catch(err => {
                    document.getElementById('loginError').innerText = err.message || 'Login failed.';
                });
        });
    </script>

</body>


</html>
