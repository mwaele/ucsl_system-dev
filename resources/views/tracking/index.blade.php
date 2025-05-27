<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Tracking Status</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
            left: -48px;
            background-color: hsl(129, 88%, 90%);
            color: hsl(217, 88.8%, 35.1%);
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
        <h2 class="mb-4 text-primary"><i class="bi bi-box-seam"></i> Track Your Parcel</h2>

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
                            `<h5 class="text-secondary mb-4">Tracking Results for <strong>${data.requestId}</strong> For <strong>${clientName}</h5> <section class="py-2">
  <ul class="timeline-with-icons">`;

                        data.tracking_infos.forEach(info => {
                            html += `
                            <li class="timeline-item mb-5">
      <span class="timeline-icon">
        <i class="fas fa-check text-success fa-sm fa-fw"></i>
      </span>

      <h5 class="fw-bold">${info.details}</h5>
      <p class="text-muted mb-2 fw-bold">${info.date}</p>
      <p class="text-muted">
        ${info.remarks}
      </p>
    </li>
             `;
                        }); + ` </ul>
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
