<?php
use App\Http\Controllers\TrackController;

Route::get('/track/{requestId}', [TrackController::class, 'getTrackingByRequestId']);

Route::get('/track/{requestId}/pdf', [TrackController::class, 'generateTrackingPdf']);