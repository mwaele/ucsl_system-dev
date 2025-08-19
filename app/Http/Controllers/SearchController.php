<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Station;

class SearchController extends Controller
{
    /**
     * Handle AJAX Select2 searches.
     */
    public function search(Request $request)
    {
        \Log::info('Search params', $request->all());
        
        $type = $request->get('type');
        $search = $request->get('q');
        $results = collect();

        switch ($type) {
            case 'client':
                $results = Client::query()
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('accountNo', 'like', "%{$search}%")
                    ->limit(20)
                    ->get(['id', 'name', 'accountNo'])
                    ->map(fn($c) => [
                        'id' => $c->id,
                        'text' => $c->name . ' (' . $c->accountNo . ')'
                    ]);
                break;

            case 'user':
                $results = User::query()
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->limit(20)
                    ->get(['id', 'name', 'email'])
                    ->map(fn($u) => [
                        'id' => $u->id,
                        'text' => $u->name . ' (' . $u->email . ')'
                    ]);
                break;

            case 'vehicle':
                $results = Vehicle::query()
                    ->where('plate_number', 'like', "%{$search}%")
                    ->limit(20)
                    ->get(['id', 'plate_number'])
                    ->map(fn($v) => [
                        'id' => $v->id,
                        'text' => $v->plate_number
                    ]);
                break;

            case 'station':
                $results = Station::query()
                    ->where('name', 'like', "%{$search}%")
                    ->limit(20)
                    ->get(['id', 'name'])
                    ->map(fn($s) => [
                        'id' => $s->id,
                        'text' => $s->name
                    ]);
                break;
        }

        return response()->json($results);
    }
}
