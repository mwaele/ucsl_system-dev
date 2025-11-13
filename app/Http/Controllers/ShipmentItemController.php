<?php

namespace App\Http\Controllers;

use App\Models\ShipmentItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShipmentItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function fetchItems($id)
    {
        $items = ShipmentItem::where('shipment_id', $id)->get();
        return response()->json(['items' => $items]);
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

    public function getItems(Request $request)
    {
        $shipmentIds = $request->input('ids');

        $items = DB::table('shipment_items')
            ->join('shipment_collections', 'shipment_items.shipment_id', '=', 'shipment_collections.id')
            ->whereIn('shipment_items.shipment_id', $shipmentIds)
            ->select(
                'shipment_collections.id as shipment_id',
                'shipment_collections.waybill_no',
                'shipment_items.item_name',
                'shipment_items.packages_no',
                'shipment_items.actual_quantity',
                'shipment_items.actual_weight'
            )
            ->get();

        return response()->json($items);
    }

    /**
     * Display the specified resource.
     */
    public function show(ShipmentItem $shipmentItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ShipmentItem $shipmentItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ShipmentItem $shipmentItem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShipmentItem $shipmentItem)
    {
        //
    }
    
}
