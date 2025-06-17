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

        .valid-icon {
            position: absolute;
            right: 10px;
            top: 38px;
            font-size: 18px;
            color: green;
            display: none;
        }

        .form-control.is-valid+.valid-feedback {
            display: block;
        }

        .form-control.is-valid~.valid-icon {
            display: inline;
        }

        .form-control.is-invalid~.valid-icon {
            display: none;
        }
    </style>
</head>

<body class="p-4">

    <div class="container ">
        <div class="row justify-content-center">

            <h2 class="mb-2 text-primary  justify-content-center">
                <img src="{{ asset('images/UCSLogo1.png') }}" height="200px" width="auto" alt="">
                Track Your Parcel
            </h2>
        </div>
        <div class=" mt-1">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card shadow rounded-4">
                        <div class="card-body p-4">
                            <h3 class="text-center text-success mb-2">Guest Sign In</h3>
                            <form id="guestForm">
                                @csrf

                                {{-- Name --}}
                                <div class="mb-3 position-relative">
                                    <label>Name</label>
                                    <input type="text" name="name" minlength="3" maxlength="50"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name') }}" required>
                                    <div class="invalid-feedback" id="nameError">
                                        @error('name')
                                            {{ $message }}
                                        @else
                                            Name must be 3–50 characters.
                                        @enderror
                                    </div>
                                    <div class="valid-feedback" id="nameValid">Looks good!</div>
                                    <span class="valid-icon" id="nameTick">✔️</span>
                                </div>

                                {{-- Phone --}}
                                <div class="mb-3 position-relative">
                                    <label>Phone</label>
                                    <input type="text" name="phone"
                                        class="form-control @error('phone') is-invalid @enderror"
                                        value="{{ old('phone') }}" required>
                                    <div class="invalid-feedback" id="phoneError">
                                        @error('phone')
                                            {{ $message }}
                                        @else
                                            Phone must be exactly 10 digits.
                                        @enderror
                                    </div>
                                    <div class="valid-feedback" id="phoneValid">Looks good!</div>
                                    <span class="valid-icon" id="phoneTick">✔️</span>
                                </div>

                                {{-- Email --}}
                                <div class="mb-3 position-relative">
                                    <label class="text-dark">Email</label>
                                    <input type="email" name="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email') }}" required>
                                    <div class="invalid-feedback" id="emailError">
                                        @error('email')
                                            {{ $message }}
                                        @else
                                            Enter a valid email address.
                                        @enderror
                                    </div>
                                    <div class="valid-feedback" id="emailValid">Looks good!</div>
                                    <span class="valid-icon" id="emailTick">✔️</span>
                                </div>

                                <button type="submit" class="btn btn-primary w-100">Continue as Guest</button>
                                <a href="{{ route('signin') }}" class="btn btn-link w-100 mt-2">Signin</a>
                            </form>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');

        const fields = {
            name: form.querySelector('input[name="name"]'),
            phone: form.querySelector('input[name="phone"]'),
            email: form.querySelector('input[name="email"]')
        };

        const errors = {
            name: document.getElementById('nameError'),
            phone: document.getElementById('phoneError'),
            email: document.getElementById('emailError')
        };

        const valids = {
            name: document.getElementById('nameValid'),
            phone: document.getElementById('phoneValid'),
            email: document.getElementById('emailValid')
        };

        const ticks = {
            name: document.getElementById('nameTick'),
            phone: document.getElementById('phoneTick'),
            email: document.getElementById('emailTick')
        };

        function validateName() {
            const value = fields.name.value.trim();
            if (value.length < 3 || value.length > 50) {
                fields.name.classList.add('is-invalid');
                fields.name.classList.remove('is-valid');
                errors.name.textContent = 'Name must be between 3 and 50 characters.';
                return false;
            }
            fields.name.classList.remove('is-invalid');
            fields.name.classList.add('is-valid');
            return true;
        }

        function validatePhone() {
            const value = fields.phone.value.trim();
            const valid = /^\d{12}$/.test(value);
            if (!valid) {
                fields.phone.classList.add('is-invalid');
                fields.phone.classList.remove('is-valid');
                errors.phone.textContent = 'Phone must be exactly 12 digits.';
                return false;
            }
            fields.phone.classList.remove('is-invalid');
            fields.phone.classList.add('is-valid');
            return true;
        }

        function validateEmail() {
            const value = fields.email.value.trim();
            const valid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
            if (!valid) {
                fields.email.classList.add('is-invalid');
                fields.email.classList.remove('is-valid');
                errors.email.textContent = 'Enter a valid email address.';
                return false;
            }
            fields.email.classList.remove('is-invalid');
            fields.email.classList.add('is-valid');
            return true;
        }

        // Real-time listeners
        fields.name.addEventListener('keyup', validateName);
        fields.phone.addEventListener('keyup', validatePhone);
        fields.email.addEventListener('keyup', validateEmail);

        // Final validation on submit
        form.addEventListener('submit', function(e) {
            const isValid = validateName() & validatePhone() & validateEmail();
            if (!isValid) e.preventDefault();
        });
    });

    document.querySelector('form').addEventListener('submit', async function(e) {
        e.preventDefault();

        const form = e.target;
        const formData = {
            name: form.name.value.trim(),
            phone: form.phone.value.trim(),
            email: form.email.value.trim()
        };

        try {
            const res = await fetch('{{ route('guests.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(formData)
            });

            const data = await res.json();

            if (!res.ok) {
                // Show validation errors
                if (data.errors) {
                    for (const field in data.errors) {
                        const input = form.querySelector(`[name="${field}"]`);
                        input.classList.add('is-invalid');
                        const errorDiv = document.getElementById(field + 'Error');
                        if (errorDiv) {
                            errorDiv.textContent = data.errors[field][0];
                        }
                    }
                }
                return;
            }

            // ✅ Success: store token & redirect or show message
            console.log('Access Token:', data.token);
            localStorage.setItem('guest_token', data.token);
            localStorage.setItem('guest_name', data.guest.name);
            //alert('Guest access granted!');
            window.location.href = '/tracker'; // or your dashboard

        } catch (error) {
            console.error('Error submitting form:', error);
        }
    });
</script>

</html>
