<?php

namespace App\Http\Controllers;
use App\Jobs\SendCollectionNotificationsJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Models\Client;
use App\Models\ClientRequest;
use App\Models\SubCategory;
use App\Models\ShipmentCollection;
use App\Services\RequestIdService;
use App\Models\Vehicle;
use App\Models\User;
use App\Models\FrontOffice;
use App\Models\SameDayRate;
use App\Models\Office;
use App\Models\Rate;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use App\Traits\PdfReportTrait;
use App\Services\SmsService;
use App\Mail\GenericMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\HtmlString;
use App\Helpers\EmailHelper;
use App\Models\Location;

class SameDayController extends Controller
{
    use PdfReportTrait;

    protected $requestIdService;

    public function __construct(RequestIdService $requestIdService)
    {
        $this->requestIdService = $requestIdService;
    }

    public function on_account(Request $request )
    {

        $timeFilter = $request->query('time', 'all'); // default to all

        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $clients = Client::where('type', 'on_account')->get();
        $vehicles = Vehicle::all();
        $drivers = User::where('role', 'driver')->get();
        $sub_category = SubCategory::where('sub_category_name', 'Same Day')->firstOrFail();

        $locations = Rate::where('office_id', Auth::user()->station)
            ->whereIn('type', ['Same Day', 'same_day'])
            ->get();

        $samedaySubCategoryIds = SubCategory::where('sub_category_name', 'Same Day')->pluck('id');

        $clientRequests = ClientRequest::whereIn('sub_category_id', $samedaySubCategoryIds)
            ->whereHas('client', function ($query) {
                $query->where('type', 'on_account');
            })
            ->orderBy('created_at', 'desc')
            ->with(['client', 'user', 'vehicle'])
            ->get();

        UserLog::create([
            'name'         => Auth::user()->name,
            'actions'      => Auth::user()->name . ' viewed sameday on-account parcels at ' . now(),
            'url'          => $request->fullUrl(),
            'reference_id' => Auth::id(),
            'table'        => Auth::user()->getTable(),
        ]);

        return view('same_day.on_account', compact('clients', 'clientRequests', 'vehicles', 'drivers','timeFilter',
            'startDate',
            'endDate', 'sub_category','locations'));
    }

    public function client_on_account(Request $request )
    {

        $timeFilter = $request->query('time', 'all'); // default to all

        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $clients = Client::where('type', 'on_account')->get();
        $vehicles = Vehicle::all();
        $drivers = User::where('role', 'driver')->get();
        $sub_category = SubCategory::where('sub_category_name', 'Same Day')->firstOrFail();

        $locations = Rate::where('office_id', Auth::user()->station)
            ->whereIn('type', ['Same Day', 'same_day'])
            ->get();

        $samedaySubCategoryIds = SubCategory::where('sub_category_name', 'Same Day')->pluck('id');

        $clientRequests = ClientRequest::whereIn('sub_category_id', $samedaySubCategoryIds)
            ->whereHas('client', function ($query) {
                $query->where(['type'=>'on_account','source'=>'client_portal']);
            })
            ->orderBy('created_at', 'desc')
            ->with(['client', 'user', 'vehicle'])
            ->get();

        UserLog::create([
            'name'         => Auth::user()->name,
            'actions'      => Auth::user()->name . ' viewed sameday on-account parcels in the client portal at ' . now(),
            'url'          => $request->fullUrl(),
            'reference_id' => Auth::id(),
            'table'        => Auth::user()->getTable(),
        ]);

        return view('same_day.client_on_account', compact('clients', 'clientRequests', 'vehicles', 'drivers','timeFilter',
            'startDate',
            'endDate', 'sub_category','locations'));
    } 

    public function walk_in()
    {
        $riders = User::where(['role'=>'driver','station'=>Auth::user()->station])->get();
        $offices = Office::where('id',Auth::user()->station)->get();
        $vehicles = Vehicle::all();
        $loggedInUserId = Auth::user()->id;
        
        $destinations = Rate::where('office_id', 2)
            ->whereIn('type', ['Same Day', 'same_day'])
            ->orderBy('destination', 'asc')
            ->get();

        $walkInClients = Client::where('type', 'walkin')->get();
        $sub_category = SubCategory::where('sub_category_name', 'Same Day')->firstOrFail();

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
        $locations = Rate::where('office_id', 2)
            ->whereIn('type', ['Same Day', 'same_day'])
            ->get();
        $samedaySubCategoryIds = SubCategory::where('sub_category_name', 'Same Day')->pluck('id');

        $clientRequests = ClientRequest::whereIn('sub_category_id', $samedaySubCategoryIds)
            ->whereHas('client', function ($query) {
                $query->where('type', 'walkin');
            })
            ->orderBy('created_at', 'desc')
            ->with(['client', 'user', 'vehicle'])
            ->get();

        UserLog::create([
            'name'         => Auth::user()->name,
            'actions'      => Auth::user()->name . ' viewed sameday walk-in parcels at ' . now(),
            'url'          => $request->fullUrl(),
            'reference_id' => Auth::id(),
            'table'        => Auth::user()->getTable(),
        ]);

        return view('same_day.walk_in', compact('clientRequests',      'offices',
            'loggedInUserId',
            'destinations',
            'walkInClients',
            'collections',
            'consignment_no',
            'sub_category',
            'locations',
            'vehicles',
            'riders'
        ));
    }
    
    public function sameday_walkin_report()
    {
        $samedaySubCategoryIds = SubCategory::where('sub_category_name', 'Same Day')->pluck('id');

        $clientRequests = ClientRequest::whereIn('sub_category_id', $samedaySubCategoryIds)
            ->whereHas('client', function ($query) {
                $query->where('type', 'walkin');
            })
            ->with(['client', 'user', 'vehicle'])
            ->get();

        UserLog::create([
            'name'         => Auth::user()->name,
            'actions'      => Auth::user()->name . ' generated sameday walk-in parcels report at ' . now(),
            'url'          => $request->fullUrl(),
            'reference_id' => Auth::id(),
            'table'        => Auth::user()->getTable(),
        ]);

        return $this->renderPdfWithPageNumbers(
            'same_day.sameday_walkin_report',
            ['clientRequests' => $clientRequests],
            'sameday_walkin_report.pdf',
            'a4',
            'landscape'
        );
    }

    public function sameday_account_report()
    {
        $samedaySubCategoryIds = SubCategory::where('sub_category_name', 'Same Day')->pluck('id');

        $clientRequests = ClientRequest::whereIn('sub_category_id', $samedaySubCategoryIds)
            ->whereHas('client', function ($query) {
                $query->where('type', 'on_account');
            })
            ->with(['client', 'user', 'vehicle'])
            ->get();

        UserLog::create([
            'name'         => Auth::user()->name,
            'actions'      => Auth::user()->name . ' generated sameday on-account parcels report at ' . now(),
            'url'          => $request->fullUrl(),
            'reference_id' => Auth::id(),
            'table'        => Auth::user()->getTable(),
        ]);


        return $this->renderPdfWithPageNumbers(
            'same_day.sameday_account_report',
            ['clientRequests' => $clientRequests],
            'sameday_account_report.pdf',
            'a4',
            'landscape'
        );
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
        //Log::info('Entered store() method.', ['request_data' => $request->all()]);

        try {
            // 1. Validate incoming request (requestId removed)
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
                'deadline_date' => 'nullable|date_format:Y-m-d\TH:i',
                'priority_extra_charge' => 'nullable|numeric',
                'fragile' => 'nullable|string',
                'fragile_charge' => 'nullable|numeric',
                'rate_id' => 'nullable',      
                'pickupLocation' => 'required|string',
            ]);
        } catch (\Exception $e) {
            Log::error('Validation failed.', ['message' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Validation error: ' . $e->getMessage()]);
        }



        DB::beginTransaction();

        try {
            Log::info('Transaction started.');

            $requestId = $this->requestIdService->generate();

            // 3. Save client request
            $clientRequest = new ClientRequest();
            $clientRequest->clientId = $validated['clientId'];
            $clientRequest->pickupLocation = $validated['pickupLocation'];
            $clientRequest->collectionLocation = $validated['collectionLocation'];
            $clientRequest->parcelDetails = $validated['parcelDetails'];
            $clientRequest->dateRequested = Carbon::parse($validated['dateRequested'])->format('Y-m-d H:i:s');
            $clientRequest->userId = $validated['userId'];
            $clientRequest->vehicleId = $validated['vehicleId'];
            $clientRequest->requestId = $requestId;
            $clientRequest->category_id = $validated['category_id'];
            $clientRequest->sub_category_id = $validated['sub_category_id'];
            $clientRequest->priority_level = $validated['priority_level'];
            $clientRequest->deadline_date = $validated['deadline_date']
                ? \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $validated['deadline_date'])->format('Y-m-d H:i:s')
                : null;
            $clientRequest->fragile_item = $validated['fragile'] ?? 'no';
            $clientRequest->priority_level_amount = $validated['priority_extra_charge'] ?? 0;
            $clientRequest->fragile_item_amount = $validated['fragile_charge'] ??
            $clientRequest->created_by = Auth::id();
            $clientRequest->office_id = Auth::user()->station;
            $clientRequest->rate_id = $validated['rate_id'];
            $clientRequest->save();

            Log::info('ClientRequest saved.', ['clientRequest_id' => $clientRequest->id]);

            // 4. Create tracking record
            $trackingId = DB::table('tracks')->insertGetId([
                'requestId' => $clientRequest->requestId,
                'clientId' => $clientRequest->clientId,
                'current_status' => 'Awaiting Collection',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            Log::info('Track created.', ['track_id' => $trackingId]);

            // 5. Get rider and vehicle details
            $rider = User::find($clientRequest->userId);
            $vehicle = Vehicle::find($clientRequest->vehicleId);

            if (!$rider || !$vehicle) {
                throw new \Exception('Rider or Vehicle not found.');
            }

            // 6. Insert tracking info
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

            Log::info('Tracking info inserted.');

            // 7. Ensure collection location exists in Location table
            Location::firstOrCreate(['location' => $clientRequest->pickupLocation]);
            Log::info('Collection location ensured.');

            // Commit transaction
            DB::commit();
            Log::info('Transaction committed.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Client Request Store Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
        }

        // Dispatch notification job (non-critical)
        try {
            $client = Client::find($clientRequest->clientId);
            SendCollectionNotificationsJob::dispatch($clientRequest, $client, $rider, $vehicle);
            Log::info('Notification job dispatched.', [
                'clientRequest_id' => $clientRequest->id,
                'client_id' => optional($client)->id
            ]);
        } catch (\Exception $e) {
            Log::warning('Failed to dispatch SendCollectionNotificationsJob', ['message' => $e->getMessage()]);
        }

        return redirect()->back()->with('success', 'Client request submitted successfully.');
    }

    public function update(Request $request, $id, SmsService $smsService)
    {
        $validatedData = $request->validate([
            'userId' => 'required|exists:users,id',
            'vehicleId' => 'required',
        ]);

        $client_request = ClientRequest::findOrFail($id);

        $now = now();
        $authId = Auth::id();
        $requestId = $client_request->requestId;

        $shipment = ShipmentCollection::with(['track:id,requestId,current_status'])
            ->where('requestId', $requestId)
            ->firstOrFail();

        // declare variables here
        $rider_name = null;
        $rider_phone = null;

        DB::transaction(function () use (
            $validatedData, $id, $now, $authId, $shipment, $requestId, $client_request,
            &$rider_name, &$rider_phone
        ) {
            $client_request->status = "collected";
            $client_request->vehicleId = $validatedData['vehicleId'];
            $client_request->userId = $validatedData['userId'];
            $client_request->save();

            // update shipment_collection
            $shipment_collection = ShipmentCollection::where('requestId', $requestId)->first();
            $shipment_collection->status = "collected";
            $shipment_collection->save();

            // get rider info
            $rider = User::findOrFail($validatedData['userId']);
            $rider_name = $rider->name;
            $rider_phone = $rider->phone_number;

            // update shipment status
            DB::table('shipment_collections')
                ->where('id', $shipment->id)
                ->update(['status' => 'delivery_rider_allocated', 'updated_at' => $now]);

            // update track
            $trackId = DB::table('tracks')
                ->where('requestId', $requestId)
                ->tap(function ($query) use ($now) {
                    $query->update([
                        'current_status' => 'Delivery Rider Allocated',
                        'updated_at' => $now
                    ]);
                })
                ->value('id');

            // insert tracking info
            DB::table('tracking_infos')->insert([
                'trackId' => $trackId,
                'date' => $now,
                'details' => "Delivery Rider Allocated",
                'remarks' => "We have allocated {$rider_name} of phone number {$rider_phone} to deliver your parcel {$requestId} Waybill No: {$shipment->waybill_no}.",
                'created_at' => $now,
                'updated_at' => $now
            ]);
        });

        try {
            $receiverMsg = "Hello {$shipment->receiver_name}, We have allocated {$rider_name} of phone number {$rider_phone} to deliver your parcel {$requestId}. Thank you for choosing UCSL.";
            $smsService->sendSms($shipment->receiver_phone, 'Parcel dispatched for delivery', $receiverMsg, true);

            // rider message
            $riderMsg = "Hello {$rider_name}, You have been allocated to deliver parcel with Request ID: {$requestId}.
            Receiver: {$shipment->receiver_name}, Phone: {$shipment->receiver_phone}, Address: {$shipment->receiver_address}. Please contact the receiver to arrange delivery. Thank you.";
            $smsService->sendSms($rider_phone, 'New Delivery Assigned', $riderMsg, true);

            DB::table('sent_messages')->insert([
                'request_id' => $requestId,
                'client_id' => $shipment->client_id,
                'rider_id' => $authId,
                'recipient_type' => 'receiver',
                'recipient_name' => $shipment->receiver_name,
                'phone_number' => $shipment->receiver_phone,
                'subject' => 'dispatched for delivery',
                'message' => $receiverMsg,
                'created_at' => $now,
                'updated_at' => $now
            ]);

            $terms = env('TERMS_AND_CONDITIONS', '#');
            $footer = "<br><p><strong>Terms & Conditions Applies:</strong> <a href=\"{$terms}\" target=\"_blank\">Click here</a></p>
                    <p>Thank you for using Ufanisi Courier Services.</p>";

            EmailHelper::sendHtmlEmail($shipment->receiver_email, 'Rider Allocated', $receiverMsg . $footer);
        } catch (\Exception $e) {
            \Log::error('Notification Error: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Rider allocated successfully.');
    }

    public function client_portal_sameday_report(Request $request)
    {
        $samedaySubCategoryIds = SubCategory::where('sub_category_name', 'Same Day')->pluck('id');
        $clientRequests = ClientRequest::where('sub_category_id', $samedaySubCategoryIds)
            ->whereHas('client', function ($query) {
                $query->where('type', 'on_account');
            })
            ->where('clientId', auth('client')->id())
            ->with(['client', 'user', 'vehicle']);

        // ✅ Apply date range filter if provided
        if ($request->filled('start') && $request->filled('end')) {
            $clientRequests->whereHas('shipmentCollection', function ($query) use ($request) {
                $query->whereBetween('created_at', [
                    $request->start . " 00:00:00",
                    $request->end . " 23:59:59"
                ]);
            });
        } elseif ($request->filled('start')) {
            $clientRequests->whereHas('shipmentCollection', function ($query) use ($request) {
                $query->whereDate('created_at', '>=', $request->start);
            });
        } elseif ($request->filled('end')) {
            $clientRequests->whereHas('shipmentCollection', function ($query) use ($request) {
                $query->whereDate('created_at', '<=', $request->end);
            });
        }

        $clientRequests = $clientRequests->get();

        return $this->renderPdfWithPageNumbers(
            'same_day.client_portal_sameday_report',
            ['clientRequests' => $clientRequests],
            'sameday_parcels_report.pdf',
            'a4',
            'landscape'
        );
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
