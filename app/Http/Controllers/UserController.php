<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Office;
use Illuminate\Http\Request;
use App\Helpers\EmailHelper;
use Illuminate\Support\Carbon;
use App\Models\ClientRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        $stations = Office::all();
        $users = User::all();
        return view('users.index', compact('users', 'stations'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        $user->station = $request->station;
        $user->role = $request->role;
        $user->status = $request->status;
        $user->password = Hash::make($request->password);
        $user->save();

        // Email Details
        $loginUrl = url('/login');
        $terms = env('TERMS_AND_CONDITIONS', '#');

        $subject = "Your UCS Account Has Been Created";
        $message = "
            Dear {$user->name},<br><br>
            Your user account has been created successfully.<br><br>

            Here are your login credentials:<br>
            <strong>Email:</strong> {$user->email}<br>
            <strong>Password:</strong> {$request->password}<br><br>

            You can log in to the UCS Portal using the link below:<br>
            <a href=\"{$loginUrl}\" target=\"_blank\">Login to UCS Portal</a><br><br>

            <p><strong>Terms & Conditions:</strong> <a href=\"{$terms}\" target=\"_blank\">Click here</a></p>
            <p>Thank you for using Ufanisi Courier Services. For we are Fast, Reliable and Secure.</p>
        ";

        try {
            EmailHelper::sendHtmlEmail($user->email, $subject, $message);
        } catch (\Exception $e) {
            \Log::error("User Account Email Error: " . $e->getMessage());
        }

        return redirect()->back()->with('success', 'User account created.');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        $user->station = $request->station;
        $user->role = $request->role;
        $user->status = $request->status;

        $user->save();   

        return redirect()->back()->with('success', 'User updated successfully.');
    }

    public function getDriversByLocation(Request $request)
    {
        $location = $request->input('location');

        $today = Carbon::today();

        $drivers = DB::table('users')
    ->join('client_requests', function ($join) use ($location, $today) {
        $join->on('users.id', '=', 'client_requests.userId')
             ->where('client_requests.collectionLocation', $location)
             ->whereIn('client_requests.status', ['pending collection', 'collected'])
             ->whereDate('client_requests.dateRequested', $today);
    })
    ->join('stations', 'users.station', '=', 'stations.id')
    ->where('users.role', 'driver')
    ->where('users.station', Auth::user()->station)
    ->select(
        'users.id',
        'users.name',
        'stations.station_name as station',
        DB::raw("GROUP_CONCAT(DISTINCT client_requests.collectionLocation SEPARATOR ', ') as collectionLocations")
    )
    ->groupBy('users.id', 'users.name', 'stations.station_name')
    ->get();

        return response()->json($drivers);
    }

    public function getUnallocatedDrivers()
    {
        $today = Carbon::today();

        // Get userIds who have requests today
        $allocatedDriverIds = ClientRequest::whereDate('dateRequested', $today)
            ->pluck('userId')
            ->toArray();

        $drivers = User::where('role', 'driver')
            ->where('station', Auth::user()->station) // Optional: match current user's station
            ->whereNotIn('users.id', $allocatedDriverIds)
            ->join('stations', 'users.station', '=', 'stations.id')
            ->select('users.id', 'users.name', 'stations.station_name as station')
            ->get();

        return response()->json($drivers);
    }


    public function getAllDrivers()
    {
        $drivers = DB::table('users')
    ->leftJoin('client_requests', function ($join) {
        $join->on('users.id', '=', 'client_requests.userId')
            ->whereIn('client_requests.status', ['pending collection', 'collected'])
            ->whereDate('client_requests.dateRequested', now());
    })
    ->join('stations', 'users.station', '=', 'stations.id')
    ->where('users.role', 'driver')
    ->where('users.station', Auth::user()->station)
    ->select(
        'users.id',
        'users.name',
        'stations.station_name as station',
        DB::raw("GROUP_CONCAT(DISTINCT client_requests.collectionLocation SEPARATOR ', ') as collectionLocations")
    )
    ->groupBy('users.id', 'users.name', 'stations.station_name')
    ->get();
        return response()->json($drivers);
    }

}

