<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Models\ClientRequest; // example model
use App\Models\ShipmentCollection;
use App\Models\User;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $station = $user->station;
        $timeFilter = $request->query('time', 'all'); // default to all

        $dateRange = match ($timeFilter) {
            'daily' => [now()->startOfDay(), now()->endOfDay()],
            'weekly' => [now()->startOfWeek(), now()->endOfWeek()],
            'biweekly' => [now()->subDays(14), now()],
            'monthly' => [now()->startOfMonth(), now()->endOfMonth()],
            'yearly' => [now()->startOfYear(), now()->endOfYear()],
            default => null
        };

        $queryWithDate = fn($q) => $dateRange
            ? $q->whereBetween('created_at', $dateRange)
            : $q;

        if ($user->role === 'admin') {
            $totalRequests = ClientRequest::when($dateRange, $queryWithDate)->count();
            $collected = ClientRequest::where('status', 'collected')->when($dateRange, $queryWithDate)->count();
            $verified = ClientRequest::where('status', 'verified')->when($dateRange, $queryWithDate)->count();
            $pendingCollection = ClientRequest::where('status', 'pending collection')->when($dateRange, $queryWithDate)->count();

            // Per-station stats
            $stations = ['Mombasa', 'Nairobi'];
            $stationStats = [];
            foreach ($stations as $stn) {
                $stationStats[$stn] = [
                    'total' => ClientRequest::whereHas('createdBy', fn($q) => $q->where('station', $stn))
                        ->when($dateRange, $queryWithDate)->count(),
                    'collected' => ClientRequest::where('status', 'collected')->whereHas('createdBy', fn($q) => $q->where('station', $stn))
                        ->when($dateRange, $queryWithDate)->count(),
                    'verified' => ClientRequest::where('status', 'verified')->whereHas('createdBy', fn($q) => $q->where('station', $stn))
                        ->when($dateRange, $queryWithDate)->count(),
                    'pending' => ClientRequest::where('status', 'pending collection')->whereHas('createdBy', fn($q) => $q->where('station', $stn))
                        ->when($dateRange, $queryWithDate)->count(),
                ];
            }
        } else {
            $totalRequests = ClientRequest::whereHas('createdBy', fn($q) => $q->where('station', $station))
                ->when($dateRange, $queryWithDate)->count();
            $collected = ClientRequest::where('status', 'collected')->whereHas('createdBy', fn($q) => $q->where('station', $station))
                ->when($dateRange, $queryWithDate)->count();
            $verified = ClientRequest::where('status', 'verified')->whereHas('createdBy', fn($q) => $q->where('station', $station))
                ->when($dateRange, $queryWithDate)->count();
            $pendingCollection = ClientRequest::where('status', 'pending collection')->whereHas('createdBy', fn($q) => $q->where('station', $station))
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
            'timeFilter'
        ));
    }
}

