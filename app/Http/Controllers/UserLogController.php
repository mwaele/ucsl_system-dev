<?php

namespace App\Http\Controllers;

use App\Models\UserLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class UserLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = UserLog::query();

    // 🔹 Filter: Today Only
    if ($request->filled('today')) {
        $query->whereDate('created_at', Carbon::today());
    }

    // 🔹 Filter: Single Date
    if ($request->filled('date')) {
        $query->whereDate('created_at', $request->date);
    }

    // 🔹 Filter: Date Range
    if ($request->filled('from') && $request->filled('to')) {
        $query->whereBetween('created_at', [
            $request->from . ' 00:00:00',
            $request->to . ' 23:59:59'
        ]);
    }

    // Get logs after applying filters
    $user_logs = $query->get()
        ->groupBy('name')
        ->map(function ($logsByName) {
            return $logsByName->groupBy(function ($log) {
                return $log->created_at->format('Y-m-d');
            });
        });
        return view('user_logs.index', compact('user_logs'));
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $log = UserLog::findOrFail($id);
        return view('user_logs.show', compact('log'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserLog $userLog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserLog $userLog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserLog $userLog)
    {
        //
    }
}
