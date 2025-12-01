<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShipmentCollection;  
use Illuminate\Support\Facades\DB;
use App\Models\DeliveryHandover;
use Carbon\Carbon;
use App\Models\ClientRequest;
use App\Models\User;  
use App\Services\SmsService;    
use Auth;
      



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
    public function approveHandover(Request $request, $id, SmsService $smsService)
    {

        \Log::info($request->all());
        //dd($request->all());
        // Find the handover record by ID
        $handover = DeliveryHandover::find($id);

        //$handover = DB::table('delivery_handovers')->where('id', $id)->first();

        $shipment   = ShipmentCollection::where('requestId', $handover->requestId)->firstOrFail();

        $fromUserId = $request->from_rider_id;

        try {
            DB::transaction(function () use ($shipment, $fromUserId, $request) {
                

                // Update shipment
                $shipment->update([
                    'collected_by' => $request->rider_id,
                    'status'       => 'rider_handover_in_transit',
                ]);

                \Log::info('Shipment updated for handover', [
                    'requestId'   => $request->requestId,
                    'new_rider'   => $request->rider_id
                ]);

                // ✅ Update related client_request
                DB::table('client_requests')
                    ->where('requestId', $shipment->requestId)
                    ->update([
                        'delivery_rider_id' => $request->rider_id,
                        'userId'            => $request->rider_id,
                        'updated_at'        => now(),
                    ]);

                \Log::info('ClientRequest updated after handover', [
                    'requestId' => $shipment->requestId,
                    'new_rider' => $request->rider_id
                ]);

                // Update tracking info
                $trackId = DB::table('tracks')
                    ->where('requestId', $shipment->requestId)
                    ->value('id');

                if ($trackId) {
                    DB::table('tracking_infos')->insert([
                        'trackId'    => $trackId,
                        'date'       => now(),
                        'details'    => "Shipment handed over",
                        'remarks'    => "Shipment handed over from rider ID {$fromUserId} to rider ID {$request->rider_id}.",
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                    DB::table('tracks')->where('id', $trackId)->update([
                        'current_status' => 'Rider Handover In Transit',
                        'updated_at'     => now(),
                    ]);

                    \Log::info('Tracking updated after handover', [
                        'trackId'   => $trackId,
                        'requestId' => $request->requestId,
                    ]);
                }
            });

            // Notify new rider
            $newRider = User::find($request->rider_id);
            if ($newRider) {
                $msg = "Hello {$newRider->name}, you have been handed over shipment with Request ID {$shipment->requestId} for delivery.";
                $smsService->sendSms($newRider->phone_number, 'Shipment Handover', $msg, true);

                \Log::info('New rider notified via SMS', [
                    'rider_id' => $newRider->id,
                    'phone'    => $newRider->phone_number
                ]);
            }

        } catch (\Exception $e) {
            \Log::error('Handover process failed', [
                'requestId' => $request->requestId,
                'shipment_id' => $shipment->id ?? null,
                'error' => $e->getMessage()
            ]);
            return redirect()->back()->with('error', 'Failed to complete rider handover.');
        }



        if (!$handover) {
            return redirect()->back()->with('error', 'Handover record not found.');
        }

        // Update the status to approved
        $handover->status = 'approved';
        $handover->approved_at = now();
        $handover->save();

        return redirect()->back()->with('success', 'Handover approved successfully.');
    }
    public function rejectHandover(Request $request, $id)
    {
        // Find the handover record by ID
        $handover = DeliveryHandover::find($id);

        if (!$handover) {
            return redirect()->back()->with('error', 'Handover record not found.');
        }

        // Update the status to rejected
        $handover->status = 'rejected';
        $handover->rejected_at = now();
        $handover->save();

        return redirect()->back()->with('success', 'Handover rejected successfully.');
    }
    public function handoverDetails($id)
    {
        $handover = DeliveryHandover::find($id);

        if (!$handover) {
            return response()->json(['error' => 'Handover record not found.'], 404);
        }

        return response()->json(['handover' => $handover], 200);
    }
}
