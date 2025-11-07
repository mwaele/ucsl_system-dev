<?php

namespace App\Http\Controllers;

use App\Models\Dispatcher;
use App\Models\Office;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Traits\PdfReportTrait;

class DispatcherController extends Controller
{
    use PdfReportTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $offices = Office::all();
        $dispatchers = Dispatcher::where('office_id', Auth::user()->station)->get();
        return view('dispatchers.index')->with(['dispatchers'=> $dispatchers,'offices'=>$offices]);
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
        $validated = $request->validate([
        'name' => 'required|string|max:255',
        'id_no' => 'required|numeric|digits_between:4,10',
        'phone_no' => 'required|string|max:20',
        'signature' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        'office_id' => 'required|numeric',
    ]);

        if ($request->hasFile('signature')) {
        $path = $request->file('signature')->store('signatures', 'public');
        $validated['signature'] = $path;
    }

    Dispatcher::create($validated);

    return redirect()->back()->with('success', 'Dispatcher saved successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Dispatcher $dispatcher)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Dispatcher $dispatcher)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        
        // Validate inputs
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'id_no' => 'required|numeric',
            'phone_no' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'type' => 'nullable|string|max:100',
            'remarks' => 'nullable|string|max:500',
            'office_id' => 'required|integer|exists:offices,id',
            'signature' => 'nullable|file|mimes:png,jpg,jpeg,pdf|max:2048',
        ]);

        // Find salesperson record
        $dispatcher = Dispatcher::findOrFail($id);

        // If a new signature is uploaded, delete the old one (optional)
        if ($request->hasFile('signature')) {
            if ($dispatcher->signature && Storage::exists('public/signatures/' . $dispatcher->signature)) {
                Storage::delete('public/signatures/' . $dispatcher->signature);
            }

            $signatureName = time() . '_' . $request->file('signature')->getClientOriginalName();
            $request->file('signature')->storeAs('public/signatures', $signatureName);
            $validatedData['signature'] = $signatureName;
        }

        // Update record
        $dispatcher->update($validatedData);

        // Return success response
        return redirect()->back()->with('success', 'Dispatchers updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dispatcher $dispatcher)
    {
        //
    }
    public function generate()
    {
        $dispatchers = Dispatcher::orderBy('created_at', 'desc')->get();

        return $this->renderPdfWithPageNumbers(
            'dispatchers.dispatchers_report',
            ['dispatchers' => $dispatchers],
            'dispatchers_report.pdf',
            'a4',
            'landscape'
        );
    }


    
}
