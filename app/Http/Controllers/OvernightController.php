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
use App\Services\RequestIdService;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendCollectionNotificationsJob;
use Carbon\Carbon;
use Auth;

class OvernightController extends Controller
{
    use PdfReportTrait;

    protected $requestIdService;

    public function __construct(RequestIdService $requestIdService)
    {
        $this->requestIdService = $requestIdService;
    }

    //
    public function on_account(Request $request )
    {
        $timeFilter = $request->query('time', 'all'); // default to all
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

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

        return view('overnight.on-account', compact('clients', 'clientRequests', 'vehicles', 'drivers','timeFilter',
            'startDate',
            'endDate', 'sub_category'));
    }
    public function client_on_account(Request $request )
    {
        $timeFilter = $request->query('time', 'all'); // default to all
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $clients = Client::where('type', 'on_account')->get();
        $vehicles = Vehicle::all();
        $drivers = User::where('role', 'driver')->get();
        $sub_category = SubCategory::where('sub_category_name', 'Overnight')->firstOrFail();

        $overnightSubCategoryIds = SubCategory::where('sub_category_name', 'Overnight')->pluck('id');

        $clientRequests = ClientRequest::whereIn('sub_category_id', $overnightSubCategoryIds)
            ->whereHas('client', function ($query) {
                $query->where(['type'=>'on_account','source'=>'client_portal']);
            })
            ->orderBy('created_at', 'desc')
            ->with(['client', 'user', 'vehicle'])
            ->get();

        return view('overnight.clients.client_on_account', compact('clients', 'clientRequests', 'vehicles', 'drivers','timeFilter',
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
    public function client_overnight_account_report()
    {
        $overnightSubCategoryIds = SubCategory::where('sub_category_name', 'Overnight')->pluck('id');

        $clientRequests = ClientRequest::whereIn('sub_category_id', $overnightSubCategoryIds)
            ->whereHas('client', function ($query) {
                $query->where(['type'=>'on_account','source'=>'client_portal']);
            })
            ->with(['client', 'user', 'vehicle'])
            ->get();

            // $title = 'Report for All Overnight On-account Parcels from Client Portal';
        return $this->renderPdfWithPageNumbers(
            'overnight.clients.client_overnight_account_report',
            ['clientRequests' => $clientRequests],
            'client_overnight_account_report.pdf',
            'a4',
            'landscape'
        );
    }

    public function walkin_report(Request $request)
    {
        $overnightSubCategoryIds = SubCategory::where('sub_category_name', 'Overnight')->pluck('id');

        $clientRequests = ClientRequest::whereIn('sub_category_id', $overnightSubCategoryIds)
            ->whereHas('client', function ($query) {
                $query->where('type', 'walkin');
            })
            ->with(['client', 'user', 'vehicle']);

        // ✅ Apply date range filter if provided
        if ($request->filled('start') && $request->filled('end')) {
            $clientRequests->whereBetween('created_at', [
                $request->start . " 00:00:00",
                $request->end . " 23:59:59"
            ]);
        }

        $clientRequests = $clientRequests->get();

        return $this->renderPdfWithPageNumbers(
            'overnight.walkin_report',
            ['clientRequests' => $clientRequests],
            'walkin_report.pdf',
            'a4',
            'landscape'
        );
    }

    public function client_portal_overnight_report(Request $request)
    {
        $overnightSubCategoryIds = SubCategory::where('sub_category_name', 'Overnight')->pluck('id');
        $clientRequests = ClientRequest::whereIn('sub_category_id', $overnightSubCategoryIds)
            ->whereHas('client', function ($query) {
                $query->where('type', 'on_account');
            })
            ->where('clientId', auth('client')->id())
            ->with(['client', 'user', 'vehicle']);

        // ✅ Apply date range filter if provided
        if ($request->filled('start') && $request->filled('end')) {
            $clientRequests->whereBetween('created_at', [
                $request->start . " 00:00:00",
                $request->end . " 23:59:59"
            ]);
        }

        $clientRequests = $clientRequests->get();

        return $this->renderPdfWithPageNumbers(
            'overnight.client_portal_overnight_report',
            ['clientRequests' => $clientRequests],
            'overnight_parcels_report.pdf',
            'a4',
            'landscape'
        );
    }

    public function updateRider(Request $request, $id)
    {
        Log::info('updateRider called (Collection Flow).', [
            'request_id' => $id,
            'payload' => $request->all(),
            'user_id' => Auth::id()
        ]);

        $validatedData = $request->validate([
            'userId' => 'required|exists:users,id',
            'vehicleId' => 'required',
        ]);

        // Lookup Client Request
        $clientRequest = ClientRequest::findOrFail($id);

        $now = now();
        $requestId = $clientRequest->requestId;

        // Lookup Rider
        $rider = User::findOrFail($request->userId);
        $vehicleId = $validatedData['vehicleId'];

        // Lookup Client (resolve id → name, contact, email)
        $client = Client::findOrFail($clientRequest->clientId);

        DB::transaction(function () use (
            $clientRequest, $validatedData, $requestId, $now, $rider
        ) {
            // update client_request
            $clientRequest->userId = $validatedData['userId'];
            $clientRequest->vehicleId = $validatedData['vehicleId'];
            $clientRequest->status = 'pending collection';
            $clientRequest->save();

            Log::info('client_request updated.', [
                'clientRequest_id' => $clientRequest->id,
                'new_userId' => $clientRequest->userId,
                'new_vehicleId' => $clientRequest->vehicleId,
                'new_status' => $clientRequest->status
            ]);

            // update track status
            $trackId = DB::table('tracks')
                ->where('requestId', $requestId)
                ->tap(function ($query) use ($now) {
                    $query->update([
                        'current_status' => 'Collection Rider Allocated',
                        'updated_at' => $now
                    ]);
                })
                ->value('id');

            // insert tracking info
            DB::table('tracking_infos')->insert([
                'trackId' => $trackId,
                'date' => $now,
                'details' => "Collection Rider Allocated",
                'remarks' => "We have allocated {$rider->name} (phone: {$rider->phone_number}) to collect parcel {$requestId}.",
                'created_at' => $now,
                'updated_at' => $now
            ]);
        });

        // Dispatch notification job
        try {
            SendCollectionNotificationsJob::dispatch($clientRequest, $client, $rider, $vehicleId);

            Log::info('Collection notification job dispatched.', [
                'clientRequest_id' => $clientRequest->id,
                'client_id' => $client->id,
                'rider_id' => $rider->id
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to dispatch SendCollectionNotificationsJob.', [
                'clientRequest_id' => $clientRequest->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }

        return redirect()->back()->with('success', 'Rider allocated successfully');
    }


}
