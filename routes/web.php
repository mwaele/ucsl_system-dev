<?php
use Illuminate\Http\Request; // ✅ Correct: the actual Request class, not the Facade
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
use App\Http\Controllers\LocationController;
use App\Http\Controllers\LoadingSheetController;
use App\Http\Controllers\OvernightController;
use App\Http\Controllers\SameDayController;
use App\Http\Controllers\TrackController;
use App\Http\Controllers\SpecialRateController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\ClientAuthController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\TransporterController;

Route::middleware('client.auth')->group(function () {
    Route::get('/track/{requestId}', [TrackController::class, 'getTrackingByRequestId']);
    Route::get('/tracker', [TrackController::class, 'index'])->name('tracker');
    Route::get('/track/{requestId}/pdf', [TrackController::class, 'generateTrackingPdf']);

    Route::get('/tracking', function () {
        return view('tracking.index');
});
     
});

Route::post('/client/login', [ClientAuthController::class, 'login']);
// Route::middleware('client.auth')->group(function () {
Route::post('/guests', [GuestController::class, 'store'])->name('guests.store');



Route::get('client_login', [AuthController::class, 'showSignIn'])->name('client_login');
Route::post('/signin', [AuthController::class, 'processSignIn'])->name('signin.process');

Route::get('/guest', [AuthController::class, 'showGuest'])->name('guest');
Route::post('/guest', [AuthController::class, 'processGuest'])->name('guest.process'); 

Route::resource('guests','App\Http\Controllers\GuestController');

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return response()->json(['message' => 'Logged out']);
});

// Route::post('/client/logout', function (Request $request) {
//     Auth::guard('client')->logout();
//     $request->session()->invalidate();
//     $request->session()->regenerateToken();

//     return response()->json(['message' => 'Logged out']);
// })->middleware('web')->name('client.logout');
Route::post('/client/logout', [AuthController::class, 'logout'])
    ->middleware('web')
    ->name('client.logout');

// Route::get('/', function () {
//     return view('index');
// })->middleware(['auth', 'verified'])->name('index');;



Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users/save', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('user.destroy');
    Route::get('/users_report', [UserController::class, 'users_report']);
    Route::put('/client/{id}', [ClientController::class, 'update'])->name('clients.update');
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
    Route::get('/rates_report', [RateController::class, 'rates_report']);
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
    Route::get('/client-requests/pdf', [ClientRequestController::class, 'exportPdf'])->name('client-requests.export.pdf');
    Route::get('/waybill/generate/{requestId}', [ClientRequestController::class, 'generateWaybill'])->name('waybill.generate');
    Route::get('/waybill/preview/{requestId}', [ClientRequestController::class, 'preview'])->name('waybill.preview');
    Route::get('/get-client-categories/{clientId}', [ClientRequestController::class, 'getClientCategories']);
    Route::get('/get-sub-categories/{categoryId}', [ClientRequestController::class, 'getSubCategories']);

    //special rates
    Route::get('/get_destinations/{office_id}/{client_id}', [SpecialRateController::class, 'getDestinations']);

    Route::get('/get_cost/{originId}/{destinationId}/{client_id}', [SpecialRateController::class, 'getCost']);

    Route::get('/clientData/{cid}', [MainController::class, 'clients']);
    Route::post('/my_collections/store', [MyCollectionController::class, 'store'])->name('my_collections.store');
    Route::put('/shipment-collections/update/{requestId}', [ShipmentCollectionController::class, 'update'])->name('shipment-collections.update');
    Route::get('/shipment-receipt/{id}', [ShipmentCollectionController::class, 'receipt'])->name('shipment.receipt');
    Route::post('/shipment-collections/walkin', [ShipmentCollectionController::class, 'create'])->name('shipment-collections.create');
    Route::delete('/shipment-collections/delete/{requestId}', [ShipmentCollectionController::class, 'delete'])->name('shipment-collections.delete');


    Route::get('/overnight/walk-in', [OvernightController::class, 'walk_in'])->name('overnight.walk-in');
    Route::get('/overnight/on-account', [OvernightController::class, 'on_account'])->name('overnight.on-account');

    Route::get('/overnight_account_report', [OvernightController::class, 'overnight_account_report'])->name('overnight_account_report');
    

    Route::get('/sameday/walk-in', [SameDayController::class, 'walk_in'])->name('sameday.walk-in');
    Route::get('/sameday/on-account', [SameDayController::class, 'on_account'])->name('sameday.on-account');

    Route::get('/shipments/{id}/items', [ShipmentItemController::class, 'fetchItems']);

    //Route::resource('tracking','App\Http\Controllers\TrackingController');
    Route::resource('trackingInfo','App\Http\Controllers\TrackingInfoController');

    Route::resource('special_rates','App\Http\Controllers\SpecialRateController');
   // Route::get('/track/{requestId}', [TrackController::class, 'showTrackingView'])->name('track.view');

   Route::get('/drivers/by-location', [UserController::class, 'getDriversByLocation'])->name('drivers.byLocation');

   Route::get('/drivers/unallocated', [UserController::class, 'getUnallocatedDrivers'])->name('drivers.unallocated');
   Route::get('/drivers/all', [UserController::class, 'getAllDrivers'])->name('drivers.all');

    Route::put('/update_collections/{id}', [ShipmentCollectionController::class, 'update_collections'])->name('shipments.update_collections');

    //Route::get('/track/{requestId}', [TrackController::class, 'getTrackingByRequestId']);

    //Route::get('/track/{requestId}/pdf', [TrackController::class, 'generateTrackingPdf']);
    
      Route::resource('categories','App\Http\Controllers\CategoryController');

      Route::resource('transporters','App\Http\Controllers\TransporterController');
      Route::resource('dispatchers','App\Http\Controllers\DispatcherController');
      Route::get('transporter/trucks/{id}', [TransporterController::class, 'fetchTrucks']);

      Route::resource('transporter_trucks','App\Http\Controllers\TransporterTrucksController');

      Route::get('/get-trucks/{transporterId}', [TransporterController::class, 'getTrucks']);

      Route::get('/loadingsheet_waybills/{id}', [LoadingSheetController::class, 'loadingsheet_waybills'])->name('loadingsheet_waybills');

      Route::get('/shipment-collection/{id}', [LoadingSheetController::class, 'show']);

      Route::get('/generate_loading_sheet/{id}', [LoadingSheetController::class, 'generate_loading_sheet']);

      Route::post('/get-shipment-items', [ShipmentItemController::class, 'getItems']);

      Route::get('/transporters_report', [TransporterController::class, 'transporter_report']);

      Route::get('/transporter_trucks_report/{id}', [TransporterController::class, 'transporterTrucksReport']);

    Route::post('/loading-sheets/{id}/dispatch', [LoadingSheetController::class, 'dispatch']);

    Route::resource('loading_sheet_waybills','App\Http\Controllers\LoadingSheetWaybillController');

       
      Route::resource('sub_categories','App\Http\Controllers\SubCategoryController');

      Route::resource('client_categories','App\Http\Controllers\ClientCategoryController');

      Route::get('/locations/search', [LocationController::class, 'search'])->name('locations.search');
   

});






require __DIR__.'/auth.php';
