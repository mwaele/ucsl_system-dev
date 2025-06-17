<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\ShipmentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StationController;
use App\Http\Controllers\RateController;
use App\Http\Controllers\ShipmentCollectionController;
use App\Http\Controllers\ShipmentItemController;
use App\Http\Controllers\MyCollectionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClientRequestController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MainController;

use App\Http\Controllers\TrackController;

Route::middleware('client.auth')->group(function () {
    Route::get('/track/{requestId}', [TrackController::class, 'getTrackingByRequestId']);
    Route::get('/tracker', [TrackController::class, 'index'])->name('tracker');
    Route::get('/track/{requestId}/pdf', [TrackController::class, 'generateTrackingPdf']);

    Route::get('/tracking', function () {
        return view('tracking.index');
    });

    Route::get('/signin', [AuthController::class, 'showSignIn'])->name('signin');
    Route::post('/signin', [AuthController::class, 'processSignIn'])->name('signin.process');

    Route::get('/guest', [AuthController::class, 'showGuest'])->name('guest');
    Route::post('/guest', [AuthController::class, 'processGuest'])->name('guest.process');
});

Route::resource('guests','App\Http\Controllers\GuestController');

Route::get('/', function () {
    return view('index');
})->middleware(['auth', 'verified'])->name('index');;



Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users/save', [UserController::class, 'store'])->name('users.store');
    Route::get('/my_collections', [MyCollectionController::class, 'show'])->name('my_collections.show');
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
    Route::get('mombasa_rates', [RateController::class, 'mombasa_office'])->name('rates.mombasa_office');
    Route::get('nairobi_rates', [RateController::class, 'nairobi_office'])->name('rates.nairobi_office');
    Route::resource('loading_sheets','App\Http\Controllers\LoadingSheetController');
    Route::resource('loading_sheets_waybills','App\Http\Controllers\ClientController');
    Route::resource('stations','App\Http\Controllers\StationController');
    Route::resource('clientRequests','App\Http\Controllers\ClientRequestController');
    Route::get('/client-requests', [ClientRequestController::class, 'index'])->name('client-requests.index');
    Route::post('/check_station_name', [StationController::class, 'checkStation']);
    Route::resource('zones','App\Http\Controllers\ZoneController');
    Route::get('/get-destinations/{office_id}', [RateController::class, 'getDestinations']);
    Route::post('/shipment_collections/store', [ShipmentCollectionController::class, 'store'])->name('shipment_collections.store');
    Route::resource('frontOffice','App\Http\Controllers\FrontOfficeController');
    Route::get('/get-cost/{originId}/{destinationId}', [RateController::class, 'getCost']);
    Route::get('/clientData', [MainController::class, 'clients']);
    Route::post('/my_collections/store', [MyCollectionController::class, 'store'])->name('my_collections.store');
    Route::put('/shipment-collections/update/{requestId}', [ShipmentCollectionController::class, 'update'])->name('shipment-collections.update');
    Route::get('/shipment-receipt/{id}', [ShipmentCollectionController::class, 'receipt'])->name('shipment.receipt');
    Route::post('/shipment-collections/walkin', [ShipmentCollectionController::class, 'create'])->name('shipment-collections.create');
    Route::delete('/shipment-collections/delete/{requestId}', [ShipmentCollectionController::class, 'delete'])->name('shipment-collections.delete');


    Route::get('/shipments/{id}/items', [ShipmentItemController::class, 'fetchItems']);

    //Route::resource('tracking','App\Http\Controllers\TrackingController');
    Route::resource('trackingInfo','App\Http\Controllers\TrackingInfoController');

    Route::resource('special_rates','App\Http\Controllers\SpecialRateController');
   // Route::get('/track/{requestId}', [TrackController::class, 'showTrackingView'])->name('track.view');


    Route::put('/update_collections/{id}', [ShipmentCollectionController::class, 'update_collections'])->name('shipments.update_collections');

    //Route::get('/track/{requestId}', [TrackController::class, 'getTrackingByRequestId']);

    //Route::get('/track/{requestId}/pdf', [TrackController::class, 'generateTrackingPdf']);
    

});






require __DIR__.'/auth.php';
