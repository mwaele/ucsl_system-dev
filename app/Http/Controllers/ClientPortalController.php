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
use App\Models\Rate;
use App\Jobs\ClientPortalJob;
use App\Models\OfficeUser;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\DeliveryControl;




class ClientPortalController extends Controller
{
    use PdfReportTrait;

    protected $requestIdService;

    public function __construct(RequestIdService $requestIdService)
    {
        $this->requestIdService = $requestIdService;
    }
    public function index(Request $request)
    {
        // $user = Auth::user();
        // $station = $clientId;
        $timeFilter = $request->query('time', 'all'); // default to all

        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $dateRange = null;

        if ($startDate && $endDate) {
            $dateRange = [Carbon::parse($startDate)->startOfDay(), Carbon::parse($endDate)->endOfDay()];
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

        // $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May','Jun', 'Jul', 'Aug','Sep'];
        // $data = [10, 20, 15, 30, 25, 40, 35, 50,35];

        $shipments = DB::table('client_requests')
        ->select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as total')
        )
        ->where('clientId', auth('client')->user()->id)
        ->groupBy('month')
        ->orderBy('month', 'asc')
        ->get();
        //dd($shipments);

    // Define all months
    $allMonths = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

    // Initialize data arrays
    $labels = [];
    $data = [];

    $overnight = DB::table('shipment_collections')
    ->join('client_requests', 'shipment_collections.requestId', '=', 'client_requests.requestId')
    ->join('sub_categories', 'client_requests.sub_category_id', '=', 'sub_categories.id')
    ->where('sub_categories.sub_category_name', 'Overnight')
    ->where('client_requests.source','client_portal')
    ->count();

     $sameDay = DB::table('shipment_collections')
    ->join('client_requests', 'shipment_collections.requestId', '=', 'client_requests.requestId')
    ->join('sub_categories', 'client_requests.sub_category_id', '=', 'sub_categories.id')
    ->where('sub_categories.sub_category_name', 'Same Day')
    ->where('client_requests.source','client_portal')
    ->count();


    foreach ($allMonths as $index => $monthName) {
        $monthNumber = $index + 1;
        $record = $shipments->firstWhere('month', $monthNumber);
        $labels[] = $monthName;
        $data[] = $record ? $record->total : 0;
    }

    // return response()->json([
    //     'labels' => $labels,
    //     'data' => $data,
    // ]);

        $queryWithDate = fn($q) => $dateRange
            ? $q->whereBetween('created_at', $dateRange)
            : $q;
        $ctrTime = DeliveryControl::where('control_id', 'CTRL-0001')->value('ctr_time');
            $timeLimit = Carbon::now()->subHours((int) $ctrTime);
            $totalRequests = ClientRequest::where('clientId', auth('client')->user()->id)->where('client_requests.source','client_portal')
                ->when($dateRange, $queryWithDate)->count();
            $delivered = ClientRequest::where('status', 'delivered')->where('clientId', auth('client')->user()->id)->where('client_requests.source','client_portal')
                ->when($dateRange, $queryWithDate)->count();
            $collected = ClientRequest::where('status', 'collected')->where('clientId', auth('client')->user()->id)->where('client_requests.source','client_portal')
                ->when($dateRange, $queryWithDate)->count();
            $verified = ClientRequest::where('status', 'verified')->where('clientId', auth('client')->user()->id)->where('client_requests.source','client_portal')
                ->when($dateRange, $queryWithDate)->count();
            $dispatched = ClientRequest::where('status', 'dispatched')->where('clientId', auth('client')->user()->id)->where('client_requests.source','client_portal')
                ->when($dateRange, $queryWithDate)->count();
            $arrived = ClientRequest::where('status', 'arrived')->where('clientId', auth('client')->user()->id)->where('client_requests.source','client_portal')
                ->when($dateRange, $queryWithDate)->count();
            $pendingCollection = ClientRequest::where('status', 'Pending-Collection')->where('clientId', auth('client')->user()->id)->where('client_requests.source','client_portal')
                ->when($dateRange, $queryWithDate)->count();
                $delayedCollections = ClientRequest::where('status', 'Pending-Collection')->where('updated_at', '<', Carbon::now()->subHour()) 
                ->whereNotIn('status', ['verified'])
                ->when($dateRange, $queryWithDate)
                ->count();
            $undeliveredParcels = ShipmentCollection::where('status', 'arrived')
                ->whereHas('clientRequestById', function ($q)  {
                    $q->where('clientId', auth('client')->user()->id)->where('client_requests.source','client_portal');
                })
                ->when($dateRange, $queryWithDate)
                ->count();
            $onTransitParcels = ShipmentCollection::whereIn('status', [
                    'Delivery Rider Allocated',
                    'delivery_rider_allocated'
                ])
                ->whereHas('clientRequestById', function ($q)  {
                    $q->where('clientId', auth('client')->user()->id)->where('client_requests.source','client_portal');
                })
                ->when($dateRange, $queryWithDate)
                ->count();
            $delayedDeliveries = ShipmentCollection::whereIn('status', [
                    'Delivery Rider Allocated',
                    'delivery_rider_allocated'
                ])
                ->where('updated_at', '<', $timeLimit)
                ->whereNotIn('status', ['delivery_failed', 'parcel_delivered'])
                ->when($dateRange, $queryWithDate)
                ->count();
            $failedDeliveries = ShipmentCollection::where('status', 'delivery_failed')
                ->whereHas('clientRequestById', function ($q)  {
                    $q->where('clientId', auth('client')->user()->id)->where('client_requests.source','client_portal');
                })
                ->when($dateRange, $queryWithDate)
                ->count();
            $successfulDeliveries = ShipmentCollection::where('status', 'parcel_delivered')
                ->whereHas('clientRequestById', function ($q)  {  
                    $q->where('clientId', auth('client')->user()->id);
                })
                ->when($dateRange, $queryWithDate)
                ->count();

            // Walk-in revenue
            $walkinRevenue = ShipmentCollection::whereHas('clientRequestById.client', function ($query) {
                $query->where('type', 'walkin');
            })->sum('actual_total_cost');

            // Account client revenue
            $accountRevenue = ShipmentCollection::whereHas('clientRequestById.client', function ($query) {
                $query->where('type', 'on_account');
            })->sum('actual_total_cost');

            $totalRevenue = $walkinRevenue + $accountRevenue;

            $stationStats = null;

        $recentRequests = ClientRequest::latest()->take(5)->get();
        $userCount = User::count();

        $collections = ShipmentCollection::with('client')
            ->whereHas('client', function ($query) {
                $query->where('client_id', auth('client')->user()->id); 
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('client_portal.index', compact(
            'totalRequests',
            'delivered',
            'collected',
            'collections',
            'verified',
            'pendingCollection',
            'recentRequests',
            'userCount',
            'stationStats',
            'timeFilter',
            'startDate',
            'endDate',
            'labels',
            'data',
            'dispatched',
            'overnight',
            'sameDay',
            'arrived',
            'undeliveredParcels',
            'onTransitParcels',
            'failedDeliveries',
            'successfulDeliveries',
            'delayedDeliveries',
            'delayedCollections',
            'walkinRevenue',
            'accountRevenue',
            'totalRevenue'
        ));
        //return view('client_portal.index');
    } 
    public function client_index(Request $request)
    {
        $clientId = auth('client')->user()->id;

        // Prepare query
        $query = ClientRequest::with([
            'client',
            'vehicle',
            'clientId',
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

        if ($clientId) {
            $query->where('clientId', $clientId);
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
            'source' => 'ucsl_portal',
            'clientId' => $clientId,
        ];

        $exportPdfUrl = URL::route('client_requests.export.pdf', array_filter($queryParams));

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
            $baseQuery = ClientRequest::where('office_id', $clientId);
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
                $q->whereHas('clientRequest', fn($r) => $r->where('office_id', $clientId))
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
    public function dashboard()
    {
        return view('client_portal.dashboard');
    }

    public function overnight_walkin()
    {
        return view('client_portal.shipments.overnight_walkin');
    }

    public function overnight_onaccount(Request $request)  
    {
            $categories = ClientCategory::where('client_id', auth('client')->user()->id)
            ->join('categories', 'client_categories.category_id', '=', 'categories.id')
            ->select('categories.id as category_id', 'categories.category_name')
            ->get();

        $offices = Office::all();
        // $loggedInUserId = Auth::user()->id;
        $id = auth('client')->user()->id;
        $destinations = Rate::where('type', 'normal')->get();
        $walkInClients = Client::where('type', 'walkin')->get();
        $sub_category = SubCategory::where('sub_category_name', 'Overnight')->firstOrFail();

        
        $collections = ShipmentCollection::with('client')
            ->whereHas('client', function ($query) {
                $query->where('client_id', auth('client')->user()->id); 
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Get the latest consignment number
        $latestConsignment = ShipmentCollection::where('consignment_no', 'LIKE', 'CN-%')
            ->orderByDesc('id')
            ->first();

        if ($latestConsignment && preg_match('/CN-(\d+)/', $latestConsignment->consignment_no, $matches)) {
            $lastNumber = intval($matches[1]);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 10000; // Start from CN-10000
        }

        $consignment_no = 'CN-' . $newNumber;

        $overnightSubCategoryIds = SubCategory::where('sub_category_name', 'Overnight')->pluck('id');
        //dd($overnightSubCategoryIds);

        $clientRequests = ClientRequest::whereIn('sub_category_id', $overnightSubCategoryIds)
            ->whereHas('client', function ($query) {
                $query->where('clientId', auth('client')->user()->id)->where('client_requests.source','client_portal');
            })
            ->orderBy('created_at', 'desc')
            ->with(['client', 'user', 'vehicle'])
            ->get();

        return view('client_portal.shipments.overnight_on_account', compact('clientRequests', 'offices', 'categories',
            //'loggedInUserId',
            'destinations',
            'walkInClients',
            'collections',
            'consignment_no',
            'sub_category'
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
    public function sameday_walkin()
    {
        return view('client_portal.shipments.sameday_walkin');
    }
    public function sameday_on_account(Request $request)
    {
        $categories = ClientCategory::where('client_id', auth('client')->user()->id)
            ->join('categories', 'client_categories.category_id', '=', 'categories.id')
            ->select('categories.id as category_id', 'categories.category_name')
            ->get();

        $offices = Office::all();
        // $loggedInUserId = Auth::user()->id;
        $id = auth('client')->user()->id;
        $destinations = Rate::where('type', 'normal')->get();
        $walkInClients = Client::where('type', 'walkin')->get();
        $sub_category = SubCategory::where('sub_category_name', 'Same Day')->firstOrFail();

        
        $collections = ShipmentCollection::with('client')
            ->whereHas('client', function ($query) {
                $query->where('client_id', auth('client')->user()->id); 
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Get the latest consignment number
        $latestConsignment = ShipmentCollection::where('consignment_no', 'LIKE', 'CN-%')
            ->orderByDesc('id')
            ->first();

        if ($latestConsignment && preg_match('/CN-(\d+)/', $latestConsignment->consignment_no, $matches)) {
            $lastNumber = intval($matches[1]);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 10000; // Start from CN-10000
        }

        $consignment_no = 'CN-' . $newNumber;

        $overnightSubCategoryIds = SubCategory::where('sub_category_name', 'Same Day')->pluck('id');
        //dd($overnightSubCategoryIds);

        $clientRequests = ClientRequest::whereIn('sub_category_id', $overnightSubCategoryIds)
            ->whereHas('client', function ($query) {
                $query->where('clientId', auth('client')->user()->id);
            })
            ->orderBy('created_at', 'desc')
            ->with(['client', 'user', 'vehicle'])
            ->get();
        //dd($clientRequests);

        return view('client_portal.shipments.same_day_on_account', compact('clientRequests', 'offices', 'categories',
            //'loggedInUserId',
            'destinations',
            'walkInClients',
            'collections',
            'consignment_no',
            'sub_category'
        )); 
    }
    


    public function store(Request $request)
    {
        $requestId = $this->requestIdService->generate();
        $source = 'client_portal';

        //dd($request);
        $validated = $request->validate([
            'clientId' => 'required|integer',
            'collectionLocation' => 'required|string',
            'parcelDetails' => 'required|string',
            'dateRequested' => 'required|date',
            // 'userId' => 'required|integer',
            // 'vehicleId' => 'required|integer',
            'category_id' => 'required|integer',
            'sub_category_id' => 'required|integer',
            'priority_level' => 'required|string',
            'deadline_date' => 'nullable|date',
            'office_id' => 'required|integer',
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
                // 'userId' => $validated['userId'],
                // 'vehicleId' => $validated['vehicleId'],
                'requestId' => $requestId,
                'category_id' => $validated['category_id'],
                'sub_category_id' => $validated['sub_category_id'],
                'priority_level' => $validated['priority_level'],
                'deadline_date' => $validated['deadline_date'],
                //'created_by' => auth('client')->user()->id,
                'office_id' => $validated['office_id'],
                'source' => $source,
                'status' => 'Pending-Collection',
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
            // $rider = User::find($clientRequest->userId);
            // $vehicle = Vehicle::find($clientRequest->vehicleId);

            $rider = null;
            $vehicle = null;

            DB::table('tracking_infos')->insert([
                'trackId' => $trackingId,
                'date' => now(),
                'details' => 'Client Request Submitted for Collection',
                //'user_id' => $rider->id,
               // 'vehicle_id' => $vehicle->id,
                'remarks' => "Received client collection request, generated client request ID {$clientRequest->requestId}",
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 4. Ensure collection location exists
            Location::firstOrCreate(['location' => $clientRequest->collectionLocation]);

            // 5. Dispatch background job to send notifications
        $client = Client::find($clientRequest->clientId);
        dd($client);
        ClientPortalJob::dispatch($clientRequest, $client);

            DB::commit();
            

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Client Request Store Error', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return back()->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
        }

        

        return redirect()->back()->with('success', 'Client request submitted successfully.');
    }
    private function formatPhoneNumber($phone)
{
    $phone = preg_replace('/\D/', '', $phone); // remove non-numeric characters

    if (str_starts_with($phone, '0')) {
        // Replace leading 0 with +254
        return '+254' . substr($phone, 1);
    } elseif (str_starts_with($phone, '254')) {
        // Already has 254, just add +
        return '+' . $phone;
    } elseif (str_starts_with($phone, '+254')) {
        // Already in correct format
        return $phone;
    }

    // fallback: return unchanged
    return $phone;
}


    public function create(Request $request, SmsService $smsService)
    {
        $requestId = $this->requestIdService->generate();
        // Validate input
        $validated = $request->validate([
            'manualWaybillStatus' => 'nullable|in:yes,no',
            'manualWaybillImage'  => 'sometimes|nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'manualWaybillNo'     => 'sometimes|nullable|string|max:13',
        ]);
      
        DB::beginTransaction();

        try {

            // Generate waybill
            $prefix = 'UCSL';
            $suffix = 'KE';
            $padLength = 10;

            $latestWaybill = DB::table('shipment_collections')
                ->whereNotNull('waybill_no')
                ->orderByDesc('id')
                ->value('waybill_no');

            if ($latestWaybill) {
                // Remove prefix and suffix
                $waybill_no = substr($latestWaybill, strlen($prefix), -strlen($suffix));

                // Convert to int and increment
                $bill_no = (int)$waybill_no + 1;
            } else {
                // First waybill
                $bill_no = 1;
            }

            // Pad with zeros
            $padded_no = str_pad($bill_no, $padLength, '0', STR_PAD_LEFT);

            $waybill_no = $prefix . $padded_no . $suffix;
            $client = Client::findOrFail($request->clientId);

            // 1a. Save ShipmentCollection
            $collection = ShipmentCollection::create([
                'sender_name' => $client->name,
                'sender_contact' => $client->contact,
                'sender_email' => $client->email,
                'sender_address' => $client->address,
                'sender_town' => $client->city,
                'sender_id_no' => $client->contact_person_id_no,
                'receiver_name' => $request->receiverContactPerson,
                'receiver_id_no' => $request->receiverIdNo,
                'receiver_phone' => $request->receiverPhone,
                'receiver_email' => $request->receiverEmail,
                'receiver_address' => $request->receiverAddress,
                'receiver_town' => $request->receiverTown,
                'requestId' => $requestId,
                'client_id' => $request->clientId,
                'origin_id' => $request->origin_id,
                'destination_id' => $request->destination_id,
                'consignment_no' => $request->consignment_no,
                'waybill_no' => $waybill_no,
                'base_cost' => $request->base_cost,
                'cost' => $request->cost,
                'vat' => $request->vat,
                'total_cost' => $request->total_cost,
                'actual_cost' => $request->cost,
                'actual_vat' => $request->vat,
                'actual_total_cost' => $request->total_cost,
                'total_weight' => $request->total_weight,
                'verified_by' => auth()->id(),
                'verified_at' => now(),
                'billing_party' => $request->billing_party,
                'payment_mode' => $request->payment_mode,
                'reference' => $request->reference,
                'status' => 'parcel_booked',
                'collected_by' => auth()->id(),
                'created_at' => now(),
                'updated_at' => now(),
                'priority_level' => $request->priority_level,
                'deadline_date' => $request->deadline_date,
                'priority_extra_charge' => $request->priority_extra_charge,
                'fragile_extra_charge' => $request->fragile_extra_charge,
                'manual_waybill_status' => $validated['manualWaybillStatus'] === 'yes' ? 1 : 0, // New field
            ]);
            // 1b. Handle manual waybill number + image upload
            if (($validated['manualWaybillStatus'] ?? null) === 'yes') {
            $collection->manual_waybillNo = $validated['manualWaybillNo'] ?? null;

            if ($request->hasFile('manualWaybillImage')) {
                $file = $request->file('manualWaybillImage');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads'), $filename);
                $collection->manual_waybill = $filename;
            }

            $collection->save();
        }

            // 2. Rebuild items array from flat structure
            $itemCount = count($request->input('item_name', []));
            $totalWeight = 0;
            for ($i = 0; $i < $itemCount; $i++) {
                $itemData = [
                    'item_name' => $request->item_name[$i],
                    'packages' => $request->packages[$i],
                    'weight' => $request->weight[$i],
                    'length' => $request->length[$i],
                    'width' => $request->width[$i],
                    'height' => $request->height[$i],
                    'volume' => $request->volume[$i],
                    'actual_quantity' => $request->packages[$i],
                    'actual_weight' => $request->weight[$i],
                    'actual_length' => $request->length[$i],
                    'actual_width' => $request->width[$i],
                    'actual_height' => $request->height[$i],
                    'actual_volume' => $request->volume[$i],
                    'remarks' => $request->remarks[$i] ?? null,
                    'sub_items' => $request->input("items.$i.sub_items", []) // sub-items kept in nested format
                ];

                $totalWeight += $itemData['weight'] * $itemData['packages'];

                // 3. Save each item
                $item = $collection->items()->create([
                    'item_name' => $itemData['item_name'],
                    'packages_no' => $itemData['packages'],
                    'weight' => $itemData['weight'],
                    'length' => $itemData['length'],
                    'width' => $itemData['width'],
                    'height' => $itemData['height'],
                    'volume' => $itemData['volume'],
                    'actual_quantity' => $itemData['actual_quantity'],
                    'actual_weight' => $itemData['actual_weight'],
                    'actual_length' => $itemData['actual_length'],
                    'actual_width' => $itemData['actual_width'],
                    'actual_height' => $itemData['actual_height'],
                    'actual_volume' => $itemData['actual_volume'],
                    'remarks' => $itemData['remarks'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                // 4. Save sub-items if any
                if (!empty($itemData['sub_items'])) {
                    foreach ($itemData['sub_items'] as $subItem) {
                        $item->subItems()->create([
                            'item_name' => $subItem['name'],
                            'quantity' => $subItem['quantity'],
                            'weight' => $subItem['weight'],
                            'remarks' => $subItem['remarks'] ?? null,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }
                }
            }
             // 5. Save Track
            $trackingId = DB::table('tracks')->insertGetId([
                'requestId' =>  $requestId,
                'clientId' => $request->clientId,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::table('tracks')
            ->where('requestId',$requestId)
            ->update([
                'current_status' => 'Awaiting Pickup - Client Premises',
                'updated_at' => now()
            ]);

            $text = $itemCount === 1 ? 'item' : 'items';
            $text2 = $totalWeight === 1 ? 'kg' : 'kgs';
            $client = Client::find($request->clientId);
            $clientName = $client ? $client->name : 'Unknown Client';

            // 6. Save Tracking Info
            DB::table('tracking_infos')->insert([
                'trackId' => $trackingId,
                'date' => now(),
                'details' => 'Parcel Collection Initiated',
                'remarks' => $clientName. ' iniated parcel collection request '.$itemCount.' '.$text.' with a total weight of '.$totalWeight.''.$text2,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // 6. Save Tracking Info
            DB::table('tracking_infos')->insert([
                'trackId' => $trackingId,
                'date' => now(),
                'details' => 'Waybill Generated',
                'remarks' => $clientName. ' Generated Waybill ',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // 6. Save to client_requests table
            DB::table('client_requests')->insert([
                'clientId' => $request->clientId,
                'category_id' => $request->category_id,
                'sub_category_id' => $request->sub_category_id,
                'requestId' => $requestId,
                'collectionLocation' => $request->collectionLocation,
                'parcelDetails' => 'Waybill No: '.$waybill_no.', Items: '.$itemCount.', Total Weight: '.$totalWeight.'kg',
                'dateRequested' => now(),
                'userId' => null,
                'vehicleId' => null,
                'created_by' => auth()->id(),
                'office_id' => $request->origin_id,
                'status' => 'Pending-Collection',
                'priority_level' => $request->priority_level,
                'deadline_date' => $request->deadline_date,
                'collected_by' => auth()->id(),
                'consignment_no' => $request->consignment_no,
                'created_at' => now(),
                'updated_at' => now(),
                'source'=> $request->source,
            ]);

            // 7. Save to payments table
            if (in_array($request->payment_mode, ['M-Pesa', 'Cash', 'Cheque'])) {
                Payment::create([
                    'client_id'              => $request->clientId,
                    'amount'                 => $request->total_cost,
                    'date_paid'              => now(),
                    'shipment_collection_id' => $collection->id,
                    'type'                   => $request->payment_mode,
                    'received_by'            => auth()->id(),
                    'verified_by'            => auth()->id(),
                    'paid_by'                => $request->clientId,
                    'status'                 => 'Pending Verification',
                    'reference_no'           => $request->reference,
                ]);
            }
            if(auth('client')->user()->id){
                $invoiced_by = 1;
            }
            if ($request->payment_mode === 'Invoice') {
                Invoice::create([
                    'invoice_no'             => $request->reference,
                    'amount'                 => $request->total_cost,
                    'due_date'               => Carbon::now()->addDays(30),
                    'client_id'              => $request->clientId,
                    'shipment_collection_id' => $collection->id,
                    'invoiced_by'            => $invoiced_by,
                ]);
            }

           /// dd($client);
            $client = Client::find($collection->clientId);
            ClientPortalJob::dispatch($collection, $client);

            DB::commit();                 


            return redirect()->back()->with('success', 'Shipment collection created and receiver notified successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error saving shipment: ' . $e->getMessage());
        }
    }


}
