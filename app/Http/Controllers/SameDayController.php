<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\ClientRequest;
use App\Models\SubCategory;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class SameDayController extends Controller
{
    public function on_account()
    {
        $samedaySubCategoryIds = SubCategory::where('sub_category_name', 'Same Day')->pluck('id');

        $clientRequests = ClientRequest::whereIn('sub_category_id', $samedaySubCategoryIds)
            ->whereHas('client', function ($query) {
                $query->where('type', 'on_account');
            })
            ->with(['client', 'user', 'vehicle'])
            ->get();


        return view('same_day.on_account', compact('clientRequests'));
    }

    public function walk_in()
    {
        $samedaySubCategoryIds = SubCategory::where('sub_category_name', 'Same Day')->pluck('id');

        $clientRequests = ClientRequest::whereIn('sub_category_id', $samedaySubCategoryIds)
            ->whereHas('client', function ($query) {
                $query->where('type', 'walkin');
            })
            ->with(['client', 'user', 'vehicle'])
            ->get();


        return view('same_day.walk_in', compact('clientRequests'));
    }
    
    public function sameday_walkin_report(){
        $samedaySubCategoryIds = SubCategory::where('sub_category_name', 'Same Day')->pluck('id');

        $clientRequests = ClientRequest::whereIn('sub_category_id', $samedaySubCategoryIds)
            ->whereHas('client', function ($query) {
                $query->where('type', 'walkin');
            })
            ->with(['client', 'user', 'vehicle'])
            ->get();
        $pdf = Pdf::loadView('same_day.sameday_walkin_report' , [
            'clientRequests'=>$clientRequests
        ])->setPaper('a4', 'landscape');;
        return $pdf->download("sameday_walkin_report.pdf");
    }

    public function sameday_account_report(){
        $samedaySubCategoryIds = SubCategory::where('sub_category_name', 'Same Day')->pluck('id');

        $clientRequests = ClientRequest::whereIn('sub_category_id', $samedaySubCategoryIds)
            ->whereHas('client', function ($query) {
                $query->where('type', 'walkin');
            })
            ->with(['client', 'user', 'vehicle'])
            ->get();
        $pdf = Pdf::loadView('same_day.sameday_account_report' , [
            'clientRequests'=>$clientRequests
        ])->setPaper('a4', 'landscape');;
        return $pdf->download("sameday_account_report.pdf");
    }
}
