<?php

namespace App\Http\Controllers;

use App\Models\Office;
use App\Models\User;
use App\Models\Rate;
use Illuminate\Http\Request;
use App\Models\OfficeUser;
use Auth;

class OfficeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rates = Rate::where('type', 'normal')
             ->where('origin', 'Nairobi')
             ->orderBy('destination', 'asc')
             ->get();
        $offices = Office::with(['users.office', 'officeUsers'])->get();
        return view('offices.index', compact('offices', 'rates'));
    
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('offices.create');
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'office_name'  => 'required|string|max:50',
            'office_code'  => 'required|string|max:10',
            'type'         => 'required',
            'status'       => 'required',
            'country'      => 'required',
            'city'         => 'required',
        ]);

        $office = new Office();
        $office->name        = $validateData['office_name'];
        $office->createdBy   = Auth::user()->id;
        $office->office_code = $validateData['office_code'];
        $office->shortName   = $validateData['office_code'];
        $office->country     = $validateData['country'];
        $office->city        = $validateData['city'];
        $office->type        = $validateData['type'];
        $office->status      = $validateData['status'];

        $office->save();

        return redirect()->route('offices.index')
            ->with('success', 'Office saved successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Office $office)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Office $office)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Office $office)
    {
        // ✅ Validate input
        $validateData = $request->validate([
            'office_name'  => 'required|string|max:50',
            'office_code'  => 'required|string|max:10',
            'type'         => 'required|string',
            'status'       => 'required|string',
            'country'      => 'required|string',
            'city'         => 'required|string',
        ]);

        // ✅ Update office fields
        $office->name        = $validateData['office_name'];
        $office->office_code = $validateData['office_code'];
        $office->shortName   = $validateData['office_code']; // if same as code
        $office->type        = $validateData['type'];
        $office->status      = $validateData['status'];
        $office->country     = $validateData['country'];
        $office->city        = $validateData['city'];

        $office->save();

        // ✅ Redirect back
        return redirect()->route('offices.index')
            ->with('success', 'Office updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $office = Office::find($id);
        $office->delete();
        return redirect()->route('offices.index')->with('success', 'Office info deleted successfully.');
    }
}
