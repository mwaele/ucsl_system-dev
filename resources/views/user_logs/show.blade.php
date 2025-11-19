@extends('layouts.custom') {{-- or your main layout --}}

@section('title', 'User Log Details')

@section('content')
    <div class=" mt-4">
        <div class="card">
            <div class="card-header text-primary">
                <h4>User Log Details</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th class="text-primary">Full Name</th>
                        <td class="text-primary">{{ $log->name }}</td>
                    </tr>
                    <tr>
                        <th class="text-primary">Actions</th>
                        <td class="text-primary">{{ $log->actions }}</td>
                    </tr>
                    <tr>
                        <th class="text-primary">URL</th>
                        <td class="text-primary">{{ $log->url }}</td>
                    </tr>
                    {{-- <tr>
                        <th class="text-primary">User Agent</th>
                        <td class="text-primary">{{ $log->user_agent }}</td>
                    </tr>
                    <tr>
                        <th class="text-primary">IP Address</th>
                        <td class="text-primary">{{ $log->ip_address }}</td>
                    </tr> --}}
                    <tr>
                        <th class="text-primary">Date</th>
                        <td class="text-primary">{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                    @if (isset($log->updated_at))
                        <tr>
                            <th class="text-primary">Last Updated</th>
                            <td class="text-primary">{{ $log->updated_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                    @endif
                </table>

                <a href="{{ route('user_logs.index') }}" class="btn btn-success mt-3"> <i class="fas fa-arrow-left"></i>
                    Back</a>
            </div>
        </div>
    </div>
@endsection
