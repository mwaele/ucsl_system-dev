<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Tracking Status</title>
    <link href="{{ asset('assets/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">


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
    </style>
</head>

<body class="p-4">

    <div class="container">
        <h2 class="mb-4 text-primary">
            <img src="{{ asset('images/UCSLogo1.png') }}" height="200px" width="auto" alt="">

            Track Your Parcel
        </h2>

        <div class="mb-3">
            <input type="text" id="requestId" class="form-control" placeholder="Enter Request ID">
        </div>

        <button id="trackBtn" class="btn btn-primary mb-3 me-2"><i class="bi bi-search"></i> Track</button>
        <button id="generatePdfBtn" class="btn btn-warning mb-3" style="display: none;"><i
                class="bi bi-filetype-pdf"></i> Generate PDF</button>


        <div id="trackingResults" class="timeline mt-4"></div>
    </div>

    <script>
        $(document).ready(function() {
            $('#trackBtn').on('click', function() {
                const requestId = $('#requestId').val().trim();
                if (!requestId) return alert('Please enter a Request ID.');

                $.ajax({
                    url: `http://127.0.0.1:8000/api/track/${requestId}`,
                    method: 'GET',
                    success: function(data) {
                        let clientName = data.client && data.client.name ? data.client.name :
                            'N/A';
                        let html =
                            `<h5 class="text-primary mb-4">Tracking Results for <strong>${data.requestId}</strong> For <strong>${clientName}</h5> 
                        <section class="mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="shadow-sm p-3">
                                    <p class="text-success mb-1">
                                        Origin: <span class="text-dark">${data.origin_office}</span>
                                    </p>
                                    <p class="text-success mb-1">
                                        Destination: <span class="text-dark">${data.destination_name}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="shadow-sm p-3">
                                    <p class="text-success mb-1">
                                        Sender:<span class="text-dark">${data.sender_name}</span>
                                    </p>
                                    <p class="text-success mb-1">
                                        Receiver:<span class="text-dark">${data.receiver_name}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        </section>
                                
                        <section class="">
                         <ul class="timeline-with-icons ">`;

                        data.tracking_infos.forEach((info, index) => {
                            let isLast = index === data.tracking_infos.length - 1;

                            html += `
        <li class="timeline-item mb-2">
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
                        }); +
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
                window.open(`/api/track/${requestId}/pdf`, '_blank');
            });
        });
    </script>

</body>

</html>
