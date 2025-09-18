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
use App\Jobs\SendCollectionNotificationsJob;

class ClientPortalController extends Controller
{
    use PdfReportTrait;

    protected $requestIdService;

    public function __construct(RequestIdService $requestIdService)
    {
        $this->requestIdService = $requestIdService;
    }
    public function index()
    {
        return view('client_portal.index');
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
        $timeFilter = $request->query('time', 'all'); // default to all
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $clients = Client::where('type', 'on_account')->get();
        $vehicles = Vehicle::all();
        $drivers = User::where('role', 'driver')->get();
        $sub_category = SubCategory::where('sub_category_name', 'Overnight')->firstOrFail();
        $offices = Office::all();

        $categories = ClientCategory::where('client_id', auth('client')->user()->id)
            ->join('categories', 'client_categories.category_id', '=', 'categories.id')
            ->select('categories.id as category_id', 'categories.category_name')
            ->get();

            //dd($categories);

        $overnightSubCategoryIds = SubCategory::where('sub_category_name', 'Overnight')->pluck('id');

        $clientRequests = ClientRequest::whereIn('sub_category_id', $overnightSubCategoryIds)
            ->whereHas('client', function ($query) {
                $query->where(['type'=>'on_account','id'=>auth('client')->user()->id]);
            })
            ->orderBy('created_at', 'desc')
            ->with(['client', 'user', 'vehicle'])
            ->get();

        return view('client_portal.shipments.overnight_on_account', compact('clients', 'clientRequests', 'vehicles', 'offices', 'drivers','timeFilter',
            'startDate',
            'endDate', 'sub_category', 'categories'));
    }  
    public function sameday_walkin()
    {
        return view('client_portal.shipments.sameday_walkin');
    }
    public function sameday_onaccount(Request $request)
    {
        return view('client_portal.shipments.sameday_on_account');    
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


}
