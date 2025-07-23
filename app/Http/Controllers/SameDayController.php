<?php

namespace App\Http\Controllers;
use App\Jobs\SendCollectionNotificationsJob;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\ClientRequest;
use App\Models\SubCategory;
use App\Models\ShipmentCollection;
use App\Models\Vehicle;
use App\Models\User;
use App\Models\FrontOffice;
use App\Models\SameDayRate;
use App\Models\Office;
use App\Models\Rate;
use Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use App\Services\SmsService;
use App\Mail\GenericMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\HtmlString;

use App\Helpers\EmailHelper;
use App\Models\Location;

class SameDayController extends Controller
{
    public function on_account(Request $request )
    {
        // Determine the correct CAST expression based on DB driver
        $driver = DB::getDriverName();
        $castExpression = $driver === 'pgsql'
            ? 'CAST(SUBSTRING("requestId" FROM 5) AS INTEGER)'
            : 'CAST(SUBSTRING(requestId, 5) AS UNSIGNED)';

        $timeFilter = $request->query('time', 'all'); // default to all

        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        // Generate Unique Request ID
        $lastRequestFromClient = ClientRequest::where('requestId', 'like', 'REQ-%')
            ->orderByRaw("$castExpression DESC")
            ->value('requestId');

        $lastRequestFromCollection = ShipmentCollection::where('requestId', 'like', 'REQ-%')
            ->orderByRaw("$castExpression DESC")
            ->value('requestId');

        $clientNumber = $lastRequestFromClient ? (int)substr($lastRequestFromClient, 4) : 0;
        $collectionNumber = $lastRequestFromCollection ? (int)substr($lastRequestFromCollection, 4) : 0;
        $nextNumber = max(max($clientNumber, $collectionNumber) + 1, 10000);
        $request_id = 'REQ-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

        $clients = Client::where('type', 'on_account')->get();
        $vehicles = Vehicle::all();
        $drivers = User::where('role', 'driver')->get();
        $sub_category = SubCategory::where('sub_category_name', 'Same Day')->firstOrFail();

        $locations = Rate::where(['office_id'=>2,'type'=>'Same Day'])->get();

        $samedaySubCategoryIds = SubCategory::where('sub_category_name', 'Same Day')->pluck('id');

        $clientRequests = ClientRequest::whereIn('sub_category_id', $samedaySubCategoryIds)
            ->whereHas('client', function ($query) {
                $query->where('type', 'on_account');
            })
            ->orderBy('created_at', 'desc')
            ->with(['client', 'user', 'vehicle'])
            ->get();


        return view('same_day.on_account', compact('clients', 'clientRequests', 'request_id', 'vehicles', 'drivers','timeFilter',
            'startDate',
            'endDate', 'sub_category','locations'));
    }

    public function walk_in()
    {
        $offices = Office::where(['id'=>2])->get();
        $loggedInUserId = Auth::user()->id;
        $destinations = Rate::where('type','Same Day')->get();
        $walkInClients = Client::where('type', 'walkin')->get();
        $sub_category = SubCategory::where('sub_category_name', 'Same Day')->firstOrFail();

        // Determine DB driver for cross-DB SQL compatibility
        $driver = DB::getDriverName();
        $castExpression = $driver === 'pgsql'
            ? 'CAST(SUBSTRING("requestId" FROM 5) AS INTEGER)'
            : 'CAST(SUBSTRING(requestId, 5) AS UNSIGNED)';

        // 1. Get the latest requestId from both tables
        $lastRequestFromClient = ClientRequest::where('requestId', 'like', 'REQ-%')
            ->orderByRaw("$castExpression DESC")
            ->value('requestId');

        $lastRequestFromCollection = ShipmentCollection::where('requestId', 'like', 'REQ-%')
            ->orderByRaw("$castExpression DESC")
            ->value('requestId');

        // 2. Extract numeric parts and determine the highest
        $clientNumber = $lastRequestFromClient ? (int)substr($lastRequestFromClient, 4) : 0;
        $collectionNumber = $lastRequestFromCollection ? (int)substr($lastRequestFromCollection, 4) : 0;
        $nextNumber = max(max($clientNumber, $collectionNumber) + 1, 10000);

        // 4. Format requestId
        $request_id = 'REQ-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

        // Walk-in collections
        $collections = ShipmentCollection::with('client')
            ->whereHas('client', function ($query) {
                $query->where('type', 'walkin'); // Only walk-in clients
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

        $samedaySubCategoryIds = SubCategory::where('sub_category_name', 'Same Day')->pluck('id');

        $clientRequests = ClientRequest::whereIn('sub_category_id', $samedaySubCategoryIds)
            ->whereHas('client', function ($query) {
                $query->where('type', 'walkin');
            })
            ->orderBy('created_at', 'desc')
            ->with(['client', 'user', 'vehicle'])
            ->get();


        return view('same_day.walk_in', compact('clientRequests', 'offices',
            'loggedInUserId',
            'destinations',
            'walkInClients',
            'collections',
            'request_id',
            'consignment_no',
            'sub_category'
        ));
    }
    
    public function sameday_walkin_report(){
        $samedaySubCategoryIds = SubCategory::where('sub_category_name', 'Same Day')->pluck('id');

        $clientRequests = ClientRequest::whereIn('sub_category_id', $samedaySubCategoryIds)
            ->whereHas('client', function ($query) {
                $query->where('type', 'walkin');
            })
            ->with(['client', 'user', 'vehicle'])
            ->get();
        $pdf = Pdf::loadView('same_day.sameday_walkin_report' , [
            'clientRequests'=>$clientRequests
        ])->setPaper('a4', 'landscape');;
        return $pdf->download("sameday_walkin_report.pdf");
    }

    public function sameday_account_report(){
        $samedaySubCategoryIds = SubCategory::where('sub_category_name', 'Same Day')->pluck('id');

        $clientRequests = ClientRequest::whereIn('sub_category_id', $samedaySubCategoryIds)
            ->whereHas('client', function ($query) {
                $query->where('type', 'walkin');
            })
            ->with(['client', 'user', 'vehicle'])
            ->get();
        $pdf = Pdf::loadView('same_day.sameday_account_report' , [
            'clientRequests'=>$clientRequests
        ])->setPaper('a4', 'landscape');;
        return $pdf->download("sameday_account_report.pdf");
    }

    public function getCost($originId, $destinationId)
    {
        $rate = Rate::where(['office_id'=>$originId,'id'=> $destinationId])->first();

        if ($rate) {
            return response()->json(['cost' => $rate->rate]);
        }

        return response()->json(['cost' => 'N/A'], 404);
    }
    public function store(Request $request)
    {
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
            'requestId' => 'required|string|unique:client_requests,requestId',
            'rate_id'=>'nullable',
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
                'requestId' => $validated['requestId'],
                'category_id' => $validated['category_id'],
                'sub_category_id' => $validated['sub_category_id'],
                'created_by' => Auth::id(),
                'office_id' => Auth::user()->station,
                'rate_id' => $validated['rate_id']
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
    // public function store(Request $request, SmsService $smsService) 
    // {
    //     dd($request);
    //     $validated = $request->validate([
    //         'clientId' => 'required|integer',
    //         'collectionLocation' => 'required|string',
    //         'parcelDetails' => 'required|string',
    //         'dateRequested' => 'required|date',
    //         'userId' => 'required|integer',
    //         'vehicleId' => 'required|integer',
    //         'category_id'=>'required|integer',
    //         'sub_category_id'=>'required|integer',
    //         'requestId' => 'required|string|unique:client_requests,requestId',
    //         'rate_id'=>'nullable',
    //     ]);

    //     DB::beginTransaction();

    //     try {
    //         // 1. Save Client Request
    //         $clientRequest = new ClientRequest();
    //         $clientRequest->clientId = $validated['clientId'];
    //         $clientRequest->collectionLocation = $validated['collectionLocation'];
    //         $clientRequest->parcelDetails = $validated['parcelDetails'];
    //         $clientRequest->dateRequested = Carbon::parse($validated['dateRequested'])->format('Y-m-d H:i:s');
    //         $clientRequest->userId = $validated['userId'];
    //         $clientRequest->vehicleId = $validated['vehicleId'];
    //         $clientRequest->requestId = $validated['requestId'];;
    //         $clientRequest->category_id = $validated['category_id'];
    //         $clientRequest->sub_category_id = $validated['sub_category_id'];
    //         $clientRequest->created_by = Auth::id();
    //         $clientRequest->office_id = Auth::user()->station;
    //         $clientRequest->rate_id = $validated['rate_id'];
    //         $clientRequest->save();

    //         // 2. Save Track
    //         $trackingId = DB::table('tracks')->insertGetId([
    //             'requestId' =>  $clientRequest->requestId,
    //             'clientId' => $clientRequest->clientId,
    //             'current_status' => 'Awaiting Collection',
    //             'created_at' => now(),
    //             'updated_at' => now()
    //         ]);

    //         // 3. Save Tracking Info
    //         $userName = User::find($validated['userId'])->name;
    //         $regNo = Vehicle::find($validated['vehicleId'])->regNo;

    //         DB::table('tracking_infos')->insert([
    //             'trackId' => $trackingId,
    //             'date' => now(),
    //             'details' => 'Client Request Submitted for Collection',
    //             'user_id' => $validated['userId'],
    //             'vehicle_id' => $validated['vehicleId'],
    //             'remarks' => 'Received client collection request, generated client request ID '.$clientRequest->requestId.', allocated '.$userName .' '. $regNo .' for collection',
    //             'created_at' => now(),
    //             'updated_at' => now()
    //         ]);

    //         $locationName = $validated['collectionLocation'];

    //         $location = Location::firstOrCreate(['location' => $locationName]);

    //         DB::commit();

    //         } catch (\Exception $e) {
    //             DB::rollBack();
    //             \Log::error('Tracking Info Insert Error', [
    //                 'message' => $e->getMessage(),
    //                 'trace' => $e->getTraceAsString(),
    //             ]);
    //             return back()->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
    //         }

    //     // --------------------------
    //     // ✅ SMS Logic After Commit
    //     // --------------------------
    //     $request_id = $clientRequest->requestId;
    //     $location = $clientRequest->collectionLocation;

    //     // Rider details
    //     $rider = User::find($clientRequest->userId);
    //     $rider_name = $rider?->name ?? 'Rider';
    //     $rider_phone = $rider?->phone_number;
    //     $rider_email = $rider->email;

    //     // Client details
    //     $client = Client::find($clientRequest->clientId);
    //     $client_name = $client?->name ?? 'Client';
    //     $client_phone = $client?->contact;

    //     $client_email = $client->email;

    //     // Rider SMS
    //     $rider_message = "Dear $rider_name, Collect Parcel for client ($client_name) $client_phone Request ID: $request_id at $location";
    //     $smsService->sendSms(
    //         phone: $rider_phone,
    //         subject: 'Client Collections Alert',
    //         message: $rider_message,
    //         addFooter: true
    //     );
    //     SentMessage::create([
    //         'request_id' => $request_id,
    //         'client_id' => $clientRequest->clientId,
    //         'rider_id' => $clientRequest->userId,
    //         'recipient_type' => 'rider',
    //         'recipient_name' => $rider_name,
    //         'phone_number' => $rider_phone,
    //         'subject' => 'Client Collections Alert',
    //         'message' => $rider_message,
    //     ]);

    //     // rider email

    //     $rider_subject = 'Client Collections Alert';
    //     $rider_email = $rider_email;
    //     $terms = env('TERMS_AND_CONDITIONS', '#'); // fallback if not set
    //     $footer = "<br><p><strong>Terms & Conditions:</strong> <a href=\"{$terms}\" target=\"_blank\">Click here</a></p>
    //                <p>Thank you for using Ufanisi Courier Services for we are <strong>Fast, Reliable and Secure</strong></p>";
        
    //     $rider_email_message = "Dear $rider_name, <br><br> Collect Parcel for client ($client_name) $client_phone Request ID: $request_id at $location";
    //     $fullRiderMessage = $rider_email_message . $footer;

    //     $emailResponse = EmailHelper::sendHtmlEmail($rider_email, $rider_subject, $fullRiderMessage);

    //     // Client SMS
    //     $client_message = "Dear $client_name, We have allocated $rider_name $rider_phone to collect your parcel Request ID: $request_id";
    //     $smsService->sendSms(
    //         phone: $client_phone,
    //         subject: 'Parcel Collection Alert',
    //         message: $client_message,
    //         addFooter: true
    //     );
    //     SentMessage::create([
    //         'request_id' => $request_id,
    //         'client_id' => $clientRequest->clientId,
    //         'rider_id' => $clientRequest->userId,
    //         'recipient_type' => 'client',
    //         'recipient_name' => $client_name,
    //         'phone_number' => $client_phone,
    //         'subject' => 'Parcel Collection Alert',
    //         'message' => $client_message,
    //     ]);

    //     // send email

    //     $subject = 'Parcel Collection Alert';
    //     $message = "Dear $client_name, <br><br> We have allocated $rider_name $rider_phone to collect your parcel Request ID: $request_id";
    //     $fullMessage = $message . $footer;

    //     $emailResponse = EmailHelper::sendHtmlEmail($client_email, $subject, $fullMessage);

    //     return redirect()->back()->with('success', 'Client Request Saved and Tracked Successfully')->with('email_status', $emailResponse->getData());
    // }
}
