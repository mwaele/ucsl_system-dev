<?php

namespace App\Http\Controllers;

use App\Models\UserLog;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Traits\PdfReportTrait;
use Illuminate\Support\Facades\Auth;


class UserLogController extends Controller
{
    use PdfReportTrait;
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
    public function show($user_id, $date)
    {
        
         $logs = UserLog::where('user_id', $user_id)
        ->whereDate('created_at', $date)
        ->orderBy('created_at', 'desc')
        ->get();
        //dd($log->toArray());
        return view('user_logs.show', compact('logs'));
    }
    public function exportPdf(Request $request, $user_id, $date)
    {
        // Log the report generation
        UserLog::create([
            'name'         => Auth::user()->name,
            'actions'      => Auth::user()->name . ' generated user logs report for User ID ' . $user_id . ' at ' . now(),
            'url'          => $request->fullUrl(),
            'reference_id' => $user_id,
            'table'        => "user_logs",
            'user_id'      => Auth::id(),
        ]);
        $logs = UserLog::where('user_id', $user_id)
            ->whereDate('created_at', $date)
            ->orderBy('created_at', 'desc')
            ->get();

        

        return $this->renderPdfWithPageNumbers(
            'user_logs.user_logs_report',     // Your PDF Blade view
            [
                'logs' => $logs,
                'user' => $logs->first()->name ?? 'Unknown User',
                'date' => $date
            ],
            'user_logs_report_' . $user_id . '_' . $date . '.pdf',
            'a4',
            'portrait'      // timeline fits better in portrait
        );
    }

    // public function exportPdf($user_id, $date)
    // {
    //     $logs = UserLog::where('user_id', $user_id)
    //         ->whereDate('created_at', $date)
    //         ->orderBy('created_at', 'desc')
    //         ->get();

    //     if ($logs->isEmpty()) {
    //         return back()->with('error', 'No logs found for the selected date.');
    //     }

    //     $pdf = \PDF::loadView('user_logs.user_logs_report', [
    //         'logs' => $logs,
    //         'user' => $logs->first()->name ?? 'User',
    //         'date' => $date
    //     ]);

    //     return $pdf->download('user_logs_report_' . $user_id . '_' . $date . '.pdf');
    // }



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
