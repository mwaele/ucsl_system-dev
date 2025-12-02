@extends('layouts.custom')

@section('content')
    <!-- DataTales Example -->
    <div class="card">
        {{-- Success Message --}}

        <div class="card-header py-3">
            <div class="d-sm-flex align-items-center justify-content-between">
                <h3 class="m-0 font-weight-bold text-danger">Rider Parcel Handover Requests</h3>

                <div>
                    <a href="/offices_report" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm">
                        <i class="fas fa-download fa-sm text-white"></i> Generate Report
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered text-primary" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>RequestId</th>
                            <th>From Rider</th>
                            <th>To Rider</th>
                            <th>Remarks</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>RequestId</th>
                            <th>From Rider</th>
                            <th>To Rider</th>
                            <th>Remarks</th>
                            <th>Status</th>
                            <th>Action</th>

                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($handovers as $handover)
                            <tr>
                                <td> {{ $loop->iteration }}.</td>
                                <td> {{ $handover->requestId }} </td>
                                <td> {{ $handover->fromUser->name }} </td>
                                <td> {{ \Illuminate\Support\Str::title($handover->toUser->name) }} </td>
                                <td> {{ $handover->remarks }} </td>
                                <td> {{ \Illuminate\Support\Str::title($handover->status) }} </td>
                                <td class="row pl-4">
                                    <!-- Edit Button -->
                                    @if ($handover->status === 'pending_approval')
                                        <button type="button" class="btn btn-sm btn-info mr-1" data-toggle="modal"
                                            data-target="#updateHandover" data-id="{{ $handover->id }}"
                                            data-request-id="{{ $handover->requestId }}"
                                            data-from-user-id="{{ $handover->from_user_id }}"
                                            data-to-user-id="{{ $handover->to_user_id }}"><i class="fas fa-check"></i>
                                            Approve
                                        </button>
                                    @else
                                        <span class="text-success">Approved</span>
                                    @endif
                                </td>
                            </tr>
            </div>
            @endforeach
            </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="updateHandover" tabindex="-1" role="dialog" aria-labelledby="UpdateHandoverModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form method="post" id="updateHandoverForm">
                @method('PUT')
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="UpdateHandoverModalLabel">Approve Handover
                        </h5>
                        <button class="close " type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"> <span class="text-danger btn btn-sm">×</span> </span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Remarks <span class="text-danger">*</span></label>
                                    <textarea name="remarks" id="remarks" class="form-control"></textarea>
                                    <input type="hidden" name="from_user_id" id="from_user_id" class="form-control"
                                        required readonly>
                                    <input type="hidden" name="to_user_id" id="to_user_id" class="form-control" required
                                        readonly>
                                    <input type="hidden" name="handover_id" id="handover_id" class="form-control" required
                                        readonly>
                                    <input type="hidden" name="requestId" id="requestId" class="form-control" required
                                        readonly>
                                </div>
                            </div>


                        </div>
                    </div>

                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Approve Handover </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Create Modal-->
    {{-- <div class="modal fade" id="createOffice" tabindex="-1" role="dialog" aria-labelledby="CreateOfficeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form action="{{ route('offices.store') }}" method="post">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="CreateOfficeModalLabel">Create Office</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Office Name <span class="text-danger">*</span></label>
                                    <input type="text" name="office_name" class="form-control" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Office Code <span class="text-danger">*</span></label>
                                    <input type="text" name="office_code" class="form-control" required maxlength="10">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Office type <span class="text-danger">*</span></label>
                                    <select name="type" class="form-control" required>
                                        <option value="">Select</option>
                                        <option value="staff">Staff</option>
                                        <option value="agent">Agent</option>
                                        <option value="caravan">Caravan</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Status <span class="text-danger">*</span></label>
                                    <select name="status" class="form-control" required>
                                        <option value="">Select</option>
                                        <option value="active" selected>Active</option>
                                        <option value="closed">Closed</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Country <span class="text-danger">*</span></label>
                                    <select name="country" class="form-control" required>
                                        <option value="">Select</option>
                                        <option value="Kenya" selected>Kenya</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Create Office</button>
                    </div>
                </div>
            </form>
        </div>
    </div> --}}
@endsection
