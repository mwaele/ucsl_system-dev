<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\ShipmentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
})->middleware(['auth', 'verified'])->name('index');;

Route::get('/dashboard', function () {
    return view('index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/vehicles/{vehicle}/allocate', [VehicleController::class, 'allocate'])->name('vehicles.allocate');
    Route::post('/shipments/{id}/deliver', [ShipmentController::class, 'markAsDelivered'])->name('shipments.deliver');
    Route::resource('shipments','App\Http\Controllers\ShipmentController');
    Route::resource('clients','App\Http\Controllers\ClientController');
    Route::resource('services','App\Http\Controllers\ServiceController');
    Route::resource('company_infos','App\Http\Controllers\CompanyInfoController');
    Route::resource('vehicles','App\Http\Controllers\VehicleController');
    Route::resource('offices','App\Http\Controllers\OfficeController');
    Route::resource('rates','App\Http\Controllers\RateController');
    Route::resource('loading_sheets','App\Http\Controllers\LoadingSheetController');
    Route::resource('loading_sheets_waybills','App\Http\Controllers\ClientController');
});

require __DIR__.'/auth.php';
