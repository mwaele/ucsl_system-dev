<?php

namespace App\Http\Controllers;

use App\Models\Dispatcher;
use App\Models\Office;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DispatcherController extends Controller
{
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
    public function update(Request $request, Dispatcher $dispatcher)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dispatcher $dispatcher)
    {
        //
    }
}
