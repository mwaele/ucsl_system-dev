<!DOCTYPE html>
<html>

<head>
    <title>Report PDF</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>

<body>
    <h2>Earnings Overview</h2>
    <img src="{{ $chartImage }}" alt="Chart" style="width:600px;height:auto;">
</body>

</html>
