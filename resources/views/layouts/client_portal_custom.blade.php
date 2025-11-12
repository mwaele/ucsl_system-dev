<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Ufanisi Courier Services Limited Parcel Management System" />
    <meta name="author" content="ICT" />
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <title>U-PARMS</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Bootstrap Multiselect CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-multiselect.css') }}">

    {{-- Chart.js --}}
    <script src="{{ asset('assets/js/chart.js') }}"></script>



    <!-- Bootstrap Select CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-select.min.css') }}">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">

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

        .table .text-primary {
            color: #14489f !important;
        }

        sidebar-divide {
            border: 2px solid #14489f !important;
        }

        /* Highlight collapsed child item (e.g., inside dropdown) */
        .collapse-item.active {
            font-weight: bold;
            border-left: 3px solid #f57f3f;
            ;
        }

        .sized {
            font-size: 18px !important;
        }
    </style>


    <!-- Custom styles for this template-->
    <link href="{{ asset('assets/css/sb-admin-2.min.css') }}" rel="stylesheet" />
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/flatpickr/flatpickr.min.css') }}">

    <!-- Optional: Flatpickr Bootstrap Theme -->
    {{-- <link rel="stylesheet" href="{{ asset('assets/flatpickr/material_blue.css') }}"> --}}
    <!-- Custom styles for this page -->
    <link href="{{ asset('assets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">

    <style>
        /* Default for large screens */
        .navbar-bg {
            background-image: url('{{ asset('images/U-Parkms Orange-1.jpg') }}');
            background-size: auto 100%;
            /* fills nav keeping aspect ratio */
            background-position: left;
            background-repeat: no-repeat;
        }

        html,
        body {
            height: 100%;
            margin: 0;
        }

        #wrapper {
            display: flex;
            min-height: 100vh;
        }

        #content-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        #content {
            flex: 1;
        }


        /* Medium screens (tablets) */
        @media (max-width: 992px) {
            .navbar-bg {
                background-size: auto 100%;
                /* still cover, adjusts automatically */
            }
        }

        /* Small screens (phones) */
        @media (max-width: 576px) {
            .navbar-bg {
                background-size: auto 100%;
                /* force-fit on very small screens */
            }
        }
    </style>

    <!-- Datatable JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.min.js"></script> --}}
    <script src="https://cdn.datatables.net/2.3.3/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.3.3/js/dataTables.bootstrap4.js"></script>
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css"> --}}
    <script src="https://cdn.datatables.net/2.3.3/css/dataTables.bootstrap4.css"></script>

    <!-- Include DataTables + Buttons CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">

    <!-- Include DataTables + Buttons JS -->
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>


    <!-- Core plugin JavaScript-->
    <script src="{{ asset('assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>


    <!-- Bootstrap Select JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center bg-white shadow"
                href="{{ route('client_portal') }}">
                <img src="{{ asset('images/UCSLogo1.png') }}" alt="" height="50" width="auto"
                    class="image-fluid">

            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0" />

            <!-- Nav Item - Dashboard -->
            <li class="nav-item {{ request()->routeIs('client_portal') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('client_portal') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span class="sized" class="sized">Dashboard</span>
                </a>
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider my-0" />

            <!-- Nav Item - Pages Collapse Menu -->

            <!-- Nav Item - Shipments -->
            <li class="nav-item {{ request()->routeIs('overnight_onaccount', 'sameday_on_account') ? 'active' : '' }}">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseShipments"
                    aria-expanded="true" aria-controls="collapseShipments">
                    <i class="fas fa-fw fa-cogs"></i>
                    <span class="sized">Shipments</span>
                </a>

                <div id="collapseShipments" class="collapse {{ request()->routeIs('shipments.*') ? 'show' : '' }}"
                    aria-labelledby="headingShipments" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item"
                            href="{{ route('overnight_onaccount', ['type' => 'client_portal']) }}">Overnight - On
                            Account</a>
                        <hr class="sidebar-divide my-0" />
                        <a class="collapse-item"
                            href="{{ route('sameday_on_account', ['type' => 'client_portal']) }}">SameDay - On
                            Account</a>

                        <hr class="sidebar-divide my-0" />




                    </div>
                </div>
            </li>


            <!-- Divider -->
            <hr class="sidebar-divider my-0" />

            <li class="nav-item {{ request()->routeIs('tracking.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('tracking.index') }}">
                    <i class="fas fa-fw fa-clipboard"></i>
                    <span class="sized">Tracking</span>
                </a>
            </li>

            {{-- <li class="nav-item {{ request()->routeIs('tracking.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('tracking.index') }}">
                    <i class="fas fa-fw fa-clipboard"></i>
                    <span class="sized">Rate Our Services</span>
                </a>
            </li> --}}

            <!-- Divider -->
            <hr class="sidebar-divider my-0" />

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
                <nav
                    class="navbar navbar-expand navbar-light bg-gradient-primary topbar mb-4 static-top shadow navbar-bg">
                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    {{-- <img src="{{ asset('images/U-Parkms Orange-1.jpg') }}" alt="" class="image-fluid"> --}}


                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">


                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <!-- Nav Item - Alerts -->
                        <li class="nav-item dropdown no-arrow mx-1 text-white ">
                            <a class="nav-link dropdown-toggle text-white" href="#" id="alertsDropdown"
                                role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell fa-fw"></i>
                                <!-- Counter - Alerts -->
                                <span class="badge badge-primary badge-counter">0</span>
                            </a>
                            <!-- Dropdown - Alerts -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="alertsDropdown">
                                <h6 class="dropdown-header">Alerts Center</h6>

                                <a class="dropdown-item text-center small text-primary" href="#">Show All
                                    Alerts</a>
                            </div>
                        </li>

                        <!-- Nav Item - Messages -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle text-white" href="#" id="messagesDropdown"
                                role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-envelope fa-fw"></i>
                                <!-- Counter - Messages -->
                                <span class="badge badge-primary badge-counter">0</span>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="messagesDropdown">
                                <h6 class="dropdown-header">Message Center</h6>

                                <a class="dropdown-item text-center small text-primary" href="#">Read More
                                    Messages</a>
                            </div>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>



                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-white ">

                                    {{ auth('client')->user()->name }}
                                </span>
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
                                <a class="dropdown-item logout">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>
                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid text-dark ">
                    <!-- Page Heading -->
                    @yield('content')
                    @stack('scripts')
                    <!-- /.container-fluid -->
                </div>
                <!-- End of Main Content -->

                @php
                    $toastTypes = [
                        'success' => ['bg' => 'bg-success', 'title' => 'Success'],
                        'error' => ['bg' => 'bg-danger', 'title' => 'Error'],
                        'warning' => ['bg' => 'bg-warning', 'title' => 'Warning'],
                        'info' => ['bg' => 'bg-info', 'title' => 'Info'],
                    ];
                @endphp

                @foreach ($toastTypes as $type => $props)
                    @if (session($type))
                        <div aria-live="polite" aria-atomic="true" class="position-fixed mr-3"
                            style="top: 1rem; right: 1rem; z-index: 400;">
                            <div class="toast show timeout-toast {{ $props['bg'] }} text-white mt-5" role="alert"
                                aria-live="assertive" aria-atomic="true">
                                <div class="toast-header {{ $props['bg'] }} text-white">
                                    <strong class="mr-auto">{{ $props['title'] }}</strong>
                                    <small>Just now</small>
                                    <button type="button" class="ml-2 mb-1 close text-white" data-dismiss="toast"
                                        aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="toast-body">
                                    {{ session($type) }}
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach

                <!-- Footer -->
                <footer class="sticky-footer bg-white">
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
                    <div class="modal-header bg-success">
                        <h5 class="modal-title text-white" id="exampleModalLabel">Ready to Leave?</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            Select "Logout" below if you are ready to end your current session.
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger" type="button" data-dismiss="modal">Cancel</button>

                        <button class="btn btn-primary" type="submit">Logout</button>

                    </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            $('.logout').on('click', function(e) {
                e.preventDefault();

                // Optional confirmation
                if (!confirm("Are you sure you want to logout?")) return;

                // Send logout request to Laravel
                $.ajax({
                    url: "/client/logout", // You can also use route() if you're in Blade
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        // Optional: Clear localStorage
                        localStorage.clear();

                        // Redirect after logout
                        window.location.href = "/tracking"; // Or wherever you want
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        alert('Logout failed. Try again.');
                    }
                });
            });
        </script>



        <!-- Bootstrap Multiselect JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap-multiselect@1.1.0/dist/js/bootstrap-multiselect.min.js"></script>

        <!-- Custom scripts for all pages-->
        <script src="{{ asset('assets/js/sb-admin-2.min.js') }}"></script>

        <!-- Page level plugins -->
        <script src="{{ asset('assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>


        <!-- Flatpickr JS -->
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

        <!-- Bootstrap Select JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>


        <!-- Toast JS -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const toasts = document.querySelectorAll('.timeout-toast');
                toasts.forEach(toast => {
                    setTimeout(() => {
                        toast.classList.remove('show');
                        toast.classList.add('fade');
                        setTimeout(() => toast.remove(), 300);
                    }, 3000);
                });
            });
        </script>

        <script>
            const paymentMode = document.getElementById('payment_mode');
            const reference = document.getElementById('reference');

            paymentMode.addEventListener('change', function() {
                if (this.value === 'M-Pesa') {
                    reference.placeholder = "e.g. TH647CDTNA";
                    reference.maxLength = 10;
                    // Require 10 uppercase letters/numbers with at least one digit
                    reference.pattern = "^(?=.*\\d)[A-Z0-9]{10}$";
                    reference.title =
                        "Enter a 10-character M-Pesa code (capital letters & numbers, at least one number, no spaces or special characters)";
                    reference.classList.add('text-uppercase');

                    // Restrict and format input
                    reference.oninput = function() {
                        this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '').slice(0, 10);
                    };
                } else {
                    reference.placeholder = "Enter reference";
                    reference.removeAttribute('maxlength');
                    reference.removeAttribute('pattern');
                    reference.removeAttribute('title');
                    reference.classList.remove('text-uppercase');

                    // Remove restrictions
                    reference.oninput = null;
                }
            });
        </script>

        <script>
            // Initialize Flatpickr
            flatpickr("#datetime", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
            });
        </script>

        <script>
            function setMinDateTime() {
                const now = new Date();
                const datetimeLocal = document.getElementById("deadline_date");

                // Format YYYY-MM-DDTHH:MM
                const year = now.getFullYear();
                const month = String(now.getMonth() + 1).padStart(2, '0');
                const day = String(now.getDate()).padStart(2, '0');
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');

                datetimeLocal.min = `${year}-${month}-${day}T${hours}:${minutes}`;
            }

            document.addEventListener("DOMContentLoaded", setMinDateTime);

            // Update min whenever the field is focused
            document.getElementById("deadline_date").addEventListener("focus", setMinDateTime);

            // Optional: validate before form submit
            document.querySelector("form")?.addEventListener("submit", function(e) {
                const deadline = document.getElementById("deadline_date").value;
                if (deadline && new Date(deadline) < new Date()) {
                    alert("Please select a time from now going forward.");
                    e.preventDefault();
                }
            });
        </script>

        <script>
            new DataTable('#ucsl-table', {
                language: {
                    lengthMenu: 'Show _MENU_ Entries Per Page', // Capitalized 'Entries'
                    info: 'Showing _START_ to _END_ of _TOTAL_ Entries', // Capitalized 'Entries'
                    search: 'Search:', // Optional: keep or customize
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    }
                },
                initComplete: function() {

                    this.api()
                        .columns()
                        .every(function(index) {
                            // Skip first and last columns
                            if (index === 0 || index === this.columns().count() - 1) {
                                return;
                            }

                            let column = this;

                            // Create select element
                            let select = document.createElement('select');
                            select.classList.add('form-control', 'form-select');
                            select.add(new Option(''));
                            column.footer().replaceChildren(select);

                            // Apply listener for user change in value
                            select.addEventListener('change', function() {
                                // Escape regex special characters in the value
                                let val = select.value.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
                                column
                                    .search(val ? val : '', true,
                                        false) // true: regex, false: smart search
                                    .draw();
                            });

                            // Extract visible text from each cell (not HTML)
                            let uniqueOptions = [];
                            column.nodes().each(function(cell) {
                                let text = $(cell).text().trim();
                                if (text && !uniqueOptions.includes(text)) {
                                    uniqueOptions.push(text);
                                }
                            });

                            uniqueOptions.sort().forEach(function(d) {
                                select.add(new Option(d));
                            });

                        });

                }
            });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const accountTypeSelect = document.getElementById('type');

                const idNumberInput = document.getElementById('id_number');
                const kraPinInput = document.getElementById('kraPin');
                const emailInput = document.getElementById('email');
                const contactInput = document.getElementById('contact');

                function toggleFieldRequirements() {
                    const type = accountTypeSelect.value;

                    // Reset all first
                    idNumberInput.removeAttribute('required');
                    kraPinInput.removeAttribute('required');
                    emailInput.removeAttribute('required');
                    contactInput.removeAttribute('required');

                    if (type === 'walkin') {
                        idNumberInput.setAttribute('required', 'required');
                        contactInput.setAttribute('required', 'required');
                    }

                    if (type === 'on_account') {
                        kraPinInput.setAttribute('required', 'required');
                        emailInput.setAttribute('required', 'required');
                        contactInput.setAttribute('required', 'required');
                    }
                }

                // Run on page load
                toggleFieldRequirements();

                // Listen for changes
                accountTypeSelect.addEventListener('change', toggleFieldRequirements);
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

                $('.selectpicker').selectpicker();

                //$('#dataTable').DataTable();
                $('#dataTable').DataTable({
                    language: {
                        lengthMenu: 'Showing _MENU_ Entries Per Page', // Capitalized 'Entries'
                        search: 'Search:', // You can also change this if needed
                        info: "Showing _START_ to _END_ of _TOTAL_ Entries", // Capitalized 'Entries'
                        paginate: {
                            first: "First",
                            last: "Last",
                            next: "Next",
                            previous: "Previous"
                        }
                        // Add other customizations if needed
                    },
                    paging: false, // Disable pagination
                    info: true, // Still shows "Showing 1 to N of N"
                    scrollY: "500px", // Adjust height as needed (e.g. 500px, 70vh)
                    scrollCollapse: true // Collapse table height if fewer rows
                });

                // Listen for change
                $('#collectionLocation').on('changed.bs.select', function() {
                    const location = $(this).val();
                    //alert('Selected: ' + location);
                    fetchDriversByLocation(location);
                });
                // let debounceTimer;
                // $('#collectionLocation').on('keyup', function() {
                //     clearTimeout(debounceTimer);
                //     const query = $(this).val().trim();

                //     debounceTimer = setTimeout(() => {
                //         if (query.length > 1) {
                //             $.ajax({
                //                 url: "{{ route('locations.search') }}",
                //                 data: {
                //                     term: query
                //                 },
                //                 success: function(data) {
                //                     const suggestions = $('#locationSuggestions');
                //                     suggestions.empty();

                //                     if (data.length > 0) {
                //                         data.forEach(function(location) {
                //                             suggestions.append(
                //                                 `<a href="#" class="list-group-item list-group-item-action">${location}</a>`
                //                             );
                //                         });
                //                         suggestions.show();
                //                     } else {
                //                         suggestions.hide();
                //                     }
                //                 }
                //             });
                //         } else {
                //             $('#locationSuggestions').hide();
                //         }
                //     }, 300); // delay search by 300ms
                // });



                // // ⬅ Handle suggestion click
                $(document).on('click', '#locationSuggestions a', function(e) {
                    e.preventDefault();
                    const selected = $(this).text();
                    $('#collectionLocation').val(selected);
                    $('#locationSuggestions').hide();

                    fetchDriversByLocation(selected); // ✅ fetch drivers on selection
                });

                // ⬅ Handle focusout on input
                $('#collectionLocation').on('focusout', function() {
                    $('#locationSuggestions').hide();
                });

                $(document).on('mousedown', '#locationSuggestions a', function(e) {
                    e.preventDefault();
                    const selected = $(this).text();
                    $('#collectionLocation').val(selected);
                    $('#locationSuggestions').hide();
                    fetchDriversByLocation(selected);
                });



                $('#collectionLocation').on('focusout', function() {
                    const location = $(this).val().trim();
                    //alert(location);

                    if (location.length > 1) {
                        $.ajax({
                            url: "{{ route('drivers.byLocation') }}",
                            method: "GET",
                            data: {
                                location: location
                            },
                            success: function(data) {
                                const userSelect = $('#userId');
                                userSelect.empty();
                                userSelect.append(`<option value="">Select Rider</option>`);

                                if (data.length > 0) {
                                    data.forEach(driver => {
                                        userSelect.append(
                                            `<option value="${driver.id}">${driver.name} (${driver.station})</option>`
                                        );
                                    });
                                } else {
                                    userSelect.append(
                                        `<option disabled>No drivers found for this location</option>`
                                    );
                                }
                            }
                        });
                    }
                });

                function fetchDriversByLocation(location) {
                    if (location.length > 1) {
                        $.ajax({
                            url: "{{ route('drivers.byLocation') }}",
                            data: {
                                location: location
                            },
                            success: function(drivers) {
                                const select = $('#userId');
                                select.empty().append('<option value="">Select Rider</option>');

                                if (drivers.length > 0) {
                                    drivers.forEach(driver => {
                                        select.append(
                                            `<option value="${driver.id}">${driver.name} (${driver.collectionLocations})</option>`
                                        );
                                    });
                                } else {
                                    select.append(
                                        '<option disabled>No drivers found for this location</option>');
                                }
                            }
                        });
                    }
                }



                // un allocated riders 
                $('#unallocatedRiders').on('change', function() {
                    if ($(this).is(':checked')) {
                        $.ajax({
                            url: "{{ route('drivers.unallocated') }}",
                            method: "GET",
                            success: function(drivers) {
                                const select = $('#userId');
                                select.empty().append('<option value="">Select Rider</option>');

                                if (drivers.length > 0) {
                                    drivers.forEach(driver => {
                                        select.append(
                                            `<option value="${driver.id}">${driver.name} (Unallocated)</option>`
                                        );
                                    });
                                } else {
                                    select.append(
                                        '<option disabled>No unallocated riders found</option>');
                                }
                            }
                        });
                    }
                });


                // all riders

                $('#allRiders').on('change', function() {
                    if ($(this).is(':checked')) {
                        $.ajax({
                            url: "{{ route('drivers.all') }}",
                            method: "GET",
                            success: function(drivers) {
                                const select = $('#userId');
                                select.empty().append('<option value="">Select Rider</option>');

                                if (drivers.length > 0) {
                                    drivers.forEach(driver => {
                                        select.append(
                                            `<option value="${driver.id}">${driver.name} (${driver.collectionLocations ?? 'Unallocated'})</option>`
                                        );
                                    });
                                } else {
                                    select.append('<option disabled>No riders found</option>');
                                }
                            }
                        });
                    }
                });

                $('#currentLocation').on('change', function() {
                    if ($(this).is(':checked')) {
                        const location = $('#collectionLocation').val().trim();

                        if (location.length > 1) {
                            fetchDriversByLocation(location);
                        } else {
                            alert('Please enter a collection location first.');
                        }
                    }
                });

                $('#categories-multiselect').multiselect({
                    includeSelectAllOption: true,
                    enableFiltering: true,
                    buttonWidth: '100%', // 👈 Ensures dropdown matches form-control width
                    nonSelectedText: 'Select categories'
                });

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
                        $.get('/getDestinations/' + selectedOfficeId)
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

                $(document).on('change', '.origin-dropdownx', function() {
                    const originSelect = $(this);
                    const selectedOfficeId = originSelect.val();
                    const modal = originSelect.closest('.modal');
                    const destinationSelect = modal.find('.destination-dropdownx');

                    destinationSelect.html('<option value="">Select Destination</option>');

                    if (selectedOfficeId) {
                        $.get('/getDestinations/' + selectedOfficeId)
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
                        const volume_weight = volume / 5000; // calculate volume weight

                        row.find('input[name="volume[]"]').val(volume_weight.toFixed(2));

                        const weight = parseFloat(row.find('input[name="weight[]"]').val()) || 0;

                        if (volume_weight > weight) {
                            row.find('input[name="weight[]"]')
                                .val(volume_weight.toFixed(2)) // set new value
                                .trigger('keyup') // simulate keyup
                                .trigger('change'); // also trigger change if needed
                        }
                    } else {
                        row.find('input[name="volume[]"]').val('');
                    }
                }


                // end calculate volume

                // Total weight calculation and cost update
                // function recalculateCosts() {
                // let totalWeight = 0;

                // $('#shipmentTable tbody tr').each(function() {
                // const row = $(this);
                // const weight = parseFloat(row.find('input[name="weight[]"]').val()) || 0;
                // const packages = parseFloat(row.find('input[name="packages[]"]').val()) || 1;
                // totalWeight += weight * packages;
                // });

                // $('input[name="total_weight"]').val(totalWeight.toFixed(2));

                // const baseCost = parseFloat($('input[name="base_cost"]').val()) || 0;
                // let cost = baseCost;

                // if (totalWeight > 25) {
                // const extraWeight = totalWeight - 25;
                // cost += extraWeight * 50;
                // }

                // $('input[name="cost"]').val(cost.toFixed(2));

                // const vat = cost * 0.16;
                // $('input[name="vat"]').val(vat.toFixed(2));
                // $('input[name="total_cost"]').val((cost + vat).toFixed(2));
                // }
                function recalculateCosts() {
                    let totalWeight = 0;
                    let totalVolume = 0;

                    $('#shipmentTable tbody tr').each(function() {
                        const row = $(this);
                        const weight = parseFloat(row.find('input[name="weight[]"]').val()) || 0;
                        const packages = parseFloat(row.find('input[name="packages[]"]').val()) || 1;
                        const volume = parseFloat(row.find('.volume').val()) || 1;
                        totalWeight += weight * packages;
                        totalVolume += volume;
                    });

                    $('input[name="total_weight"]').val(totalWeight.toFixed(2));

                    const baseCost = parseFloat($('input[name="base_cost"]').val()) || 0;
                    let cost = baseCost;
                    volumeWeight = totalVolume / 5000;

                    let baseWeight = 0;


                    if (totalWeight > volumeWeight) {
                        baseWeight = totalWeight;
                        //alert('weight' + baseWeight)
                    }
                    if (volumeWeight > totalWeight) {
                        baseWeight = volumeWeight;
                        //alert('volume weight' + baseWeight)
                        $('input[name="total_weight"]').val(baseWeight.toFixed(2));
                    }

                    if (baseWeight > 25) {
                        const extraWeight = baseWeight - 25;
                        cost += extraWeight * 50;
                    }



                    // function extractVAT(costWithVAT) {
                    //     // Calculate raw VAT when total already includes VAT
                    //     const rawVat = (costWithVAT * 0.16) / 1.16;

                    //     let integerPart = Math.floor(rawVat);
                    //     const decimal = rawVat - integerPart;

                    //     let roundedVat;
                    //     if (decimal < 0.3) {
                    //         roundedVat = integerPart;
                    //     } else if (decimal >= 0.7) {
                    //         roundedVat = integerPart + 0.5;
                    //     } else {
                    //         roundedVat = integerPart + 1;
                    //     }

                    //     // Always return a formatted string like "69.00" or "69.50"
                    //     return roundedVat.toFixed(2);
                    // }
                    function extractVAT(costWithVAT) {
                        // Calculate raw VAT when total already includes VAT
                        const rawVat = (costWithVAT * 0.16) / 1.16;

                        const integerPart = Math.floor(rawVat);
                        const decimalPart = rawVat - integerPart;
                        let roundedDecimal = 0;

                        // Apply the same custom rounding rules
                        if (decimalPart <= 0.03) {
                            roundedDecimal = 0.00;
                        } else if (decimalPart > 0.03 && decimalPart <= 0.07) {
                            roundedDecimal = 0.05;
                        } else {
                            roundedDecimal = 0.10;
                        }

                        let result = integerPart + roundedDecimal;

                        // Handle carry-over if rounding pushes to next integer
                        if (result >= integerPart + 1) {
                            result = integerPart + 1.00;
                        }

                        // Return always formatted to 2 decimals, e.g., "69.00" or "69.05"
                        return result.toFixed(2);
                    }


                    const vat = extractVAT(cost);
                    $('input[name="cost"]').val((cost - vat).toFixed(2));
                    $('input[name="vat"]').val(vat);
                    $('input[name="total_cost"]').val((cost).toFixed(2));
                }


                // Watch for changes in volume dimensions
                $(document).on('input', 'input[name="length[]"], input[name="width[]"], input[name="height[]"]',
                    function() {
                        const row = $(this).closest('tr');
                        calculateVolume(row);
                        recalculateCosts();
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
                        $.get(`/getCost/${originId}/${destinationId}`)
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

                $(document).on('change', '.destination-dropdownx', function() {
                    const destinationId = $(this).val();
                    const selectedOption = $(this).find('option:selected');
                    const destination_id = selectedOption.data('id');
                    $("#destination_id").val(destination_id);
                    const modal = $(this).closest('form'); // Adjust if you're using modal or form wrapper
                    const originId = modal.find('.origin-dropdownx').val();

                    if (originId && destinationId) {
                        $.get(`/getCost/${originId}/${destinationId}`)
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

        <script>
            $('.ajax-select').select2({
                placeholder: 'Start typing...',
                minimumInputLength: 2,
                ajax: {
                    url: '/search',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term,
                            type: $(this).data('type')
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    }
                }
            });
        </script>

        <script>
            $(document).ready(function() {

                // When client is selected
                $('#clientId').on('change', function() {
                    let clientId = $(this).val();
                    if (clientId) {
                        $.ajax({
                            url: '/get-client-categories/' + clientId,
                            type: 'GET',
                            success: function(data) {
                                $('#clientCategories').empty().append(
                                    '<option value="">Select Client Categories</option>');
                                $('#subCategories').empty().append(
                                    '<option value="">Select Sub Categories</option>');
                                $.each(data, function(key, category) {
                                    $('#clientCategories').append('<option value="' +
                                        category.category_id + '">' + category
                                        .category_name + '</option>');
                                });
                            }
                        });
                    } else {
                        $('#clientCategories').empty().append(
                            '<option value="">Select Client Categories</option>');
                        $('#subCategories').empty().append('<option value="">Select Sub Categories</option>');
                    }
                });

                // When category is selected
                $('#clientCategories').on('change', function() {
                    let categoryId = $(this).val();
                    if (categoryId) {
                        $.ajax({
                            url: '/get-sub-categories/' + categoryId,
                            type: 'GET',
                            success: function(data) {
                                $('#subCategories').empty().append(
                                    '<option value="">Select Sub Categories</option>');
                                $.each(data, function(key, sub) {
                                    $('#subCategories').append('<option value="' + sub.id +
                                        '">' + sub.sub_category_name + '</option>');
                                });
                            }
                        });
                    } else {
                        $('#subCategories').empty().append('<option value="">Select Sub Categories</option>');
                    }
                });
            });
        </script>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const riderSelect = document.querySelector(".userId");
                const riderRadios = document.querySelectorAll("input[name='riderOption']");
                const riderInfo = document.getElementById("riderInfo");

                // Disable select by default + show info
                riderSelect.disabled = true;
                riderInfo.style.display = "block";

                // Add change event to all radio buttons
                riderRadios.forEach(radio => {
                    radio.addEventListener("change", function() {
                        const anyChecked = Array.from(riderRadios).some(r => r.checked);
                        riderSelect.disabled = !anyChecked;

                        // Toggle info message
                        riderInfo.style.display = anyChecked ? "none" : "block";
                    });
                });
            });
        </script>

        <script>
            document.addEventListener("DOMContentLoaded", () => {
                // elements
                const prioritySelect = document.getElementById("priority_level");
                const deadlineGroup = document.getElementById("priority-deadline-group");
                const priorityConfirm = document.getElementById("priority-confirm");
                const priorityExtraGroup = document.getElementById("priority-extra-charge-group");
                const priorityExtraInput = document.getElementById("priority_extra_charge");

                const fragileSelect = document.getElementById("fragile");
                const fragileConfirm = document.getElementById("fragile-confirm");
                const fragileExtraGroup = document.getElementById("fragile-charge-group");
                const fragileExtraInput = document.getElementById("fragile_charge");


                const insuranceSelect = document.getElementById("insurance");
                const insuranceConfirm = document.getElementById("insurance-confirm");
                const insuranceExtraGroup = document.getElementById("insurance-charge-group");
                const insuranceExtraGroup2 = document.getElementById("insurance-charge-group2");
                const insuranceExtraInput = document.getElementById("total_insurance");
                const insuranceExtraInput2 = document.getElementById("insurance_charged");



                const baseCostInput = document.querySelector("input[name='base_cost']");
                const costInput = document.querySelector("input[name='cost']");
                const vatInput = document.querySelector("input[name='vat']");
                const totalInput = document.querySelector("input[name='total_cost']");

                const priorityYesBtn = document.getElementById("priorityYesBtn");
                const priorityNoBtn = document.getElementById("priorityNoBtn");

                const fragileYesBtn = document.getElementById("fragileYesBtn");
                const fragileNoBtn = document.getElementById("fragileNoBtn");

                const insuranceYesBtn = document.getElementById("insuranceYesBtn");
                const insuranceNoBtn = document.getElementById("insuranceNoBtn");

                // helper function to update totals
                function updateTotals() {
                    // Get base cost (the main cost before extras)
                    const baseCost = parseFloat(baseCostInput.value) || 0;

                    // Extra charges
                    const priorityExtra = parseFloat(priorityExtraInput.value) || 0;
                    const fragileExtra = parseFloat(fragileExtraInput.value) || 0;
                    const insuranceExtra = parseFloat(insuranceExtraInput2.value) || 0;

                    // Compute the new total — starting from the base cost
                    const itemCost = baseCost + priorityExtra + fragileExtra + insuranceExtra;

                    // Update total field with two decimals
                    totalInput.value = itemCost.toFixed(2);
                }


                // priority selection logic
                prioritySelect.addEventListener("change", () => {
                    if (prioritySelect.value === "high") {
                        deadlineGroup.style.display = "block";
                        priorityConfirm.style.display = "block";
                    } else {
                        deadlineGroup.style.display = "none";
                        priorityConfirm.style.display = "none";
                        priorityExtraGroup.style.display = "none";
                        priorityExtraInput.value = "";
                        updateTotals();
                    }
                });

                priorityYesBtn.addEventListener("click", () => {
                    priorityExtraGroup.style.display = "block";
                    priorityConfirm.style.display = "none";
                });

                priorityNoBtn.addEventListener("click", () => {
                    // Keep HIGH but with zero charge
                    priorityConfirm.style.display = "none";
                    priorityExtraGroup.style.display = "none";
                    priorityExtraInput.value = "0";
                    updateTotals();
                });

                // fragile selection logic
                fragileSelect.addEventListener("change", () => {
                    if (fragileSelect.value === "yes") {
                        fragileConfirm.style.display = "block";
                    } else {
                        fragileConfirm.style.display = "none";
                        fragileExtraGroup.style.display = "none";
                        fragileExtraInput.value = "";
                        updateTotals();
                    }
                });

                fragileYesBtn.addEventListener("click", () => {
                    fragileExtraGroup.style.display = "block";
                    fragileConfirm.style.display = "none";
                });

                fragileNoBtn.addEventListener("click", () => {
                    // Keep YES but with zero charge
                    fragileConfirm.style.display = "none";
                    fragileExtraGroup.style.display = "none";
                    fragileExtraInput.value = "0";
                    updateTotals();
                });

                // watch extra charge inputs
                priorityExtraInput.addEventListener("input", updateTotals);
                fragileExtraInput.addEventListener("input", updateTotals);


                //insurance logic

                insuranceSelect.addEventListener("change", () => {
                    if (insuranceSelect.value === "yes") {
                        insuranceConfirm.style.display = "block";
                    } else {
                        insuranceConfirm.style.display = "none";
                        insuranceExtraGroup.style.display = "none";
                        insuranceExtraInput.value = "";
                        insuranceExtraGroup2.style.display = "none";
                        insuranceExtraInput2.value = "";
                        updateTotals();
                    }
                });

                insuranceYesBtn.addEventListener("click", () => {
                    insuranceExtraGroup.style.display = "block";
                    insuranceExtraGroup2.style.display = "block";
                    insuranceConfirm.style.display = "none";
                    $('#insurance_status').val('insured');

                });

                insuranceNoBtn.addEventListener("click", () => {
                    // Keep YES but with zero charge
                    insuranceConfirm.style.display = "none";
                    insuranceExtraGroup.style.display = "none";
                    insuranceExtraInput.value = "0";
                    insuranceExtraGroup2.style.display = "none";
                    insuranceExtraInput2.value = "0";
                    updateTotals();
                    $('#insurance_status').val('not_insured');
                });

                // watch extra charge inputs

                document.getElementById('total_insurance').addEventListener('input', function() {
                    // Clean input to prevent partial parseFloat issues
                    let value = this.value.trim();

                    // If user enters non-numeric values, reset to empty
                    if (!/^\d*\.?\d*$/.test(value)) {
                        this.value = value.replace(/[^\d.]/g, '');
                        return;
                    }

                    // Convert to float safely
                    let total = parseFloat(this.value) || 0;

                    // Always recalculate fresh 1%
                    let charge = (total * 0.01).toFixed(2);

                    // Show/hide based on input
                    if (total > 0) {
                        document.getElementById('insurance-charge-group2').style.display = "block";
                        document.getElementById('insurance_charged').value = charge;
                    } else {
                        document.getElementById('insurance-charge-group2').style.display = "none";
                        document.getElementById('insurance_charged').value = "";
                    }

                    // Update totals every time
                    updateTotals();
                });







                // initial calc
                updateTotals();
            });
        </script>

        <script>
            function printModalContent(id, type = null) {
                // Default content id if no type provided
                var contentId = type ?
                    'print-content-' + id + '-' + type :
                    'print-content-' + id;

                var element = document.getElementById(contentId);
                if (!element) {
                    alert('Receipt content not found: ' + contentId);
                    return;
                }

                var content = element.innerHTML;
                var printWindow = window.open('', '', 'width=800,height=600');
                printWindow.document.write('<html><head><title>Print Receipt</title>');
                printWindow.document.write('<link rel="stylesheet" href="/css/app.css">');
                printWindow.document.write('</head><body>');
                printWindow.document.write(content);
                printWindow.document.write('</body></html>');
                printWindow.document.close();
                printWindow.print();
            }
        </script>

        <script>
            /**
             * Reusable Date Filter + Report Generator
             * @param {string} tableId - The ID of the table to filter
             * @param {number} dateColIndex - Column index where the date is stored
             * @param {string} reportUrl - The base URL for report generation
             */
            function initDateFilter(tableId, dateColIndex, reportUrl, startInputId = "startDate", endInputId = "endDate",
                reportBtnId = "generateReport", clearBtnId = "clearFilter") {
                const startInput = document.getElementById(startInputId);
                const endInput = document.getElementById(endInputId);
                const reportBtn = document.getElementById(reportBtnId);
                const clearBtn = document.getElementById(clearBtnId);

                function filterTable() {
                    let startDate = startInput.value;
                    let endDate = endInput.value;

                    let table = document.getElementById(tableId);
                    if (!table) return;

                    let rows = table.getElementsByTagName("tr");

                    for (let i = 1; i < rows.length; i++) { // skip header
                        let dateCell = rows[i].getElementsByTagName("td")[dateColIndex];
                        if (dateCell) {
                            let rowDateStr = dateCell.getAttribute("data-date");
                            let rowDate = rowDateStr ? new Date(rowDateStr) : new Date(dateCell.innerText);
                            rowDate.setHours(0, 0, 0, 0);

                            let showRow = true;

                            if (startDate) {
                                let from = new Date(startDate);
                                from.setHours(0, 0, 0, 0);
                                if (rowDate < from) showRow = false;
                            }

                            if (endDate) {
                                let to = new Date(endDate);
                                to.setHours(0, 0, 0, 0);
                                if (rowDate > to) showRow = false;
                            }

                            rows[i].style.display = showRow ? "" : "none";
                        }
                    }
                }

                function clearFilter() {
                    startInput.value = "";
                    endInput.value = "";

                    let table = document.getElementById(tableId);
                    if (!table) return;

                    let rows = table.getElementsByTagName("tr");
                    for (let i = 1; i < rows.length; i++) {
                        rows[i].style.display = "";
                    }
                }

                startInput.addEventListener("change", filterTable);
                endInput.addEventListener("change", filterTable);
                clearBtn.addEventListener("click", clearFilter);

                reportBtn.addEventListener("click", function() {
                    let startDate = startInput.value;
                    let endDate = endInput.value;
                    window.location.href = `${reportUrl}?start=${startDate}&end=${endDate}`;
                });
            }
        </script>

        <script>
            new DataTable('#reports', {
                initComplete: function() {
                    this.api()
                        .columns()
                        .every(function() {
                            let column = this;

                            // Create select element
                            let select = document.createElement('select');
                            select.add(new Option(''));
                            column.header().replaceChildren(select);

                            // Apply listener for user change in value
                            select.addEventListener('change', function() {
                                column
                                    .search(select.value, {
                                        exact: true
                                    })
                                    .draw();
                            });

                            // Add list of options
                            column
                                .data()
                                .unique()
                                .sort()
                                .each(function(d, j) {
                                    select.add(new Option(d));
                                });
                        });
                }
            });
        </script>
    </div>
</body>

</html>
