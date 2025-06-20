<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Tracking Status</title>
    <link href="{{ asset('assets/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">



    <style>
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
</head>

<body class="p-4">

    <div class="container">
        <div class="row align-items-center mb-4 text-center text-md-start">
            <!-- Logo -->
            <div class="col-12 col-sm-12 col-md-4 col-lg-4 mb-3 mb-md-0">
                <img src="{{ asset('images/UCSLogo1.png') }}" height="100px" width="auto" alt="UCS Logo"
                    class="img-fluid">
            </div>

            <!-- Title -->
            <div class="col-12 col-sm-12 col-md-3 col-lg-3 mb-2 mb-md-0">
                <h2 class="text-primary">Track Your Parcel</h2>
            </div>

            <!-- Date -->
            <div class="col-12 col-sm-12 col-md-5 col-lg-5">
                <h5 class="text-dark mb-0">
                    Tracking Date: <span id="liveDateTime"></span>
                </h5>

                <button class="btn btn-danger mt-2 logout">Logout:
                    {{ auth('client')->user()->name ?? auth('guest')->user()->name }}</button>
                {{-- <p class="fw-bold">
            Tracking done by: {{ auth('api')->user()->name }}
        </p> --}}
            </div>
        </div>


        <div class="mb-3">
            <input type="text" id="requestId" class="form-control" placeholder="Enter Request ID">
        </div>

        <button id="trackBtn" class="btn btn-primary mb-1 me-2"><i class="bi bi-search"></i> Track</button>
        <button id="generatePdfBtn" class="btn btn-warning mb-1" style="display: none;"><i
                class="bi bi-filetype-pdf"></i> Generate PDF</button>


        <div id="trackingResults" class="timeline mt-2"></div>
    </div>

    <script>
        $(document).ready(function() {
            $('#trackBtn').on('click', function() {
                const requestId = $('#requestId').val().trim();
                if (!requestId) return alert('Please enter a Request ID.');

                $.ajax({
                    url: `http://127.0.0.1:8000/track/${requestId}`,
                    method: 'GET',
                    success: function(data) {
                        let clientName = data.client && data.client.name ? data.client.name :
                            'N/A';
                        let shipmentItemsHtml = '';
                        if (Array.isArray(data.shipment_items) && data.shipment_items.length >
                            0) {
                            data.shipment_items.forEach((item, index) => {
                                shipmentItemsHtml += `
            <div class="mb-2">
                <p class="text-primary fw-bold mb-1">Item: ${item.item_name}; <strong>Packages:</strong> ${item.packages_no}; <strong>Weight:</strong> ${item.weight} Kgs </p>
            </div>
        `;
                            });
                        } else {
                            shipmentItemsHtml =
                                '<p class="text-muted">No shipment items found.</p>';
                        }

                        let html =
                            `<h5 class="text-primary mb-2">Tracking Results for <strong>${data.requestId}</strong> For <strong>${clientName}</h5> 
                        <section class="mb-3">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="shadow-sm p-3">
                                    <p class="text-primary mb-1">
                                        Origin: <span class="text-dark">${data.origin_office}</span>
                                    </p>
                                    <p class="text-primary mb-1">
                                        Destination: <span class="text-dark">${data.destination_name}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="shadow-sm p-3">
                                    <p class="text-primary mb-1">
                                        Sender: <span class="text-dark">${data.sender_name}</span>
                                    </p>
                                    <p class="text-primary mb-1">
                                        Receiver: <span class="text-dark">${data.receiver_name}</span>
                                    </p>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="shadow-sm p-3">
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
                <p class="text-dark mb-1">${info.remarks}</p>
                ${isLast ? `<p class="text-success p-0 m-0 fst-italic"><strong>Current Status: ${data.current_status}</strong></p>` : ''}
            </li>
        `;
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



</body>

</html>
