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

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class LoadingSheetController extends Controller
{
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
        ->select('rates.destination as destination_name', DB::raw('count(shipment_collections.id) as total_shipments'))
        ->groupBy('rates.destination')
        ->get();

        //dd($destinations);
        $transporters = Transporter::orderBy('id', 'desc')->get();
        
        $dispatchers = Dispatcher::all();

        $sheets = LoadingSheet::orderBy('id', 'desc')->get();
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([

            'origin_station_id' => 'required|exists:stations,id',
            'destination' => 'required|string|max:255',
            'transporter_id' => 'required|exists:transporters,id',
            'reg_no' => 'required|string|max:100',
            'dispatcher_id' => 'required|exists:dispatchers,id',
            'batch_no' => 'required|string|max:50',
        ]);
        //dd($request->reg_no);

        $loadingSheet = new LoadingSheet();
        $loadingSheet->office_id = $request->origin_station_id;
        $loadingSheet->station_id = Auth::user()->station;
        $loadingSheet->destination = $request->destination;
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

    public function loadingsheet_waybills($id){
        $loadingSheet = LoadingSheet::findOrFail($id);
        $shipment_collections = ShipmentCollection::join('client_requests', 'shipment_collections.requestId', '=', 'client_requests.requestId')
        ->where('client_requests.status', 'verified')
        ->where('shipment_collections.destination_id', $loadingSheet->destination)
        ->where('shipment_collections.waybill_no', '!=', '') // Correct syntax
        ->select('shipment_collections.*') // Select whatever fields you need
        ->get();


        //dd($shipment_collections);
        
        return view('loading-sheet.loading_waybills')->with(['shipment_collections'=>$shipment_collections, 'ls_id'=>$id]);
    }

    public function generate_loading_sheet($id){

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
            DB::raw('GROUP_CONCAT(si.item_name SEPARATOR ", ") as item_names'),
            DB::raw('SUM(si.actual_quantity) as total_quantity'),
            DB::raw('SUM(si.actual_weight) as total_weight')
        )
        ->groupBy('lsw.waybill_no', 'c.name', 'r.id', 'sc.total_cost')
        ->get();

        $totals = DB::table('loading_sheet_waybills as lsw')
        ->join('shipment_items as si', 'lsw.shipment_item_id', '=', 'si.id')
        ->join('shipment_collections as sc', 'lsw.shipment_id', '=', 'sc.id')
        ->select(
            DB::raw('SUM(si.actual_quantity) as total_quantity_sum'),
            DB::raw('SUM(si.actual_weight) as total_weight_sum'),
            DB::raw('SUM(sc.total_cost) as total_cost_sum')
        )
        ->first();
        //dd($data);
    

        $pdf = Pdf::loadView('loading-sheet.loading-sheet-pdf' , [
            'loading_sheet'=>$loadingSheet,'destination'=>$destination,'data'=>$data,'totals'=>$totals
        ]);
        return $pdf->download("loading_sheet.pdf");
       
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
            DB::raw('GROUP_CONCAT(si.item_name SEPARATOR ", ") as item_names'),
            DB::raw('SUM(si.actual_quantity) as total_quantity'),
            DB::raw('SUM(si.actual_weight) as total_weight')
        )
        ->groupBy('lsw.waybill_no', 'c.name', 'r.id', 'sc.total_cost')
        ->get();

        $totals = DB::table('loading_sheet_waybills as lsw')
        ->join('shipment_items as si', 'lsw.shipment_item_id', '=', 'si.id')
        ->join('shipment_collections as sc', 'lsw.shipment_id', '=', 'sc.id')
        ->select(
            DB::raw('SUM(si.actual_quantity) as total_quantity_sum'),
            DB::raw('SUM(si.actual_weight) as total_weight_sum'),
            DB::raw('SUM(sc.total_cost) as total_cost_sum')
        )
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
    public function update(Request $request, LoadingSheet $loadingSheet)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LoadingSheet $loadingSheet)
    {
        //
    }
}
