<?php

namespace App\Http\Controllers;

use App\Models\DeliveryControl;
use Illuminate\Http\Request;

class DeliveryControlController extends Controller
{
    public function index()
    {
        $controls = DeliveryControl::latest()->get();
        return view('delivery_controls.index', compact('controls'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ctr_time' => 'required|string',
        ]);

        // Generate control ID
        $lastControl = \App\Models\DeliveryControl::latest('id')->first();
        $nextId = $lastControl ? $lastControl->id + 1 : 1;
        $controlId = 'CTRL-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        DeliveryControl::create([
            'control_id' => $controlId,
            'ctr_time' => $validated['ctr_time'],
            'details' => $request->details,
            'route_id' => $request->route_id,
            'ctr_days' => $request->ctr_days,
            'ctr_months' => $request->ctr_months,
            'ctr_years' => $request->ctr_years,
        ]);

        return redirect()->back()->with('success', "Record created successfully with ID: $controlId");
    }


    public function update(Request $request, $id)
    {
        $control = DeliveryControl::findOrFail($id);

        $validated = $request->validate([
            'control_id' => 'required|string|max:255',
            'ctr_time' => 'required|string',
        ]);

        $control->update($validated + $request->only([
            'details', 'route_id', 'ctr_days', 'ctr_months', 'ctr_years'
        ]));

        return redirect()->back()->with('success', 'Record updated successfully!');
    }

    public function destroy($id)
    {
        DeliveryControl::destroy($id);
        return redirect()->back()->with('success', 'Record deleted successfully!');
    }
}
