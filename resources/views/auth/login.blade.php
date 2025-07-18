@extends('layouts.custom_app')

@section('content')
    <div class="card shadow border-0 rounded-4 p-4">
        {{-- Logo at the top --}}
        <div class="text-center mb-4">
            <img src="{{ asset('images/UCSLogo1.png') }}" alt="Logo" class="img-fluid" style="max-height: 60px;">
        </div>

        {{-- Heading --}}
        {{-- <h4 class="mb-3 text-center text-primary fw-semibold">{{ __('Welcome Back!') }}</h4> --}}
        <p class="text-center text-warning mb-4">{{ __('Please login to continue') }}</p>

        {{-- Session Status --}}
        @if (session('status'))
            <div class="alert alert-success text-center">{{ session('status') }}</div>
        @endif

        {{-- Login Form --}}
        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- Email --}}
            <div class="mb-3">
                <label for="email" class="form-label text-primary">{{ __('Email Address') }}</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                    name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Password --}}
            <div class="mb-3">
                <label for="password" class="form-label text-primary">{{ __('Password') }}</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                    name="password" required autocomplete="current-password">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Remember Me --}}
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                <label class="form-check-label" for="remember_me">{{ __('Remember Me') }}</label>
            </div>

            {{-- Actions --}}
            <div class="d-flex justify-content-between align-items-center">
                {{-- @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-decoration-none small">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif --}}

                <button type="submit" class="btn btn-primary px-4">
                    {{ __('Log in') }}
                </button>
            </div>
        </form>
    </div>
@endsection
