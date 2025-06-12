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
            background-color: #f57f3f;
            ;
            /* Example: Bootstrap primary */
            color: #fff !important;
        }

        .bg-success {
            background-color: #f57f3f !important
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
            border-left: 3px solid #f57f3f;
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
                        <a class="collapse-item" href="{{ route('frontOffice.index') }}">Walk-in</a>
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
                        <div class="mt-2 mx-1">
                            <svg width="180" height="51" viewBox="0 0 180 51" fill="none"
                                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <rect width="180" height="51" fill="url(#pattern0_30_4)" />
                                <defs>
                                    <pattern id="pattern0_30_4" patternContentUnits="objectBoundingBox"
                                        width="1" height="1">
                                        <use xlink:href="#image0_30_4" transform="scale(0.00239234 0.00833333)" />
                                    </pattern>
                                    <image id="image0_30_4" width="418" height="120" preserveAspectRatio="none"
                                        xlink:href="data:image/png;base64,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" />
                                </defs>
                            </svg>
                        </div>
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
                                <img class="img-profile rounded-circle" src="{{ asset('images/jkl.jpg') }}" />
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
                    @stack('scripts')
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

                // get destinations based on origin select

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

                // end get destinations

                // calculate volume

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

                // end calculate volume

                // Total weight calculation and cost update
                function recalculateCosts() {
                    let totalWeight = 0;

                    $('#shipmentTable tbody tr').each(function() {
                        const row = $(this);
                        const weight = parseFloat(row.find('input[name="weight[]"]').val()) || 0;
                        const packages = parseFloat(row.find('input[name="packages[]"]').val()) || 1;
                        totalWeight += weight * packages;
                    });

                    $('input[name="total_weight"]').val(totalWeight.toFixed(2));

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

                $(document).on('click', '.remove-row', function() {
                    const rowCount = $('#shipmentTable tbody tr').length;

                    if (rowCount > 1) {
                        $(this).closest('tr').remove();
                        recalculateCosts();
                    } else {
                        alert("You must have at least one shipment row.");
                    }
                });

            });
        </script>
        <script>
            // Handle form submission
            $(document).on('submit', '#shipmentForm', function(e) {
                e.preventDefault();

                const form = $(this);
                const shipmentId = $('.verify-btn').data('id');
                const formData = form.serialize();

                $.ajax({
                    url: `/update_collections/${shipmentId}`,
                    type: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('input[name="_token"]').val(),
                        'X-HTTP-Method-Override': 'PUT'
                    },
                    success: function(response) {
                        alert('Shipment Collection Verified Successfully!');
                        $('#itemsModal').modal('hide');
                        // Optionally reload the page or update the table
                        location.reload();
                    },
                    error: function(xhr) {
                        alert('Error verifying shipment');
                        console.error(xhr.responseText);
                    }
                });
            });
        </script>
</body>

</html>
