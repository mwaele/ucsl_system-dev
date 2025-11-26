<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShipmentCollection;  
use Illuminate\Support\Facades\DB;
use App\Models\DeliveryHandover;

class HandoverController extends Controller
{
    public function riderHandover(Request $request)
    {
       
        $handovers = DeliveryHandover::all();
        return view('handover.delivery_handover', compact('handovers'));
        
    }

    public function riderCollectionHandover(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'rider_id' => 'required|integer',
            'collection_id' => 'required|integer',
            'handover_time' => 'required|date_format:Y-m-d H:i:s',
        ]);

        // Process the handover logic here
        // For example, save the handover details to the database

        return response()->json(['message' => 'Rider collection handover recorded successfully.'], 200);
    }
    public function approveHandover(Request $request, $id)
    {
        dd($request->all());
        // Find the handover record by ID
        $handover = DeliveryHandover::find($id);

        if (!$handover) {
            return redirect()->back()->with('error', 'Handover record not found.');
        }

        // Update the status to approved
        $handover->status = 'approved';
        $handover->approved_at = now();
        $handover->save();

        return redirect()->back()->with('success', 'Handover approved successfully.');
    }
}
