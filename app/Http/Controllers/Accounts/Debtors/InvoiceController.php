<?php

namespace App\Http\Controllers\Accounts\Debtors;

use App\Http\Controllers\Controller;
use App\Traits\PdfReportTrait;
use Illuminate\Http\Request;
use App\Models\Invoice;

class InvoiceController extends Controller
{
    use PdfReportTrait;

    public function index()
    {
        $invoices = Invoice::whereHas('client', function ($query) {
            $query->where('type', 'on_account');
        })->get();
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

    public function unposted_invoices_report()
    {
        $invoices = Invoice::whereHas('client', function ($query) {
            $query->where('type', 'on_account');
        })->get();

        return $this->renderPdfWithPageNumbers(
            'accounts.debtors.invoices.unposted_invoices_report',
            ['invoices' => $invoices],
            'unposted_invoices_report.pdf',
            'a4',
            'landscape'
        );
    }
}
