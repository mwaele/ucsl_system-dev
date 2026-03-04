<?php

namespace App\Http\Controllers;

use App\Models\ShipmentItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        Log::info('REQUEST TIME:', ['time' => microtime(true)]);
        $shipmentIds = $request->input('ids');
    
        if (!is_array($shipmentIds)) {
            $shipmentIds = explode(',', $shipmentIds);
        }
    
        if (empty($shipmentIds)) {
            Log::warning('No shipment IDs provided');
            return response()->json([]);
        }
    
        $query = DB::table('shipment_items')
            ->join('shipment_collections', 'shipment_items.shipment_id', '=', 'shipment_collections.id')
            ->whereIn('shipment_items.shipment_id', $shipmentIds)
            ->select(
                'shipment_collections.id as shipment_id',
                'shipment_collections.requestId',
                'shipment_collections.waybill_no',
                'shipment_items.item_name',
                'shipment_items.packages_no',
                'shipment_items.actual_quantity',
                'shipment_items.actual_weight'
            );
    
        Log::info('SQL:', [
            'query' => $query->toSql(),
            'bindings' => $query->getBindings()
        ]);
    
        $items = $query->get();
    
        Log::info('Fetched Items:', $items->toArray());
    
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
