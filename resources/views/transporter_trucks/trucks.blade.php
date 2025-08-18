@extends('layouts.custom')

@section('content')
    <!-- DataTales Example -->
    <div class="card">
        {{-- Success Message --}}

        <div class="card-header py-3">
            <div class="d-sm-flex align-items-center justify-content-between">
                <button data-toggle="modal" data-target="#add_truck" class="btn btn-success"><i class="fas fa-plus-circle"></i>
                    Add Truck</button>
                <h4 class="m-0 font-weight-bold text-success">Truck Lists for {{ $name }}</h4>


                <a href="/transporter_trucks_report/{{ $id }}"
                    class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm"><i
                        class="fas fa-download fa-sm text-white"></i> Generate Report</a>
            </div>




        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered text-primary" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Transporter Name</th>
                            <th>Reg No</th>
                            <th>Driver</th>
                            <th>Driver Contact</th>
                            <th>Truck Type</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Transporter Name</th>
                            <th>Reg No</th>
                            <th>Driver</th>
                            <th>Driver Contact</th>
                            <th>Truck Type</th>
                            <th>Action</th>

                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($trucks as $truck)
                            <tr>
                                <td> {{ $truck->transporter->name }} </td>
                                <td> {{ $truck->reg_no }} </td>
                                <td> {{ $truck->driver_name }} </td>
                                <td>{{ $truck->driver_contact }}</td>
                                <td>{{ $truck->truck_type }}</td>

                                <td class="row pl-4">
                                    {{-- <a href="{{ route('trucks.edit', $truck->id) }}">
                                        <button class="btn btn-sm btn-info mr-1" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </a> --}}
                                    {{-- <button type="button" class="btn btn-sm btn-danger" data-toggle="modal"
                                        data-target="#delete_floor-{{ $truck->id }}"><i
                                            class="fas fa-trash"></i></button>
                                    <!-- Logout Modal-->
                                    <div class="modal fade" id="delete_floor-{{ $truck->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <p>Are you sure you want to delete {{ $truck->truck }}.
                                                    </p>
                                                </div>
                                                <div class="modal-footer">
                                                    <form action =" {{ route('trucks.destroy', ['truck' => $truck->id]) }}"
                                                        method = "POST">
                                                        @method('DELETE')
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete"
                                                            value="DELETE">YES DELETE <i class="fas fa-trash"></i> </button>
                                                    </form>
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Cancel</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}

                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="add_truck" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document"> <!-- Added modal-lg for wider layout -->
                <div class="modal-content">
                    <div class="modal-header bg-success">

                        <h5 class="modal-title text-white" id="exampleModalLabel"> <strong>Add New Truck for
                                {{ $name }}</strong>
                        </h5>

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('transporter_trucks.store') }} " method="post">
                            {{-- <h6 class="fw-bold text-black">Loading Sheet Information</h6> --}}
                            @csrf
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="reg_no" class="form-label">Reg No</label>
                                    <input type="text" name="reg_no" class="form-control">

                                </div>
                                <div class="col-md-6">
                                    <label for="driver_name" class="form-label">Driver Name</label>
                                    <input type="text" class="form-control" id="driver_name" name="driver_name"
                                        placeholder="driver_name">
                                </div>
                            </div>
                            <div class="row">

                                <div class="mb-3 col-md-6">
                                    <label for="driver_contact" class="form-label">Driver Contact</label>
                                    <input type="text" class="form-control" name="driver_contact" id="driver_contact">
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="id_no" class="form-label">Driver ID Number</label>
                                    <input type="number" name="driver_id_no" maxlength="8" class="form-control">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="truck_type" class="form-label">Truck Type</label>
                                <select class="form-control" id="truck_type" name="truck_type">
                                    <option value="">Select Type</option>
                                    <option value="Van">Van</option>
                                    <option value="Canter">Canter</option>
                                    <option value="Truck">Truck</option>

                                </select>
                                <input type="hidden" name="transporter_id" value={{ $id }}>
                            </div>

                    </div>
                    <div class="modal-footer d-flex justify-content-between ">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close X</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
