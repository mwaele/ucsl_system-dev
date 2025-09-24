@extends('layouts.custom')

@section('content')
    <div class="d-flex flex-column justify-content-center align-items-center" style="height:70vh;">
        <h1 class="text-danger fw-bold mb-3">Not Authorized</h1>
        <p class="text-muted mb-4">Sorry {{ Auth::user()->name ?? 'Guest' }}, you don’t have permission to access this page.
        </p>
        <a href="{{ route('home') }}" class="btn btn-primary">Go Back Home</a>
    </div>
@endsection
