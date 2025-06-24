<?php

namespace App\Http\Controllers;

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
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\Services\SmsService;
use App\Mail\GenericMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\HtmlString;

use App\Helpers\EmailHelper;
use App\Models\Location;

class ClientRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $user = Auth::user();

        // Generate Unique Request ID
        $lastRequestFromClient = ClientRequest::where('requestId', 'like', 'REQ-%')
            ->orderByRaw("CAST(SUBSTRING(requestId, 5) AS UNSIGNED) DESC")
            ->value('requestId');

        $lastRequestFromCollection = ShipmentCollection::where('requestId', 'like', 'REQ-%')
            ->orderByRaw("CAST(SUBSTRING(requestId, 5) AS UNSIGNED) DESC")
            ->value('requestId');

        $clientNumber = $lastRequestFromClient ? (int)substr($lastRequestFromClient, 4) : 0;
        $collectionNumber = $lastRequestFromCollection ? (int)substr($lastRequestFromCollection, 4) : 0;
        $nextNumber = max(max($clientNumber, $collectionNumber) + 1, 10000);
        $request_id = 'REQ-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

        // Prepare query
        $query = ClientRequest::with([
            'client',
            'vehicle',
            'user',
            'shipmentCollection.items.subItems',
            'createdBy'
        ]);

        // Apply filters
        $stationName = $request->query('station');
        $status = $request->query('status');
        $timeFilter = $request->query('time', 'all');

        // Time filter
        $dateRange = match ($timeFilter) {
            'daily' => [now()->startOfDay(), now()->endOfDay()],
            'weekly' => [now()->startOfWeek(), now()->endOfWeek()],
            'biweekly' => [now()->subDays(14), now()],
            'monthly' => [now()->startOfMonth(), now()->endOfMonth()],
            'yearly' => [now()->startOfYear(), now()->endOfYear()],
            default => null
        };

        if ($user->role === 'admin') {
            // If station filter is present, convert name to ID
            if ($stationName) {
                $officeId = Office::where('name', $stationName)->value('id');
                if ($officeId) {
                    $query->where('office_id', $officeId);
                }
            }
        } else {
            // Non-admin users only see their assigned office data
            $query->where('office_id', $user->station);
        }

        // Apply status filter
        if ($status) {
            $query->where('status', $status);
        }

        // Apply date range
        if ($dateRange) {
            $query->whereBetween('created_at', $dateRange);
        }

        // Final data
        $client_requests = $query->orderBy('created_at', 'desc')->get();

        $clients = Client::where('type', 'COD')->get();
        $vehicles = Vehicle::all();
        $drivers = User::where('role', 'driver')->get();

        // Summary counts
        if ($user->role === 'admin') {
            $baseQuery = ClientRequest::query();
            if ($stationName) {
                $officeId = Office::where('name', $stationName)->value('id');
                if ($officeId) {
                    $baseQuery->where('office_id', $officeId);
                }
            }

            if ($dateRange) {
                $baseQuery->whereBetween('created_at', $dateRange);
            }

            $totalRequests = (clone $baseQuery)->count();
            $collected = (clone $baseQuery)->where('status', 'collected')->count();
            $verified = (clone $baseQuery)->where('status', 'verified')->count();
            $pending_collection = (clone $baseQuery)->where('status', 'pending collection')->count();

        } else {
            $baseQuery = ClientRequest::where('office_id', $user->station);
            if ($dateRange) {
                $baseQuery->whereBetween('created_at', $dateRange);
            }

            $totalRequests = (clone $baseQuery)->count();
            $collected = (clone $baseQuery)->where('status', 'collected')->count();
            $verified = (clone $baseQuery)->where('status', 'verified')->count();
            $pending_collection = (clone $baseQuery)->where('status', 'pending collection')->count();
        }

        return view('client-request.index', compact(
            'clients',
            'vehicles',
            'drivers',
            'client_requests',
            'request_id',
            'totalRequests',
            'collected',
            'verified',
            'pending_collection'
        ));
    }

    public function exportPdf(Request $request)
    {
        $user = Auth::user();
        $station = $request->query('station', $user->station);
        $status = $request->query('status');
        $timeFilter = $request->query('time', 'all');

        // Determine date range
        $dateRange = match ($timeFilter) {
            'daily' => [now()->startOfDay(), now()->endOfDay()],
            'weekly' => [now()->startOfWeek(), now()->endOfWeek()],
            'biweekly' => [now()->subDays(14), now()],
            'monthly' => [now()->startOfMonth(), now()->endOfMonth()],
            'yearly' => [now()->startOfYear(), now()->endOfYear()],
            default => null
        };

        $queryWithDate = fn($q) => $dateRange ? $q->whereBetween('created_at', $dateRange) : $q;

        // Build query
        $query = ClientRequest::with(['client', 'vehicle', 'user', 'shipmentCollection', 'createdBy'])
            ->when($user->role !== 'admin', fn($q) => $q->whereHas('createdBy', fn($q2) => $q2->where('station', $user->station)))
            ->when($user->role === 'admin' && $station, fn($q) => $q->whereHas('createdBy', fn($q2) => $q2->where('station', $station)))
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($dateRange, $queryWithDate)
            ->orderBy('created_at', 'desc');

        $client_requests = $query->get();

        $pdf = Pdf::loadView('pdf.client-requests', [
            'client_requests' => $client_requests,
            'station' => $station,
            'status' => $status,
            'timeFilter' => $timeFilter,
        ])->setPaper('a4', 'landscape');

        // Add page numbers
        $pdf->getDomPDF()->get_canvas()->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
            $font = $fontMetrics->getFont('Helvetica', 'normal');
            $canvas->text(420, 580, "Page $pageNumber of $pageCount", $font, 10);
        });

        return $pdf->download('client_requests.pdf');
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

    public function store(Request $request, SmsService $smsService) 
    {
        $validated = $request->validate([
            'clientId' => 'required|integer',
            'collectionLocation' => 'required|string',
            'parcelDetails' => 'required|string',
            'dateRequested' => 'required|date',
            'userId' => 'required|integer',
            'vehicleId' => 'required|integer',
            'category_id'=>'required|integer',
            'sub_category_id'=>'required|integer',
            'requestId' => 'required|string|unique:client_requests,requestId',
        ]);

        DB::beginTransaction();

        try {
            // 1. Save Client Request
            $clientRequest = new ClientRequest();
            $clientRequest->clientId = $validated['clientId'];
            $clientRequest->collectionLocation = $validated['collectionLocation'];
            $clientRequest->parcelDetails = $validated['parcelDetails'];
            $clientRequest->dateRequested = Carbon::parse($validated['dateRequested'])->format('Y-m-d H:i:s');
            $clientRequest->userId = $validated['userId'];
            $clientRequest->vehicleId = $validated['vehicleId'];
            $clientRequest->requestId = $validated['requestId'];;
            $clientRequest->category_id = $validated['category_id'];
            $clientRequest->sub_category_id = $validated['sub_category_id'];
            $clientRequest->created_by = Auth::id();
            $clientRequest->office_id = Auth::user()->station;
            $clientRequest->save();

            // 2. Save Track
            $trackingId = DB::table('tracks')->insertGetId([
                'requestId' =>  $clientRequest->requestId,
                'clientId' => $clientRequest->clientId,
                'current_status' => 'Awaiting Collection',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // 3. Save Tracking Info
            $userName = User::find($validated['userId'])->name;
            $regNo = Vehicle::find($validated['vehicleId'])->regNo;

            DB::table('tracking_infos')->insert([
                'trackId' => $trackingId,
                'date' => now(),
                'details' => 'Client Request Submitted for Collection',
                'user_id' => $validated['userId'],
                'vehicle_id' => $validated['vehicleId'],
                'remarks' => 'Received client collection request, generated client request ID '.$clientRequest->requestId.', allocated '.$userName .' '. $regNo .' for collection',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $locationName = $validated['collectionLocation'];

            $location = Location::firstOrCreate(['location' => $locationName]);

            DB::commit();

            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error('Tracking Info Insert Error', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
                return back()->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
            }

        // --------------------------
        // ✅ SMS Logic After Commit
        // --------------------------
        $request_id = $clientRequest->requestId;
        $location = $clientRequest->collectionLocation;

        // Rider details
        $rider = User::find($clientRequest->userId);
        $rider_name = $rider?->name ?? 'Rider';
        $rider_phone = $rider?->phone_number;
        $rider_email = $rider->email;

        // Client details
        $client = Client::find($clientRequest->clientId);
        $client_name = $client?->name ?? 'Client';
        $client_phone = $client?->contact;

        $client_email = $client->email;

        // Rider SMS
        $rider_message = "Dear $rider_name, Collect Parcel for client ($client_name) $client_phone Request ID: $request_id at $location";
        $smsService->sendSms(
            phone: $rider_phone,
            subject: 'Client Collections Alert',
            message: $rider_message,
            addFooter: true
        );
        SentMessage::create([
            'request_id' => $request_id,
            'client_id' => $clientRequest->clientId,
            'rider_id' => $clientRequest->userId,
            'recipient_type' => 'rider',
            'recipient_name' => $rider_name,
            'phone_number' => $rider_phone,
            'subject' => 'Client Collections Alert',
            'message' => $rider_message,
        ]);

        // rider email

        $rider_subject = 'Client Collections Alert';
        $rider_email = $client_email;
        $terms = env('TERMS_AND_CONDITIONS', '#'); // fallback if not set
        $footer = "<br><p><strong>Terms & Conditions:</strong> <a href=\"{$terms}\" target=\"_blank\">Click here</a></p>
                   <p>Thank you for using Ufanisi Courier Services for we are <strong>Fast, Reliable and Secure</strong></p>";
        
        $rider_email_message = "Dear $rider_name, <br><br> Collect Parcel for client ($client_name) $client_phone Request ID: $request_id at $location";
        $fullRiderMessage = $rider_email_message . $footer;

        $emailResponse = EmailHelper::sendHtmlEmail($rider_email, $rider_subject, $fullRiderMessage);

        // Client SMS
        $client_message = "Dear $client_name, We have allocated $rider_name $rider_phone to collect your parcel Request ID: $request_id";
        $smsService->sendSms(
            phone: $client_phone,
            subject: 'Parcel Collection Alert',
            message: $client_message,
            addFooter: true
        );
        SentMessage::create([
            'request_id' => $request_id,
            'client_id' => $clientRequest->clientId,
            'rider_id' => $clientRequest->userId,
            'recipient_type' => 'client',
            'recipient_name' => $client_name,
            'phone_number' => $client_phone,
            'subject' => 'Parcel Collection Alert',
            'message' => $client_message,
        ]);

        // send email

        $subject = 'Parcel Collection Alert';
        $message = "Dear $client_name, <br><br> We have allocated $rider_name $rider_phone to collect your parcel Request ID: $request_id";
        $fullMessage = $message . $footer;

        $emailResponse = EmailHelper::sendHtmlEmail($client_email, $subject, $fullMessage);

        return redirect()->back()->with('Success', 'Client Request Saved and Tracked Successfully')->with('email_status', $emailResponse->getData());
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
}
