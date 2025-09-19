<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Models\ClientRequest; 
use App\Models\ShipmentCollection;
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
        $timeFilter = $request->query('time', 'all'); // default to all

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

        $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May','Jun', 'Jul', 'Aug','Sep'];
        $data = [10, 20, 15, 30, 25, 40, 35, 50,35];

        $queryWithDate = fn($q) => $dateRange
            ? $q->whereBetween('created_at', $dateRange)
            : $q;

        if ($user->role === 'admin') {
            $totalRequests = ClientRequest::when($dateRange, $queryWithDate)->count();
            $delivered = ClientRequest::where('status', 'delivered')->when($dateRange, $queryWithDate)->count();
            $collected = ClientRequest::where('status', 'collected')->when($dateRange, $queryWithDate)->count();
            $verified = ClientRequest::where('status', 'verified')->when($dateRange, $queryWithDate)->count();
            $pendingCollection = ClientRequest::where('status', 'pending collection')->when($dateRange, $queryWithDate)->count();

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
                'pending' => ClientRequest::where('status', 'pending collection')->where('office_id', $id)
                    ->when($dateRange, $queryWithDate)->count(),
                ];
            }
        } else {
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
            'recentRequests',
            'userCount',
            'stationStats',
            'timeFilter',
            'startDate',
            'endDate',
            'labels',
            'data'
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

