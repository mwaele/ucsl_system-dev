@extends('layouts.custom')

@section('content')
<div class="container">
    <h2>Decline Agent Pickup Request</h2>
    <p>Request ID: <strong>{{ $requestId }}</strong></p>

    <form method="POST" action="{{ route('agent.decline.submit', $requestId) }}">
        @csrf
        <div class="form-group">
            <label for="remarks">Remarks</label>
            <textarea name="remarks" class="form-control" required></textarea>
        </div>
        <button type="submit" class="btn btn-danger mt-3">Submit Decline</button>
    </form>
</div>
@endsection
