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
use App\Traits\PdfReportTrait;
use Carbon\Carbon;

use Illuminate\Support\Facades\Validator;
use App\Services\SmsService;
use App\Mail\GenericMail;

use App\Jobs\SendCollectionNotificationsJob;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;

class ClientController extends Controller
{
    use PdfReportTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients = Client::all();
        return view('clients.index')->with('clients', $clients);
    }

    public function clients_report()
    {
        $clients = Client::all();

        return $this->renderPdfWithPageNumbers(
            'clients.clients_report',
            ['clients' => $clients],
            'clients_report.pdf',
            'a4',
            'landscape'
        );
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
    
    public function store(Request $request, SmsService $smsService)
    {
        try {
            // Common validation rules
            $rules = [
                'accountNo' => 'required|unique:clients|max:255',
                'name' => 'required|string|max:255',
                'password' => 'required|string|min:8',
                'contact' => 'required|string|max:255',
                'address' => 'nullable|string|max:255',
                'city' => 'nullable|string|max:255',
                'building' => 'nullable|string|max:255',
                'country' => 'required|string|max:255',
                'type' => 'required|string|in:on_account,walkin',
                'status' => 'required|string|in:active,inactive',
                'special_rates_status' => 'nullable|string',
                'sales_person_id' => 'nullable|string',
                'id_number' => 'nullable|string',
            ];

            // Add extra requirements for on-account clients
            if ($request->input('type') !== 'walkin') {
                $rules = array_merge($rules, [
                    'contactPerson' => 'required|string|max:255',
                    'contactPersonPhone' => 'required|string|max:255',
                    'contactPersonEmail' => 'required|email|max:255',
                    'kraPin' => 'required|string|max:255',
                    'postalCode' => 'required|string|max:255',
                    'email' => 'required|email',
                ]);
            } else {
                // Walk-in clients → optional fields
                $rules = array_merge($rules, [
                    'contactPerson' => 'nullable|string|max:255',
                    'contactPersonPhone' => 'nullable|string|max:255',
                    'contactPersonEmail' => 'nullable|email|max:255',
                    'kraPin' => 'nullable|string|max:255',
                    'postalCode' => 'nullable|string|max:255',
                    'email' => 'nullable',
                ]);
            }

            $validated = $request->validate($rules);

            // Transaction ensures DB integrity
            DB::beginTransaction();

            $client = new Client();
            $client->accountNo = $validated['accountNo'];
            $client->name = $validated['name'];
            $client->email = $validated['email'];
            $client->password = bcrypt($validated['password']);
            $client->contact = $this->normalizePhoneNumber($validated['contact']);
            $client->address = $validated['address'];
            $client->id_number = $validated['id_number'] ?? null;
            $client->city = $validated['city'];
            $client->building = $validated['building'];
            $client->country = $validated['country'];
            $client->category = 'NULL'; // TODO: remove if not needed
            $client->type = $validated['type'];
            $client->contactPerson = $validated['contactPerson'] ?? null;
            $client->contactPersonPhone = $validated['contactPersonPhone'] ?? null;
            $client->contactPersonEmail = $validated['contactPersonEmail'] ?? null;
            $client->kraPin = $validated['kraPin'] ?? null;
            $client->postalCode = $validated['postalCode'] ?? null;
            $client->status = $validated['status'];
            $client->special_rates_status = $validated['special_rates_status'] ?? null;
            $client->sales_person_id = $validated['sales_person_id'] ?? null;

            // Generate and assign OTP
            $client->otp = rand(100000, 999999);
            $client->save();

            // Save categories safely
            $categoryIds = (array) $request->input('category_id', []);
            foreach ($categoryIds as $catId) {
                if (!empty($catId)) {
                    ClientCategory::create([
                        'client_id' => $client->id,
                        'category_id' => $catId,
                    ]);
                }
            }

            DB::commit();

            // Try sending SMS, but don’t fail client creation if SMS fails
            try {
                $message = "Your OTP is {$client->otp}.";
                $smsService->sendSms(
                    phone: $client->contact,
                    subject:'',
                    message: $message,
                    addFooter: true
                );
            } catch (\Throwable $e) {
                Log::error("Failed to send OTP SMS to client {$client->id}: " . $e->getMessage());
                // Don’t rollback — client is already created
            }

            return redirect()->route('clients.index')
                            ->with('success', 'Client created successfully!');

        } catch (QueryException $e) {
            DB::rollBack();
            Log::error("Database error while creating client: " . $e->getMessage());
            return back()->withErrors(['error' => 'Could not save client. Please try again.'])
                        ->withInput();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error("Unexpected error while creating client: " . $e->getMessage());
            return back()->withErrors(['error' => 'An unexpected error occurred. Please try again.'])
                        ->withInput();
        }
    }

    public function checkClient($id = null)
    {
        $client = Client::where('id_number', $id)->first();
        return response()->json(['exists' => $client ? true : false]);
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

    public function update_otp(Request $request, $id)
    {
        
        $client = Client::find($id);
        $otp = $client->otp;
        if($otp == $request->verified_otp){
        $client->verified_otp = $request->verified_otp;
        $client->save();
        return redirect()->route('clients.index')->with('success','OTP Verified');
        }
        else{
            return redirect()->back()->with('error', 'OTP does not march');
        }
        
        
        
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
