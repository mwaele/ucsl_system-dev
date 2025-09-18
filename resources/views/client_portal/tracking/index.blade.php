@extends('layouts.client_portal_custom')

@section('content')
    <div class="mt-4 card bg-white">
        <div class="card-header py-3">
            <div class="d-flex justify-content-between align-items-center">
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="text-primary"><strong>Track Your Shipment</strong></h2>

                <!-- Right Side (Date Filter + Generate PDF) -->
                <div class="d-flex align-items-center ms-auto">
                    <!-- Date Range Filter -->

                    <h5 class="text-success mb-0 text-md-end" style="text-align: right
                    !important;">
                        <strong>Tracking Date:</strong> <span id="liveDateTime"></span>
                    </h5>

                </div>
            </div>

            {{-- <div class="row mb-4 text-md-start"> --}}


            <!-- Title -->
            {{-- <div class="col-12 col-sm-12 col-md-6 col-lg-6 mb-2 mb-md-0">

                </div> --}}

            <!-- Date -->
            <div class="col-12 col-sm-12 col-md-6 col-lg-6">

                {{-- 
                <button class="btn btn-danger mt-2 logout">Logout:
                    {{ auth('client')->user()->name ?? auth('guest')->user()->name }}</button> --}}
                {{-- <p class="fw-bold">
            Tracking done by: {{ auth('api')->user()->name }}
        </p> --}}
            </div>
        </div>


        <div class="d-flex justify-content-center  p-4 bg-success">
            <div class="input-group modern-search shadow-sm" style="max-width: 600px; width: 100%;">
                <input type="text" id="requestId" class="form-control px-4 py-3" placeholder="Enter Tracking Number">
                <button id="trackBtn" class="btn btn-primary px-4 d-flex align-items-center justify-content-center">
                    <i class="bi bi-search fs-5">TRACK</i>
                </button>
            </div>
        </div>


        <!-- Custom CSS -->
        <style>
            .input-group .form-control {
                border: none;
                background: #f8f9fa;
                font-size: 1.1rem;
                box-shadow: none;
            }

            .input-group .form-control:focus {
                background: #fff;
                box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, .25);
            }

            .input-group .btn {
                border: none;
                border-top-left-radius: 0 !important;
                border-bottom-left-radius: 0 !important;
                transition: all 0.3s ease;
            }

            .input-group .btn:hover {
                transform: scale(1.05);
                box-shadow: 0 4px 12px rgba(13, 110, 253, .3);
            }
        </style>


        <!-- Custom CSS -->
        <style>
            .modern-search .form-control {
                border: none;
                background: #f8f9fa;
                font-size: 1.1rem;
                transition: all 0.3s ease;
            }

            .modern-search .form-control:focus {
                background: #fff;
                box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, .25);
            }

            .modern-search .btn {
                border: none;
                transition: all 0.3s ease;
            }

            .modern-search .btn:hover {
                transform: scale(1.05);
                box-shadow: 0 4px 12px rgba(213, 123, 5, 0.847);
            }

            body {
                background-color: #f4f6f9;
            }

            .timeline-with-icons {
                border-left: 1px solid hsl(0, 0%, 90%);
                position: relative;
                list-style: none;
            }

            .timeline-with-icons .timeline-item {
                position: relative;
            }

            .timeline-with-icons .timeline-item:after {
                position: absolute;
                display: block;
                top: 0;
            }

            .timeline-with-icons .timeline-icon {
                position: absolute;
                left: -56px;
                background-color: #f57f3f;
                color: #14489f;
                border-radius: 50%;
                height: 31px;
                width: 31px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .format {
                margin: 0;
                padding: 0;
            }
        </style>



        <!-- Generate PDF Button (Initially Hidden) -->


        @php
            $role = optional(auth('client')->user())->role;
        @endphp

        @if (strtolower($role ?? '') === 'admin')
            <button id="generatePdfBtn" class="btn btn-warning mb-1">
                <i class="bi bi-filetype-pdf"></i> Generate PDF
            </button>
        @endif


        <div id="trackingResults" class="timeline  p-4"></div>
    </div>

    <script>
        $(document).ready(function() {
            $('#trackBtn').on('click', function() {
                const requestId = $('#requestId').val().trim();
                if (!requestId) return alert('Please enter a Request ID.');

                $.ajax({
                    url: `/track/${requestId}`,
                    method: 'GET',
                    success: function(data) {
                        let clientName = data.client && data.client.name ? data.client.name :
                            'N/A';
                        let shipmentItemsHtml = '';
                        if (Array.isArray(data.shipment_items) && data.shipment_items.length >
                            0) {
                            data.shipment_items.forEach((item, index) => {
                                shipmentItemsHtml += `
            <div class="mb-1">
                <p class="text-primary fw-bold mb-1">Item: ${item.item_name}; <strong>Packages:</strong> ${item.packages_no}; <strong>Weight:</strong> ${item.actual_weight} Kgs </p>
            </div>
        `;
                            });
                        } else {
                            shipmentItemsHtml =
                                '<p class="text-muted">No shipment items found.</p>';
                        }

                        // <h5 class="text-primary mb-2">
                        //         Tracking Results for <strong>${data.requestId}</strong> For <strong>${clientName}</strong> 
                        //         <span >${data.tracking_label ? data.tracking_label : ''}</span>
                        //     </h5>

                        let html =
                            `
                            
 
                        <section class="mb-1">
                        <div class="row mb-1">
                            <div class="col-md-6">
                                <div class="shadow-sm p-3">
                                    <p class="text-primary mb-1">
                                        Origin: <span class="text-dark">${data.origin_office}</span>
                                    </p>
                                    <p class="text-primary mb-1">
                                        Destination: <span class="text-dark">${data.destination_name}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="shadow-sm p-3">
                                    <p class="text-primary mb-1">
                                        Sender: <span class="text-dark">${data.sender_name}</span>
                                    </p>
                                    <p class="text-primary mb-1">
                                        Receiver: <span class="text-dark">${data.receiver_name}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="shadow-sm p-3">
                                    <p class="text-dark">Item Description</p>
                                     ${shipmentItemsHtml}
                                </div>
                            </div>
                        </div>
                        </section>
                                
                        <section class="">
                         <ul class="timeline-with-icons ">`;

                        if (Array.isArray(data.tracking_infos)) {
                            data.tracking_infos.forEach((info, index) => {
                                let isLast = index === data.tracking_infos.length - 1;
                                html += `
            <li class="timeline-item mb-3">
                <span class="timeline-icon">
                    <i class="fas fa-check text-white fa-sm fa-fw"></i>
                </span>
                 <h5 class="fw-bold text-primary"><strong>${info.details}</strong></h5>
                <p class="text-dark mb-1 fw-bold">${new Date(info.date).toLocaleString('en-US', {
                    month: 'long',
                    day: 'numeric',
                    year: 'numeric',
                    hour: 'numeric',
                    minute: '2-digit',
                    hour12: true
                })}</p>
                
                ${isLast ? `<p class="text-success p-0 m-0 fst-italic"><strong>Current Status: ${data.current_status}</strong></p>` : ''}
            </li>
        `;
                                // <p class="text-dark mb-1">${info.remarks}</p>
                            });
                        } else {
                            html +=
                                `<div class="alert alert-warning">No tracking history found.</div>`;
                        } +
                        ` </ul>
                </section> `;

                        $('#trackingResults').html(html);
                        $('#generatePdfBtn').show().data('requestId', requestId);
                    },
                    error: function() {
                        $('#trackingResults').html(
                            `<div class="alert alert-danger">Tracking data not found.</div>`
                        );
                        $('#generatePdfBtn').hide();
                    }
                });
            });

            $('#generatePdfBtn').on('click', function() {
                const requestId = $(this).data('requestId');
                window.open(`/track/${requestId}/pdf`, '_blank');
            });

            // $('.logout').on('click', function(e) {
            //     e.preventDefault();

            //     // Optional: Confirm before logout
            //     if (!confirm("Are you sure you want to logout?")) return;

            //     // Clear localStorage
            //     localStorage.clear();

            //     window.location.href =
            //         "/tracking";

            //     //location.reload();
            //     // const token = localStorage.getItem('client_token') || localStorage.getItem('guest_token');


            //     // Send logout request via AJAX
            //     // Send logout request
            //     // $.ajax({
            //     //     url: "/client/logout", // or use `{{ route('client.logout') }}` in Blade
            //     //     method: 'POST',
            //     //     headers: {
            //     //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //     //     },
            //     //     success: function(response) {
            //     //         // Redirect after logout
            //     //         window.location.href =
            //     //             "/tracking"; // or `{{ route('client_login') }}`
            //     //     },
            //     //     error: function(xhr) {
            //     //         console.error(xhr.responseText);
            //     //         alert('Logout failed. Try again.');
            //     //     }
            //     // });
            // });
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
            // Update every second


            function updateDateTime() {
                const now = new Date();

                const options = {
                    weekday: undefined,
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: 'numeric',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: true
                };

                const formatted = now.toLocaleString('en-US', options);
                document.getElementById('liveDateTime').textContent = formatted;
            }

            updateDateTime(); // Initial run
            setInterval(updateDateTime, 1000);

        });
    </script>
@endsection
