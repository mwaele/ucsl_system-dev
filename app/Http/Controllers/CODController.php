<?php

namespace App\Http\Controllers;

use App\Models\COD;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\ShipmentCollection;
use App\Traits\PdfReportTrait;
use Barryvdh\DomPDF\Facade\Pdf;



class CODController extends Controller
{
    use PdfReportTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
         $cods = COD::with(['shipment', 'collector', 'receiver'])
        ->latest()
        ->paginate(10);

    return view('accounts.cod.index', compact('cods'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(COD $cOD)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(COD $cOD)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
     public function update(Request $request, $id)
{
    // ✅ Validate form input
    $request->validate([
        'amount_received'   => 'required|numeric|min:0',
        'receiver_remarks'  => 'nullable|string|max:255',
    ]);

    // ✅ Find the COD record
    $cod = COD::findOrFail($id);

    // ✅ Fetch related shipment details to get actual total
    $shipment = ShipmentCollection::where('requestId', $cod->requestId)->first();

    $actualTotal = 0;
    if ($shipment) {
        $actualTotal = ($shipment->actual_total_cost ?? 0) + ($shipment->last_mile_delivery_charges ?? 0);
    }

    // ✅ Calculate balance
    $amountReceived = $request->amount_received;
    $balance = $actualTotal - $amountReceived;

    // Prevent negative balance
    if ($balance < 0) {
        $balance = 0;
    }
    if($balance > 0){
        $status = 'Partial';
    } else {
        $status = 'Complete';
    }   

    // ✅ Update COD record
    $cod->update([
        'amountReceived'   => $amountReceived,
        'receicedBalance'  => $balance,  // your DB column spelling
        'receiverRemarks'  => $request->receiver_remarks,
        'receivedBy'       => Auth::id(), // current logged-in user
        'dateReceived'     => now(),
        'status' =>$status,     // current timestamp
    ]);

    // ✅ Return JSON response for AJAX
    return response()->json([
        'success' => true,
        'message' => 'COD record updated successfully.',
        'data' => [
            'id'               => $cod->id,
            'amountReceived'   => number_format($cod->amountReceived, 2),
            'receicedBalance'  => number_format($cod->receicedBalance, 2),
            'receiverRemarks'  => $cod->receiverRemarks,
            'receivedBy'       => Auth::user()->name ?? 'N/A',
            'dateReceived'     => $cod->dateReceived->format('Y-m-d H:i:s'),
        ],
    ]);
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(COD $cOD)
    {
        //
    }

    public function generate(Request $request)
    {
        $filter = $request->query('filter');
        $value = $request->query('value');

        // Safely split start/end date
        [$startDate, $endDate] = explode('_', $value) + [null, null];

        $query = COD::query();

        $query->with(['shipment', 'collector', 'receiver'])
        ->latest();

        $titled = '';
            //dd($filter);
            if ($startDate && $endDate) {
        // Convert for display only
        $displayStart = \Carbon\Carbon::parse($startDate)->format('d-m-Y');
        $displayEnd = \Carbon\Carbon::parse($endDate)->format('d-m-Y');

        $titled = "Between {$displayStart} To {$displayEnd}";

        // Query uses original Y-m-d format
        $query->whereBetween('dateCollected', [
            $startDate . ' 00:00:00',
            $endDate . ' 23:59:59'
        ]);

        } elseif ($startDate && empty($endDate)) {
            // Convert for display
            $displayStart = \Carbon\Carbon::parse($startDate)->format('d-m-Y');
            $titled = "of {$displayStart}";

            // Query remains the same
            $query->whereDate('dateCollected', $startDate);
        }


        $cods = $query->get();

        return $this->renderPdfWithPageNumbers(
            'accounts.cod.cod_report',
            [
            'cods' => $cods,
            'titled' => $titled,
            ],
            'cod_management_report.pdf',
            'a4',
            'landscape'
        );
    }
}
