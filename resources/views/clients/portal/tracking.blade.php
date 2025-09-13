@extends('client.portal.main')

@section('page-title', 'Track Parcel')

@section('page-content')
    {{-- Here we can simply include your existing tracking view --}}
    @include('tracking.index')
@endsection
