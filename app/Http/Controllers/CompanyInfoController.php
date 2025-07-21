<?php

namespace App\Http\Controllers;

use App\Models\CompanyInfo;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class CompanyInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $company_info = CompanyInfo::all();
        return view('companyInfo.index')->with('company_infos',$company_info);
    }


    public function company_info_report(){
        $company_infos = CompanyInfo::all();
        $pdf = Pdf::loadView('companyInfo.company_info_report' , [
            'company_infos'=>$company_infos
        ])->setPaper('a4', 'landscape');;
        return $pdf->download("company_info_report.pdf");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('companyInfo.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'logo'=>'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'company_name'=>'required',
            'slogan'=>'required',
            'location'=>'required',
            'address'=>'required',
            'website'=>'required',
            'email'=>'required',
            'pin'=>'required',
            'contact'=>''

        ]);

        $logo = time().$request->file('logo')->getClientOriginalName();
        $request->logo->move(public_path('images'), $logo);

        $company_info = CompanyInfo::create([
            'logo'=> $logo,
            'company_name' => $request->company_name,
            'slogan' => $request->slogan,
            'location' => $request->location,
            'address' => $request->address,
            'website' => $request->website,
            'email' => $request->email,
            'pin' => $request->pin,
            'contact' => $request->contact,
        ]);
        return redirect()->route('company_infos.index')->with('success', 'Company Info Saved successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(CompanyInfo $companyInfo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $company_infos = CompanyInfo::where('id',$id)->get();
        return view('companyInfo.edit')->with('company_infos', $company_infos);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    $validatedData = $request->validate([
        'company_name' => 'required',
        'slogan' => 'required',
        'location' => 'required',
        'address' => 'required',
        'email' => 'required|email',
        'pin' => 'required',
        'contact' => 'required',
        'website' => 'required',
    ]);
    
    $companyInfo = CompanyInfo::find($id);
    
    if (!$companyInfo) {
        return back()->with('error', 'Company info not found.');
    }
    
    $companyInfo->update($validatedData);

    return redirect()->route('company_infos.index')
                     ->with('success', 'Company info updated successfully.');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $client = CompanyInfo::find($id);
        $client->delete();
        return redirect()->route('company_infos.index')->with('Success', 'Company info deleted successfully.');
    }
}