@extends('layouts.custom')

@section('content')
    <!-- DataTales Example -->
    <div class="card">
        {{-- Success Message --}}

        <div class="card-header py-3">
            <div class="d-sm-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-danger">Motor Vehicles Lists</h6>
                <a href="/vehicles_report" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm"><i
                        class="fas fa-download fa-sm text-white"></i> Generate Report</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Reg No.</th>
                            <th>Type</th>
                            <th>Tonnage</th>
                            <th>Driver</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Reg No.</th>
                            <th>Type</th>
                            <th>Tonnage</th>
                            <th>Driver</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($vehicles as $vehicle)
                            <tr>
                                <td> {{ $vehicle->regNo }} </td>
                                <td> {{ $vehicle->type }} </td>
                                <td> {{ $vehicle->tonnage }} </td>
                                <td> {{ $vehicle->user->name }} </td>
                                <td> {{ $vehicle->status }} </td>
                                <td class="row pl-4">
                                    <a href="{{ route('vehicles.edit', $vehicle->id) }}">
                                        <button class="btn btn-sm btn-info mr-1" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-primary mr-1" data-toggle="modal" data-target="#allocate_vehicle-{{ $vehicle->id }}">
                                        <i class="fas fa-truck"></i>
                                    </button>
                                    <!-- Modal -->
                                    <div class="modal fade" id="allocate_vehicle-{{ $vehicle->id }}" tabindex="-1" role="dialog" aria-labelledby="allocateVehicleLabel-{{ $vehicle->id }}" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="allocateVehicleLabel-{{ $vehicle->id }}">Allocate Vehicle to Shipment</h5>
                                                    <button type="" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>

                                                <form action="{{ route('vehicles.allocate', $vehicle->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label>Select shipment to allocate to vehicle</label>
                                                            <select name="shipment_id" class="form-control" id="shipment-select-{{ $vehicle->id }}" onchange="enableButton({{ $vehicle->id }})">
                                                                <option value="">Select Shipment</option>
                                                                @foreach ($shipments as $shipment)
                                                                    <option value="{{ $shipment->id }}">{{ $shipment->waybillNo }} - {{ $shipment->senderName }}</option>
                                                                @endforeach
                                                            </select>
                                                            <label>Select driver to allocate to shipment</label>
                                                            <select name="driver" class="form-control" id="shipment-select-{{ $vehicle->id }}" onchange="enableButton({{ $vehicle->id }})">
                                                                <option value="">Select Driver</option>
                                                                @foreach ($drivers as $driver)
                                                               button     <option value="{{ $driver->id }}">{{ $driver->name }} ({{ $driver->status }})</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary" id="allocate-btn-{{ $vehicle->id }}" disabled>Allocate</button>
                                                    </div>
                                                </form>
                                                <script>
                                                function enableButton(vehicleId) {
                                                    const select = document.getElementById(`shipment-select-${vehicleId}`);
                                                    const btn = document.getElementById(`allocate-btn-${vehicleId}`);
                                                    btn.disabled = !select.value;
                                                }
                                                </script>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="button" class="btn btn-sm btn-danger mr-1" data-toggle="modal" data-target="#delete_floor-{{ $vehicle->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <!-- Logout Modal-->
                                    <div class="modal fade" id="delete_floor-{{ $vehicle->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <p>Are you sure you want to delete {{ $vehicle->regNo }}.
                                                    </p>
                                                </div>
                                                <div class="modal-footer">
                                                    <form
                                                        action =" {{ route('vehicles.destroy', ['vehicle' => $vehicle->id]) }}"
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
                                    </div>

                                </td>
                            </tr>
                        @endforeach

                        <!-- Allocation Modal -->
                        <div class="modal fade" id="vehicleAllocationModal" tabindex="-1" aria-labelledby="vehicleAllocationModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="vehicleAllocationModalLabel">Allocate Vehicle</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <form action="/allocate-vehicle" method="POST">
                                @csrf
                                <div class="modal-body">
                                <div class="mb-3">
                                    <label for="vehicle_id" class="form-label">Vehicle</label>
                                    <select name="vehicle_id" id="vehicle_id" class="form-select">
                                    @foreach ($vehicles as $vehicle)
                                        <option value="{{ $vehicle->id }}">{{ $vehicle->name }}</option>
                                    @endforeach
                                    </select>
                                </div>
                                <input type="hidden" name="delivery_id" id="delivery_id">
                                </div>

                                <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-success">Allocate</button>
                                </div>
                            </form>
                            </div>
                        </div>
                        </div>
                        <script>
                            const vehicleModal = document.getElementById('vehicleAllocationModal');
                            vehicleModal.addEventListener('show.bs.modal', function (event) {
                                const button = event.relatedTarget;
                                const deliveryId = button.getAttribute('data-delivery-id');
                                const input = vehicleModal.querySelector('#delivery_id');
                                input.value = deliveryId;
                            });
                        </script>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
