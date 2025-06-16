<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClientRequest; // example model
use App\Models\ShipmentCollection;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // Fetch data
        $totalRequests = ClientRequest::count();
        $pendingCollection = ClientRequest::where('status', 'Pending Collection')->count();
        $collected = ClientRequest::where('status', 'Collected')->count();
        $verified = ClientRequest::where('status', 'Verified')->count();

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

