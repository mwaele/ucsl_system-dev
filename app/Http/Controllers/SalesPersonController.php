<?php

namespace App\Http\Controllers;

use App\Models\SalesPerson;
use App\Models\Office;
use Illuminate\Http\Request;
use App\Helpers\EmailHelper;
use Illuminate\Support\Carbon;
use App\Models\ClientRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class SalesPersonController extends Controller
{
    public function index()
    {
        $sales_person = SalesPerson::all();
        $offices = Office::where('id', Auth::user()->station)->get();
        return view('sales_person.index', compact('sales_person', 'offices'));
    }

    public function create()
    {
        return view('sales_person.create');
    }

    public function store(Request $request)
    {
        
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'phone_number' => 'required|string|max:20|unique:sales_people,phone_number',
            'id_no'        => 'required|string|max:50|unique:sales_people,id_no',
            'type'         => 'required|in:Sales,Marketing,Both',
            'remarks'      => 'nullable|string|max:500',
            'office_id'    => 'required|exists:offices,id',
            'email'        => 'nullable|email|max:255|unique:sales_people,email',
        ]);
        
        $sales_person = new SalesPerson();
        $sales_person->name =  $validated['name'];
        $sales_person->phone_number =  $validated['phone_number'];
        $sales_person->id_no =  $validated['id_no'];
        $sales_person->type =  $validated['type'];
        $sales_person->remarks =  $validated['remarks'];
        $sales_person->office_id =  $validated['office_id'];
        $sales_person->email =  $validated['email'];
        $sales_person->created_by = Auth::user()->id;
        $sales_person->save();

        return redirect()->back()->with('success', 'User account created.');
    }
    public function getSalesPerson($id)
{
    $salesPerson = \App\Models\SalesPerson::find($id);

    if (!$salesPerson) {
        return response()->json(['error' => 'Sales person not found'], 404);
    }

    return response()->json([
        'phone_number' => $salesPerson->phone_number,
        'email'        => $salesPerson->email,
        'id_no'        => $salesPerson->id_no,
    ]);
}


    public function update(Request $request, $id)
    {
        $user = SalesPerson::findOrFail($id);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        $user->station = $request->station;
        $user->role = $request->role;
        $user->status = $request->status;

        $user->save();   

        return redirect()->back()->with('success', 'User updated successfully.');
    }

    public function sales_person_report(){

        $sales_person = SalesPerson::orderBy('created_at', 'desc')->get();
        $pdf = Pdf::loadView('sales_person.user_report' , [
            'sales_person'=>$sales_person
        ])->setPaper('a4', 'landscape');;
        return $pdf->download("sales_person_report.pdf");
       
    }

    public function destroy($id)
    {
        //
        $user = SalesPerson::where('id', $id)->firstOrFail();
        $user->delete();

        return back()->with('success', 'User deleted successfully.');
    }

}

