<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClientRequest;
use App\Models\ShipmentCollection;
use App\Models\ShipmentItem;
use App\Traits\PdfReportTrait;
use App\Models\User;
use App\Models\Payment;
use Carbon\Carbon;

class ReportController extends Controller
{
    use PdfReportTrait;

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

    public function shipmentReportGenerate(Request $request)
    {
        $startDate = $request->input('start');
        $endDate = $request->input('end');
        $clientType = $request->input('clientType');
        $serviceLevel = $request->input('serviceLevel');

        $query = ClientRequest::with(['client', 'shipmentCollection', 'serviceLevel', 'user', 'vehicle', 'createdBy']);

        if ($startDate) {
            $query->whereDate('dateRequested', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('dateRequested', '<=', $endDate);
        }

        if ($clientType) {
            $query->whereHas('client', function ($q) use ($clientType) {
                $q->where('type', $clientType);
            });
        }

        if ($serviceLevel) {
            $query->whereHas('serviceLevel', function ($q) use ($serviceLevel) {
                $q->where('sub_category_name', $serviceLevel);
            });
        }

        $clientRequests = $query->orderBy('dateRequested', 'desc')->get();

        // Dynamically build the report title
        $reportTitle = 'Shipment Report';

        if ($clientType || $serviceLevel || $startDate || $endDate) {
            $filters = [];

            if ($clientType) {
                $filters[] = ucfirst(str_replace('_', ' ', $clientType)) . " Clients";
            }

            if ($serviceLevel) {
                $filters[] = "$serviceLevel parcels";
            }

            if ($startDate && $endDate) {
                $filters[] = "From " . Carbon::parse($startDate)->format('M d, Y') . " to " . Carbon::parse($endDate)->format('M d, Y');
            } elseif ($startDate) {
                $filters[] = "From " . Carbon::parse($startDate)->format('M d, Y');
            } elseif ($endDate) {
                $filters[] = "Until " . Carbon::parse($endDate)->format('M d, Y');
            }

            $reportTitle .= ' - ' . implode(', ', $filters);
        } else {
            $reportTitle .= ' - All Shipments';
        }

        return $this->renderPdfWithPageNumbers(
            'reports.pdf.shipment_pdf_report',
            [
                'clientRequests' => $clientRequests,
                'reportTitle' => $reportTitle
            ],
            'shipment_report.pdf',
            'a4',
            'landscape'
        );
    }


}
