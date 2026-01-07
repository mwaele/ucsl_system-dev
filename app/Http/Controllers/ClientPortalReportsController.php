<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShipmentCollection;
use App\Models\ClientRequest;
use App\Traits\PdfReportTrait;

class ClientPortalReportsController extends Controller
{
    use PdfReportTrait;

    public function client_shipments_report(Request $request)  
    {
        
        $shipments = ShipmentCollection::with('client')
            ->whereHas('client', function ($query) {
                $query->where('client_id', auth('client')->user()->id); 
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('client_portal.reports.shipments', compact('shipments'));
    }

    public function client_payments_report(Request $request)
    {
        $categories = ClientCategory::where('client_id', auth('client')->user()->id)
            ->join('categories', 'client_categories.category_id', '=', 'categories.id')
            ->select('categories.id as category_id', 'categories.category_name')
            ->get();

        $dedicatedRider = User::where('role', 'driver')
            ->where('status', 'active')
            ->where('isDedicatedToClient', 1)
            ->where('dedicatedClientId', auth('client')->user()->id)
            ->first();

            //dd($dedicatedRider);

        $offices = Office::all();
        $vehicles = Vehicle::all();
        // $loggedInUserId = Auth::user()->id;
        $id = auth('client')->user()->id;
        $destinations = Rate::where('type', 'normal')->get();
        $walkInClients = Client::where('type', 'walkin')->get();
        $sub_category = SubCategory::where('sub_category_name', 'Same Day')->firstOrFail();

        
        $collections = ShipmentCollection::with('client')
            ->whereHas('client', function ($query) {
                $query->where('client_id', auth('client')->user()->id); 
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $overnightSubCategoryIds = SubCategory::where('sub_category_name', 'Same Day')->pluck('id');
        //dd($overnightSubCategoryIds);

        $clientRequests = ClientRequest::whereIn('sub_category_id', $overnightSubCategoryIds)
            ->whereHas('client', function ($query) {
                $query->where('clientId', auth('client')->user()->id);
            })
            ->orderBy('created_at', 'desc')
            ->with(['client', 'user', 'vehicle'])
            ->get();
        //dd($clientRequests);

        ClientLog::create([
            'name' => auth('client')->user()->name ?? auth('guest')->user()->name,
            'actions' => 'Accessed the client portal shipments Sameday on-account',
            'url' => $request->fullUrl(),
            'reference_id' => '',
            'client_id' => auth('client')->user()->id ?? null,
            'table' => 'shipment_collections',
        ]);

        return view('client_portal.shipments.same_day_on_account', compact('clientRequests', 'offices', 'categories',
            //'loggedInUserId',
            'destinations',
            'walkInClients',
            'collections',
            'sub_category',
            'dedicatedRider',
            'offices',
            'vehicles',
        )); 
    }

    public function shipmentReportGenerate(Request $request)
    {
        $startDate = $request->input('start');
        $endDate = $request->input('end');
        $serviceLevel = $request->input('serviceLevel');
        $status = $request->input('status');

        $query = ClientRequest::with(['client', 'shipmentCollection', 'serviceLevel', 'user', 'vehicle', 'createdBy']);

        if ($startDate) {
            $query->whereDate('dateRequested', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('dateRequested', '<=', $endDate);
        }

        if ($serviceLevel) {
            $query->whereHas('serviceLevel', function ($q) use ($serviceLevel) {
                $q->where('sub_category_name', $serviceLevel);
            });
        }

        if ($status) {
            $query->where('status', $status);
        };

        $clientRequests = $query->orderBy('dateRequested', 'desc')->get();

        // Dynamically build the report title
        $reportTitle = 'Shipment Report';

        if ($status || $serviceLevel || $startDate || $endDate) {
            $filters = [];

            if ($status) {
                $filters[] = "$status shipments";
            }

            if ($serviceLevel) {
                $filters[] = "$serviceLevel parcels";
            }

            if ($startDate && $endDate) {
                $filters[] = "From " . Carbon::parse($startDate)->format('M d, Y') . " to " . Carbon::parse($endDate)->format('M d, Y');
            } elseif ($startDate) {
                $filters[] = "From " . Carbon::parse($startDate)->format('M d, Y');
            } elseif ($endDate) {
                $filters[] = "Until " . Carbon::parse($endDate)->format('M d, Y');
            }

            $reportTitle .= ' - ' . implode(', ', $filters);
        } else {
            $reportTitle .= ' - All Shipments';
        }

        return $this->renderPdfWithPageNumbers(
            'client_portal.reports.shipment_pdf_report',
            [
                'clientRequests' => $clientRequests,
                'reportTitle' => $reportTitle
            ],
            'client_shipments_report.pdf',
            'a4',
            'landscape'
        );
    }
}
