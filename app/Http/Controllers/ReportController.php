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
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ShipmentsExport;
use App\Models\TransporterTruck;
use App\Exports\VehiclePerformanceExport;
use App\Exports\RoutePerformanceExport;

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
            ->withSum('shipmentCollections as total_revenue', 'actual_total_cost')
            ->withAvg('shipmentCollections as avg_revenue_per_shipment', 'actual_total_cost')
            ->get();
        // Add payment mix and premium services manually
        foreach ($clients as $client) {
            // Payment Mix
            $paymentMix = $client->shipmentCollections()
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
     * Generate the shipment report PDF
     */
    public function shipmentReportGenerate(Request $request)
    {
        $startDate = $request->input('start');
        $endDate = $request->input('end');
        $clientType = $request->input('clientType');
        $serviceLevel = $request->input('serviceLevel');
        $paymentType = $request->input('paymentType');
        $status = $request->input('status');

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

        if ($status) {
            $query->where('status', $status);
        };

        $clientRequests = $query->orderBy('dateRequested', 'desc')->get();

        // Dynamically build the report title
        $reportTitle = 'Shipment Report';

        if ($status ||$paymentType || $clientType || $serviceLevel || $startDate || $endDate) {
            $filters = [];

            if ($status) {
                $filters[] = "$status shipments";
            }
            
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
     * Generate the client performance report PDF
     */
    public function clientPerformanceReportGenerate(Request $request)
    {
        $clients = Client::withCount('shipmentItems as items_count')
            ->withSum('shipmentItems as total_weight', 'weight')
            ->withSum('shipmentCollections as total_revenue', 'actual_total_cost')
            ->withAvg('shipmentCollections as avg_revenue_per_shipment', 'actual_total_cost')
            ->get();
        // Add payment mix and premium services manually
        foreach ($clients as $client) {
            // Payment Mix
            $paymentMix = $client->shipmentCollections()
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

    /**
     * Generate the office performance report PDF 
     */
    public function officePerformanceReportGenerate(Request $request)
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

        return $this->renderPdfWithPageNumbers(
            'reports.pdf.office_performance_pdf_report',
            ['offices' => $offices],
            'office_performance_report.pdf',
            'a4',
            'landscape'
        );
    }

    /**
     * Generate the office performance report PDF 
     */
    public function dispatchSummaryReportGenerate(Request $request)
    {
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

        return $this->renderPdfWithPageNumbers(
            'reports.pdf.dispatch_summary_pdf_report',
            ['offices' => $offices],
            'dispatch_summary_report.pdf',
            'a4',
            'landscape'
        );
    }

    public function officePerformanceDetail($officeId)
    {
        $office = Office::findOrFail($officeId);

        // Get all shipments originating from this office
        $shipments = \DB::table('shipment_collections')
            ->where('origin_id', $officeId)
            ->select('id', 'requestId', 'consignment_no', 'waybill_no', 'receiver_name',
                    'receiver_phone', 'total_weight', 'actual_total_cost', 'payment_mode',
                    'status', 'created_at')
            ->get();

        return view('reports.office_performance_detail', compact('office', 'shipments'));
    }

    /**
     * 4b. Dispatch Summary Report - Office Detail View
     */
    public function dispatchSummaryDetail(Request $request, $officeId)
    {
        $startDate = $request->input('start_date', now()->startOfMonth());
        $endDate   = $request->input('end_date', now());

        // 1️⃣ Fetch the specific office
        $office = Office::with(['clientRequests' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }])
            ->findOrFail($officeId);

        $requests = $office->clientRequests;

        // 2️⃣ Compute the same metrics but for this one office
        $totalShipments = ShipmentCollection::whereIn('requestId', $requests->pluck('requestId'))->count();
        $totalWeight    = ShipmentCollection::whereIn('requestId', $requests->pluck('requestId'))->sum('total_weight');
        $totalRevenue   = ShipmentCollection::whereIn('requestId', $requests->pluck('requestId'))->sum('actual_total_cost');
        $avgRevenue     = $totalShipments > 0 ? $totalRevenue / $totalShipments : 0;

        // Payment Mix
        $paymentModes = ShipmentCollection::whereIn('requestId', $requests->pluck('requestId'))
            ->select('payment_mode', \DB::raw('count(*) as count'))
            ->groupBy('payment_mode')
            ->pluck('count', 'payment_mode');

        $totalPayments = $paymentModes->sum();
        $paymentMix = $paymentModes->map(function ($count) use ($totalPayments) {
            return $totalPayments > 0 ? round(($count / $totalPayments) * 100, 2) : 0;
        });

        // Premium Services
        $premiumServices = ShipmentCollection::whereIn('requestId', $requests->pluck('requestId'))
            ->where(function ($q) {
                $q->where('priority_level', 'high')
                ->orWhere('fragile_item', 'yes');
            })
            ->count();

        // Destination Breakdown
        $destBreakdown = ShipmentCollection::whereIn('requestId', $requests->pluck('requestId'))
            ->select(
                'destination_id',
                \DB::raw('count(*) as shipments'),
                \DB::raw('sum(actual_total_cost) as revenue'),
                \DB::raw('sum(total_weight) as weight'),
                \DB::raw("
                    sum(
                        case
                            when priority_level = 'high' or fragile_item = 'yes'
                            then 1 else 0
                        end
                    ) as premium
                ")
            )
            ->groupBy('destination_id')
            ->with('destination:id,destination')
            ->get()
            ->map(function ($row) {
                return [
                    'office'   => $row->destination->destination ?? 'Unknown',
                    'shipments'=> $row->shipments,
                    'revenue'  => $row->revenue,
                    'weight'  => $row->weight,
                    'premium'  => $row->premium,
                ];
            });

        // 3️⃣ Attach computed values for easy access in the view
        $office->total_shipments = $totalShipments;
        $office->total_weight = $totalWeight;
        $office->total_revenue = $totalRevenue;
        $office->avg_revenue_per_shipment = $avgRevenue;
        $office->payment_mix = $paymentMix;
        $office->premium_services = $premiumServices;
        $office->destination_breakdown = $destBreakdown;

        // 4️⃣ Pass data to the view
        return view('reports.dispatch_summary_detail', compact('office', 'startDate', 'endDate'));
    }


    public function riderPerformanceReport(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');
        $userName  = $request->input('user_name');

        $query = DB::table('client_requests')
            ->join('users', 'client_requests.userId', '=', 'users.id')
            ->join('shipment_collections', 'client_requests.requestId', '=', 'shipment_collections.requestId')
            ->select(
                'users.id',
                'users.name',
                DB::raw('COUNT(shipment_collections.id) as total_shipments'),
                DB::raw('SUM(shipment_collections.actual_total_cost) as total_amount'),
                DB::raw('SUM(shipment_collections.total_weight) as total_volume')
            )
            ->groupBy('users.id', 'users.name');

        // Filters
        if ($startDate && $endDate) {
            $query->whereBetween('shipment_collections.created_at', [$startDate, $endDate]);
        }

        if ($userName) {
            $query->where('users.name', 'like', "%{$userName}%");
        }

        $report = $query->get();

        // If AJAX request → return JSON data
        if ($request->ajax()) {
            return response()->json($report);
        }

        // Otherwise load view normally
        return view('reports.rider_performance_report', compact('report', 'startDate', 'endDate', 'userName'));
    }

    public function exportPdf(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');
        $userName  = $request->input('user_name');

        $query = DB::table('client_requests')
            ->join('users', 'client_requests.userId', '=', 'users.id')
            ->join('shipment_collections', 'client_requests.requestId', '=', 'shipment_collections.requestId')
            ->select(
                'users.id',
                'users.name',
                DB::raw('COUNT(shipment_collections.id) as total_shipments'),
                DB::raw('SUM(shipment_collections.actual_total_cost) as total_amount'),
                DB::raw('SUM(shipment_collections.total_weight) as total_volume')
            )
            ->groupBy('users.id', 'users.name');

        if ($startDate && $endDate) {
            $query->whereBetween('shipment_collections.created_at', [$startDate, $endDate]);
        }

        if ($userName) {
            $query->where('users.name', 'like', "%{$userName}%");
        }

        $report = $query->get();

        $pdf = Pdf::loadView('reports.rider_performance_pdf', compact('report', 'startDate', 'endDate', 'userName'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('rider_performance_shipment_report.pdf');
    }

    public function exportExcel(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $userName = $request->get('user_name');

        return Excel::download(new ShipmentsExport($startDate, $endDate, $userName), 'reports.rider_performance_excel.xlsx');
    }

    public function clientDetail($id)
    {
        $client = Client::with([
            'shipmentCollections.items',   // all items per shipment collection
            'shipmentCollections.payments' // payment breakdown
        ])->findOrFail($id);

        // Use shipmentCollections instead of shipments
        $totalShipments = $client->shipmentCollections->count();
        $totalWeight = $client->shipmentCollections->sum(fn($s) => $s->items->sum('weight'));
        $totalRevenue = $client->shipmentCollections->sum(fn($s) => $s->payments->sum('amount'));
        $avgRevenue = $totalShipments > 0 ? $totalRevenue / $totalShipments : 0;

        // Payment mix %
        $paymentCounts = $client->shipmentCollections
            ->flatMap->payments
            ->groupBy('mode')
            ->map(fn($group) => round(($group->sum('amount') / $totalRevenue) * 100, 2));

        return view('reports.client_detail', compact(
            'client',
            'totalShipments',
            'totalWeight',
            'totalRevenue',
            'avgRevenue',
            'paymentCounts'
        ));
    }
    public function vehiclePerformanceReport(Request $request)
    {
        $startDate = $request->start_date
            ? Carbon::parse($request->start_date)->startOfDay()
            : now()->startOfMonth()->startOfDay();

        $endDate = $request->end_date
            ? Carbon::parse($request->end_date)->endOfDay()
            : now()->endOfDay();

        $report = DB::table('transporter_trucks')
            ->leftJoin('transporters', 'transporters.id', '=', 'transporter_trucks.transporter_id')
            ->select(
                'transporter_trucks.id',
                'transporter_trucks.reg_no',
                'transporters.name as transporter_name'
            )
            ->addSelect([
                'total_trips' => DB::table('loading_sheets')
                    ->selectRaw('COUNT(*)')
                    ->whereColumn('loading_sheets.vehicle_reg_no', 'transporter_trucks.id')
                    ->whereRaw('loading_sheets.dispatch_date BETWEEN ? AND ?', [$startDate, $endDate]),
            ])
            ->addSelect([
                'total_waybills' => DB::table('loading_sheets')
                    ->join('loading_sheet_waybills', 'loading_sheets.id', '=', 'loading_sheet_waybills.loading_sheet_id')
                    ->selectRaw('COUNT(loading_sheet_waybills.id)')
                    ->whereColumn('loading_sheets.vehicle_reg_no', 'transporter_trucks.id')
                    ->whereRaw('loading_sheets.dispatch_date BETWEEN ? AND ?', [$startDate, $endDate]),
            ])
            ->addSelect([
                'total_quantity' => DB::table('loading_sheets')
                    ->join('loading_sheet_waybills', 'loading_sheets.id', '=', 'loading_sheet_waybills.loading_sheet_id')
                    ->join('shipment_items', 'shipment_items.shipment_id', '=', 'loading_sheet_waybills.shipment_id')
                    ->selectRaw('SUM(shipment_items.actual_quantity)')
                    ->whereColumn('loading_sheets.vehicle_reg_no', 'transporter_trucks.id')
                    ->whereRaw('loading_sheets.dispatch_date BETWEEN ? AND ?', [$startDate, $endDate]),
            ])
            ->addSelect([
                'total_weight' => DB::table('loading_sheets')
                    ->join('loading_sheet_waybills', 'loading_sheets.id', '=', 'loading_sheet_waybills.loading_sheet_id')
                    ->join('shipment_items', 'shipment_items.shipment_id', '=', 'loading_sheet_waybills.shipment_id')
                    ->selectRaw('SUM(shipment_items.actual_weight)')
                    ->whereColumn('loading_sheets.vehicle_reg_no', 'transporter_trucks.id')
                    ->whereRaw('loading_sheets.dispatch_date BETWEEN ? AND ?', [$startDate, $endDate]),
            ])
            ->addSelect([
                'total_volume' => DB::table('loading_sheets')
                    ->join('loading_sheet_waybills', 'loading_sheets.id', '=', 'loading_sheet_waybills.loading_sheet_id')
                    ->join('shipment_items', 'shipment_items.shipment_id', '=', 'loading_sheet_waybills.shipment_id')
                    ->selectRaw('SUM(shipment_items.actual_volume)')
                    ->whereColumn('loading_sheets.vehicle_reg_no', 'transporter_trucks.id')
                    ->whereRaw('loading_sheets.dispatch_date BETWEEN ? AND ?', [$startDate, $endDate]),
            ])->addSelect([
            // ✅ New: total amount achieved
            'total_amount' => DB::table('loading_sheets')
                ->join('loading_sheet_waybills', 'loading_sheets.id', '=', 'loading_sheet_waybills.loading_sheet_id')
                ->join('shipment_collections', 'loading_sheet_waybills.shipment_id', '=', 'shipment_collections.id')
                ->selectRaw('SUM(shipment_collections.actual_total_cost)')
                ->whereColumn('loading_sheets.vehicle_reg_no', 'transporter_trucks.id')
                ->whereBetween('loading_sheets.dispatch_date', [$startDate, $endDate]),
        ])->get();

        return view('reports.vehicle_performance_report', compact('report', 'startDate', 'endDate'));
    }
    public function exportVehiclePerformanceExcel(Request $request)
    {
        // Reuse the same logic as your report method
        $data = $this->generateVehiclePerformanceReport($request);

        return Excel::download(
            new VehiclePerformanceExport($data['report'], $data['startDate'], $data['endDate']),
            'vehicle_performance_report.xlsx'
        );
    }

    public function exportVehiclePerformancePdf(Request $request)
    {
        $data = $this->generateVehiclePerformanceReport($request);

        $pdf = PDF::loadView('reports.vehicle_performance_pdf', [
            'report' => $data['report'],
            'startDate' => $data['startDate'],
            'endDate' => $data['endDate'],
        ])->setPaper('a4', 'landscape');

        return $pdf->download('vehicle_performance_report.pdf');
    }

    // ✅ Helper method so you don't repeat the query
    private function generateVehiclePerformanceReport(Request $request)
    {
        $startDate = $request->start_date
            ? \Carbon\Carbon::parse($request->start_date)->startOfDay()
            : now()->startOfMonth()->startOfDay();

        $endDate = $request->end_date
            ? \Carbon\Carbon::parse($request->end_date)->endOfDay()
            : now()->endOfDay();

        $destination = $request->destination; // ✅ new search filter

        $report = DB::table('transporter_trucks')
            ->leftJoin('transporters', 'transporters.id', '=', 'transporter_trucks.transporter_id')
            ->select(
                'transporter_trucks.id',
                'transporter_trucks.reg_no',
                'transporters.name as transporter_name'
            )
            ->addSelect([
                'total_trips' => DB::table('loading_sheets')
                    ->selectRaw('COUNT(*)')
                    ->whereColumn('loading_sheets.vehicle_reg_no', 'transporter_trucks.id')
                    ->whereRaw('loading_sheets.dispatch_date BETWEEN ? AND ?', [$startDate, $endDate]),
            ])
            ->addSelect([
                'total_waybills' => DB::table('loading_sheets')
                    ->join('loading_sheet_waybills', 'loading_sheets.id', '=', 'loading_sheet_waybills.loading_sheet_id')
                    ->selectRaw('COUNT(loading_sheet_waybills.id)')
                    ->whereColumn('loading_sheets.vehicle_reg_no', 'transporter_trucks.id')
                    ->whereRaw('loading_sheets.dispatch_date BETWEEN ? AND ?', [$startDate, $endDate]),
            ])
            ->addSelect([
                'total_quantity' => DB::table('loading_sheets')
                    ->join('loading_sheet_waybills', 'loading_sheets.id', '=', 'loading_sheet_waybills.loading_sheet_id')
                    ->join('shipment_items', 'shipment_items.shipment_id', '=', 'loading_sheet_waybills.shipment_id')
                    ->selectRaw('SUM(shipment_items.actual_quantity)')
                    ->whereColumn('loading_sheets.vehicle_reg_no', 'transporter_trucks.id')
                    ->whereRaw('loading_sheets.dispatch_date BETWEEN ? AND ?', [$startDate, $endDate]),
            ])
            ->addSelect([
                'total_weight' => DB::table('loading_sheets')
                    ->join('loading_sheet_waybills', 'loading_sheets.id', '=', 'loading_sheet_waybills.loading_sheet_id')
                    ->join('shipment_items', 'shipment_items.shipment_id', '=', 'loading_sheet_waybills.shipment_id')
                    ->selectRaw('SUM(shipment_items.actual_weight)')
                    ->whereColumn('loading_sheets.vehicle_reg_no', 'transporter_trucks.id')
                    ->whereRaw('loading_sheets.dispatch_date BETWEEN ? AND ?', [$startDate, $endDate]),
            ])
            ->addSelect([
                'total_volume' => DB::table('loading_sheets')
                    ->join('loading_sheet_waybills', 'loading_sheets.id', '=', 'loading_sheet_waybills.loading_sheet_id')
                    ->join('shipment_items', 'shipment_items.shipment_id', '=', 'loading_sheet_waybills.shipment_id')
                    ->selectRaw('SUM(shipment_items.actual_volume)')
                    ->whereColumn('loading_sheets.vehicle_reg_no', 'transporter_trucks.id')
                    ->whereRaw('loading_sheets.dispatch_date BETWEEN ? AND ?', [$startDate, $endDate]),
            ])->addSelect([
                // ✅ New: total amount achieved
                'total_amount' => DB::table('loading_sheets')
                    ->join('loading_sheet_waybills', 'loading_sheets.id', '=', 'loading_sheet_waybills.loading_sheet_id')
                    ->join('shipment_collections', 'loading_sheet_waybills.shipment_id', '=', 'shipment_collections.id')
                    ->selectRaw('SUM(shipment_collections.actual_total_cost)')
                    ->whereColumn('loading_sheets.vehicle_reg_no', 'transporter_trucks.id')
                    ->whereBetween('loading_sheets.dispatch_date', [$startDate, $endDate]),
            ])
            ->get();

        return compact('report', 'startDate', 'endDate', 'destination');
    }

    public function routePerformanceReport(Request $request)
    {
        $data = $this->getRouteData($request);

        $totalRevenue = $data->sum('total_revenue');
        $totalVolume = $data->sum('total_volume');

        $report = $data->map(function ($row) use ($totalRevenue, $totalVolume) {
            $row->revenue_contribution = $totalRevenue > 0 ? round(($row->total_revenue / $totalRevenue) * 100, 2) : 0;
            $row->volume_contribution = $totalVolume > 0 ? round(($row->total_volume / $totalVolume) * 100, 2) : 0;
            return $row;
        });

        return view('reports.route-performance', compact('report'));
    }

    private function getRouteData(Request $request)
    {
       $query = DB::table('shipment_collections')
        ->join('offices as origin_office', 'shipment_collections.origin_id', '=', 'origin_office.id')
        ->join('rates', 'shipment_collections.destination_id', '=', 'rates.id')
        ->join('offices as destination_office', 'rates.office_id', '=', 'destination_office.id')
        ->select(
            DB::raw('MAX(origin_office.name) as origin'),
            DB::raw('MAX(rates.destination) as destination'),
            DB::raw('COUNT(shipment_collections.id) as total_shipments'),
            DB::raw('SUM(shipment_collections.total_weight) as total_volume'),
            DB::raw('SUM(shipment_collections.actual_total_cost) as total_revenue')
        )
        ->groupBy('shipment_collections.origin_id', 'shipment_collections.destination_id');




        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween(DB::raw('DATE(shipment_collections.created_at)'), [
                $request->start_date, $request->end_date
            ]);
        }

        return $query->get();
    }

    public function exportRoutePerformancePDF(Request $request)
    {
        $report = $this->getRouteData($request);

        $totalRevenue = $report->sum('total_revenue');
        $totalVolume = $report->sum('total_volume');

        $report->transform(function ($row) use ($totalRevenue, $totalVolume) {
            $row->revenue_contribution = $totalRevenue > 0 ? round(($row->total_revenue / $totalRevenue) * 100, 2) : 0;
            $row->volume_contribution = $totalVolume > 0 ? round(($row->total_volume / $totalVolume) * 100, 2) : 0;
            return $row;
        });

        $pdf = PDF::loadView('exports.route_performance_pdf', [
            'report'     => $report,
            'startDate'  => $request->start_date,
            'endDate'    => $request->end_date,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('Route_Performance_Report.pdf');
    }

    public function exportRoutePerformanceExcel(Request $request)
    {
        return Excel::download(new RoutePerformanceExport($request), 'Route_Performance_Report.xlsx');
    }

    public function officePerformanceDetailPdf($officeId)
    {
        $office = Office::findOrFail($officeId);

        $shipments = \DB::table('shipment_collections as sc')
            ->join('client_requests as cr', 'cr.requestId', '=', 'sc.requestId')
            ->where('cr.office_id', $officeId)
            ->select(
                'sc.requestId',
                'sc.consignment_no',
                'sc.waybill_no',
                'sc.receiver_name',
                'sc.receiver_phone',
                'sc.total_weight',
                'sc.actual_total_cost',
                'sc.payment_mode',
                'sc.status',
                'sc.created_at'
            )
            ->orderBy('sc.created_at', 'desc')
            ->get();

        return $this->renderPdfWithPageNumbers(
            'reports.pdf.office_performance_detail_pdf',
            compact('office', 'shipments'),
            'office_performance_detail_' . $office->name . '.pdf',
            'a4',
            'landscape'
        );
    }

}
