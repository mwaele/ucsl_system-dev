<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\ShipmentItem;
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
        $invoice = Invoice::with([
            'shipment_collection.office',
            'shipment_collection.destination',
            'shipment_collection.special_destination',
            'shipment_collection.client'
        ])
        ->where('shipment_collection_id', $id)
        ->firstOrFail();

        $shipment_collection = $invoice->shipment_collection;

        $shipmentItems = ShipmentItem::where('shipment_id', $shipment_collection->id)->get();

        $totalWeight = $shipmentItems->sum('weight');

        $pdf = Pdf::loadView('same_day.invoice', [
            'invoice' => $invoice,
            'shipment_collection' => $shipment_collection,
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
