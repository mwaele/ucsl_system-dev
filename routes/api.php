<?php
use App\Http\Controllers\TrackController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\API\ClientAuthController;
use App\Http\Controllers\GuestController;

use App\Http\Controllers\API\TrackingController;

Route::get('/v1/track/{requestId}', [TrackingController::class, 'getTrackingByRequestId']);

// Route::get('/track/{requestId}/pdf', [TrackController::class, 'generateTrackingPdf']);

// Route::get('/tracker', [TrackController::class, 'index']);




    
// });

