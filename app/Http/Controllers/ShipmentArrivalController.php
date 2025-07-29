<?php

namespace App\Http\Controllers;

use App\Models\ShipmentArrival;
use App\Models\LoadingSheet;
use App\Models\Office;
use App\Models\Transporter;
use App\Models\Dispatcher;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class ShipmentArrivalController extends Controller
{
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
        
        $dispatchers = Dispatcher::all();

        $sheets = LoadingSheet::with('rate')
        ->orderBy('id', 'asc')
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
    public function update(Request $request, ShipmentArrival $shipmentArrival)
    {
        //
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

        $query = LoadingSheet::query();

        if ($filter === 'date') {
            $query->whereDate('arrival_date', $value);
        } elseif ($filter === 'dispatch') {
            $query->where('batch_no', 'like', "%$value%");
        } elseif ($filter === 'type') {
            $query->where('shipment_type', $value);
        }

        $loading_sheets = $query->get();
        //dd($shipments);

        $pdf = Pdf::loadView('clients.clients_report' , [
            'clients'=>$loading_sheets
        ])->setPaper('a4', 'landscape');;
        return $pdf->download("clients_report.pdf");

        // Example: return as downloadable CSV or a view
        // return view('reports.shipment_arrivals', compact('shipments', 'filter', 'value'));
    }
}
