<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoutePerformanceExport implements FromView
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function view(): View
    {
        $start_date = $this->request->start_date;
        $end_date = $this->request->end_date;

        $query = DB::table('shipment_collections')
            ->join('offices as origin_office', 'shipment_collections.origin_id', '=', 'origin_office.id')
            ->join('rates', 'shipment_collections.destination_id', '=', 'rates.id')
            ->join('offices as destination_office', 'rates.office_id', '=', 'destination_office.id')
            ->select(
                DB::raw('MAX(origin_office.name) as origin'),
                DB::raw('MAX(rates.destination) as destination'),
                DB::raw('COUNT(shipment_collections.id) as total_shipments'),
                DB::raw('SUM(shipment_collections.total_weight) as total_volume'),
                DB::raw('SUM(shipment_collections.actual_total_cost) as total_revenue')
            );

        // ✅ Apply date filters if provided
        if (!empty($start_date) && !empty($end_date)) {
            $query->whereBetween('shipment_collections.created_at', [$start_date, $end_date]);
        }

        $query->groupBy('shipment_collections.origin_id', 'shipment_collections.destination_id');

        $report = $query->get();

        // ✅ Calculate contributions
        $totalRevenue = $report->sum('total_revenue');
        $totalVolume = $report->sum('total_volume');

        $report->transform(function ($row) use ($totalRevenue, $totalVolume) {
            $row->revenue_contribution = $totalRevenue > 0 ? round(($row->total_revenue / $totalRevenue) * 100, 2) : 0;
            $row->volume_contribution = $totalVolume > 0 ? round(($row->total_volume / $totalVolume) * 100, 2) : 0;
            return $row;
        });

        return view('exports.route_performance_excel', [
            'report' => $report,
            'start_date' => $start_date,
            'end_date' => $end_date,
        ]);
    }
}
