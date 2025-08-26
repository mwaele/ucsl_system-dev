<?php

namespace App\Http\Controllers\Accounts\Debtors;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::latest()->paginate(10);
        return view('accounts.debtors.invoices.index', compact('invoices'));
    }

    public function create()
    {
        return view('accounts.debtors.invoices.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'amount'    => 'required|numeric|min:0',
            'due_date'  => 'required|date',
        ]);

        Invoice::create($validated);

        return redirect()->route('accounts.debtors.invoices.index')
                         ->with('success', 'Invoice created successfully.');
    }
}
