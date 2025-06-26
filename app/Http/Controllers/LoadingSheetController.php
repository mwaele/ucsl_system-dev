<?php

namespace App\Http\Controllers;

use App\Models\LoadingSheet;
use App\Models\Office;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;

class LoadingSheetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $offices = Office::where('id',Auth::user()->station)->get();
        $sheets = LoadingSheet::all();
        $drivers = User::where('role', 'driver')->get();
        return view('loading-sheet.index', with(['sheets'=>$sheets, 'drivers'=>$drivers, 'offices'=>$offices]));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('loading-sheet.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(LoadingSheet $loadingSheet)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LoadingSheet $loadingSheet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LoadingSheet $loadingSheet)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LoadingSheet $loadingSheet)
    {
        //
    }
}
