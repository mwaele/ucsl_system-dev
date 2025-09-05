<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClientRequest;
use App\Models\ShipmentCollection;
use App\Models\ShipmentItem;
use App\Models\User;
use App\Models\Payment;

class ReportController extends Controller
{
    /**
     * Show the main reports dashboard
     */
    public function shipmentReport()
    {
        $clientRequests = ClientRequest::all();
        
        return view('reports.shipment_report', compact('clientRequests'));
    }

    /**
     * 1. Sameday & Overnight Report
     */
    public function samedayOvernight(Request $request)
    {
        $query = ClientRequest::with('client')
            ->whereIn('type', ['sameday', 'overnight']);

        $samedaySubCategoryIds = SubCategory::where('sub_category_name', 'Same Day')->pluck('id');

        $clientRequests = ClientRequest::whereIn('sub_category_id', $samedaySubCategoryIds)
            ->with(['client', 'user', 'vehicle'])
            ->get();

        if ($request->filled('from') && $request->filled('to')) {
            $clientRequests->whereBetween('created_at', [$request->from, $request->to]);
        }

        $samedayReports = $clientRequests->orderBy('created_at', 'desc')->get();

        return view('reports.partials.sameday', compact('samedayReports'));
    }

    /**
     * 2. Parcel Collection Report
     */
    public function parcelCollection(Request $request)
    {
        $query = ShipmentCollection::with('clientRequest.client', 'items');

        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('created_at', [$request->from, $request->to]);
        }

        $data = $query->orderBy('created_at', 'desc')->get();

        return view('reports.partials.collection', compact('data'));
    }

    /**
     * 3. Rider Performance Report
     */
    public function riderPerformance(Request $request)
    {
        $query = ShipmentCollection::with('rider')
            ->selectRaw('rider_id, COUNT(*) as total_collections')
            ->groupBy('rider_id');

        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('created_at', [$request->from, $request->to]);
        }

        $data = $query->get();

        return view('reports.partials.rider', compact('data'));
    }

    /**
     * 4. Driver Shipment Analysis
     */
    public function driverShipment(Request $request)
    {
        $query = ShipmentCollection::with('driver', 'items')
            ->selectRaw('driver_id, COUNT(*) as total_shipments')
            ->groupBy('driver_id');

        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('created_at', [$request->from, $request->to]);
        }

        $data = $query->get();

        return view('reports.partials.driver', compact('data'));
    }

    /**
     * 5. CoD & Cash Reports
     */
    public function codCash(Request $request)
    {
        $query = Payment::with('shipmentCollection.clientRequest.client')
            ->select('id', 'shipment_collection_id', 'amount', 'method', 'status', 'created_at');

        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('created_at', [$request->from, $request->to]);
        }

        $data = $query->orderBy('created_at', 'desc')->get();

        return view('reports.partials.codcash', compact('data'));
    }

    public function filter(Request $request)
    {
        $query = ClientRequest::with(['client','shipmentCollection','serviceLevel','user','vehicle','createdBy']);

        if ($request->fromDate && $request->toDate) {
            $query->whereBetween('created_at', [$request->fromDate, $request->toDate]);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $clientRequests = $query->get();

        // return only the <tbody> rows
        return view('reports.partials.reports_table_rows', compact('clientRequests'));
    }

    public function exportPdf(Request $request)
    {
        $query = ClientRequest::with(['client','shipmentCollection','serviceLevel','user','vehicle','createdBy']);

        if ($request->fromDate && $request->toDate) {
            $query->whereBetween('created_at', [$request->fromDate, $request->toDate]);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $clientRequests = $query->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.pdf', compact('clientRequests'));
        return $pdf->download('filtered_report.pdf');
    }

}
