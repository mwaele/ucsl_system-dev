<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\ClientRequest;
use App\Models\SubCategory;

class OvernightController extends Controller
{
    //
    public function on_account()
    {
        $clients = Client::where('type', 'on_account')->get();

        $overnightSubCategory = SubCategory::where('sub_category_name', 'Overnight')->first();

        $clientRequests = [];

        if ($overnightSubCategory) {
            $clientRequests = ClientRequest::where('sub_category_id', $overnightSubCategory->id)
                                        ->with('client') // optional: eager load
                                        ->get();
        }

        return view('overnight.on-account', compact('clients', 'clientRequests'));
    }

    public function walk_in()
    {
        $overnightSubCategoryIds = SubCategory::where('sub_category_name', 'Overnight')->pluck('id');

        $clientRequests = ClientRequest::whereIn('sub_category_id', $overnightSubCategoryIds)
            ->whereHas('client', function ($query) {
                $query->where('type', 'walkin');
            })
            ->with(['client', 'user', 'vehicle'])
            ->get();


        return view('overnight.walk-in', compact('clientRequests'));
    }

}
