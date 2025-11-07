<?php

namespace App\Http\Controllers;

use App\Models\DeliveryFailed;
use Illuminate\Http\Request;
use App\Traits\PdfReportTrait;

class DeliveryFailedController extends Controller
{
    use PdfReportTrait;
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
    

    public function delivery_failed_report()
    {
        $delivery_faileds = DeliveryFailed::orderBy('created_at', 'desc')->get();

        return $this->renderPdfWithPageNumbers(
            'delivery_faileds.delivery_faileds_report',
            ['delivery_faileds' => $delivery_faileds],
            'delivery_faileds_report.pdf',
            'a4',
            'landscape'
        );
    }
}
