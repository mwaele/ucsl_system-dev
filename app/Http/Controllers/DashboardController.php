<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Models\ClientRequest; 
use App\Models\ShipmentCollection;
use App\Models\User;
use App\Models\Office;
use Carbon\Carbon;


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

        $queryWithDate = fn($q) => $dateRange
            ? $q->whereBetween('created_at', $dateRange)
            : $q;

        if ($user->role === 'admin') {
            $totalRequests = ClientRequest::when($dateRange, $queryWithDate)->count();
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
            'collected',
            'verified',
            'pendingCollection',
            'recentRequests',
            'userCount',
            'stationStats',
            'timeFilter',
            'startDate',
            'endDate'
        ));
    }
}

