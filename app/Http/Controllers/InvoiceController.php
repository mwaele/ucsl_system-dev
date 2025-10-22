<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\Dompdf\Facade\Options;

class InvoiceController extends Controller
{

    public function getLatestInvoiceNo()
    {
        // Get latest invoice with highest number
        $latestInvoice = DB::table('invoices')
            ->where('invoice_no', 'like', 'INV-%')
            ->orderByDesc('id')
            ->first();

        if ($latestInvoice) {
            // Extract numeric part and increment
            $lastNumber = (int) str_replace('INV-', '', $latestInvoice->invoice_no);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $newInvoiceNo = 'INV-' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);

        return response()->json(['invoice_no' => $newInvoiceNo]);
    }
    /**
     * Display a listing of the resource.
     */
    public function generateInvoice($id)
    {
        $invoice = DB::table('invoices')
            ->join('shipment_collections', 'invoices.shipment_collection_id', '=', 'shipment_collections.id')
            ->join('client_requests', 'shipment_collections.requestId', '=', 'client_requests.requestId')
            ->join('clients', 'shipment_collections.client_id', '=', 'clients.id')->join('rates', 'shipment_collections.destination_id', '=', 'rates.id')
            ->where('invoices.shipment_collection_id', $id)
            ->select('invoices.*','invoices.status as invoice_status', 'shipment_collections.*', 'client_requests.*','clients.*','rates.routeFrom')
            ->first(); // Use ->first() to get a single invoice

        // Step 2: Get shipment items separately
        $shipmentItems = DB::table('shipment_items')
            ->where('shipment_id', $id) // assuming $id is shipment_collection_id
            ->get();
        $totalWeight = DB::table('shipment_items')
        ->where('shipment_id', $id)
        ->sum('weight');

        $data = [
            'invoice' => $invoice,
            'shipment_items' => $shipmentItems,
            'totalWeight' => $totalWeight,
            'onlyOnePage' => true,
        ];
        //dd($data);
        $pdf = Pdf::loadView('same_day.invoice' , [
            'invoice' => $invoice,
            'shipmentItems' => $shipmentItems,
            'totalWeight' => $totalWeight,
            'onlyOnePage' => true,
        ])->setPaper('a4', 'portrait'); 
        return $pdf->download("invoice.pdf");

    }

    public function index()
    {
        
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
    public function show(Invoice $invoice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        //
    }
}
