<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\ClientRequest;
use App\Models\SubCategory;

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
}
