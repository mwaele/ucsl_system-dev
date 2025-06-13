<?php
use App\Http\Controllers\TrackController;
use App\Http\Controllers\AuthController;

Route::get('/track/{requestId}', [TrackController::class, 'getTrackingByRequestId']);

Route::get('/track/{requestId}/pdf', [TrackController::class, 'generateTrackingPdf']);

Route::get('/signin', [AuthController::class, 'showSignIn'])->name('signin');
Route::post('/signin', [AuthController::class, 'processSignIn'])->name('signin.process');

Route::get('/guest', [AuthController::class, 'showGuest'])->name('guest');
Route::post('/guest', [AuthController::class, 'processGuest'])->name('guest.process');