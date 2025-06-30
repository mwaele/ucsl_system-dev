<?php

namespace App\Http\Controllers;

use App\Models\LoadingSheetWaybill;
use App\Models\ShipmentCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoadingSheetWaybillController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    $request->validate([
        'loading_sheet_id' => 'required|exists:loading_sheets,id',
        'waybill_no' => 'required|array',
        'waybill_no.*' => 'exists:shipment_collections,id'
    ]);

    foreach ($request->waybill_no as $shipmentId) {
        $shipment = ShipmentCollection::find($shipmentId);

        if ($shipment) {
            $shipmentItems = DB::table('shipment_items')
                ->where('shipment_id', $shipment->id)
                ->get();

            foreach ($shipmentItems as $item) {
                DB::table('loading_sheet_waybills')->insert([
                    'loading_sheet_id' => $request->loading_sheet_id,
                    'waybill_no' => $shipment->waybill_no,
                    'shipment_id' => $shipment->id,
                    'shipment_item_id' => $item->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    return redirect()->back()->with('success', 'Waybill items successfully assigned to loading sheet.');
}


    /**
     * Display the specified resource.
     */
    public function show(LoadingSheetWaybill $loadingSheetWaybill)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LoadingSheetWaybill $loadingSheetWaybill)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LoadingSheetWaybill $loadingSheetWaybill)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LoadingSheetWaybill $loadingSheetWaybill)
    {
        //
    }
}
