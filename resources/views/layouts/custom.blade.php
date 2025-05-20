<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Ufanisi Courier Services Limited Management Information System" />
    <meta name="author" content="ICT" />

    <title>Courier MIS</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css" />


    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet" />
    <style>
        .results tr[visible='false'],
        .no-result {
            display: none;
        }

        .results tr[visible='true'] {
            display: table-row;
        }

        .counter {
            padding: 8px;
            color: #ccc;
        }

        /* Highlight the active link background */
        .nav-item.active>.nav-link,
        .collapse-item.active {
            background-color: #f5b642;
            ;
            /* Example: Bootstrap primary */
            color: #fff !important;
        }

        .bg-success {
            background-color: #f5b642 !important
        }

        /* Optional: icon and text inside nav-link */
        .nav-item.active i,
        .nav-item.active span {
            color: #fff !important;
        }

        label {
            font-size: 20px;
            color: black;
        }

        /* Highlight collapsed child item (e.g., inside dropdown) */
        .collapse-item.active {
            font-weight: bold;
            border-left: 3px solid #f5b642;
            ;
        }
    </style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.1/css/dataTables.bootstrap4.css">

    <!-- Custom styles for this template-->
    <link href="{{ asset('assets/css/sb-admin-2.min.css') }}" rel="stylesheet" />
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <!-- Optional: Flatpickr Bootstrap Theme -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
    <!-- Custom styles for this page -->
    <link href="{{ asset('assets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">

</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
                <div class="sidebar-brand-icon">
                    <i class="fas fa-truck"></i>
                </div>
                <div class="sidebar-brand-text mx-3">UCSL MIS</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0" />

            <!-- Nav Item - Dashboard -->
            <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('dashboard') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="nav-item {{ request()->routeIs('my_collections') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('my_collections.show') }}">
                    <i class="fas fa-fw fa-clipboard"></i>
                    <span>My Collections</span>
                </a>
            </li>

            <!-- Divider -->

            <!-- Nav Item - Clients Collapse Menu -->
            <li class="nav-item {{ request()->routeIs('clients.*') ? 'active' : '' }}">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseClients"
                    aria-expanded="true" aria-controls="collapseClients">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Client Registration</span>
                </a>
                <div id="collapseClients" class="collapse {{ request()->routeIs('clients.*') ? 'active' : '' }}"
                    aria-labelledby="headingClients" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="{{ route('clients.index') }}">All Clients</a>
                        <a class="collapse-item" href="{{ route('clients.create') }}">Add Clients</a>
                    </div>
                </div>
            </li>


            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item {{ request()->routeIs('shipments.*') ? 'active' : '' }}">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-cogs"></i>
                    <span>Shipments</span>
                </a>
                <div id="collapseTwo" class="collapse {{ request()->routeIs('shipments.*') ? 'active' : '' }}"
                    aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="{{ route('shipments.index') }}">All Shipments</a>
                        <a class="collapse-item" href="{{ route('shipments.create') }}">Create New Shipment</a>
                        <a class="collapse-item" href="{{ route('loading_sheets.index') }}">Loading Sheets</a>
                        <a class="collapse-item" href="{{ route('clientRequests.index') }}">Client Requests</a>
                        <a class="collapse-item" href="{{ route('frontOffice.index') }}">Verify Collections</a>
                    </div>
                </div>
            </li>

            <!-- Divider -->


            <!-- Nav Item - Clients Collapse Menu -->
            <li class="nav-item {{ request()->routeIs('company_infos.*') ? 'active' : '' }}">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseCompanyInfo"
                    aria-expanded="true" aria-controls="collapseCompanyInfo">
                    <i class="fas fa-fw fa-building"></i>
                    <span>Company Info</span>
                </a>
                <div id="collapseCompanyInfo"
                    class="collapse {{ request()->routeIs('company_infos.*') ? 'active' : '' }}"
                    aria-labelledby="headingCompanyInfo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="{{ route('company_infos.index') }}">All Company Info</a>
                        <a class="collapse-item" href="{{ route('company_infos.create') }}">Add Company Info</a>
                    </div>
                </div>
            </li>



            <!-- Nav Item - Clients Collapse Menu -->
            <li class="nav-item {{ request()->routeIs('offices.*') ? 'active' : '' }}">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseOffice"
                    aria-expanded="true" aria-controls="collapseOffice">
                    <i class="fas fa-fw fa-home"></i>
                    <span>Office</span>
                </a>
                <div id="collapseOffice" class="collapse {{ request()->routeIs('offices.*') ? 'active' : '' }}"
                    aria-labelledby="headingCompanyInfo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="{{ route('offices.index') }}">All Offices</a>
                        <a class="collapse-item" href="{{ route('offices.create') }}">Add Office</a>
                    </div>
                </div>
            </li>

            <!-- Divider -->


            <!-- Nav Item - Vehicles Collapse Menu -->
            <li class="nav-item {{ request()->routeIs('vehicles.*') ? 'active' : '' }}">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseVehicles"
                    aria-expanded="true" aria-controls="collapseVehicles">
                    <i class="fas fa-fw fa-truck"></i>
                    <span>Vehicles</span>
                </a>
                <div id="collapseVehicles" class="collapse {{ request()->routeIs('vehicles.*') ? 'active' : '' }}"
                    aria-labelledby="headingVehicles" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="{{ route('vehicles.index') }}">All Vehicles</a>
                        <a class="collapse-item" href="{{ route('vehicles.create') }}">Add Vehicles</a>
                        <a class="collapse-item" href="{{ route('vehicles.create') }}"> Vehicle Allocation</a>
                    </div>
                </div>
            </li>

            <!-- Divider -->


            <!-- Nav Item - Rates Collapse Menu -->
            <li class="nav-item {{ request()->routeIs('rates.*') ? 'active' : '' }}">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseRates"
                    aria-expanded="true" aria-controls="collapseRates">
                    <i class="fas fa-fw fa-dollar-sign"></i>
                    <span>Rates</span>
                </a>
                <div id="collapseRates" class="collapse {{ request()->routeIs('rates.*') ? 'active' : '' }}"
                    aria-labelledby="headingRates" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="{{ route('rates.mombasa_office') }}">Mombasa Rates</a>
                        <a class="collapse-item" href="{{ route('rates.nairobi_office') }}">Nairobi Rates</a>
                        <a class="collapse-item" href="{{ route('rates.create') }}">Add Rate</a>

                        <a class="collapse-item" href="{{ route('rates.index') }}">All Rates</a>
                    </div>
                </div>
            </li>
            <!-- Nav Item - Zones Collapse Menu -->
            <li class="nav-item {{ request()->routeIs('zones.*') ? 'active' : '' }}">
                <a class="nav-link {{ request()->routeIs('zones.*') ? '' : 'collapsed' }}" href="#"
                    data-toggle="collapse" data-target="#collapseZones"
                    aria-expanded="{{ request()->routeIs('zones.*') ? 'true' : 'false' }}"
                    aria-controls="collapseZones">
                    <i class="fas fa-fw fa-map"></i>
                    <span>Zones</span>
                </a>
                <div id="collapseZones" class="collapse {{ request()->routeIs('zones.*') ? 'show' : '' }}"
                    aria-labelledby="headingZones" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="{{ route('zones.index') }}">All Zones</a>
                        <a class="collapse-item " href="{{ route('zones.create') }}">Add Station</a>
                    </div>
                </div>
            </li>


            <!-- Nav Item - Pages Collapse Menu -->


            <!-- Nav Item - Charts -->
            {{-- <li class="nav-item">
                <a class="nav-link" href="charts.html">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Charts</span></a>
            </li> --}}

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block" />

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>


                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->

                        <!-- Nav Item - Alerts -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell fa-fw"></i>
                                <!-- Counter - Alerts -->
                                <span class="badge badge-danger badge-counter">3+</span>
                            </a>
                            <!-- Dropdown - Alerts -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="alertsDropdown">
                                <h6 class="dropdown-header">Alerts Center</h6>

                                <a class="dropdown-item text-center small text-gray-500" href="#">Show All
                                    Alerts</a>
                            </div>
                        </li>

                        <!-- Nav Item - Messages -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-envelope fa-fw"></i>
                                <!-- Counter - Messages -->
                                <span class="badge badge-danger badge-counter">7</span>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="messagesDropdown">
                                <h6 class="dropdown-header">Message Center</h6>

                                <a class="dropdown-item text-center small text-gray-500" href="#">Read More
                                    Messages</a>
                            </div>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span
                                    class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name }}</span>
                                <img class="img-profile rounded-circle" src="img/undraw_profile.svg" />
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Activity Log
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal"
                                    data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid h-100">
                    <!-- Page Heading -->
                    @yield('content')
                    <!-- /.container-fluid -->
                </div>
                <!-- End of Main Content -->

                <!-- Footer -->
                <footer class="sticky-footer bg-white">
                    @if (session('success'))
                        <div id="flash-message" class="alert alert-success text-center p-2">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div id="flash-message" class="alert alert-danger text-center p-2">
                            {{ session('error') }}
                        </div>
                    @endif
                    <div class="container my-auto">
                        <div class="copyright text-center my-auto">
                            <span>Copyright &copy; www.ufanisicourier.co.ke</span>
                        </div>
                    </div>
                </footer>
                <!-- End of Footer -->
            </div>
            <!-- End of Content Wrapper -->
        </div>
        <!-- End of Page Wrapper -->

        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>

        <!-- Logout Modal-->
        <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Select "Logout" below if you are ready to end your current session.
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">
                            Cancel
                        </button>
                        <a class="btn btn-primary" href="login.html">Logout</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Datatable JS -->
        <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script src="https://cdn.datatables.net/2.3.1/js/dataTables.js"></script>
        <script src="https://cdn.datatables.net/2.3.1/js/dataTables.bootstrap4.js"></script>

        <!-- Bootstrap core JavaScript-->
        <script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

        <!-- Core plugin JavaScript-->
        <script src="{{ asset('assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

        <!-- Custom scripts for all pages-->
        <script src="{{ asset('assets/js/sb-admin-2.min.js') }}"></script>

        <!-- Page level plugins -->
        <script src="{{ asset('assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

        {{-- <!-- Moment.js (required for legacy versions) -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>

        <!-- Bootstrap core JavaScript-->
        <script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

        <!-- Core plugin JavaScript-->
        <script src="{{ asset('assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

        <!-- Custom scripts for all pages-->
        <script src="{{ asset('assets/js/sb-admin-2.min.js') }}"></script> --}}

        <!-- Flatpickr JS -->
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

        {{-- <!-- Page level plugins -->
        <script src="{{ asset('assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script> --}}

        <script>
            // Initialize Flatpickr
            flatpickr("#datetime", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
            });
        </script>

        <script>
            // Initialise the datatable
            new DataTable('#ucsl-table', {
                paging: true,
                scrollCollapse: true,
                scrollY: '50vh'
            });
        </script>


        {{-- <!-- Page level plugins -->
        <script src="{{ asset('assets/vendor/chart.js/Chart.min.js') }}"></script>

        <!-- Page level custom scripts -->
        <script src="{{ asset('assets/js/demo/chart-area-demo.js') }}"></script>
        <script src="{{ asset('assets/js/demo/chart-pie-demo.js') }}"></script> --}}

        <script>
            // document.addEventListener('DOMContentLoaded', function() {
            //     new tempusDominus.TempusDominus(document.getElementById('kt_datetimepicker_1'), {
            //         display: {
            //             components: {
            //                 calendar: true,
            //                 date: true,
            //                 month: true,
            //                 year: true,
            //                 decades: true,
            //                 clock: true,
            //                 hours: true,
            //                 minutes: true,
            //                 seconds: false
            //             }
            //         },
            //         localization: {
            //             format: 'yyyy-MM-DD HH:mm'
            //         }
            //     });
            // });
        </script>

        <script>
            $('#station_name').on('focusout', function() {
                let station_name = $(this).val();

                if (station_name !== '') {
                    $.ajax({
                        url: '/check_station_name',
                        method: 'POST',
                        data: {
                            station_name: station_name,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.exists) {
                                $('#station_name_feedback').text('Station Name already saved').css('color',
                                    'red');
                                $('#submit-btn').prop('disabled', true);
                            } else {
                                $('#station_name_feedback').text('Station Name available').css('color',
                                    'green');
                                $('#submit-btn').prop('disabled', false);
                            }
                        }
                    });
                } else {
                    $('#station_name_feedback').text('');
                    $('#submit-btn').prop('disabled', true);
                }
            });
        </script>
        <script>
            // Wait for DOM to load
            document.addEventListener("DOMContentLoaded", function() {
                const flash = document.getElementById("flash-message");
                if (flash) {
                    setTimeout(() => {
                        flash.style.transition = "all 0.5s ease";
                        flash.style.transform = "translateY(-100%)";
                        flash.style.opacity = 0;

                        setTimeout(() => {
                            flash.remove();
                        }, 500);
                    }, 3000); // 3 seconds delay
                }
            });
        </script>
        <script>
            $(document).ready(function() {
                $('.addRowBtn').on('click', function() {
                    let newRow = $('#shipmentTable tbody tr:first').clone();
                    newRow.find('input').val(''); // clear inputs
                    $('.shipmentTable tbody').append(newRow);
                });

                // Delegate event to handle dynamically added rows
                $('.shipmentTable').on('click', '.remove-row', function() {
                    if ($('#shipmentTable tbody tr').length > 1) {
                        $(this).closest('tr').remove();
                    }
                });
                // Initialize flatpickr datetime picker
                $(".datetime").flatpickr({
                    enableTime: true,
                    dateFormat: "Y-m-d H:i"
                });

                // Add new row to shipment table
                // $('#addRowBtn').click(function() {
                //     $('#shipmentTable tbody').append(`
        //       <tr>
        //         <td><input type="number" class="form-control" name="packages[]"></td>
        //         <td><input type="number" class="form-control" name="weight[]"></td>
        //         <td><input type="number" class="form-control" name="length[]"></td>
        //         <td><input type="number" class="form-control" name="width[]"></td>
        //         <td><input type="number" class="form-control" name="height[]"></td>
        //         <td><button type="button" class="btn btn-danger btn-sm remove-row">Remove</button></td>
        //       </tr>
        //     `);
                // });

                // // Remove row
                // $(document).on('click', '.remove-row', function() {
                //     $(this).closest('tr').remove();
                // });

                // $('#origin').on('change', function() {
                //     let officeId = $(this).val();

                //     $('#destination').html('<option value="">Select</option>'); // Reset

                //     if (officeId) {
                //         $.get('/get-destinations/' + officeId, function(data) {
                //             data.forEach(function(item) {
                //                 $('#destination').append(
                //                     `<option value="${item.destination}">${item.destination}</option>`
                //                 );
                //             });
                //         });
                //     }
                // });
                $(document).on('change', '.origin-dropdown', function() {
                    const originSelect = $(this);
                    const selectedOfficeId = originSelect.val();
                    const modal = originSelect.closest('.modal');
                    const destinationSelect = modal.find('.destination-dropdown');

                    destinationSelect.html('<option value="">Select Destination</option>');

                    if (selectedOfficeId) {
                        $.get('/get-destinations/' + selectedOfficeId)
                            .done(function(data) {
                                data.forEach(function(item) {
                                    destinationSelect.append(
                                        `<option data-id="${item.id}" value="${item.destination}">${item.destination}</option>`
                                    );
                                });
                            })
                            .fail(function() {
                                console.error("Failed to load destinations");
                            });
                    }
                });

                function calculateVolume(row) {
                    const length = parseFloat(row.find('input[name="length[]"]').val()) || 0;
                    const width = parseFloat(row.find('input[name="width[]"]').val()) || 0;
                    const height = parseFloat(row.find('input[name="height[]"]').val()) || 0;

                    if (length && width && height) {
                        const volume = length * width * height;
                        row.find('input[name="volume[]"]').val(volume.toFixed(2));
                    } else {
                        row.find('input[name="volume[]"]').val('');
                    }
                }

                // Total weight calculation and cost update
                function recalculateCosts() {
                    let totalWeight = 0;

                    $('#shipmentTable tbody tr').each(function() {
                        const row = $(this);
                        const weight = parseFloat(row.find('input[name="weight[]"]').val()) || 0;
                        const packages = parseFloat(row.find('input[name="packages[]"]').val()) || 1;
                        totalWeight += weight * packages;
                    });

                    const baseCost = parseFloat($('input[name="base_cost"]').val()) || 0;
                    let cost = baseCost;

                    if (totalWeight > 25) {
                        const extraWeight = totalWeight - 25;
                        cost += extraWeight * 50;
                    }

                    $('input[name="cost"]').val(cost.toFixed(2));

                    const vat = cost * 0.16;
                    $('input[name="vat"]').val(vat.toFixed(2));
                    $('input[name="total_cost"]').val((cost + vat).toFixed(2));
                }

                // Watch for changes in volume dimensions
                $(document).on('input', 'input[name="length[]"], input[name="width[]"], input[name="height[]"]',
                    function() {
                        const row = $(this).closest('tr');
                        calculateVolume(row);
                    });

                // Watch for changes in weight or packages
                $(document).on('input', 'input[name="weight[]"], input[name="packages[]"]', function() {
                    recalculateCosts();
                });

                // Trigger when destination changes
                $(document).on('change', '.destination-dropdown', function() {
                    const destinationId = $(this).val();
                    const selectedOption = $(this).find('option:selected');
                    const destination_id = selectedOption.data('id');
                    $("#destination_id").val(destination_id);
                    const modal = $(this).closest('form'); // Adjust if you're using modal or form wrapper
                    const originId = modal.find('.origin-dropdown').val();

                    if (originId && destinationId) {
                        $.get(`/get-cost/${originId}/${destinationId}`)
                            .done(function(data) {
                                const baseCost = parseFloat(data.cost);
                                $('input[name="base_cost"]').val(baseCost);
                                recalculateCosts();
                            })
                            .fail(function() {
                                console.error("Failed to fetch base cost");
                                $('input[name="base_cost"]').val(0);
                            });
                    }
                });

                // Add row functionality
                // $('#addRowBtn').on('click', function () {
                //     const newRow = `
        //     <tr>
        //         <td><input type="text" class="form-control" name="item[]"></td>
        //         <td><input type="number" min="0" max="100" class="form-control" name="packages[]"></td>
        //         <td><input type="number" min="0" max="100" class="form-control" name="weight[]"></td>
        //         <td><input type="number" min="0" max="100" class="form-control" name="length[]"></td>
        //         <td><input type="number" min="0" max="100" class="form-control" name="width[]"></td>
        //         <td><input type="number" min="0" max="100" class="form-control" name="height[]"></td>
        //         <td class="volume-display text-muted"><input type="number" min="0" max="100" class="form-control" name="volume[]"></td>
        //         <td><button type="button" class="btn btn-danger btn-sm remove-row">Remove</button></td>
        //     </tr>`;
                //     $('#shipmentTable tbody').append(newRow);
                // });

                // Remove row functionality
                $(document).on('click', '.remove-row', function() {
                    const rowCount = $('#shipmentTable tbody tr').length;

                    if (rowCount > 1) {
                        $(this).closest('tr').remove();
                        recalculateCosts();
                    } else {
                        alert("You must have at least one shipment row.");
                    }
                });


                // When destination dropdown changes, query cost
                // $(document).on('change', '.destination-dropdown', function() {
                //     const destinationSelect = $(this);
                //     const destinationId = destinationSelect.val();
                //     console.log('destination:' +
                //         destinationId);
                //     const modal = destinationSelect.closest('.modal');
                //     const originId = modal.find('.origin-dropdown').val();

                //     console.log('Origin Id:' + originId);
                //     const costDisplay = modal.find('.cost-display'); // element to show cost

                //     costDisplay.text(''); // Clear cost first

                //     if (originId && destinationId) {
                //         $.get(`/get-cost/${originId}/${destinationId}`)
                //             .done(function(data) {
                //                 costDisplay.text(`Cost: ${data.cost}`);
                //             })
                //             .fail(function() {
                //                 console.error("Failed to load cost");
                //             });
                //     }
                // });
                // $(document).on('change', '.destination-dropdown', function() {
                //     const destinationSelect = $(this);
                //     const destinationId = destinationSelect.val();
                //     const modal = destinationSelect.closest('.modal');
                //     const originId = modal.find('.origin-dropdown').val();
                //     const costDisplay = modal.find('.cost-display'); // Display element
                //     const costInput = modal.find('input[name="cost"]');
                //     const vatInput = modal.find('input[name="vat"]');
                //     const totalCostInput = modal.find('input[name="total_cost"]');

                //     costDisplay.text(''); // Clear cost first

                //     if (originId && destinationId) {
                //         $.get(`/get-cost/${originId}/${destinationId}`)
                //             .done(function(data) {
                //                 let baseCost = parseFloat(data.cost);

                //                 // Calculate weight from inputs
                //                 let totalWeight = 0;
                //                 modal.find('input[name="weight[]"]').each(function() {
                //                     totalWeight += parseFloat($(this).val()) || 0;
                //                 });

                //                 if (totalWeight > 25) {
                //                     const extraKg = totalWeight - 25;
                //                     baseCost += extraKg * 50;
                //                 }

                //                 // Update the UI
                //                 costDisplay.text(`Cost: ${baseCost.toFixed(2)}`);
                //                 if (costInput.length) costInput.val(baseCost.toFixed(2));

                //                 const vat = baseCost * 0.16;
                //                 const totalCost = baseCost + vat;

                //                 if (vatInput.length) vatInput.val(vat.toFixed(2));
                //                 if (totalCostInput.length) totalCostInput.val(totalCost.toFixed(2));
                //             })
                //             .fail(function() {
                //                 console.error("Failed to load cost");
                //             });
                //     }
                // });

                // Recalculate Cost, VAT, and Total Cost
                // function recalculateCosts(modal) {
                //     const costInput = modal.find('input[name="cost"]');
                //     const vatInput = modal.find('input[name="vat"]');
                //     const totalCostInput = modal.find('input[name="total_cost"]');

                //     let baseCost = parseFloat(costInput.val()) || 0;

                //     // Calculate total weight
                //     let totalWeight = 0;
                //     modal.find('input[name="weight[]"]').each(function() {
                //         totalWeight += parseFloat($(this).val()) || 0;
                //         console.log('total weight' + totalWeight);
                //     });

                //     // Apply additional charge: 50 per kg over 25kg
                //     let finalCost = baseCost;
                //     if (totalWeight > 25) {
                //         const extraKg = totalWeight - 25;
                //         console.log('extra kg' + extraKg)

                //         console.log('base cost' + baseCost)
                //         finalCost = baseCost + (extraKg * 50);
                //     }

                //     // Update inputs
                //     costInput.val(finalCost.toFixed(2));

                //     const vat = finalCost * 0.16;
                //     const totalCost = finalCost + vat;

                //     vatInput.val(vat.toFixed(2));
                //     totalCostInput.val(totalCost.toFixed(2));
                // }

                // Trigger when destination changes
                // $(document).on('change', '.destination-dropdown', function() {
                //     const destinationSelect = $(this);
                //     const destinationId = destinationSelect.val();
                //     const modal = destinationSelect.closest('.modal');
                //     const originId = modal.find('.origin-dropdown').val();

                //     const costDisplay = modal.find('.cost-display');
                //     const costInput = modal.find('input[name="cost"]');

                //     costDisplay.text('');

                //     if (originId && destinationId) {
                //         $.get(`/get-cost/${originId}/${destinationId}`)
                //             .done(function(data) {
                //                 const baseCost = parseFloat(data.cost);
                //                 costDisplay.text(`Base Cost: ${baseCost.toFixed(2)}`);
                //                 costInput.val(baseCost.toFixed(2));
                //                 recalculateCosts(modal);
                //             })
                //             .fail(function() {
                //                 console.error("Failed to load cost");
                //                 costInput.val('');
                //             });
                //     }
                // });

                // // Trigger when any weight changes
                // $(document).on('input', 'input[name="weight[]"]', function() {
                //     const modal = $(this).closest('.modal');
                //     recalculateCosts(modal);
                // });

                // Global

                // function to recalculate based on fixed baseCost

                // function recalculateCosts(modal) {
                //     const baseCost = parseFloat(modal.find('input[name="base_cost"]').val()) || 0;

                //     // Sum total weight
                //     let totalWeight = 0;
                //     modal.find('input[name="weight[]"]').each(function() {
                //         totalWeight += parseFloat($(this).val()) || 0;
                //     });

                //     // Determine new cost based on weight
                //     let cost = baseCost;
                //     if (totalWeight > 25) {
                //         cost = baseCost + ((totalWeight - 25) * 50);
                //     }

                //     const vat = cost * 0.16;
                //     const totalCost = cost + vat;

                //     // Set calculated fields
                //     modal.find('input[name="cost"]').val(cost.toFixed(2));
                //     modal.find('input[name="vat"]').val(vat.toFixed(2));
                //     modal.find('input[name="total_cost"]').val(totalCost.toFixed(2));
                // }

                // // On destination change — fetch base cost and set base_cost field
                // $(document).on('change', '.destination-dropdown', function() {
                //     const destinationSelect = $(this);
                //     const destinationId = destinationSelect.val();
                //     const modal = destinationSelect.closest('.modal');
                //     const originId = modal.find('.origin-dropdown').val();

                //     const baseCostInput = modal.find('input[name="base_cost"]');
                //     const costDisplay = modal.find('.cost-display');

                //     costDisplay.text('');

                //     if (originId && destinationId) {
                //         $.get(`/get-cost/${originId}/${destinationId}`)
                //             .done(function(data) {
                //                 const baseCost = parseFloat(data.cost);
                //                 baseCostInput.val(baseCost.toFixed(2));
                //                 costDisplay.text(`Base Cost: ${baseCost.toFixed(2)}`);

                //                 // Trigger cost calculation based on this base
                //                 recalculateCosts(modal);
                //             })
                //             .fail(function() {
                //                 console.error("Failed to fetch cost");
                //                 baseCostInput.val('');
                //             });
                //     }
                // });

                // // On weight change — recalculate based on stored base_cost
                // $(document).on('input', 'input[name="weight[]"]', function() {
                //     const modal = $(this).closest('.modal');
                //     recalculateCosts(modal);
                // });

                // // Trigger when destination changes
                // $(document).on('change', '.destination-dropdown', function() {
                //     const destinationSelect = $(this);
                //     const destinationId = destinationSelect.val();
                //     const modal = destinationSelect.closest('.modal');
                //     const originId = modal.find('.origin-dropdown').val();

                //     const costDisplay = modal.find('.cost-display');
                //     const costInput = modal.find('input[name="cost"]');
                //     const baseCostInput = modal.find('input[name="base_cost"]'); // hidden input

                //     costDisplay.text('');
                //     costInput.val('');
                //     baseCostInput.val('');

                //     if (originId && destinationId) {
                //         $.get(`/get-cost/${originId}/${destinationId}`)
                //             .done(function(data) {
                //                 const baseCost = parseFloat(data.cost);
                //                 costDisplay.text(`Base Cost: ${baseCost.toFixed(2)}`);

                //                 // Set both cost and base_cost fields
                //                 baseCostInput.val(baseCost.toFixed(2));
                //                 costInput.val(baseCost.toFixed(2));

                //                 recalculateCosts(modal);
                //             })
                //             .fail(function() {
                //                 console.error("Failed to load cost");
                //                 costInput.val('');
                //                 baseCostInput.val('');
                //             });
                //     }
                // });

                // function recalculateCosts(modal) {
                //     let totalWeight = 0;
                //     const baseCost = parseFloat(modal.find('input[name="base_cost"]').val()) || 0;

                //     modal.find('input[name="weight[]"]').each(function(index) {
                //         const weight = parseFloat($(this).val()) || 0;
                //         const packages = parseInt(modal.find('input[name="packages[]"]').eq(index).val()) || 1;
                //         totalWeight += weight * packages;
                //     });

                //     let cost = baseCost;
                //     if (totalWeight > 25) {
                //         cost += (totalWeight - 25) * 50;
                //     }

                //     const vat = cost * 0.16;
                //     const totalCost = cost + vat;

                //     modal.find('input[name="cost"]').val(cost.toFixed(2));
                //     modal.find('input[name="vat"]').val(vat.toFixed(2));
                //     modal.find('input[name="total_cost"]').val(totalCost.toFixed(2));
                // }






            });
        </script>
</body>

</html>
