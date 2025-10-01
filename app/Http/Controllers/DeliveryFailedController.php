<?php

namespace App\Http\Controllers;

use App\Models\DeliveryFailed;
use Illuminate\Http\Request;

class DeliveryFailedController extends Controller
{
    public function index()
    {
        $records = DeliveryFailed::latest()->paginate(10);
        return view('delivery_faileds.index', compact('records'));
    }

    public function create()
    {
        return view('delivery_faileds.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'reason' => 'required|string',
            'reference_code' => 'required|string|unique:delivery_faileds',
            'description' => 'nullable|string',
        ]);

        DeliveryFailed::create($validated);

        return redirect()->route('delivery_faileds.index')
            ->with('success', 'Record created successfully.');
    }

    public function show(DeliveryFailed $deliveryFailed)
    {
        return view('delivery_faileds.show', compact('deliveryFailed'));
    }

    public function edit(DeliveryFailed $deliveryFailed)
    {
        return view('delivery_faileds.edit', compact('deliveryFailed'));
    }

    public function update(Request $request, DeliveryFailed $deliveryFailed)
    {
        $validated = $request->validate([
            'reason' => 'required|string',
            'reference_code' => 'required|string|unique:delivery_faileds,reference_code,' . $deliveryFailed->id,
            'description' => 'nullable|string',
        ]);

        $deliveryFailed->update($validated);

        return redirect()->route('delivery_faileds.index')
            ->with('success', 'Record updated successfully.');
    }

    public function destroy(DeliveryFailed $deliveryFailed)
    {
        $deliveryFailed->delete();

        return redirect()->route('delivery_faileds.index')
            ->with('success', 'Record deleted successfully.');
    }
}
