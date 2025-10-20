<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class VehiclePerformanceExport implements FromView
{
    protected $report;
    protected $startDate;
    protected $endDate;

    public function __construct($report, $startDate, $endDate)
    {
        $this->report = $report;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function view(): View
    {
        return view('reports.vehicle_performance_excel', [
            'report' => $this->report,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
        ]);
    }
}

