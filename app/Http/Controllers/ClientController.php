<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        do {
            $number = 'UCSL-' . mt_rand(10000, 99999);
        } while (Client::where('accountNo', $number)->exists());

        
        return view('clients.create')->with('accountNo',$number);
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
            'category' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'industry' => 'required|string|max:255',
            'contactPerson' => 'required|string|max:255',
            'contactPersonPhone' => 'required|string|max:255',
            'contactPersonEmail' => 'required|email|max:255',
            'kraPin' => 'required|string|max:255',
            'postalCode' => 'required|string|max:255',
            'status' => 'required|string|in:active,inactive',
            'special_rates_status'=>'nullable|string',
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
        $client->city = $validated['city'];
        $client->building = $validated['building'];
        $client->country = $validated['country'];
        $client->category = $validated['category'];
        $client->type = $validated['type'];
        $client->industry = $validated['industry'];
        $client->contactPerson = $validated['contactPerson'];
        $client->contactPersonPhone = $validated['contactPersonPhone'];
        $client->contactPersonEmail = $validated['contactPersonEmail'];
        $client->kraPin = $validated['kraPin'];
        $client->postalCode = $validated['postalCode'];
        $client->status = $validated['status'];
        $client->special_rates_status = $validated['special_rates_status'];

        //dd($client);
        $client->save();  // Save the client to the database


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
        
        $validatedData = $request->validate([
            'client_name'=>'required',
            'postal_address'=>'required',
            'physical_address'=>'',
            'tel_number'=>'required',
            'email'=>'required',
            'postal_code'=>'required',
            'finance_person'=>'required',
            'f_phone'=>'required',
            'f_email'=>'required',
            'operations_person'=>'required',
            'o_phone'=>'required',
            'o_email'=>'required',
        ]);
        $client = Client::find($id);
        $client->client_name = $request->client_name;
        $client->postal_address = $request->postal_address;
        $client->physical_address = $request->physical_address;
        $client->tel_number = $request->tel_number;
        $client->email = $request->email;
        $client->postal_code = $request->postal_code;
        $client->finance_person = $request->finance_person;
        $client->f_phone = $request->f_phone;
        $client->f_email = $request->f_email;
        $client->operations_person = $request->operations_person;
        $client->o_phone = $request->o_phone;
        $client->o_email = $request->o_email;
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
