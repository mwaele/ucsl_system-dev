<?php

namespace App\Http\Controllers;

use App\Models\ServiceRate;
use App\Models\Client;
use Illuminate\Http\Request;

class ServiceRateController extends Controller
{
    // Show list of rates
    public function index()
    {
        $rates = ServiceRate::with('client')->latest()->paginate(10);
        return view('service_rates.index', compact('rates'));
    }

    // Show form
    public function create()
    {
        $clients = Client::all();
        return view('service_rates.create', compact('clients'));
    }

    // Save rate
    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_name' => 'required|string|max:255',
            'description'  => 'nullable|string',
            'rate'         => 'required|numeric|min:0',
            'client_id'    => 'required|exists:clients,id',
        ]);

        ServiceRate::create($validated);

        return redirect()->route('service_rates.index')->with('success', 'Service Rate added successfully!');
    }

    // Show edit form
    public function edit(ServiceRate $serviceRate)
    {
        $clients = Client::all();
        return view('service_rates.edit', compact('serviceRate', 'clients'));
    }

    // Update
    public function update(Request $request, ServiceRate $serviceRate)
    {
        $validated = $request->validate([
            'service_name' => 'required|string|max:255',
            'description'  => 'nullable|string',
            'rate'         => 'required|numeric|min:0',
            'client_id'    => 'required|exists:clients,id',
        ]);

        $serviceRate->update($validated);

        return redirect()->route('service_rates.index')->with('success', 'Service Rate updated successfully!');
    }

    // Delete
    public function destroy(ServiceRate $serviceRate)
    {
        $serviceRate->delete();
        return redirect()->route('service_rates.index')->with('success', 'Service Rate deleted successfully!');
    }
}
