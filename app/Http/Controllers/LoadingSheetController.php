<?php

namespace App\Http\Controllers;

use App\Models\LoadingSheet;
use App\Models\LoadingSheetWaybill;
use App\Models\Office;
use App\Models\Rate;
use App\Models\ShipmentCollection;
use App\Models\Transporter;
use App\Models\Dispatcher;
use App\Models\ClientRequest;
use App\Models\TransporterTrucks;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\PdfReportTrait;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Services\SmsService;
use App\Models\SentMessage;
use App\Models\Client;
use App\Helpers\EmailHelper;

class LoadingSheetController extends Controller
{
    use PdfReportTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $offices = Office::where('id',Auth::user()->station)->get();
        $destinations = $shipments = DB::table('shipment_collections')
        ->join('client_requests', 'shipment_collections.requestId', '=', 'client_requests.requestId')
        ->join('rates', 'shipment_collections.destination_id', '=', 'rates.id')
        ->where('client_requests.status', 'verified')
        ->where('shipment_collections.loading_status', null)
        ->select('rates.destination as destination_name', 'rates.id as destination_id', DB::raw('count(shipment_collections.id) as total_shipments'))
        ->groupBy('rates.destination','rates.id')
        ->get();

        //dd($destinations);
        $transporters = Transporter::orderBy('id', 'desc')->get();
        
        $dispatchers = Dispatcher::where('office_id',Auth::user()->station)->get();

        $sheets = LoadingSheet::with(['rate'])
            ->withCount('waybills') // This adds a `waybills_count` column
            ->orderBy('id', 'desc')
            ->get();
        //dd($sheets);

        $count = LoadingSheet::count()+1; // Example: 1
        $number = str_pad($count, 5, '0', STR_PAD_LEFT); // Result: 00001
        $drivers = User::where('role', 'driver')->get();
        return view('loading-sheet.index', with(['sheets'=>$sheets, 'drivers'=>$drivers, 'offices'=>$offices,'destinations'=>$destinations,'transporters'=>$transporters,'dispatchers'=>$dispatchers,'batch_no'=>$number]));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('loading-sheet.create');
    }

    public function updateArrivalDetails(Request $request)
    {
        $request->validate([
            'loading_sheet_id' => 'required|exists:loading_sheets,id',
            'dispatchers' => 'required|exists:users,id',
        ]);

        $sheet = LoadingSheet::find($request->loading_sheet_id);
        $sheet->dispatcher_id = $request->dispatchers;
        $sheet->save();

        return response()->json(['success' => true, 'message' => 'Updated successfully']);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        

        $loadingSheet = new LoadingSheet();
        $loadingSheet->office_id = $request->origin_station_id;
        $loadingSheet->station_id = Auth::user()->station;
        $loadingSheet->destination = $request->destination;
        $loadingSheet->destination_id = $request->destination;
        $loadingSheet->transported_by = $request->transporter_id;
        $loadingSheet->vehicle_reg_no = $request->reg_no;
        $loadingSheet->dispatcher_id = $request->dispatcher_id;
        $loadingSheet->dispatched_by = $request->dispatcher_id;
        $loadingSheet->batch_no = $request->batch_no;
        $loadingSheet->save();

        TransporterTrucks::where('id', $request->reg_no)
                        ->update(['status' => 'booked']);

        return redirect()->back()->with('success', 'Loading Sheet saved successfully!');
    }

    public function loadingsheet_waybills($id)
    {
        $loadingSheet = LoadingSheet::findOrFail($id);

        // Build the base query (no ->get() yet)
        $shipmentQuery = ShipmentCollection::join(
                'client_requests',
                'shipment_collections.requestId',
                '=',
                'client_requests.requestId'
            )
            ->where('client_requests.status', 'verified')
            ->where('shipment_collections.waybill_no', '!=', '') // Exclude empty waybill numbers
            ->whereNull('shipment_collections.loading_status') // Correct way to check NULL
            ->select('shipment_collections.*');

        // If destination_id is NOT 0, filter by destination
        if ($loadingSheet->destination_id != 0) {
            $shipmentQuery->where('shipment_collections.destination_id', $loadingSheet->destination_id);
        }

        // Fetch results at the end
        $shipment_collections = $shipmentQuery->get();

        // Get destination name (if applicable)
        $destination = DB::table('loading_sheets')
            ->join('rates', 'loading_sheets.destination', '=', 'rates.id')
            ->where('loading_sheets.id', $id)
            ->select('rates.destination as destination_name')
            ->first();

        $loadingSheets = DB::table('loading_sheets')
            ->join('transporters', 'loading_sheets.transported_by', '=', 'transporters.id')
            ->join('transporter_trucks', 'transporters.id', '=', 'transporter_trucks.transporter_id')
            ->select('loading_sheets.*', 'transporters.name as transporter_name', 'transporter_trucks.reg_no')
            ->first();

    return view('loading-sheet.loading_waybills')->with([
        'shipment_collections' => $shipment_collections,
        'ls_id' => $id,
        'loadingSheet' => $loadingSheets,
        'loading_sheet' => $loadingSheet,
        'destination' => $destination,
    ]);
}



    public function generate_loading_sheet($id)
    {
                // Fetch loading sheet details
        $loadingSheet = LoadingSheet::with(['office'])->find($id);

        // Destination (fallback if not found)
        $destination = Rate::find($loadingSheet->destination);
        if ($destination === null) {
            $destination = (object)[
                'id'          => null,
                'destination' => $loadingSheet->office->name ?? 'Unknown Destination',
            ];
        }

        // Main table data
        $data = DB::table('loading_sheet_waybills as lsw')
            ->join('shipment_items as si', 'lsw.shipment_item_id', '=', 'si.id')
            ->join('shipment_collections as sc', 'lsw.shipment_id', '=', 'sc.id')
            ->leftJoin('rates as r', 'sc.destination_id', '=', 'r.id') // allow missing rates
            ->join('clients as c', 'sc.client_id', '=', 'c.id')
            ->select(
                'lsw.waybill_no',
                'lsw.id',
                DB::raw('COALESCE(r.destination, "'.$destination->destination.'") as destination'),
                'sc.total_cost',
                'sc.payment_mode',
                'c.name as client_name',
                DB::raw('GROUP_CONCAT(si.item_name SEPARATOR ", ") as item_names'),
                DB::raw('SUM(si.actual_quantity) as total_quantity'),
                DB::raw('SUM(si.actual_weight) as total_weight')
            )
            ->where('lsw.loading_sheet_id', $id)
            ->groupBy(
                'lsw.waybill_no', 
                'c.name', 
                'r.id', 
                'sc.total_cost', 
                'sc.payment_mode', 
                'r.destination',
                'lsw.id'
            )
            ->orderBy('lsw.id', 'desc')
            ->get();

        // Totals for footer
        $totals = DB::table('loading_sheet_waybills as lsw')
            ->join('shipment_items as si', 'lsw.shipment_item_id', '=', 'si.id')
            ->join('shipment_collections as sc', 'lsw.shipment_id', '=', 'sc.id')
            ->select(
                DB::raw('SUM(si.actual_quantity) as total_quantity_sum'),
                DB::raw('SUM(si.actual_weight) as total_weight_sum'),
                DB::raw('SUM(sc.total_cost) as total_cost_sum')
            )
            ->where('lsw.loading_sheet_id', $id)
            ->first();

        // Generate PDF
        return $this->renderPdfWithPageNumbers(
            'loading-sheet.loading-sheet-pdf',
            [
                'loading_sheet' => $loadingSheet,
                'destination'   => $destination,
                'data'          => $data,
                'totals'        => $totals
            ],
            'loading_sheet.pdf',
            'a4',
            'portrait'
        );

    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $loadingSheet = LoadingSheet::with(['office'])->find($id);
        $destination = Rate::where('id',$loadingSheet->destination)->first();

        $loading_sheet_waybills = LoadingSheetWaybill::with(['shipment_item', 'loading_sheet'])->get();

       $data = DB::table('loading_sheet_waybills as lsw')
        ->join('shipment_items as si', 'lsw.shipment_item_id', '=', 'si.id')
        ->join('shipment_collections as sc', 'lsw.shipment_id', '=', 'sc.id')
        ->join('rates as r', 'sc.destination_id', '=', 'r.id')
        ->join('clients as c', 'sc.client_id', '=', 'c.id')

        ->select(
            'lsw.waybill_no',
            'r.destination', // Destination from rates
            'sc.total_cost',
            'c.name as client_name',
            'sc.payment_mode',
            DB::raw('GROUP_CONCAT(si.item_name SEPARATOR ", ") as item_names'),
            DB::raw('SUM(si.actual_quantity) as total_quantity'),
            DB::raw('SUM(si.actual_weight) as total_weight')
        )
        ->where('lsw.loading_sheet_id', $id)
        ->groupBy('lsw.waybill_no', 'c.name', 'r.id', 'sc.total_cost','sc.payment_mode', 'r.destination')
        ->get();

        $totals = DB::table('loading_sheet_waybills as lsw')
        ->join('shipment_items as si', 'lsw.shipment_item_id', '=', 'si.id')
        ->join('shipment_collections as sc', 'lsw.shipment_id', '=', 'sc.id')
        ->select(
            DB::raw('SUM(si.actual_quantity) as total_quantity_sum'),
            DB::raw('SUM(si.actual_weight) as total_weight_sum'),
            DB::raw('SUM(sc.total_cost) as total_cost_sum')
        )->where('lsw.loading_sheet_id',$id)
        ->first();
        //dd($data);

        return view('loading-sheet.loading_sheet')->with([
            'loading_sheet'=>$loadingSheet,'destination'=>$destination,'data'=>$data,'totals'=>$totals
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LoadingSheet $loadingSheet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'dispatch_date'=>'required',
        ]);
        $loadingSheet = LoadingSheet::find($id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LoadingSheet $loadingSheet)
    {
        //
    }
    public function dispatch($id, SmsService $smsService) 
    {
        $sheet = LoadingSheet::findOrFail($id);

        $shipment_ids = LoadingSheetWaybill::where('loading_sheet_id', $id)
            ->pluck('shipment_id')
            ->groupBy('loading_sheet_id')
            ->toArray();

        $shipmentIds = collect($shipment_ids[""])->unique()->values();

        $shipments = ShipmentCollection::with('client')
            ->whereIn('id', $shipmentIds)
            ->get();

        foreach ($shipments as $shipment) {
            $client = $shipment->client;
            $waybill_no = $shipment->waybill_no;
            $requestId = $shipment->requestId;

            $existingTrack = DB::table('tracks')->where('requestId', $requestId)->first();

            if ($existingTrack) {
                $trackingId = $existingTrack->id;
                DB::table('tracks')
                    ->where('id', $trackingId)
                    ->update([
                        'current_status' => 'Parcel Dispatched',
                        'updated_at' => now(),
                    ]);
            } else {
                $trackingId = DB::table('tracks')->insertGetId([
                    'requestId' => $requestId,
                    'clientId' => $client->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::table('tracking_infos')->insert([
                'trackId'    => $trackingId,
                'date'       => now(),
                'details'    => 'Parcel dispatched',
                'remarks'    => $sheet->dispatcher->name.' dispatched the parcel from '.$client->name.', request ID '.$requestId,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // ✅ Update client_requests status to dispatched
            ClientRequest::where('id', $requestId)->update([
                'status' => 'dispatched',
                'updated_at' => now(),
            ]);

            // Send receiver SMS
            $receiverPhone = $shipment->receiver_phone;
            $parcelMessage = "Dear Customer, your parcel (Request ID: $requestId) has been dispatched. We will notify when the parcel arrives. Thank you for using Ufanisi Courier Services";

            $smsService->sendSms(
                phone: $receiverPhone,
                subject: 'Parcel Dispatched Alert',
                message: $parcelMessage,
                addFooter: true
            );

            SentMessage::create([
                'request_id'      => $requestId,
                'client_id'       => $client->id,
                'recipient_type'  => 'receiver',
                'recipient_name'  => $shipment->receiver_name ?? 'Receiver',
                'phone_number'    => $receiverPhone,
                'subject'         => 'Parcel Dispatch Alert',
                'message'         => $parcelMessage,
            ]);

            // sender email
            $parcelSenderMessage = "Dear Customer, your parcel has been dispatched. Track it using tracking no. $requestId ";
            $senderSubject = 'Parcel Dispatch Alert';
            $clientEmail = $client->email;
            $terms = env('TERMS_AND_CONDITIONS', '#'); // fallback if not set
            $footer = "<br><p><strong>Terms & Conditions:</strong> <a href=\"{$terms}\" target=\"_blank\">Click here</a></p>
                    <p>Thank you for using Ufanisi Courier Services.</p>";
            $fullSenderMessage = $parcelSenderMessage . $footer;
            $emailResponse = EmailHelper::sendHtmlEmail($clientEmail, $senderSubject, $fullSenderMessage);

            $sheet->dispatch_date = Carbon::now();
            $sheet->save();
        }

        return response()->json(['message' => 'Dispatch date updated', 'dispatch_date' => $sheet->dispatch_date,  'redirect' => route('loading_sheets.index')]);
    }
}
