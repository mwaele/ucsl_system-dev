@extends('layouts.custom_app')

@section('content')
    <div class="card shadow border-0 rounded-4 p-4">


        {{-- Logo at the top --}}
        <div class="text-center mb-4">
            <img src="{{ asset('images/UCSLogo1.png') }}" alt="Logo" class="img-fluid" style="max-height: 60px;">
        </div>

        @if (session('status'))
            <div class="alert alert-success text-center">
                {{ session('status') }}
            </div>
        @endif

        {{-- Heading --}}
        <h4 class="mb-3 text-center text-primary fw-semibold">{{ __('Reset Password') }}</h4>
        <p class="text-center text-success mb-4">{{ __('Enter your email address below.') }}</p>

        {{-- Form --}}
        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            {{-- Password Reset Token --}}
            <input type="hidden" name="token" value="{{ request()->route('token') ?? '' }}">

            {{-- Email --}}
            <div class="mb-3">
                <label for="email" class="form-label">{{ __('Email Address') }}</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                    name="email" value="{{ old('email', request()->email ?? '') }}" required autofocus
                    autocomplete="username">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- New Password --}}
            {{-- <div class="mb-3">
                <label for="password" class="form-label">{{ __('New Password') }}</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                    name="password" required autocomplete="new-password">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div> --}}

            {{-- Confirm Password --}}
            {{-- <div class="mb-3">
                <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
                <input id="password_confirmation" type="password"
                    class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation"
                    required autocomplete="new-password">
                @error('password_confirmation')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div> --}}

            {{-- Submit Button --}}
            <div class="d-flex justify-content-between">
                <a class=" btn btn-sm text-white btn-danger" href="{{ route('login') }}">
                    {{ __('Back to Login') }}
                </a>
                <button type="submit" class="btn btn-primary px-4" id="submit-btn">
                    <span class="spinner-border spinner-border-sm d-none me-2" role="status" aria-hidden="true"
                        id="spinner"></span>
                    <span id="btn-text">Send Reset Link</span>
                </button>
            </div>
        </form>
    </div>
@endsection
