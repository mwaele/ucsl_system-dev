<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Models\ClientRequest; // example model
use App\Models\ShipmentCollection;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // Fetch data

        $user = Auth::user();
        $station = $user->station;

        if ($user->role === 'admin') {
            // Admin sees all
            $totalRequests = ClientRequest::count();
            $collected = ClientRequest::where('status', 'collected')->count();
            $verified = ClientRequest::where('status', 'verified')->count();
            $pendingCollection = ClientRequest::where('status', 'pending collection')->count();
        } else {
            // Non-admins see only their station
            $totalRequests = ClientRequest::whereHas('createdBy', fn($q) => $q->where('station', $station))->count();
            $collected = ClientRequest::where('status', 'collected')->whereHas('createdBy', fn($q) => $q->where('station', $station))->count();
            $verified = ClientRequest::where('status', 'verified')->whereHas('createdBy', fn($q) => $q->where('station', $station))->count();
            $pendingCollection = ClientRequest::where('status', 'pending collection')->whereHas('createdBy', fn($q) => $q->where('station', $station))->count();
        }

        $recentRequests = ClientRequest::latest()->take(5)->get();
        $userCount = User::count();

        // Send to view
        return view('index', compact(
            'totalRequests',
            'pendingCollection',
            'collected',
            'verified',
            'recentRequests',
            'userCount'
        ));
    }
}

