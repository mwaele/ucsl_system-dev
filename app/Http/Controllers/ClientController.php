<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\SubCategory;
use App\Models\Category;
use App\Models\ClientCategory;
use App\Models\SalesPerson;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients = Client::all();
        return view('clients.index')->with('clients', $clients);
    }

    public function clients_report(){
        $clients = Client::all();
        $pdf = Pdf::loadView('clients.clients_report' , [
            'clients'=>$clients
        ])->setPaper('a4', 'landscape');
        return $pdf->download("clients_report.pdf");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sub_categories = SubCategory::all();
        $categories = Category::all();
        $sales_person = SalesPerson::all();
        do {
            $number = 'UCSL-' . mt_rand(10000, 99999);
        } while (Client::where('accountNo', $number)->exists());

        
        return view('clients.create')->with(['accountNo'=>$number, 'categories'=>$categories,'sales_person'=>$sales_person]);
    }

    private function normalizePhoneNumber($phone)
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/\D/', '', $phone);

        if (Str::startsWith($phone, '0')) {
            return '+254' . substr($phone, 1);
        } elseif (Str::startsWith($phone, '7') && strlen($phone) === 9) {
            return '+254' . $phone;
        } elseif (Str::startsWith($phone, '254')) {
            return '+' . $phone;
        }

        return $phone; // fallback: assume it's already in international format
    }

    /**
     * Store a newly created resource in storage.
     */
    
    public function store(Request $request)
    {
        //dd($request);
        // Validate the incoming request
        $validated = $request->validate([
            'accountNo' => 'required|unique:clients|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients',
            'password' => 'required|string|min:8',
            'contact' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'building' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            //'category_id' => 'required|string|max:11',
            'type' => 'required|string|max:255',
            'contactPerson' => 'required|string|max:255',
            'contactPersonPhone' => 'required|string|max:255',
            'contactPersonEmail' => 'required|email|max:255',
            'kraPin' => 'required|string|max:255',
            'postalCode' => 'required|string|max:255',
            'status' => 'required|string|in:active,inactive',
            'special_rates_status'=>'nullable|string',
            'sales_person_id'=>'nullable|string',
            'id_number'=>'nullable|string',
        ]);
    

        //dd($validated);
        // If validation passes, create the new client with a unique account number
         // Insert the validated data
        $client = new Client();
        $client->accountNo = $validated['accountNo'];
        $client->name = $validated['name'];
        $client->email = $validated['email'];
        $client->password = bcrypt($validated['password']);  // Ensure password is hashed
        $client->contact = $this->normalizePhoneNumber($validated['contact']); 
        $client->address = $validated['address'];
        $client->id_number = $validated['id_number'];
        $client->city = $validated['city'];
        $client->building = $validated['building'];
        $client->country = $validated['country'];
        $client->category = 'NULL';
        //$client->category_id = $validated['category_id'];
        $client->type = $validated['type'];
        $client->contactPerson = $validated['contactPerson'];
        $client->contactPersonPhone = $validated['contactPersonPhone'];
        $client->contactPersonEmail = $validated['contactPersonEmail'];
        $client->kraPin = $validated['kraPin'];
        $client->postalCode = $validated['postalCode'];
        $client->status = $validated['status'];
        $client->special_rates_status = $validated['special_rates_status'] ?? null;
        $client->sales_person_id = $validated['sales_person_id'];

        //dd($client);
        $client->save();  // Save the client to the database

        // Get selected category IDs
        $categoryIds = $request->input('category_id');

        // Save to pivot table client_categories
        foreach ($categoryIds as $catId) {
            ClientCategory::create([
                'client_id' => $client->id,
                'category_id' => $catId,
            ]);
        }


        return redirect()->route('clients.index')->with('success', 'Client created successfully!');

    }
    

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $clients = Client::where('id',$id)->get();
        return view('clients.show')->with('clients', $clients);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $client = Client::where('id',$id)->get();
        return view('clients.edit')->with('clients', $client);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        
        $client = Client::find($id);
        $client->name = $request->name;
        $client->email = $request->email;
        $client->contact = $request->contact;
        $client->type = $request->type;
        $client->password = bcrypt($request->password);
        $client->save();
        
        return redirect()->route('clients.index')->with('Success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $client = Client::find($id);
        $client->delete();
        return redirect()->route('clients.index')->with('Success');
    }
}
