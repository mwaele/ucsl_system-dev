<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClientRequest;
use App\Models\ShipmentCollection;
use App\Models\ShipmentItem;
use App\Traits\PdfReportTrait;
use App\Models\User;
use App\Models\Client;
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
        $clientRequests = ClientRequest::latest()->get();
        
        return view('reports.shipment_report', compact('clientRequests'));
    }

    /**
     * 2. Client Performance Report
     */
    public function clientPerformanceReport()
    {
        $clients = Client::withCount('shipmentItems as items_count')
            ->withSum('shipmentItems as total_weight', 'weight')
            ->withSum('shipmentCollection as total_revenue', 'actual_total_cost')
            ->withAvg('shipmentCollection as avg_revenue_per_shipment', 'actual_total_cost')
            ->get();
        // Add payment mix and premium services manually
        foreach ($clients as $client) {
            // Payment Mix
            $paymentMix = $client->shipmentCollection()
                ->selectRaw('payment_mode, COUNT(*) as count')
                ->groupBy('payment_mode')
                ->pluck('count', 'payment_mode')
                ->toArray();
            $client->payment_mix = $paymentMix;
        }

        return view('reports.client_performance_report', compact('clients'));
    }

    /**
     * 3. Office Performance Report
     */
    public function officePerformanceReport()
    {
        $offices = Office::withCount('shipmentItems as items_count')
            ->withSum('shipmentItems as total_weight', 'weight')
            ->withSum('shipmentCollection as total_revenue', 'actual_total_cost')
            ->withAvg('shipmentCollection as avg_revenue_per_shipment', 'actual_total_cost')
            ->get();
        // Add payment mix and premium services manually
        foreach ($clients as $client) {
            // Payment Mix
            $paymentMix = $client->shipmentCollection()
                ->selectRaw('payment_mode, COUNT(*) as count')
                ->groupBy('payment_mode')
                ->pluck('count', 'payment_mode')
                ->toArray();
            $client->payment_mix = $paymentMix;
        }

        return view('reports.client_performance_report', compact('clients'));
    }


    public function shipmentReportGenerate(Request $request)
    {
        $startDate = $request->input('start');
        $endDate = $request->input('end');
        $clientType = $request->input('clientType');
        $serviceLevel = $request->input('serviceLevel');
        $paymentType = $request->input('paymentType');

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

        if ($paymentType) {
            $query->whereHas('shipmentCollection', function ($q) use ($paymentType) {
                $q->where('payment_mode', $paymentType);
            });
        }

        $clientRequests = $query->orderBy('dateRequested', 'desc')->get();

        // Dynamically build the report title
        $reportTitle = 'Shipment Report';

        if ($paymentType || $clientType || $serviceLevel || $startDate || $endDate) {
            $filters = [];

            if ($paymentType) {
                $filters[] = "$paymentType Payments";
            }

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
