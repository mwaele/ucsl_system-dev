<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ClientRequest;
use Illuminate\Support\Facades\DB;
use App\Models\Client;
use App\Models\ClientCategory;
use App\Models\SubCategory;
use App\Models\Office;
use App\Models\Vehicle;
use App\Models\SentMessage;
use App\Models\ShipmentCollection;
use App\Models\DeliveryControl;
use App\Models\User;
use App\Models\Tracking;
use App\Models\TrackingInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Services\SmsService;
use App\Mail\GenericMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\HtmlString;
use App\Traits\PdfReportTrait;
use App\Services\RequestIdService;
use App\Helpers\EmailHelper;
use App\Models\Location;
use App\Jobs\SendCollectionNotificationsJob;

class ClientRequestController extends Controller
{
    use PdfReportTrait;

    protected $requestIdService;

    public function __construct(RequestIdService $requestIdService)
    {
        $this->requestIdService = $requestIdService;
    }
    
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $user = Auth::user();

        // Prepare query
        $query = ClientRequest::with([
            'client',
            'vehicle',
            'user',
            'shipmentCollection',
            'shipmentCollection.items.subItems',
            'createdBy'
        ]);

        // Filters
        $stationName = $request->query('station');
        $status = $request->query('status');
        $timeFilter = $request->query('time', 'all');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $dateRange = null;

        if ($startDate && $endDate) {
            $dateRange = [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay(),
            ];
        } else {
            $dateRange = match ($timeFilter) {
                'daily' => [now()->startOfDay(), now()->endOfDay()],
                'weekly' => [now()->startOfWeek(), now()->endOfWeek()],
                'biweekly' => [now()->subDays(14)->startOfDay(), now()->endOfDay()],
                'monthly' => [now()->startOfMonth(), now()->endOfMonth()],
                'yearly' => [now()->startOfYear(), now()->endOfYear()],
                default => null
            };
        }

        if ($user->role === 'admin') {
            if ($stationName) {
                $officeId = Office::where('name', $stationName)->value('id');
                if ($officeId) {
                    $query->where('office_id', $officeId);
                }
            }
        } else {
            $query->where('office_id', $user->station);
        }

        if ($status) {
            if (is_array($status)) {
                $query->whereIn('status', $status);
            } else {
                $query->where('status', $status);
            }
        }

        if ($dateRange) {
            $query->whereBetween('created_at', $dateRange);
        }

        $queryParams = [
            'station' => $stationName,
            'status' => $status,
            'time' => $timeFilter,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];

        $exportPdfUrl = URL::route('client-requests.export.pdf', array_filter($queryParams));

        $query->whereIn('clientId', function ($q) {
            $q->select('id')->from('clients');
        });

        $client_requests = $query->orderBy('created_at', 'desc')->get();

        $clients = Client::where('type', 'on_account')->get();
        $vehicles = Vehicle::all();
        $drivers = User::where('role', 'driver')->get();
        $sub_categories = SubCategory::all();

        // Base query for summaries
        if ($user->role === 'admin') {
            $baseQuery = ClientRequest::query();
            if ($stationName) {
                $officeId = Office::where('name', $stationName)->value('id');
                if ($officeId) {
                    $baseQuery->where('office_id', $officeId);
                }
            }
        } else {
            $baseQuery = ClientRequest::where('office_id', $user->station);
        }

        if ($dateRange) {
            $baseQuery->whereBetween('created_at', $dateRange);
        }

        // --- Summary counts ---
        $totalRequests = (clone $baseQuery)->count();
        $delivered = (clone $baseQuery)->where('status', 'delivered')->count();
        $collected = (clone $baseQuery)->where('status', 'collected')->count();
        $verified = (clone $baseQuery)->where('status', 'verified')->count();
        $pending_collection = (clone $baseQuery)->where('status', 'pending collection')->count();

        // --- Delivery-specific metrics ---
        $ctrTime = DeliveryControl::where('control_id', 'CTRL-0001')
            ->value('ctr_time');

        $timeLimit = Carbon::now()->subHours((int) $ctrTime);
        $shipmentBase = ShipmentCollection::query()
            ->when($user->role !== 'admin', fn($q) =>
                $q->whereHas('clientRequest', fn($r) => $r->where('office_id', $user->station))
            )
            ->when($stationName && $user->role === 'admin', function ($q) use ($stationName) {
                $officeId = Office::where('name', $stationName)->value('id');
                if ($officeId) {
                    $q->whereHas('clientRequest', fn($r) => $r->where('office_id', $officeId));
                }
            })
            ->when($dateRange, fn($q) => $q->whereBetween('created_at', $dateRange));

        $undeliveredParcels = (clone $shipmentBase)->where('status', 'arrived')->get();
        $onTransitParcels = (clone $shipmentBase)->whereIn('status', [
                'Delivery Rider Allocated',
                'delivery_rider_allocated'
            ])
            ->get();
        $delayedDeliveries = (clone $shipmentBase)->whereIn('status', [
                'Delivery Rider Allocated',
                'delivery_rider_allocated'
            ])
            ->where('updated_at', '<', $timeLimit)
            ->whereNotIn('status', ['delivery_failed', 'parcel_delivered'])
            ->get();
        $failedDeliveries = (clone $shipmentBase)->where('status', 'del
        ivery_failed')
            ->get();
        $successfulDeliveries = (clone $shipmentBase)->where('status', 'parcel_delivered')
            ->get();
        return view('client-request.index', compact(
            'clients',
            'vehicles',
            'drivers',
            'client_requests',
            'totalRequests',
            'delivered',
            'collected',
            'verified',
            'pending_collection',
            'undeliveredParcels',
            'onTransitParcels',
            'failedDeliveries',
            'successfulDeliveries',
            'delayedDeliveries',
            'timeFilter',
            'startDate',
            'endDate',
            'exportPdfUrl',
            'sub_categories',
            'status',
        ));
    }

    public function generateWaybill($requestId) 
    {
        $collection = ShipmentCollection::with(['items', 'office', 'destination', 'clientRequest.serviceLevel'])->where('requestId', $requestId)->firstOrFail();
        $pdf = PDF::loadView('pdf.waybill', [
            'collection' => $collection,
            'isPdf' => true
        ])->setPaper('a5', 'portrait');
        return $pdf->stream('Waybill_'.$collection->waybill_no.'.pdf');
    }

    public function preview($requestId)
    {
        $collection = ShipmentCollection::with(['items', 'office', 'destination', 'clientRequest.serviceLevel'])->where('requestId', $requestId)->firstOrFail();
        return view('pdf.waybill', ['collection' => $collection, 'isPdf' => false]);
    }
    
    public function exportPdf(Request $request)
    {
        $user = Auth::user();
        $stationParam = $request->query('station');
        $status = $request->query('status');
        $timeFilter = $request->query('time', 'all');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        if ($user->role === 'admin') {
            $station = $stationParam ?: 'All';
        } else {
            $station = Office::where('id', $user->station)->value('name') ?? 'Unknown';
        }

        if ($startDate && $endDate) {
            $dateRange = [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay(),
            ];
        } else {
            $dateRange = match ($timeFilter) {
                'daily' => [now()->startOfDay(), now()->endOfDay()],
                'weekly' => [now()->startOfWeek(), now()->endOfWeek()],
                'biweekly' => [now()->subDays(14)->startOfDay(), now()->endOfDay()],
                'monthly' => [now()->startOfMonth(), now()->endOfMonth()],
                'yearly' => [now()->startOfYear(), now()->endOfYear()],
                default => null
            };
        }

        $query = ClientRequest::with(['client', 'vehicle', 'user', 'shipmentCollection', 'createdBy', 'office']);

        if ($user->role === 'admin') {
            if ($station && strtolower($station) !== 'all') {
                $officeId = Office::where('name', $station)->value('id');
                if ($officeId) {
                    $query->where('office_id', $officeId);
                }
            }
        } else {
            $query->where('office_id', $user->station);
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($dateRange) {
            $query->whereBetween('created_at', $dateRange);
        }

        $client_requests = $query->orderBy('created_at', 'desc')->get();

        return $this->renderPdfWithPageNumbers(
            'pdf.client-requests',
            [
                'client_requests' => $client_requests,
                'station' => $station,
                'status' => $status,
                'reportingPeriod' => $dateRange,
                'timeFilter' => $timeFilter,
            ],
            'client_requests.pdf',
            'a4',
            'landscape'
        );
    }
    
    public function exportPdfDelayed(Request $request)
{
    $user = Auth::user();
    $stationParam = $request->query('station');
    $timeFilter = $request->query('time', 'all');
    $startDate = $request->query('start_date');
    $endDate = $request->query('end_date');

    // Determine station name
    if ($user->role === 'admin') {
        $station = $stationParam ?: 'All';
    } else {
        $station = Office::where('id', $user->station)->value('name') ?? 'Unknown';
    }

    // Determine date range
    if ($startDate && $endDate) {
        $dateRange = [
            Carbon::parse($startDate)->startOfDay(),
            Carbon::parse($endDate)->endOfDay(),
        ];
    } else {
        $dateRange = match ($timeFilter) {
            'daily' => [now()->startOfDay(), now()->endOfDay()],
            'weekly' => [now()->startOfWeek(), now()->endOfWeek()],
            'biweekly' => [now()->subDays(14)->startOfDay(), now()->endOfDay()],
            'monthly' => [now()->startOfMonth(), now()->endOfMonth()],
            'yearly' => [now()->startOfYear(), now()->endOfYear()],
            default => null,
        };
    }

    // Build base query
    $query = ClientRequest::with(['client', 'vehicle', 'user', 'shipmentCollection', 'createdBy', 'office'])
        ->where('status', 'pending collection')
        ->where('updated_at', '<', Carbon::now()->subHours(2)); // Delayed by more than 2 hours
        

    // Filter by station
    if ($user->role === 'admin') {
        if ($stationParam && strtolower($stationParam) !== 'all') {
            $officeId = Office::where('name', $stationParam)->value('id');
            if ($officeId) {
                $query->where('office_id', $officeId);
            }
        }
    } else {
        $query->where('office_id', $user->station);
    }

    // Apply optional date range
    if ($dateRange) {
        $query->whereBetween('created_at', $dateRange);
    }

    // Fetch delayed pending collection requests
    $client_requests = $query->orderBy('created_at', 'desc')->get();

    // Generate PDF
    return $this->renderPdfWithPageNumbers(
        'pdf.client-requests',
        [
            'client_requests' => $client_requests,
            'station' => $station,
            'status' => 'Delayed Collection (Pending > 2 Hours)',
            'reportingPeriod' => $dateRange,
            'timeFilter' => $timeFilter,
        ],
        'delayed_collections.pdf',
        'a4',
        'landscape'
    );
}


    public function getClientCategories($clientId)
    {
        $categories = ClientCategory::where('client_id', $clientId)
            ->join('categories', 'client_categories.category_id', '=', 'categories.id')
            ->select('categories.id as category_id', 'categories.category_name')
            ->get();

        return response()->json($categories);
    }

    public function getSubCategories($categoryId)
    {
        $subCategories = SubCategory::where('category_id', $categoryId)->get();
        return response()->json($subCategories);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //      // Validate incoming request data
    // $validated = $request->validate([
    //     'clientId' => 'required|integer|exists:clients,id',
    //     'collectionLocation' => 'required|string|max:255',
    //     'parcelDetails' => 'required|string',
    //     'dateRequested' => 'required|date',
    //     'vehicleId' => 'required|integer|exists:vehicles,id',
    //     'requestId' => 'required|string|unique:client_requests,requestId',
    // ]);

    // // Wrap in DB transaction to ensure atomicity
    // DB::beginTransaction();

    // try {
    //     // Create client request
    //     $clientRequest = new ClientRequest();
    //     $clientRequest->clientId = $validated['clientId'];
    //     $clientRequest->collectionLocation = $validated['collectionLocation'];
    //     $clientRequest->parcelDetails = $validated['parcelDetails'];
    //     $clientRequest->dateRequested = Carbon::parse($validated['dateRequested'])->format('Y-m-d H:i:s');
    //     $clientRequest->userId = Auth::user()->id; // Avoid trusting user input
    //     $clientRequest->vehicleId = $validated['vehicleId'];
    //     $clientRequest->requestId = $validated['requestId'];
    //     $clientRequest->save();

    //     // Insert into tracking_table (using Eloquent or Query Builder)
    //     $trackingId = DB::table('tracks')->insertGetId([
    //         'requestId' => $clientRequest->id,   // FK to client_requests
    //         'clientId' => $clientRequest->clientId,
    //         'clientRequestId' => $validated['requestId'],
    //         'created_at' => now(),
    //         'updated_at' => now()
    //     ]);
    //     // Insert into tracking_infos
    //     DB::table('tracking_infos')->insert([
    //         'trackId' => $trackingId,
    //         'date' => now(),
    //         'details' => 'Client Request Submitted for Collection',
    //         'created_at' => now(),
    //         'updated_at' => now()
    //     ]);

    //     DB::commit();

    //     return redirect()->route('clientRequests.index')->with('Success', 'Client request saved and tracking updated.');

    // } catch (\Exception $e) {
    //     DB::rollBack();
    //     return redirect()->back()->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()]);
    // }
        // $validated = $request->validate([
        //     'clientId' => 'required|integer|exists:clients,id',
        //     'collectionLocation' => 'required|string|max:255',
        //     'parcelDetails' => 'required|string',
        //     'dateRequested' => 'required|date',
        //     'userId' => 'required|integer|exists:users,id',
        //     'vehicleId' => 'required|integer|exists:vehicles,id',
        //     'requestId' => 'required|string|unique:client_requests,requestId',
        // ]);

        // $clientRequest = new ClientRequest();
        // $clientRequest->clientId = $validated['clientId'];
        // $clientRequest->collectionLocation = $validated['collectionLocation'];
        // $clientRequest->parcelDetails = $validated['parcelDetails'];
        // $clientRequest->dateRequested = Carbon::parse($validated['dateRequested'])->format('Y-m-d H:i:s');
        // $clientRequest->userId = $validated['userId'];
        // $clientRequest->vehicleId = $validated['vehicleId'];
        // $clientRequest->requestId = $validated['requestId'];
        // try {
        //    $client_request = $clientRequest->save();

        //    // update tracking
        //    Tracking::create(

        //    )


        // } catch (\Exception $e) {
        //     return redirect()->back()->withErrors(['error' => 'Failed to save client request.']);
        // }

        // return redirect()->route('clientRequests.index')->with('Success', 'Client request saved successfully.');
    //}

    public function store(Request $request)
    {
        $requestId = $this->requestIdService->generate();

        //dd($request);
        $validated = $request->validate([
            'clientId' => 'required|integer',
            'collectionLocation' => 'required|string',
            'parcelDetails' => 'required|string',
            'dateRequested' => 'required|date',
            'userId' => 'required|integer',
            'vehicleId' => 'required|integer',
            'category_id' => 'required|integer',
            'sub_category_id' => 'required|integer',
            'priority_level' => 'required|string',
            'deadline_date' => 'nullable|date',
            'priority_extra_charge' => 'nullable|numeric',
            'fragile' => 'nullable|string',
            'fragile_charge' => 'nullable|numeric',
            //'rate_id'=>'nullable',
        ]);

        DB::beginTransaction();

        try {
            // 1. Create client request
            $clientRequest = ClientRequest::create([
                'clientId' => $validated['clientId'],
                'collectionLocation' => $validated['collectionLocation'],
                'parcelDetails' => $validated['parcelDetails'],
                'dateRequested' => Carbon::parse($validated['dateRequested']),
                'userId' => $validated['userId'],
                'vehicleId' => $validated['vehicleId'],
                'requestId' => $requestId,
                'category_id' => $validated['category_id'],
                'sub_category_id' => $validated['sub_category_id'],
                'priority_level' => $validated['priority_level'],
                'deadline_date' => $validated['deadline_date'],
                'created_by' => Auth::id(),
                'office_id' => Auth::user()->station,
                'priority_level_amount' => $validated['priority_extra_charge'] ?? 0,
                'fragile_item' => $validated['fragile'] ?? 'no',
                'fragile_item_amount' => $validated['fragile_charge'] ?? 0,
            ]);

            // 2. Create track
            $trackingId = DB::table('tracks')->insertGetId([
                'requestId' => $clientRequest->requestId,
                'clientId' => $clientRequest->clientId,
                'current_status' => 'Awaiting Collection',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 3. Create tracking info
            $rider = User::find($clientRequest->userId);
            $vehicle = Vehicle::find($clientRequest->vehicleId);

            DB::table('tracking_infos')->insert([
                'trackId' => $trackingId,
                'date' => now(),
                'details' => 'Client Request Submitted for Collection',
                'user_id' => $rider->id,
                'vehicle_id' => $vehicle->id,
                'remarks' => "Received client collection request, generated client request ID {$clientRequest->requestId}, allocated {$rider->name} {$vehicle->regNo} for collection",
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 4. Ensure collection location exists
            Location::firstOrCreate(['location' => $clientRequest->collectionLocation]);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Client Request Store Error', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return back()->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
        }

        // 5. Dispatch background job to send notifications
        $client = Client::find($clientRequest->clientId);
        SendCollectionNotificationsJob::dispatch($clientRequest, $client, $rider, $vehicle);

        return redirect()->back()->with('success', 'Client request submitted successfully.');
    }


    // public function store(Request $request)
    // {       
    //     $client_requests = new ClientRequest();
    //     $client_requests -> clientId = $request -> clientId;
    //     $client_requests -> collectionLocation = $request -> collectionLocation;
    //     $client_requests -> parcelDetails = $request -> parcelDetails;
    //     $client_requests->dateRequested = Carbon::parse($request->dateRequested)->format('Y-m-d H:i:s');
    //     $client_requests -> userId = $request -> userId;
    //     $client_requests -> vehicleId = $request -> vehicleId;
    //     $client_requests -> requestId = $request -> requestId;
    //     $client_requests->save();
        
    //     return redirect()->route('clientRequests.index')->with('Success', 'client request Saved Successfully');
    // }

    /**
     * Display the specified resource.
     */
    public function show(ClientRequest $clientRequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ClientRequest $clientRequest)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $requestId)
    {
        //
        $clientRequest = ClientRequest::where('requestId', $requestId)->firstOrFail();;

        $clientRequest->clientId = $request->clientId;
        $clientRequest->collectionLocation = $request->collectionLocation;
        $clientRequest->dateRequested = $request->dateRequested;
        $clientRequest->userId = $request->userId;
        $clientRequest->vehicleId = $request->vehicleId;
        $clientRequest->parcelDetails = $request->parcelDetails;

        $clientRequest->save();

        return back()->with('success', 'Client request updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $clientRequest = ClientRequest::where('requestId', $id)->firstOrFail();
        $clientRequest->delete();

        return back()->with('success', 'Client request deleted successfully.');
    }

    public function delayed_collection(Request $request)
     {

        $user = Auth::user();

        // Prepare query
        $query = ClientRequest::with([
            'client',
            'vehicle',
            'user',
            'shipmentCollection.items.subItems',
            'createdBy'
        ]);

        // Filters
        $stationName = $request->query('station');
        $status = $request->query('status');
        $timeFilter = $request->query('time', 'all');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $dateRange = null;
        $stat= "pending collection";

        //dd($status);

        if ($startDate && $endDate) {
            $dateRange = [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay(),
            ];
        } else {
            $dateRange = match ($timeFilter) {
                'daily' => [now()->startOfDay(), now()->endOfDay()],
                'weekly' => [now()->startOfWeek(), now()->endOfWeek()],
                'biweekly' => [now()->subDays(14)->startOfDay(), now()->endOfDay()],
                'monthly' => [now()->startOfMonth(), now()->endOfMonth()],
                'yearly' => [now()->startOfYear(), now()->endOfYear()],
                default => null
            };
        }

        if ($user->role === 'admin') {
            if ($stationName) {
                $officeId = Office::where('name', $stationName)->value('id');
                if ($officeId) {
                    $query->where('office_id', $officeId);
                }
            }
        } else {
            $query->where('office_id', $user->station);
        }

        if ($stat) {
            $query->where('status', $stat);
        }
        // Add this part to specifically get delayed collections (older than 2 hours)
        $query->where('status', 'pending collection')
        ->where('updated_at', '<', Carbon::now()->subHours(2));

        if ($dateRange) {
            $query->whereBetween('created_at', $dateRange);
        }

        $queryParams = [
            'station' => $stationName,
            'status' => $stat,
            'time' => $timeFilter,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];

        $exportPdfUrl = URL::route('client-requests.export.pdf', array_filter($queryParams));

        $query->whereIn('clientId', function ($q) {
            $q->select('id')->from('clients');
        });

        $client_requests = $query->orderBy('created_at', 'desc')->get();

        $clients = Client::where('type', 'on_account')->get();
        $vehicles = Vehicle::all();
        $drivers = User::where('role', 'driver')->get();
        $sub_categories = SubCategory::all();

        // Base query for summaries
        if ($user->role === 'admin') {
            $baseQuery = ClientRequest::query();
            if ($stationName) {
                $officeId = Office::where('name', $stationName)->value('id');
                if ($officeId) {
                    $baseQuery->where('office_id', $officeId);
                }
            }
        } else {
            $baseQuery = ClientRequest::where('office_id', $user->station);
        }

        if ($dateRange) {
            $baseQuery->whereBetween('created_at', $dateRange);
        }

        // --- Summary counts ---
        $totalRequests = (clone $baseQuery)->count();
        $delivered = (clone $baseQuery)->where('status', 'delivered')->count();
        $collected = (clone $baseQuery)->where('status', 'collected')->count();
        $verified = (clone $baseQuery)->where('status', 'verified')->count();
        $pending_collection = (clone $baseQuery)->where('status', 'pending collection')->count();

        // --- Delivery-specific metrics ---
        $shipmentBase = ShipmentCollection::query()
            ->when($user->role !== 'admin', fn($q) => $q->whereHas('clientRequest', fn($r) => $r->where('office_id', $user->station)))
            ->when($stationName && $user->role === 'admin', function ($q) use ($stationName) {
                $officeId = Office::where('name', $stationName)->value('id');
                if ($officeId) {
                    $q->whereHas('clientRequest', fn($r) => $r->where('office_id', $officeId));
                }
            })
            ->when($dateRange, fn($q) => $q->whereBetween('created_at', $dateRange));

        $undeliveredParcels = (clone $shipmentBase)->where('status', 'arrived')->get();
        $onTransitParcels = (clone $shipmentBase)->where('status', 'Delivery Rider Allocated')->get();
        $failedDeliveries = (clone $shipmentBase)->where('status', 'delivery_failed')->get();
        $successfulDeliveries = (clone $shipmentBase)->where('status', 'parcel_delivered')->get();
        $delayedDeliveries = (clone $shipmentBase)
            ->where('status', 'Delivery Rider Allocated')
            ->where('updated_at', '<', Carbon::now()->subHour(2))
            ->count();
        return view('client-request.index', compact(
            'clients',
            'vehicles',
            'drivers',
            'client_requests',
            'totalRequests',
            'delivered',
            'collected',
            'verified',
            'pending_collection',
            'undeliveredParcels',
            'onTransitParcels',
            'failedDeliveries',
            'successfulDeliveries',
            'delayedDeliveries',
            'timeFilter',
            'startDate',
            'endDate',
            'exportPdfUrl',
            'sub_categories',
            'status'
        ));
    }
}
