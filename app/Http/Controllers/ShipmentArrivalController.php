<?php

namespace App\Http\Controllers;

use App\Models\ShipmentArrival;
use App\Models\LoadingSheet;
use App\Models\Office;
use App\Models\Transporter;
use App\Models\Dispatcher;
use App\Models\Payment;
use App\Models\User;
use App\Models\Rate;
use App\Models\LoadingSheetWaybill;
use App\Models\ShipmentCollection;
use Illuminate\Http\Request;
use App\Traits\PdfReportTrait;
use Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Jobs\SendIssueNotificationsJob;
use Illuminate\Support\Facades\Log;
use App\Services\SmsService;
use App\Models\SentMessage;
use App\Helpers\EmailHelper;

class ShipmentArrivalController extends Controller
{
    use PdfReportTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        
        $offices = Office::where('id',Auth::user()->station)->get();
        $destinations = $shipments = DB::table('shipment_collections')
            ->join('client_requests', 'shipment_collections.requestId', '=', 'client_requests.requestId')
            ->join('rates', 'shipment_collections.destination_id', '=', 'rates.id')
            ->where('client_requests.status', 'verified')
            ->select('rates.destination as destination_name', 'rates.id as destination_id', DB::raw('count(shipment_collections.id) as total_shipments'))
            ->groupBy('rates.destination','rates.id')
            ->get();

        //dd($destinations);
        $transporters = Transporter::orderBy('id', 'desc')->get();
        
        $dispatchers = Dispatcher::where('office_id', Auth::user()->station)->get();

        $sheets = LoadingSheet::with('rate')
            ->where('office_id', Auth::user()->station)
            ->withCount('waybills') // adds waybills_count
            ->orderBy('created_at', 'desc')
            ->get();
        //dd($sheets);

        $count = LoadingSheet::count()+1; // Example: 1
        $number = str_pad($count, 5, '0', STR_PAD_LEFT); // Result: 00001
        $drivers = User::where('role', 'driver')->get();
        return view('shipment_arrivals.index', with(['sheets'=>$sheets, 'drivers'=>$drivers, 'offices'=>$offices,'destinations'=>$destinations,'transporters'=>$transporters,'dispatchers'=>$dispatchers,'batch_no'=>$number]));
    
        //return view('shipment_arrivals.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ShipmentArrival $shipmentArrival)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ShipmentArrival $shipmentArrival)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
   

    public function updateArrivalDetails(Request $request)
    {
        $request->validate([
            'loading_sheet_id' => 'required|exists:loading_sheets,id',
            'dispatchers' => 'required|exists:users,id',
        ]);

        $sheet = LoadingSheet::find($request->loading_sheet_id);
        $sheet->offloading_clerk = $request->dispatchers;
        $sheet->save();

        return response()->json(['success' => true, 'message' => 'Updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShipmentArrival $shipmentArrival)
    {
        //
    }

    public function generate(Request $request)
    {
        $filter = $request->query('filter');
        $value = $request->query('value');

        

        // Safely split start/end date
        [$startDate, $endDate] = explode('_', $value) + [null, null];

        $query = LoadingSheet::query();
        $titled = '';

        if ($filter === 'daterange') {
            //dd($filter);
            if ($startDate && $endDate) {
                $titled = "Between ".$startDate." To ".$endDate;
                // Both dates provided
                $query->whereBetween('dispatch_date', [
                $startDate . ' 00:00:00',
                $endDate . ' 23:59:59'
            ]);
            } elseif ($startDate && empty($endDate)) {
                // Only start date provided
                $titled ="of ".$startDate;
                $query->whereDate('dispatch_date', $startDate);
            }
        } elseif ($filter === 'dispatch') {
            $titled = "of batch No. ".str_pad($value, 5, '0', STR_PAD_LEFT);
            $query->where('batch_no', 'like', "%$value%");
        } 

        $loading_sheets = $query->get();

        return $this->renderPdfWithPageNumbers(
            'shipment_arrivals.arrivals_report',
            [
            'sheets' => $loading_sheets,
            'titled' => $titled,
            ],
            'arrivals_report.pdf',
            'a4',
            'landscape'
        );
    }

    public function generateParcels(Request $request)
    {
        $filter = $request->query('filter');
        $value = $request->query('value');
        
        $from = $request->query('from');
        $to = $request->query('to');
        $sheet_id = $request->query('sheet_id');
        $status = $request->query('status');
        //dd($request->all());

        $loadingSheet = LoadingSheet::with(['office'])->find($sheet_id);
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
        ->where('lsw.loading_sheet_id', $sheet_id)
        ->where('sc.payment_mode',$value)
        ->groupBy('lsw.waybill_no', 'c.name', 'r.id', 'sc.total_cost','sc.payment_mode')
        ->get();

        $totals = DB::table('loading_sheet_waybills as lsw')
        ->join('shipment_items as si', 'lsw.shipment_item_id', '=', 'si.id')
        ->join('shipment_collections as sc', 'lsw.shipment_id', '=', 'sc.id')
        ->select(
            DB::raw('SUM(si.actual_quantity) as total_quantity_sum'),
            DB::raw('SUM(si.actual_weight) as total_weight_sum'),
            DB::raw('SUM(sc.total_cost) as total_cost_sum')
        )->where('lsw.loading_sheet_id',$sheet_id)
        ->first();

        $query = ShipmentCollection::query();

        if ($filter === 'type') {
            $query->where('payment_mode', $value);
        }

        $waybills = $query->get();

        //dd($data);
        $title = "Report of ";

        $pdf = Pdf::loadView('shipment_arrivals.arrivals_report_detail' , [
            'data'=>$data,
            'title'=>$title,
        ])->setPaper('a4', 'landscape');;
        return $pdf->download("arrivals_report_detailed.pdf");

        // Example: return as downloadable CSV or a view
        // return view('reports.shipment_arrivals', compact('shipments', 'filter', 'value'));
    }

    public function generateParcelsUncollected($id,$type)
    {
        $sheet_id = $id;
        if($type=='Uncollected'){
            $status=null;
            $title = "Report of Uncollected ";
        }elseif($type=='Collected'){
            $status='Collected';
            $title = "Report of Collected ";
        }else{
            $status=null;
            $title = "Report of ";
        }
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
            ->where('lsw.loading_sheet_id', $sheet_id)
            ->where('sc.status',$status)
            ->groupBy('lsw.waybill_no', 'c.name', 'r.id', 'sc.total_cost','sc.payment_mode')
            ->get();

        
        $pdf = Pdf::loadView('shipment_arrivals.arrivals_report_detail' , [
            'data'=>$data,
            'title'=>$title
        ])->setPaper('a4', 'landscape');;
        return $pdf->download("arrivals_report_detailed.pdf");
    }

    public function arrival_details($id)
    {

        $loadingSheet = LoadingSheet::with(['office'])->find($id);
        $destination = Rate::where('id',$loadingSheet->destination)->first();

        $loading_sheet_waybills = LoadingSheetWaybill::with(['shipment_item', 'loading_sheet'])->get();

        $data = DB::table('loading_sheet_waybills as lsw')
            ->join('shipment_items as si', 'lsw.shipment_item_id', '=', 'si.id')
            ->join('shipment_collections as sc', 'lsw.shipment_id', '=', 'sc.id')
            ->join('rates as r', 'sc.destination_id', '=', 'r.id')
            ->join('loading_sheets as ls', 'lsw.loading_sheet_id', '=', 'ls.id')
            ->join('clients as c', 'sc.client_id', '=', 'c.id')
            ->join('transporter_trucks as v', 'ls.vehicle_reg_no', '=', 'v.id') // join for vehicle
            ->join('transporters as t', 'ls.transported_by', '=', 't.id') //
            ->select(
                'lsw.waybill_no',
                DB::raw('MAX(r.destination) as destination'),
                'sc.actual_cost',
                'sc.actual_total_cost',
                'sc.id',
                'sc.requestId',
                'ls.vehicle_reg_no',
                'ls.transported_by',
                'ls.dispatch_date',
                'sc.status',
                'c.name as client_name',
                'sc.actual_vat',
                't.name',
                'v.reg_no',
                'sc.payment_mode',
                DB::raw('GROUP_CONCAT(si.item_name SEPARATOR ", ") as item_names'),
                DB::raw('SUM(si.actual_quantity) as total_quantity'),
                DB::raw('SUM(si.actual_weight) as total_weight')
            )
            ->where('lsw.loading_sheet_id', $id)
            ->groupBy(
                'lsw.waybill_no',
                'c.name',
                'r.id',
                'sc.actual_vat','sc.actual_cost','sc.actual_total_cost',
                'sc.payment_mode',
                'sc.id',
                'sc.status',
                'sc.requestId',
                'ls.vehicle_reg_no',
                'ls.transported_by',
                'ls.dispatch_date',
                't.name',
                'v.reg_no',
            )
            ->get();

        //dd($data);

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

        return view('shipment_arrivals.manifest_details')->with([
            'loading_sheet'=>$loadingSheet,'destination'=>$destination,'data'=>$data,'totals'=>$totals,'id'=>$id
        ]);
    }

    /**
     * Display the parcel collection data.
     */
    public function parcel_collection()
    {
        // Fetch all shipment arrivals
        $shipmentArrivals = ShipmentArrival::with(['payment', 'transporter_truck', 'transporter'])
            ->orderBy('created_at', 'desc')
            ->get();

        $riders = User::where(['role'=>'driver','station'=>Auth::user()->station])->get();

        // Get the latest consignment number
        $latestGRN = ShipmentCollection::where('grn_no', 'LIKE', 'GRN-%')
            ->orderByDesc('id')
            ->first();

        if ($latestGRN && preg_match('/GRN-(\d+)/', $latestGRN->grn_no, $matches)) {
            $lastNumber = intval($matches[1]);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 10000; // Start from GRN-10000
        }

        $grn_no = 'GRN-' . $newNumber;

        // Pass data to the view
        return view('shipment_arrivals.parcel_collection', compact('shipmentArrivals', 'riders', 'grn_no'));
    } 

    public function update(Request $request, $id, SmsService $smsService)
    {
        $validatedData = $request->validate([
            'delivery_rider' => 'required|exists:users,id', //  rider ID comes from users table
        ]);

        $arrival = ShipmentArrival::findOrFail($id);

        //dd($arrival);

        $now = now();
        $authId = Auth::id();
        $requestId = $arrival->requestId;

        // Preload shipment + track in one query
        $shipment = ShipmentCollection::with(['track:id,requestId,current_status'])
            ->where('requestId', $requestId)
            ->firstOrFail();

        DB::transaction(function () use ($validatedData, $id, $now, $authId, $shipment, $requestId, $arrival) {

        //dd($validatedData['delivery_rider']);

        $arrival->delivery_rider = $validatedData['delivery_rider']; 
        $arrival->delivery_rider_status="Allocated";
        $arrival->save();

        $rider = User::findOrFail($validatedData['delivery_rider']);

        //dd($rider);

        $rider_name = $rider->name;

        $rider_phone = $rider->phone_number;

         // Update shipment status
        DB::table('shipment_collections')
            ->where('id', $shipment->id)
            ->update(['status' => 'Delivery Rider Allocated', 'updated_at' => $now]);

        // Update client requests status
        DB::table('client_requests')
            ->where('requestId', $requestId)
            ->update(['delivery_rider_id' => $validatedData['delivery_rider'],'status'=>'Delivery Rider Allocated']);

        // Update track and get ID in one go
        $trackId = DB::table('tracks')
            ->where('requestId', $requestId)
            ->tap(function ($query) use ($now) {
                $query->update([
                    'current_status' => 'Delivery Rider Allocated',
                    'updated_at' => $now
                ]);
            })
            ->value('id');

        // Insert tracking info
        DB::table('tracking_infos')->insert([
                'trackId' => $trackId,
                'date' => $now,
                'details' => "Delivery Rider Allocated",
                'remarks' => "We have allocated {$rider_name} of phone number { $rider_phone } to deliver your parcel {$requestId} Waybill No: {$shipment->waybill_no}.",
                'created_at' => $now,
                'updated_at' => $now
            ]);
        });
        // Send notifications after commit
        try {
            $receiverMsg = "Hello {$shipment->receiver_name}, We have allocated {$rider_name} of phone number { $rider_phone } to deliver your parcel {$requestId} Waybill No: {$shipment->waybill_no}. Thank you for choosing UCSL.";
            $smsService->sendSms($shipment->receiver_phone, 'Parcel dispatched for delivery', $receiverMsg, true);

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
            $footer = "<br><p><strong>Terms & Conditions:</strong> <a href=\"{$terms}\" target=\"_blank\">Click here</a></p>
                    <p>Thank you for using Ufanisi Courier Services.</p>";

            EmailHelper::sendHtmlEmail($shipment->receiver_email, 'Parcel Arrived', $receiverMsg . $footer);
        } catch (\Exception $e) {
            \Log::error('Notification Error: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Rider allocated successfully.');
    }

    public function issue(Request $request, $id) 
    {
        $arrival = ShipmentArrival::with('shipmentCollection.client', 'shipmentCollection.payments')->findOrFail($id);

        // Always validate if there's a balance or no payment yet
        if (!$arrival->payment || $arrival->payment->balance > 0) {
            $request->validate([
                'payment_mode' => 'required|string',
                'reference' => 'required|string|max:10',
                'amount_paid' => 'required|numeric|min:1',
            ]);

            Payment::create([
                'type' => $request->payment_mode,
                'amount' => $request->amount_paid,
                'reference_no' => $request->reference,
                'date_paid' => now(),
                'client_id' => $arrival->shipmentCollection->client_id,
                'shipment_collection_id' => $arrival->shipment_collection_id,
                'status' => 'Pending Verification',
                'paid_by' => auth()->id(),
                'received_by' => auth()->id(),
                'verified_by' => auth()->id(),
            ]);
        }

        // 🔎 Recalculate total paid and balance across all payments
        $totalPaid = $arrival->shipmentCollection->payments->sum('amount');
        $totalCost = $arrival->shipmentCollection->total_cost ?? 0;
        $balance   = max(0, $totalCost - $totalPaid);

        // 🚫 If balance still exists, do not issue
        if ($balance > 0) {
            return back()->withErrors([
                'error' => "Cannot release parcel. Balance of Ksh. " . number_format($balance, 0) . " is still pending."
            ]);
        }

        DB::beginTransaction();
        try {
            // ✅ No balance, update to issued
            $arrival->update([
                'status' => 'delivered',
                'remarks' => $request->remarks ?? null,
            ]);

            // 2️⃣ Create goods received note number
            ShipmentCollection::where('requestId', $arrival->shipmentCollection->requestId)
                ->update(['grn_no' => $request->grn_no]);

            // Update track status
            DB::table('tracks')
                ->where('requestId', $arrival->shipmentCollection->requestId)
                ->update([
                    'current_status' => 'Parcel Delivered in Good Order',
                    'updated_at' => now(),
                ]);

            // Add tracking info
            $trackId = DB::table('tracks')
                ->where('requestId', $arrival->shipmentCollection->requestId)
                ->value('id');

            DB::table('tracking_infos')->insert([
                'trackId'   => $trackId,
                'date'      => now(),
                'details'   => 'Parcel delivered to receiver/agent',
                'user_id'   => auth()->id(),
                'vehicle_id'=> null,
                'remarks'   => "Delivered parcel for request ID {$arrival->shipmentCollection->requestId} to designated receiver/agent.",
                'created_at'=> now(),
                'updated_at'=> now(),
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to deliver parcel: ' . $e->getMessage()]);
        }

        // Send notification
        try {
            SendIssueNotificationsJob::dispatch(
                $arrival->shipmentCollection,
                $arrival->shipmentCollection->client,
                auth()->user()
            );
        } catch (\Exception $e) {
            Log::warning('Failed to dispatch SendIssueNotificationsJob', ['message' => $e->getMessage()]);
        }

        return back()->with('success', 'Parcel delivered successfully.');
    }

    public function parcel_collection_report()
    {
        // Fetch all shipment arrivals
        $shipmentArrivals = ShipmentArrival::with(['payment', 'transporter_truck', 'transporter'])
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->renderPdfWithPageNumbers(
            'shipment_arrivals.parcel_collection_report',
            ['shipmentArrivals' => $shipmentArrivals],
            'parcel_collection_report.pdf',
            'a4',
            'landscape'
        );
    }

}
