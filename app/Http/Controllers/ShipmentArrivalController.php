<?php

namespace App\Http\Controllers;

use App\Models\ShipmentArrival;
use App\Models\LoadingSheet;
use App\Models\Office;
use App\Models\Transporter;
use App\Models\Dispatcher;
use App\Models\User;
use App\Models\Rate;
use App\Models\LoadingSheetWaybill;
use App\Models\ShipmentCollection;
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
        
        $dispatchers = Dispatcher::where('office_id', Auth::user()->station)->get();

        $sheets = LoadingSheet::with('rate')
        ->withCount('waybills') // adds waybills_count
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
        $from = $request->query('from');
        $to = $request->query('to');

        $query = LoadingSheet::query();

        if ($filter === 'date') {
            $query->whereDate('dispatch_date', $value);
        } elseif ($filter === 'date_range') {
            if ($from && $to) {
                $query->whereBetween('dispatch_date', [$from, $to]);
            }
        } elseif ($filter === 'dispatch') {
            $query->where('batch_no', 'like', "%$value%");
        } elseif ($filter === 'type') {
            $query->where('payment_mode', $value);
        }

        $loading_sheets = $query->get();

        // Create PDF instance
        $pdf = Pdf::loadView('shipment_arrivals.arrivals_report', [
            'sheets' => $loading_sheets
        ])->setPaper('a4', 'landscape');

        // Force Dompdf to render first
        $dompdf = $pdf->getDomPDF();
        $dompdf->render();

        // Now we can safely write on the canvas
        $canvas = $dompdf->getCanvas();
        $text = "Page {PAGE_NUM} of {PAGE_COUNT}";
        $size = 10;
        $font = null; // default font
        $width = $canvas->get_width();
        $height = $canvas->get_height();
        $textWidth = $canvas->get_text_width($text, $font, $size);

        $canvas->page_text(
            ($width - $textWidth) / 2, // center horizontally
            $height - 28,              // 28 points from bottom
            $text,
            $font,
            $size,
            [0, 0, 0]
        );

        // Return the modified PDF output
        return response($dompdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="arrivals_report.pdf"');
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
        ->join('clients as c', 'sc.client_id', '=', 'c.id')

        ->select(
            'lsw.waybill_no',
            'r.destination', // Destination from rates
            'sc.total_cost',
            'sc.id',
            'sc.status',
            'c.name as client_name',
            'sc.payment_mode',
            DB::raw('GROUP_CONCAT(si.item_name SEPARATOR ", ") as item_names'),
            DB::raw('SUM(si.actual_quantity) as total_quantity'),
            DB::raw('SUM(si.actual_weight) as total_weight')
        )
        ->where('lsw.loading_sheet_id', $id)
        ->groupBy('lsw.waybill_no', 'c.name', 'r.id', 'sc.total_cost','sc.payment_mode','sc.id',
            'sc.status')
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

        return view('shipment_arrivals.manifest_details')->with([
            'loading_sheet'=>$loadingSheet,'destination'=>$destination,'data'=>$data,'totals'=>$totals,'id'=>$id
        ]);
    }
}
