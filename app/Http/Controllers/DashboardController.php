<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ClientRequest; 
use App\Models\ShipmentCollection;
use App\Models\DeliveryControl;
use App\Models\User;
use App\Models\Office;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Barryvdh\DomPDF\Facade\Pdf;


class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $station = $user->station;
        $timeFilter = $request->query('time', 'daily'); // default to all

        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $dateRange = null;

        if ($startDate && $endDate) {
            $dateRange = [Carbon::parse($startDate)->startOfDay(), Carbon::parse($endDate)->endOfDay()];
        } else {
            $dateRange = match ($timeFilter) {
                'daily' => [now()->startOfDay(), now()->endOfDay()],
                'weekly' => [now()->startOfWeek(), now()->endOfWeek()],
                'biweekly' => [now()->subDays(14)->startOfDay(), now()->endOfDay()],
                'monthly' => [now()->startOfMonth(), now()->endOfMonth()],
                'yearly' => [now()->startOfYear(), now()->endOfYear()],
                default => null
            };
        }

        // $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May','Jun', 'Jul', 'Aug','Sep'];
        // $data = [10, 20, 15, 30, 25, 40, 35, 50,35];

        $shipments = DB::table('client_requests')
        ->select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as total')
        )
        ->groupBy('month')
        ->orderBy('month', 'asc')
        ->get();

    // Define all months
    $allMonths = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

    // Initialize data arrays
    $labels = [];
    $data = [];

    foreach ($allMonths as $index => $monthName) {
        $monthNumber = $index + 1;
        $record = $shipments->firstWhere('month', $monthNumber);
        $labels[] = $monthName;
        $data[] = $record ? $record->total : 0;
    }

    // return response()->json([
    //     'labels' => $labels,
    //     'data' => $data,
    // ]);

        $queryWithDate = fn($q) => $dateRange
            ? $q->whereBetween('created_at', $dateRange)
            : $q;

        if ($user->role === 'admin') {

            $ctrTime = DB::table('delivery_controls')
                ->where('control_id', 'CTRL-0001')
                ->value('ctr_time');

            $timeLimit = Carbon::now()->subHours((int) $ctrTime);
            
            $totalRequests = ClientRequest::when($dateRange, $queryWithDate)->count();
            $delivered = ClientRequest::where('status', 'delivered')->when($dateRange, $queryWithDate)->count();
            $collected = ClientRequest::where('status', 'collected')->when($dateRange, $queryWithDate)->count();
            $delayedCollections = ClientRequest::where('status', 'pending collection')
            ->where('updated_at', '<', $timeLimit) // older than 2 hours
            ->whereNotIn('status', ['verified'])
            ->when($dateRange, $queryWithDate)
            ->count();
            $verified = ClientRequest::where('status', 'verified')->when($dateRange, $queryWithDate)->count();
            $pendingCollection = ClientRequest::where('status', 'pending collection')->when($dateRange, $queryWithDate)->count();
            $failed_collection = ClientRequest::where('status', 'collection_failed')->when($dateRange, $queryWithDate)->count();

            $undeliveredParcels = ShipmentCollection::where('status', 'arrived')->when($dateRange, $queryWithDate)->count();
            $onTransitParcels = ShipmentCollection::whereIn('status', [
                    'Delivery Rider Allocated',
                    'delivery_rider_allocated'
                ])->when($dateRange, $queryWithDate)->count();
            $delayedDeliveries = ShipmentCollection::whereIn('status', [
                    'Delivery Rider Allocated',
                    'delivery_rider_allocated'
                ])
                ->where('updated_at', '<', $timeLimit)
                ->whereNotIn('status', ['delivery_failed', 'parcel_delivered'])
                ->when($dateRange, $queryWithDate)
                ->count();
            $failedDeliveries = ShipmentCollection::where('status', 'delivery_failed')->when($dateRange, $queryWithDate)->count();
            $successfulDeliveries = ShipmentCollection::where('status', 'parcel_delivered')->when($dateRange, $queryWithDate)->count();

            // Walk-in revenue
            $walkinRevenue = ShipmentCollection::whereHas('clientRequestById.client', function ($query) {
                $query->where('type', 'walkin');
            })->sum('total_cost');

            // Account client revenue
            $accountRevenue = ShipmentCollection::whereHas('clientRequestById.client', function ($query) {
                $query->where('type', 'on_account');
            })->sum('total_cost');

            $totalRevenue = $walkinRevenue + $accountRevenue;

            // Per-station stats based on office_id directly
            $stations = Office::pluck('name', 'id');
            $stationStats = [];

            foreach ($stations as $id => $name) {
            $stationStats[$name] = [
                'total' => ClientRequest::where('office_id', $id)
                    ->when($dateRange, $queryWithDate)->count(),
                'delivered' => ClientRequest::where('status', 'delivered')->where('office_id', $id)
                    ->when($dateRange, $queryWithDate)->count(),
                'collected' => ClientRequest::where('status', 'collected')->where('office_id', $id)
                    ->when($dateRange, $queryWithDate)->count(),
                'verified' => ClientRequest::where('status', 'verified')->where('office_id', $id)
                    ->when($dateRange, $queryWithDate)->count(),
                'pending' => ClientRequest::whereIn('status', ['pending collection', 'Pending-Collection'])->where('office_id', $id)
                    ->when($dateRange, $queryWithDate)->count(),
                'on-transit' => ClientRequest::where('status', 'Delivery Rider Allocated')->where('office_id', $id)
                    ->when($dateRange, $queryWithDate)->count(),
                'failed' => ClientRequest::where('status', 'delivery_failed')->where('office_id', $id)
                    ->when($dateRange, $queryWithDate)->count(),
                'failed_collection' => ClientRequest::where('status', 'collection_failed')->where('office_id', $id)->when($dateRange, $queryWithDate)->count()
                ];
            }
        } else {
            $ctrTime = DeliveryControl::where('control_id', 'CTRL-0001')->value('ctr_time');
            $timeLimit = Carbon::now()->subHours((int) $ctrTime);
            $totalRequests = ClientRequest::where('office_id', $station)
                ->when($dateRange, $queryWithDate)->count();
            $delivered = ClientRequest::where('status', 'delivered')->where('office_id', $station)
                ->when($dateRange, $queryWithDate)->count();
            $collected = ClientRequest::where('status', 'collected')->where('office_id', $station)
                ->when($dateRange, $queryWithDate)->count();
            $verified = ClientRequest::where('status', 'verified')->where('office_id', $station)
                ->when($dateRange, $queryWithDate)->count();
            $pendingCollection = ClientRequest::where('status', 'pending collection')->where('office_id', $station)
                ->when($dateRange, $queryWithDate)->count();
            $delayedCollections = ClientRequest::where('status', 'pending collection')->where('updated_at', '<', $timeLimit) 
                ->whereNotIn('status', ['verified'])
                ->when($dateRange, $queryWithDate)
                ->count();
            $failed_collection = ClientRequest::where('status', 'collection_failed')->where('office_id', $station)
                ->when($dateRange, $queryWithDate)->count();
            $undeliveredParcels = ShipmentCollection::where('status', 'arrived')
                ->whereHas('clientRequestById', function ($q) use ($station) {
                    $q->where('office_id', $station);
                })
                ->when($dateRange, $queryWithDate)
                ->count();
            $onTransitParcels = ShipmentCollection::whereIn('status', [
                    'Delivery Rider Allocated',
                    'delivery_rider_allocated'
                ])
                ->whereHas('clientRequestById', function ($q) use ($station) {
                    $q->where('office_id', $station);
                })
                ->when($dateRange, $queryWithDate)
                ->count();
            $delayedDeliveries = ShipmentCollection::whereIn('status', [
                    'Delivery Rider Allocated',
                    'delivery_rider_allocated'
                ])
                ->where('updated_at', '<', $timeLimit)
                ->whereNotIn('status', ['delivery_failed', 'parcel_delivered'])
                ->when($dateRange, $queryWithDate)
                ->count();
            $failedDeliveries = ShipmentCollection::where('status', 'delivery_failed')
                ->whereHas('clientRequestById', function ($q) use ($station) {
                    $q->where('office_id', $station);
                })
                ->when($dateRange, $queryWithDate)
                ->count();
            $successfulDeliveries = ShipmentCollection::where('status', 'parcel_delivered')
                ->whereHas('clientRequestById', function ($q) use ($station) {  
                    $q->where('office_id', $station);
                })
                ->when($dateRange, $queryWithDate)
                ->count();

            // Walk-in revenue
            $walkinRevenue = ShipmentCollection::whereHas('clientRequestById.client', function ($query) {
                $query->where('type', 'walkin');
            })->sum('actual_total_cost');

            // Account client revenue
            $accountRevenue = ShipmentCollection::whereHas('clientRequestById.client', function ($query) {
                $query->where('type', 'on_account');
            })->sum('actual_total_cost');

            $totalRevenue = $walkinRevenue + $accountRevenue;

            $stationStats = null;
        }

        $recentRequests = ClientRequest::latest()->take(5)->get();
        $userCount = User::count();

        return view('index', compact(
            'totalRequests',
            'delivered',
            'collected',
            'verified',
            'pendingCollection',
            'failed_collection',
            'recentRequests',
            'userCount',
            'stationStats',
            'timeFilter',
            'startDate',
            'endDate',
            'labels',
            'data',
            'undeliveredParcels',
            'onTransitParcels',
            'failedDeliveries',
            'successfulDeliveries',
            'delayedDeliveries',
            'delayedCollections',
            'walkinRevenue',
            'accountRevenue',
            'totalRevenue',
        ));
    }

    public function downloadPdf(Request $request)
    {
        $chart = $request->input('chart'); // Base64 image

        $html = '
            <h3 style="text-align:center;">Earnings Overview</h3>
            <img src="'.$chart.'" style="width:100%; max-height:500px;" />
        ';

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return response($dompdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="chart.pdf"');
    }

    public function exportPdf(Request $request)
    {
        $chartImage = $request->input('chartImage'); // Base64 string
        $pdf = Pdf::loadView('pdf.report', compact('chartImage'));

        return $pdf->download('report.pdf'); // triggers download
    }
 
}

