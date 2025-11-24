<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Office;
use App\Models\UserLog;
use Illuminate\Http\Request;
use App\Helpers\EmailHelper;
use Illuminate\Support\Carbon;
use App\Models\ClientRequest;
use App\Traits\PdfReportTrait;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Jobs\SendUserAccountEmail;

class UserController extends Controller
{
    use PdfReportTrait;

    public function index(Request $request)
    {
        $stations = Office::all();
        $users = User::all();

        UserLog::create([
            'name'         => Auth::user()->name,
            'actions'      => 'Accessed users module',
            'url'          => $request->fullUrl(),
            'table'        => "users",
            'user_id'      => Auth::id(),
        ]);

        return view('users.index', compact('users', 'stations'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        try {
            $user = new User();
            $user->name         = $request->name;
            $user->email        = $request->email;
            $user->phone_number = $request->phone_number;
            $user->station      = $request->station;
            $user->role         = $request->role;
            $user->status       = $request->status;
            $plainPassword      = $request->password;
            $user->password     = Hash::make($plainPassword);

            if ($user->save()) {
                // $loginUrl = url('/login');
                // $terms    = env('TERMS_AND_CONDITIONS', '#');

                // $subject = "Your UCS Account Has Been Created";
                // $message = "
                //     Dear {$user->name},<br><br>
                //     Your user account has been created successfully.<br><br>

                //     Here are your login credentials:<br>
                //     <strong>Email:</strong> {$user->email}<br>
                //     <strong>Password:</strong> {$plainPassword}<br><br>

                //     You can log in to the UCS Portal using the link below:<br>
                //     <a href=\"{$loginUrl}\" target=\"_blank\">Login to UCS Portal</a><br><br>

                //     <p><strong>Terms & Conditions:</strong> <a href=\"{$terms}\" target=\"_blank\">Click here</a></p>
                //     <p>Thank you for using Ufanisi Courier Services. For we are Fast, Reliable and Secure.</p>
                // ";

                // // Send email
                // EmailHelper::sendHtmlEmail($user->email, $subject, $message);
                // Dispatch email job
        SendUserAccountEmail::dispatch($user, $request->password);

        UserLog::create([
            'name'         => Auth::user()->name,
            'actions'      => 'Created a new user, ' . $request->name . ' ',
            'url'          => $request->fullUrl(),
            'reference_id' => $user->id,
            'table'        => "users",
            'user_id'      => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'User account created.');

                return redirect()->back()->with('success', 'User account created successfully.');
            } else {
                return redirect()->back()->with('error', 'Failed to create User account. Please try again.');
            }

        } catch (\Exception $e) {
            // Log the actual error for debugging
            \Log::error('User creation failed: ' . $e->getMessage());

            return redirect()->back()->with('error', 'An unexpected error occurred while creating the account. Please contact support.');
        }
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
        $user->password = Hash::make($request->password);

        $user->save(); 
        
        UserLog::create([
            'name'         => Auth::user()->name,
            'actions'      => 'Updated details of a user whose name is  , ' . $request->name . '',
            'url'          => $request->fullUrl(),
            'reference_id' => $user->id,
            'table'        => "users",
            'user_id'      => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'User updated successfully.');
    }

    public function getDriversByLocation(Request $request)
    {
        $location = $request->input('location');

        $today = Carbon::now(config('app.timezone'))->format('Y-m-d H:i:s');

        $drivers = DB::table('users')
        ->join('client_requests', function ($join) use ($today) {
            $join->on('users.id', '=', 'client_requests.userId')
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
    $today = Carbon::now(config('app.timezone'))->format('Y-m-d H:i:s');


    // Get user IDs from client_requests table for today
    $allocatedDriverIds = ClientRequest::whereDate('dateRequested', $today)
        ->pluck('userId')
        ->toArray();

    // Fetch drivers not in that list
    $drivers = User::where('users.role', 'driver')
        ->where('users.station', Auth::user()->station)
        ->when(!empty($allocatedDriverIds), function ($query) use ($allocatedDriverIds) {
            $query->whereNotIn('users.id', $allocatedDriverIds);
        })
        ->join('offices', 'users.station', '=', 'offices.id')
        ->select('users.id', 'users.name', 'offices.name as station')
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
            ->join('offices', 'users.station', '=', 'offices.id')
            ->where('users.role', 'driver')
            ->where('users.station', Auth::user()->station)
            ->select(
                'users.id',
                'users.name',
                'offices.name as station',
                DB::raw("GROUP_CONCAT(DISTINCT client_requests.collectionLocation SEPARATOR ', ') as collectionLocations")
            )
            ->groupBy('users.id', 'users.name', 'offices.name')
            ->get();
        return response()->json($drivers);
    }

    public function users_report()
    {
        $users = User::orderBy('created_at', 'desc')->get();

        UserLog::create([
            'name'         => Auth::user()->name,
            'actions'      => 'Generated users report',
            'url'          => $request->fullUrl(),
            'reference_id' => $users->id,
            'table'        => "users",
            'user_id'      => Auth::id(),
        ]);

        return $this->renderPdfWithPageNumbers(
            'users.user_report',
            ['users' => $users],
            'users_report.pdf',
            'a4',
            'landscape'
        );
    }

    public function destroy($id)
    {
        //
        $user = User::where('id', $id)->firstOrFail();
        $user->delete();

        UserLog::create([
            'name'         => Auth::user()->name,
            'actions'      => Auth::user()->name . ' deleted a user, ' . $request->name . ', at ' . now(),
            'url'          => $request->fullUrl(),
            'reference_id' => $user->id,
            'table'        => "users",
            'user_id'      => Auth::id(),
        ]);

        return back()->with('success', 'User deleted successfully.');
    }

}

