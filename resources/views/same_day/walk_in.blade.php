@extends('layouts.custom')

@section('content')
<div class="card">

    <div class="card-header py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Same Day - Walk-in Parcels</h5>
            
            <div class="d-flex gap-2 ms-auto">
                <a href="/users_report" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm mr-2">
                    <i class="fas fa-download fa text-white"></i> Generate Report
                </a>

                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createUserModal">
                    + Create Parcel
                </button>
            </div>
        </div>
    </div>



    <div class="card-body">
        <div class="table-responsive">
            <table class="table text-primary table-bordered table-striped table-hover" id="ucsl-table" width="100%"
                            cellspacing="0" style="font-size: 14px;">
                <thead>
                    <tr class="text-success">
                        <th>#</th>
                        <th>Request ID</th>
                        <th>Client</th>
                        <th>Pick-up Location</th>
                        <th>Date Requested</th>
                        <th>Rider</th>
                        <th>Vehicle</th>
                        <th>Desc.</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($clientRequests as $request)
                        <tr>
                            <td> {{ $loop->iteration }}. </td>
                            <td> {{ $request->requestId }} </td>
                            <td> {{ $request->client->name }} </td>
                            <td> {{ $request->collectionLocation }} </td>
                            <td> {{ \Carbon\Carbon::parse($request->dateRequested)->format('F j, Y \a\t g:i A') }}
                            </td>
                            <td> {{ $request->user->name ?? '—' }} </td>
                            <td> {{ $request->vehicle->regNo ?? '—' }} </td>
                            <td> {{ $request->parcelDetails }} </td>
                            <td>
                                <span
                                    class="badge p-2
                                    @if ($request->status == 'pending collection') bg-secondary
                                    @elseif ($request->status == 'collected')
                                        bg-warning
                                    @elseif ($request->status == 'verified')
                                        bg-primary @endif
                                    fs-5 text-white">
                                            {{ \Illuminate\Support\Str::title($request->status) }}
                                </span>
                            </td>
                            <td class="d-flex pl-2">
                                <button class="btn btn-sm btn-info mr-1" data-toggle="modal" data-target="#editUserModal-{{ $request->id }}">
                                    Edit
                                </button>
                                <button class="btn btn-sm btn-danger mr-1" title="Delete Client Request"
                                    data-toggle="modal"
                                    data-target="#deleteUser-{{ $request->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <!-- Delete Modal-->
                                <div class="modal fade" id="deleteUser-{{ $request->id }}"
                                    tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                <p>Are you sure you want to delete {{ $request->name }}?
                                                </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-sm btn-secondary"
                                                    data-dismiss="modal">Cancel</button>
                                                <form
                                                    action =" {{ route('user.destroy', $request->id) }}"
                                                    method = "POST">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                        title="Delete" value="DELETE">YES DELETE <i
                                                            class="fas fa-trash"></i> </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
