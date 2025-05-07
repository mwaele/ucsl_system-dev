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

        /* Highlight collapsed child item (e.g., inside dropdown) */
        .collapse-item.active {
            font-weight: bold;
            border-left: 3px solid #f5b642;
            ;
        }
    </style>

    <!-- Custom styles for this template-->
    <link href="{{ asset('assets/css/sb-admin-2.min.css') }}" rel="stylesheet" />
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

                <!-- Divider -->


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
                        <a class="collapse-item" href="{{ route('loading_sheets.index') }}">Collections</a>
                    </div>
                </div>
            </li>

            <!-- Divider -->


            <!-- Nav Item - Clients Collapse Menu -->
            <li class="nav-item {{ request()->routeIs('clients.*') ? 'active' : '' }}">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseClients"
                    aria-expanded="true" aria-controls="collapseClients">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Clients</span>
                </a>
                <div id="collapseClients" class="collapse {{ request()->routeIs('clients.*') ? 'active' : '' }}"
                    aria-labelledby="headingClients" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="{{ route('clients.index') }}">All Clients</a>
                        <a class="collapse-item" href="{{ route('clients.create') }}">Add Clients</a>
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
                        <a class="collapse-item" href="{{ route('rates.index') }}">All Rates</a>
                        <a class="collapse-item" href="{{ route('rates.create') }}">Add Rate</a>
                    </div>
                </div>
            </li>
            <!-- Nav Item - Stations Collapse Menu -->
            <li class="nav-item {{ request()->routeIs('stations.*') ? 'active' : '' }}">
                <a class="nav-link {{ request()->routeIs('stations.*') ? '' : 'collapsed' }}" href="#"
                    data-toggle="collapse" data-target="#collapseStations"
                    aria-expanded="{{ request()->routeIs('stations.*') ? 'true' : 'false' }}"
                    aria-controls="collapseStations">
                    <i class="fas fa-fw fa-map"></i>
                    <span>Stations</span>
                </a>
                <div id="collapseStations" class="collapse {{ request()->routeIs('stations.*') ? 'show' : '' }}"
                    aria-labelledby="headingStations" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item {{ request()->routeIs('stations.index') ? 'active' : '' }}"
                            href="{{ route('stations.index') }}">All Stations</a>
                        <a class="collapse-item {{ request()->routeIs('stations.create') ? 'active' : '' }}"
                            href="{{ route('stations.create') }}">Add Station</a>
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

        <!-- Bootstrap core JavaScript-->
        <script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

        <!-- Core plugin JavaScript-->
        <script src="{{ asset('assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

        <!-- Custom scripts for all pages-->
        <script src="{{ asset('assets/js/sb-admin-2.min.js') }}"></script>

        {{-- <!-- Page level plugins -->
        <script src="{{ asset('assets/vendor/chart.js/Chart.min.js') }}"></script>

        <!-- Page level custom scripts -->
        <script src="{{ asset('assets/js/demo/chart-area-demo.js') }}"></script>
        <script src="{{ asset('assets/js/demo/chart-pie-demo.js') }}"></script> --}}

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
                $('#addRowBtn').on('click', function() {
                    let newRow = $('#shipmentTable tbody tr:first').clone();
                    newRow.find('input').val(''); // clear inputs
                    $('#shipmentTable tbody').append(newRow);
                });

                // Delegate event to handle dynamically added rows
                $('#shipmentTable').on('click', '.remove-row', function() {
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
            });
        </script>
</body>

</html>
