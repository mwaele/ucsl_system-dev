<?php
use Illuminate\Http\Request; // ✅ Correct: the actual Request class, not the Facade
use App\Http\Controllers\SalesPersonController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\ShipmentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StationController;
use App\Http\Controllers\RateController;
use App\Http\Controllers\ShipmentCollectionController;
use App\Http\Controllers\ShipmentItemController;
use App\Http\Controllers\MyCollectionController;
use App\Http\Controllers\ShipmentDeliveriesController;
use App\Http\Controllers\MyDeliveryController;
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
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SpecialRateController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\ClientAuthController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\TransporterController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CompanyInfoController;
use App\Http\Controllers\SameDayRateController;
use App\Http\Controllers\ShipmentArrivalController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ShipmentArrivalsController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\Accounts\Debtors\InvoiceController as DebtorInvoiceController;
use App\Http\Controllers\Accounts\Debtors\ReceiptController as DebtorReceiptController;
use App\Http\Controllers\Accounts\Debtors\DebitNoteController;
use App\Http\Controllers\Accounts\Debtors\CreditNoteController;
use App\Http\Controllers\Accounts\Creditors\InvoiceController as CreditorInvoiceController;
use App\Http\Controllers\Accounts\LedgerController;
use App\Http\Controllers\ClientPortalController;

use App\Http\Controllers\ServiceRateController;
use App\Http\Controllers\ParcelController;
use App\Http\Controllers\HomeController;


Route::middleware('client.auth')->group(function () {
    // Route::get('/tracking', function () {
    //     return view('tracking.index');
    // })->name('tracking.index');
    Route::get('/tracking', [TrackController::class, 'tracking'])->name('tracking.index');
    Route::get('/track/{requestId}', [TrackController::class, 'getTrackingByRequestId']);
    Route::get('/tracker', [TrackController::class, 'index'])->name('tracker');
    Route::get('/track/{requestId}/pdf', [TrackController::class, 'generateTrackingPdf']);
    
    Route::get('/parcel/create', [ParcelController::class, 'create'])->name('parcel.create');
    Route::post('/parcel', [ParcelController::class, 'store'])->name('parcel.store'); 

    Route::resource('service_rates', ServiceRateController::class);

    // client portal
    Route::get('/client_portal', [ClientPortalController::class, 'index'])->name('client_portal');
    Route::get('/client_portal/dashboard', [ClientPortalController::class, 'dashboard'])->name('client_portal_dashboard');
    // Route::get('/client_portal/overnight-walkin', [ClientPortalController::class, 'overnight_walkin'])->name('overnight_walkin');
    Route::get('/client_portal/overnight-on-account', [ClientPortalController::class, 'overnight_onaccount'])->name('overnight_onaccount');
    // Route::get('/client_portal/sameday-walkin', [ClientPortalController::class, 'sameday_walkin'])->name('sameday_walkin');
    Route::get('/client_portal/sameday-on-account', [ClientPortalController::class, 'sameday_on_account'])->name('sameday_on_account');

    Route::post('/client_portal_request', [ClientPortalController::class, 'store'])->name('clientPortalRequest.store');

    Route::post('/client_portal_request/on-account', [ClientPortalController::class, 'create'])->name('client_portal_request.create');

    Route::get('client_portal/get-latest-invoice-no', [InvoiceController::class, 'getLatestInvoiceNo'])->name('get-latest-invoice-no');

    Route::get('/generate-waybill/{requestId}', [ClientPortalController::class, 'generateWaybill'])->name('generate-waybill');

    Route::get('/waybill-preview/{requestId}', [ClientRequestController::class, 'preview'])->name('waybill-preview');

    Route::get('/getDestinations/{office_id}', [RateController::class, 'getDestinations']);

    Route::get('/getCost/{originId}/{destinationId}', [RateController::class, 'getCost']);

});

// Client auth routes
Route::get('/client/login', [ClientAuthController::class, 'showLoginForm'])->name('client.login');
Route::post('/client/login', [ClientAuthController::class, 'login']);
Route::post('/client/logout', [ClientAuthController::class, 'logout'])->name('client.logout');

Route::post('/client/login', [ClientAuthController::class, 'login']);
// Route::middleware('client.auth')->group(function () {
Route::post('/guests', [GuestController::class, 'store'])->name('guests.store');

Route::post('/download-chart-pdf', [DashboardController::class, 'downloadPdf']);


Route::post('/export-pdf', [DashboardController::class, 'exportPDF'])->name('export.pdf');

Route::get('/client_login', [AuthController::class, 'showSignIn'])->name('client_login');
Route::post('/signin', [AuthController::class, 'processSignIn'])->name('signin.process');

Route::get('/guest', [AuthController::class, 'showGuest'])->name('guest');

Route::get('/home',[HomeController::class, 'home'])->name('home');
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

// Route::middleware('rider')->group(function(){
// });

Route::middleware(['auth'])->group(function () {
    Route::get('/my_collections/client-portal', [MyCollectionController::class, 'collect'])->name('my_collections.collect');
//collections
    Route::get('/my_collections', [MyCollectionController::class, 'show'])->name('my_collections.show');

    Route::post('/my_collections/store', [MyCollectionController::class, 'store'])->name('my_collections.store');

    Route::get('/my_deliveries', [MyDeliveryController::class, 'show'])->name('my_deliveries.show');
    Route::post('/my_deliveries/store', [ShipmentDeliveriesController::class, 'store'])->name('my_deliveries.store');
    

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users/save', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('user.destroy');
    Route::get('/users_report', [UserController::class, 'users_report']);
    Route::put('/client/{id}', [ClientController::class, 'update'])->name('clients.update');

    Route::get('/clients_report', [ClientController::class, 'clients_report'])->name('clients_report');
   
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/vehicles/{vehicle}/allocate', [VehicleController::class, 'allocate'])->name('vehicles.allocate');
    Route::post('/shipments/{id}/deliver', [ShipmentController::class, 'markAsDelivered'])->name('shipments.deliver');
    Route::resource('delivery_faileds','App\Http\Controllers\DeliveryFailedController');
    Route::resource('shipments','App\Http\Controllers\ShipmentController');
    Route::resource('clients','App\Http\Controllers\ClientController');
    Route::resource('services','App\Http\Controllers\ServiceController');
    Route::resource('company_infos','App\Http\Controllers\CompanyInfoController');
    
    Route::get('/company_info_report', [CompanyInfoController::class, 'company_info_report'])->name('company_info_report');
    
    Route::get('/offices_report', [CompanyInfoController::class, 'offices_report'])->name('offices_report');
    

    Route::resource('vehicles','App\Http\Controllers\VehicleController');
    Route::resource('offices','App\Http\Controllers\OfficeController');
    Route::resource('rates','App\Http\Controllers\RateController');
    Route::get('mombasa_rates', [RateController::class, 'mombasa_office'])->name('rates.mombasa_office');
    Route::get('mombasa_rates_sameday', [RateController::class, 'mombasa_rates_sameday'])->name('rates.mombasa_rates_sameday');
    Route::get('nairobi_rates_sameday', [SameDayRateController::class, 'nairobi_rates_sameday'])->name('rates.nairobi_rates_sameday');

    Route::resource('rates_sameday','App\Http\Controllers\SameDayRateController');

    Route::get('/nrb_rates_sameday_report', [SameDayRateController::class, 'nrb_rates_sameday_report']);

    Route::post('/clientRequestSameDay', [SameDayController::class, 'store'])->name('clientRequestSameDay.store');

    Route::get('nrb_rates_sameday',[SameDayRateController::class,'nairobi_rates_sameday'])->name('rates.nrb_rates_sameday');
    Route::get('nairobi_rates', [RateController::class, 'nairobi_office'])->name('rates.nairobi_office');
    Route::get('/rates_report', [RateController::class, 'rates_report']);
    
    Route::get('/msa_rates_report', [RateController::class, 'msa_rates_report']);

    Route::get('/nrb_rates_report', [RateController::class, 'nrb_rates_report']);

    Route::get('/get-sales-person/{id}', [SalesPersonController::class, 'getSalesPerson']);

    Route::resource('loading_sheets','App\Http\Controllers\LoadingSheetController');
    Route::resource('sales_person','App\Http\Controllers\SalesPersonController');
    Route::resource('loading_sheets_waybills','App\Http\Controllers\ClientController');
    Route::resource('stations','App\Http\Controllers\StationController');
    Route::resource('clientRequests','App\Http\Controllers\ClientRequestController');
    Route::get('/client-requests', [ClientRequestController::class, 'index'])->name('client-requests.index');
    Route::post('/check_station_name', [StationController::class, 'checkStation']);
    Route::resource('zones','App\Http\Controllers\ZoneController');
    Route::get('/get-destinations/{office_id}', [RateController::class, 'getDestinations']);
    Route::get('/nairobi_rates_sameday', [RateController::class, 'nairobi_rates_sameday']);
    Route::post('/shipment_collections/store', [ShipmentCollectionController::class, 'store'])->name('shipment_collections.store');
    Route::resource('frontOffice','App\Http\Controllers\FrontOfficeController');
    Route::get('/get-cost/{originId}/{destinationId}', [RateController::class, 'getCost']);
    Route::get('/get-cost-same-day/{originId}/{destinationId}', [RateController::class, 'getCostSameDay']);
    Route::get('/client-requests/pdf', [ClientRequestController::class, 'exportPdf'])->name('client-requests.export.pdf');
    Route::get('/waybill/generate/{requestId}', [ClientRequestController::class, 'generateWaybill'])->name('waybill.generate');
    Route::get('/waybill/preview/{requestId}', [ClientRequestController::class, 'preview'])->name('waybill.preview');
    Route::get('/get-client-categories/{clientId}', [ClientRequestController::class, 'getClientCategories']);
    Route::get('/get-sub-categories/{categoryId}', [ClientRequestController::class, 'getSubCategories']);

    //special rates
    Route::get('/get_destinations/{office_id}/{client_id}', [SpecialRateController::class, 'getDestinations']);

    // rates 
    Route::get('/get-destination/{office_id}', [RateController::class, 'getDestinationSameDay']);

    Route::get('/get_cost/{office_id}/{destinationId}', [SameDayController::class, 'getCost']);

    Route::get('/get_cost/{originId}/{destinationId}/{client_id}', [SpecialRateController::class, 'getCost']);

    Route::get('/clientData/{cid}', [MainController::class, 'clients']);


    Route::post('/agent/request-approval', [ShipmentDeliveriesController::class, 'requestApproval'])->name('request.agent.approval');
    Route::post('/failed_delivery_alert', [ShipmentDeliveriesController::class, 'failed_delivery_alert'])->name('failed_delivery_alert');
    Route::get('/agent/approve/{requestId}', [ShipmentDeliveriesController::class, 'approveAgent'])->name('agent.approve');
    Route::post('/client-request/agent-approval', [ShipmentDeliveriesController::class, 'handleAgentApproval'])->name('client-request.agent-approval');
    Route::get('/agent/decline/{requestId}', [ShipmentDeliveriesController::class, 'showDeclineForm'])->name('agent.decline.form');
    Route::post('/agent/decline/{requestId}', [ShipmentDeliveriesController::class, 'submitDecline'])->name('agent.decline.submit');

    Route::get('/collections_report', [MyCollectionController::class, 'collections_report'])->name('collections_report');

    Route::put('/shipment-collections/update/{requestId}', [ShipmentCollectionController::class, 'update'])->name('shipment-collections.update');
    Route::get('/shipment-receipt/{id}', [ShipmentCollectionController::class, 'receipt'])->name('shipment.receipt');
    Route::post('/shipment-collections/walkin', [ShipmentCollectionController::class, 'create'])->name('shipment-collections.create');
    Route::delete('/shipment-collections/delete/{requestId}', [ShipmentCollectionController::class, 'delete'])->name('shipment-collections.delete');

    Route::get('/get-latest-invoice-no', [InvoiceController::class, 'getLatestInvoiceNo'])->name('get.latest.invoice.no');

    Route::get('/generate-invoice/{id}', [InvoiceController::class, 'generateInvoice'])->name('generate-invoice');

    Route::get('/generate-receipt/{id}', [PaymentController::class, 'generateReceipt'])->name('generate-receipt');


    Route::get('/overnight/walk-in', [OvernightController::class, 'walk_in'])->name('overnight.walk-in');
    Route::get('/overnight/on-account', [OvernightController::class, 'on_account'])->name('overnight.on-account');
    Route::get('client/overnight/on-account', [OvernightController::class, 'client_on_account'])->name('client.overnight.on-account');
    Route::get('/walkin_report', [OvernightController::class, 'walkin_report'])->name('walkin_report');
    Route::get('/overnight_account_report', [OvernightController::class, 'overnight_account_report'])->name('overnight_account_report');
    Route::get('/client_overnight_account_report', [OvernightController::class, 'client_overnight_account_report'])->name('client_overnight_account_report');
    Route::put('/update_rider/{id}', [OvernightController::class, 'updateRider'])->name('client_request.update_rider');
    Route::get('/sameday_walkin_report', [SameDayController::class, 'sameday_walkin_report'])->name('sameday_walkin_report');
    Route::get('/sameday_account_report', [SameDayController::class, 'sameday_account_report'])->name('sameday_account_report');

    Route::get('/sameday/walk-in', [SameDayController::class, 'walk_in'])->name('sameday.walk-in');
    Route::get('/sameday/on-account', [SameDayController::class, 'on_account'])->name('sameday.on-account');
    Route::get('client/sameday/on-account', [SameDayController::class, 'client_on_account'])->name('client.sameday.on-account');

    Route::resource('shipment_arrival', 'App\Http\Controllers\ShipmentArrivalsController');

    Route::post('/shipment_arrival/{id}', [ShipmentArrivalsController::class, 'saveArrivals'])->name('shipment_arrival');

    //parcel receipts
    Route::resource('parcelReceipts','App\Http\Controllers\ParcelReceiptController');

    // Route::resource('shipment_arrivals','App\Http\Controllers\ShipmentArrivalController');

    Route::resource('parcelReceiptItems','App\Http\Controllers\ParcelReceiptItemController');

    // payments
    Route::resource('payments','App\Http\Controllers\PaymentController');

    Route::get('/payments_report', [PaymentController::class, 'payments_report'])->name('payments_report');

    Route::get('/shipments/{id}/items', [ShipmentItemController::class, 'fetchItems']);

    //Route::resource('tracking','App\Http\Controllers\TrackingController');
    Route::resource('trackingInfo','App\Http\Controllers\TrackingInfoController');

    Route::resource('special_rates','App\Http\Controllers\SpecialRateController');
    // Route::get('/track/{requestId}', [TrackController::class, 'showTrackingView'])->name('track.view');

    Route::get('/drivers/by-location', [UserController::class, 'getDriversByLocation'])->name('drivers.byLocation');

    Route::get('/drivers/unallocated', [UserController::class, 'getUnallocatedDrivers'])->name('drivers.unallocated');
    Route::get('/drivers/all', [UserController::class, 'getAllDrivers'])->name('drivers.all');

    Route::put('/update_collections/{id}', [ShipmentCollectionController::class, 'update_collections'])->name('shipments.update_collections');

    Route::get('/check-client/{id?}', [ClientController::class, 'checkClient'])->name('check.client');


    //Route::get('/track/{requestId}', [TrackController::class, 'getTrackingByRequestId']);

    //Route::get('/track/{requestId}/pdf', [TrackController::class, 'generateTrackingPdf']);

    Route::resource('categories','App\Http\Controllers\CategoryController');

    Route::resource('shipment_deliveries','App\Http\Controllers\shipmentDeliveriesController');

    Route::resource('shipment_arrivals','App\Http\Controllers\ShipmentArrivalController');

    Route::get('/shipment_arrivals_report', [ShipmentArrivalController::class, 'generate'])
    ->name('shipment_arrivals_report');

    Route::get('/shipment_arrivals_report_detail', [ShipmentArrivalController::class, 'generateParcels'])
    ->name('shipment_arrivals_report_detail');

    Route::get('/shipment_arrivals_report_uncollected/{id}/{type}', [ShipmentArrivalController::class, 'generateParcelsUncollected'])
    ->name('shipment_arrivals_report_uncollected');

    Route::get('/arrival_details/{id}', [ShipmentArrivalController::class, 'arrival_details'])->name('arrival_details');
    Route::get('/parcel_collection', [ShipmentArrivalController::class, 'parcel_collection'])->name('parcel_collection');
    Route::post('/arrivals/{id}/issue', [ShipmentArrivalController::class, 'issue'])->name('arrivals.issue');
    Route::get('/parcel-collection-report', [ShipmentArrivalController::class, 'parcel_collection_report']);

    Route::put('/clients_update/{id}', [ClientController::class, 'update_otp'])->name('clients_update.update_otp');
    

    Route::put('/shipment-arrivals/{id}', [ShipmentArrivalController::class, 'update'])->name('shipment-arrivals.update');

    Route::put('/client_request/{id}', [SameDayController::class, 'update'])->name('client_request.update');

    Route::post('/update-arrival-details', [ShipmentArrivalController::class, 'updateArrivalDetails'])->name('update_arrival_details');

    Route::resource('lastMileDelivery','App\Http\Controllers\LastMileDeliveryController');

    Route::resource('transporters','App\Http\Controllers\TransporterController');
    Route::resource('dispatchers','App\Http\Controllers\DispatcherController');
    Route::get('transporter/trucks/{id}', [TransporterController::class, 'fetchTrucks']);

    Route::resource('transporter_trucks','App\Http\Controllers\TransporterTrucksController');

    Route::get('/get-trucks/{transporterId}', [TransporterController::class, 'getTrucks']);

    Route::get('/loadingsheet_waybills/{id}', [LoadingSheetController::class, 'loadingsheet_waybills'])->name('loadingsheet_waybills');

    Route::get('/shipment-collection/{id}', [LoadingSheetController::class, 'show']);

    Route::get('/generate_loading_sheet/{id}', [LoadingSheetController::class, 'generate_loading_sheet']);

    Route::post('/get-shipment-items', [ShipmentItemController::class, 'getItems']);
    Route::get('/search', [SearchController::class, 'search'])->name('search');;

    Route::get('/transporters_report', [TransporterController::class, 'transporter_report']);

    Route::get('/transporter_trucks_report/{id}', [TransporterController::class, 'transporterTrucksReport']);

    Route::post('/loading-sheets/{id}/dispatch', [LoadingSheetController::class, 'dispatch']);

    Route::resource('loading_sheet_waybills','App\Http\Controllers\LoadingSheetWaybillController');

      
    Route::resource('sub_categories','App\Http\Controllers\SubCategoryController');

    Route::resource('client_categories','App\Http\Controllers\ClientCategoryController');

    Route::get('/locations/search', [LocationController::class, 'search'])->name('locations.search');

    Route::prefix('accounts')->name('accounts.')->group(function () {

        // ---------------- Debtors (AR) ----------------
        Route::prefix('debtors')->name('debtors.')->group(function () {
            Route::resource('invoices', DebtorInvoiceController::class)->names('invoices');
            Route::resource('receipts', DebtorReceiptController::class)->names('receipts');
            Route::resource('debit-notes', DebitNoteController::class)->names('debit_notes');
            Route::resource('credit-notes', CreditNoteController::class)->names('credit_notes');
            Route::get('invoices/unposted-report', [DebtorInvoiceController::class, 'unposted_invoices_report'])->name('invoices.unposted_report');
        });

        // ---------------- Creditors (AP) ----------------
        Route::prefix('creditors')->name('creditors.')->group(function () {
            Route::resource('invoices', CreditorInvoiceController::class)->names('invoices');
        });

        // ---------------- General Ledger ----------------
        Route::prefix('ledger')->name('ledger.')->group(function () {
            Route::get('journals', [LedgerController::class, 'journals'])->name('journals');
            Route::get('trial-balance', [LedgerController::class, 'trialBalance'])->name('trial_balance');
            Route::get('reports', [LedgerController::class, 'reports'])->name('reports');
        });

    });

    Route::get('/unposted_invoices_report', [DebtorInvoiceController::class, 'unposted_invoices_report'])->name('unposted_invoices_report');
    Route::post('/accounts/debtors/invoice/post-invoice/{id}', [DebtorInvoiceController::class, 'postInvoice'])->name('accounts.debtors.invoices.postInvoice');
    Route::get('/accounts/debtors/invoice/statement/{id}', [DebtorInvoiceController::class, 'client_statement'])->name('accounts-receivable.statement');
    Route::post('/accounts/debtors/invoices/{id}/payment', [DebtorInvoiceController::class, 'postPayment'])->name('accounts.debtors.invoices.postPayment');
    Route::get('/test-aging', [DebtorInvoiceController::class, 'testAging']);

    Route::prefix('reports')->group(function () {
        Route::get('/shipment-report', [ReportController::class, 'shipmentReport'])->name('reports.shipment');
        Route::get('/client-performance-report', [ReportController::class, 'clientPerformanceReport'])->name('reports.client_performance');
        Route::get('/office-performance-report', [ReportController::class, 'officePerformanceReport'])->name('reports.office_performance');
        Route::get('/dispatch-summary-report', [ReportController::class, 'dispatchSummaryReport'])->name('reports.dispatch_summary');
    });

    Route::get('/reports/filter', [ReportController::class, 'filter'])->name('reports.filter');
    Route::get('/reports/pdf', [ReportController::class, 'exportPdf'])->name('reports.exportPdf');
    Route::get('/shipment_report/generate', [ReportController::class, 'shipmentReportGenerate'])->name('shipment_report.generate');
    Route::get('/client_performance_report/generate', [ReportController::class, 'clientPerformanceReportGenerate'])->name('client_performance_report.generate');
    Route::get('/office_performance_report/generate', [ReportController::class, 'officePerformanceReportGenerate'])->name('office_performance_report.generate');
    Route::get('/dispatch_summary_report/generate', [ReportController::class, 'dispatchSummaryReportGenerate'])->name('dispatch_summary_report.generate');

    Route::get('/reports/office/{office}', [ReportController::class, 'officePerformanceDetail'])->name('reports.office.detail');
    Route::get('/reports/client/{id}', [ReportController::class, 'clientDetail'])->name('reports.client.detail');

});


require __DIR__.'/auth.php';
