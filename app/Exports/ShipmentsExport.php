<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;

class ShipmentsExport implements FromView
{
    protected $startDate, $endDate, $userName;

    public function __construct($startDate, $endDate, $userName)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->userName = $userName;
    }

    public function view(): View
    {
        $report = DB::table('client_requests')
            ->join('users', 'client_requests.userId', '=', 'users.id')
            ->join('shipment_collections', 'client_requests.requestId', '=', 'shipment_collections.requestId')
            ->select(
                'users.id',
                'users.name',
                DB::raw('COUNT(shipment_collections.id) as total_shipments'),
                DB::raw('SUM(shipment_collections.actual_total_cost) as total_amount'),
                DB::raw('SUM(shipment_collections.total_weight) as total_volume')
            )
            ->when($this->startDate && $this->endDate, function ($query) {
                $query->whereBetween('shipment_collections.created_at', [$this->startDate, $this->endDate]);
            })
            ->when($this->userName, function ($query) {
                $query->where('users.name', 'like', '%' . $this->userName . '%');
            })
            ->groupBy('users.id', 'users.name')
            ->get();

        return view('reports.rider_performance_excel', compact('report'));
    }
}
