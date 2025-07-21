<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\ClientRequest;
use App\Models\SubCategory;
use App\Models\ShipmentCollection;
use App\Models\Vehicle;
use App\Models\User;
use App\Models\FrontOffice;
use App\Models\Office;
use App\Models\Rate;
use Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SameDayController extends Controller
{
    public function on_account(Request $request )
    {
        // Determine the correct CAST expression based on DB driver
        $driver = DB::getDriverName();
        $castExpression = $driver === 'pgsql'
            ? 'CAST(SUBSTRING("requestId" FROM 5) AS INTEGER)'
            : 'CAST(SUBSTRING(requestId, 5) AS UNSIGNED)';

        $timeFilter = $request->query('time', 'all'); // default to all

        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        // Generate Unique Request ID
        $lastRequestFromClient = ClientRequest::where('requestId', 'like', 'REQ-%')
            ->orderByRaw("$castExpression DESC")
            ->value('requestId');

        $lastRequestFromCollection = ShipmentCollection::where('requestId', 'like', 'REQ-%')
            ->orderByRaw("$castExpression DESC")
            ->value('requestId');

        $clientNumber = $lastRequestFromClient ? (int)substr($lastRequestFromClient, 4) : 0;
        $collectionNumber = $lastRequestFromCollection ? (int)substr($lastRequestFromCollection, 4) : 0;
        $nextNumber = max(max($clientNumber, $collectionNumber) + 1, 10000);
        $request_id = 'REQ-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

        $clients = Client::where('type', 'on_account')->get();
        $vehicles = Vehicle::all();
        $drivers = User::where('role', 'driver')->get();
        $sub_category = SubCategory::where('sub_category_name', 'Same Day')->firstOrFail();

        $samedaySubCategoryIds = SubCategory::where('sub_category_name', 'Same Day')->pluck('id');

        $clientRequests = ClientRequest::whereIn('sub_category_id', $samedaySubCategoryIds)
            ->whereHas('client', function ($query) {
                $query->where('type', 'on_account');
            })
            ->with(['client', 'user', 'vehicle'])
            ->get();


        return view('same_day.on_account', compact('clients', 'clientRequests', 'request_id', 'vehicles', 'drivers','timeFilter',
            'startDate',
            'endDate', 'sub_category'));
    }

    public function walk_in()
    {
        $offices = Office::all();
        $loggedInUserId = Auth::user()->id;
        $destinations = Rate::where('type', 'normal')->get();
        $walkInClients = Client::where('type', 'walkin')->get();
        $sub_category = SubCategory::where('sub_category_name', 'Same Day')->firstOrFail();

        // Determine DB driver for cross-DB SQL compatibility
        $driver = DB::getDriverName();
        $castExpression = $driver === 'pgsql'
            ? 'CAST(SUBSTRING("requestId" FROM 5) AS INTEGER)'
            : 'CAST(SUBSTRING(requestId, 5) AS UNSIGNED)';

        // 1. Get the latest requestId from both tables
        $lastRequestFromClient = ClientRequest::where('requestId', 'like', 'REQ-%')
            ->orderByRaw("$castExpression DESC")
            ->value('requestId');

        $lastRequestFromCollection = ShipmentCollection::where('requestId', 'like', 'REQ-%')
            ->orderByRaw("$castExpression DESC")
            ->value('requestId');

        // 2. Extract numeric parts and determine the highest
        $clientNumber = $lastRequestFromClient ? (int)substr($lastRequestFromClient, 4) : 0;
        $collectionNumber = $lastRequestFromCollection ? (int)substr($lastRequestFromCollection, 4) : 0;
        $nextNumber = max(max($clientNumber, $collectionNumber) + 1, 10000);

        // 4. Format requestId
        $request_id = 'REQ-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

        // Walk-in collections
        $collections = ShipmentCollection::with('client')
            ->whereHas('client', function ($query) {
                $query->where('type', 'walkin'); // Only walk-in clients
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Get the latest consignment number
        $latestConsignment = ShipmentCollection::where('consignment_no', 'LIKE', 'CN-%')
            ->orderByDesc('id')
            ->first();

        if ($latestConsignment && preg_match('/CN-(\d+)/', $latestConsignment->consignment_no, $matches)) {
            $lastNumber = intval($matches[1]);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 10000; // Start from CN-10000
        }

        $consignment_no = 'CN-' . $newNumber;

        $samedaySubCategoryIds = SubCategory::where('sub_category_name', 'Same Day')->pluck('id');

        $clientRequests = ClientRequest::whereIn('sub_category_id', $samedaySubCategoryIds)
            ->whereHas('client', function ($query) {
                $query->where('type', 'walkin');
            })
            ->with(['client', 'user', 'vehicle'])
            ->get();


        return view('same_day.walk_in', compact('clientRequests', 'offices',
            'loggedInUserId',
            'destinations',
            'walkInClients',
            'collections',
            'request_id',
            'consignment_no',
            'sub_category'
        ));
    }
    
    public function sameday_walkin_report(){
        $samedaySubCategoryIds = SubCategory::where('sub_category_name', 'Same Day')->pluck('id');

        $clientRequests = ClientRequest::whereIn('sub_category_id', $samedaySubCategoryIds)
            ->whereHas('client', function ($query) {
                $query->where('type', 'walkin');
            })
            ->with(['client', 'user', 'vehicle'])
            ->get();
        $pdf = Pdf::loadView('same_day.sameday_walkin_report' , [
            'clientRequests'=>$clientRequests
        ])->setPaper('a4', 'landscape');;
        return $pdf->download("sameday_walkin_report.pdf");
    }

    public function sameday_account_report(){
        $samedaySubCategoryIds = SubCategory::where('sub_category_name', 'Same Day')->pluck('id');

        $clientRequests = ClientRequest::whereIn('sub_category_id', $samedaySubCategoryIds)
            ->whereHas('client', function ($query) {
                $query->where('type', 'walkin');
            })
            ->with(['client', 'user', 'vehicle'])
            ->get();
        $pdf = Pdf::loadView('same_day.sameday_account_report' , [
            'clientRequests'=>$clientRequests
        ])->setPaper('a4', 'landscape');;
        return $pdf->download("sameday_account_report.pdf");
    }
}
