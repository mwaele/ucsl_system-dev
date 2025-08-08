<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Client;
use App\Models\ClientRequest;
use App\Models\SubCategory;
use App\Models\ShipmentCollection;
use App\Models\Vehicle;
use App\Models\User;
use App\Models\FrontOffice;
use App\Models\Office;
use App\Models\Rate;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Traits\PdfReportTrait;
use Carbon\Carbon;
use Auth;

class OvernightController extends Controller
{
    use PdfReportTrait;

    //
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
        $sub_category = SubCategory::where('sub_category_name', 'Overnight')->firstOrFail();

        $overnightSubCategoryIds = SubCategory::where('sub_category_name', 'Overnight')->pluck('id');

        $clientRequests = ClientRequest::whereIn('sub_category_id', $overnightSubCategoryIds)
            ->whereHas('client', function ($query) {
                $query->where('type', 'on_account');
            })
            ->orderBy('created_at', 'desc')
            ->with(['client', 'user', 'vehicle'])
            ->get();


        return view('overnight.on-account', compact('clients', 'clientRequests', 'request_id', 'vehicles', 'drivers','timeFilter',
            'startDate',
            'endDate', 'sub_category'));
    }

    public function walk_in()
    {
        $offices = Office::where('id',2)->get();
        $loggedInUserId = Auth::user()->id;
        $destinations = Rate::where('type', 'normal')->get();
        $walkInClients = Client::where('type', 'walkin')->get();
        $sub_category = SubCategory::where('sub_category_name', 'Overnight')->firstOrFail();

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

        $overnightSubCategoryIds = SubCategory::where('sub_category_name', 'Overnight')->pluck('id');

        $clientRequests = ClientRequest::whereIn('sub_category_id', $overnightSubCategoryIds)
            ->whereHas('client', function ($query) {
                $query->where('type', 'walkin');
            })
            ->orderBy('created_at', 'desc')
            ->with(['client', 'user', 'vehicle'])
            ->get();


        return view('overnight.walk-in', compact('clientRequests', 'offices',
            'loggedInUserId',
            'destinations',
            'walkInClients',
            'collections',
            'request_id',
            'consignment_no',
            'sub_category'
        ));
    }

    public function overnight_account_report()
    {
        $overnightSubCategoryIds = SubCategory::where('sub_category_name', 'Overnight')->pluck('id');

        $clientRequests = ClientRequest::whereIn('sub_category_id', $overnightSubCategoryIds)
            ->whereHas('client', function ($query) {
                $query->where('type', 'on_account');
            })
            ->with(['client', 'user', 'vehicle'])
            ->get();

        return $this->renderPdfWithPageNumbers(
            'overnight.overnight_account_report',
            ['clientRequests' => $clientRequests],
            'overnight_account_report.pdf',
            'a4',
            'landscape'
        );
    }

    public function walkin_report()
    {
        $overnightSubCategoryIds = SubCategory::where('sub_category_name', 'Overnight')->pluck('id');

        $clientRequests = ClientRequest::whereIn('sub_category_id', $overnightSubCategoryIds)
            ->whereHas('client', function ($query) {
                $query->where('type', 'walkin');
            })
            ->with(['client', 'user', 'vehicle'])
            ->get();

        return $this->renderPdfWithPageNumbers(
            'overnight.walkin_report',
            ['clientRequests' => $clientRequests],
            'walkin_report.pdf',
            'a4',
            'landscape'
        );
    }

}
