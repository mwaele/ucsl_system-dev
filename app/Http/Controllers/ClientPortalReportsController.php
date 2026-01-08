<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShipmentCollection;
use App\Models\ClientRequest;
use App\Models\Payment;
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
        $payments = Payment::with('client')
            ->whereHas('client', function ($query) {
                $query->where('client_id', auth('client')->user()->id); 
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('client_portal.reports.payments', compact('payments'));
    }

    public function paymentReportGenerate(Request $request)
    {
        $payments = Payment::with('client')
            ->whereHas('client', function ($query) {
                $query->where('client_id', auth('client')->user()->id); 
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->renderPdfWithPageNumbers(
            'client_portal.reports.payment_pdf_report',
            ['payments' => $payments],
            'payments_report.pdf',
            'a4',
            'landscape'
        );
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
