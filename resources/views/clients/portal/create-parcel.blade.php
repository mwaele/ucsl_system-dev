@extends('client.portal.main')

@section('page-title', 'Create Parcel')

@section('page-content')
    <form method="POST" action="{{ route('client.parcel.store') }}">
        @csrf
        <div class="mb-3">
            <label for="sender" class="form-label">Sender Name</label>
            <input type="text" name="sender" id="sender" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="receiver" class="form-label">Receiver Name</label>
            <input type="text" name="receiver" id="receiver" class="form-control" required>
        </div>
        <!-- Add more fields as per your model -->
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
@endsection
