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
use App\Models\Office;
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
        $offices = Office::withCount([
                'clientRequests as total_requests' => function ($query) {
                    $query->whereNotNull('id');
                }
            ])
            ->get()
            ->map(function ($office) {
                // Join shipment_collections via client_requests.requestId
                $shipments = \DB::table('shipment_collections as sc')
                    ->join('client_requests as cr', 'cr.requestId', '=', 'sc.requestId')
                    ->where('cr.office_id', $office->id);

                // Total shipments
                $office->total_shipments = $shipments->count();

                // Total weight
                $office->total_weight = (clone $shipments)->sum('sc.total_weight');

                // Total revenue
                $office->total_revenue = (clone $shipments)->sum('sc.actual_total_cost');

                // Average revenue per shipment
                $office->avg_revenue_per_shipment = $office->total_shipments > 0
                    ? $office->total_revenue / $office->total_shipments
                    : 0;

                // Payment mix breakdown
                $paymentModes = (clone $shipments)
                    ->select('sc.payment_mode', \DB::raw('count(*) as count'))
                    ->groupBy('sc.payment_mode')
                    ->pluck('count', 'payment_mode');

                $totalPayments = $paymentModes->sum();
                $office->payment_mix = $paymentModes->map(function ($count) use ($totalPayments) {
                    return $totalPayments > 0 ? round(($count / $totalPayments) * 100, 2) : 0;
                });

                // Premium services (priority or fragile)
                $office->premium_services = (clone $shipments)
                    ->where(function ($q) {
                        $q->where('sc.priority_level', 1)
                        ->orWhere('sc.fragile_item', 1);
                    })
                    ->count();

                return $office;
            });

        return view('reports.office_performance_report', compact('offices'));
    }

    /**
     * 4. Dispatch Summary Report (Office-Wide)
     */
    public function dispatchSummaryReport(Request $request)
    {
        // Optional: allow date range filters
        $startDate = $request->input('start_date', now()->startOfMonth());
        $endDate   = $request->input('end_date', now());

        $offices = Office::withCount([
                // Total dispatches (client requests tied to this office in given period)
                'clientRequests as total_dispatches' => function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                }
            ])
            ->with(['clientRequests' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }])
            ->get()
            ->map(function ($office) {
                $requests = $office->clientRequests;

                // Total shipments dispatched (count of related shipment_collections)
                $totalShipments = ShipmentCollection::whereIn('requestId', $requests->pluck('requestId'))
                    ->count();

                // Total weight
                $totalWeight = ShipmentCollection::whereIn('requestId', $requests->pluck('requestId'))
                    ->sum('total_weight');

                // Total revenue
                $totalRevenue = ShipmentCollection::whereIn('requestId', $requests->pluck('requestId'))
                    ->sum('actual_total_cost');

                // Avg revenue per shipment
                $avgRevenue = $totalShipments > 0 ? $totalRevenue / $totalShipments : 0;

                // Payment mix
                $paymentModes = ShipmentCollection::whereIn('requestId', $requests->pluck('requestId'))
                    ->select('payment_mode', \DB::raw('count(*) as count'))
                    ->groupBy('payment_mode')
                    ->pluck('count', 'payment_mode');

                $totalPayments = $paymentModes->sum();
                $paymentMix = $paymentModes->map(function ($count) use ($totalPayments) {
                    return $totalPayments > 0 ? round(($count / $totalPayments) * 100, 2) : 0;
                });

                // Premium services (fragile or priority)
                $premiumServices = ShipmentCollection::whereIn('requestId', $requests->pluck('requestId'))
                    ->where(function ($q) {
                        $q->where('priority_level', 1)
                        ->orWhere('fragile_item', 1);
                    })
                    ->count();

                // Destination breakdown (group by destination office)
                $destBreakdown = ShipmentCollection::whereIn('requestId', $requests->pluck('requestId'))
                    ->select('destination_id', \DB::raw('count(*) as shipments'), \DB::raw('sum(actual_total_cost) as revenue'))
                    ->groupBy('destination_id')
                    ->with('destination:id,destination')
                    ->get()
                    ->map(function ($row) {
                        return [
                            'office'   => $row->destination->destination ?? 'Unknown',
                            'shipments'=> $row->shipments,
                            'revenue'  => $row->revenue,
                        ];
                    });

                // Attach calculated values
                $office->total_shipments = $totalShipments;
                $office->total_weight = $totalWeight;
                $office->total_revenue = $totalRevenue;
                $office->avg_revenue_per_shipment = $avgRevenue;
                $office->payment_mix = $paymentMix;
                $office->premium_services = $premiumServices;
                $office->destination_breakdown = $destBreakdown;

                return $office;
            });

        return view('reports.dispatch_summary_report', compact('offices', 'startDate', 'endDate'));
    }

    /**
     * Generate the shipment report PDF based on filters
     */
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

    /**
     * Generate the shipment report PDF based on filters
     */
    public function clientPerformanceReportGenerate(Request $request)
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

        return $this->renderPdfWithPageNumbers(
            'reports.pdf.client_performance_pdf_report',
            ['clients' => $clients],
            'client_performance_report.pdf',
            'a4',
            'landscape'
        );
    }
}
