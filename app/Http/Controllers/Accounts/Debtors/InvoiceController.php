<?php

namespace App\Http\Controllers\Accounts\Debtors;

use App\Http\Controllers\Controller;
use App\Traits\PdfReportTrait;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\AccountsReceivableMain;
use App\Models\AccountsReceivableTransaction;

class InvoiceController extends Controller
{
    use PdfReportTrait;

    public function index()
    {
        $invoices = Invoice::where('status', 'Un Paid')
            ->whereHas('client', function ($query) { 
                $query->where('type', 'on_account');
            })
            ->get();

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
        $invoices = Invoice::where('status', 'Un Paid')
            ->whereHas('client', function ($query) { 
                $query->where('type', 'on_account');
            })
            ->get();

        return $this->renderPdfWithPageNumbers(
            'accounts.debtors.invoices.unposted_invoices_report',
            ['invoices' => $invoices],
            'unposted_invoices_report.pdf',
            'a4',
            'landscape'
        );
    }

    public function postInvoice(Request $request, $id)
    {
        $invoice = Invoice::with(['client', 'shipment_collection'])->findOrFail($id);

        if ($invoice->status === 'posted') {
            return redirect()->back()->with('warning', 'Invoice already posted.');
        }

        // 1. Ensure Accounts Receivable Main exists for this client
        $arMain = AccountsReceivableMain::firstOrCreate(
            ['client_id' => $invoice->client_id],
            [
                'balance'  => 0,
                'current'  => 0,
                '30_days'  => 0,
                '60_days'  => 0,
                '90_days'  => 0,
            ]
        );

        // 2. Create the Accounts Receivable Transaction
        AccountsReceivableTransaction::create([
            'client_id'           => $invoice->client_id,
            'request_id'          => $invoice->shipment_collection->requestId ?? null,
            'batch_no'            => optional($invoice->loading_sheet_waybills->loading_sheet ?? null)->batch_no_formatted,
            'waybill_no'          => $invoice->shipment_collection->waybill_no ?? null,
            'reference'           => $invoice->invoice_no,
            'details'             => 'Invoice Posting for Waybill ' . ($invoice->shipment_collection->waybill_no ?? ''),
            'date'                => now()->toDateString(),
            'datetime'            => now(),
            'posted_by'           => auth()->id(),
            'amount'              => $invoice->shipment_collection->actual_cost,
            'vat'                 => $invoice->shipment_collection->actual_vat,
            'total'               => $invoice->amount,
            'dr'                  => $invoice->amount, // debit AR
            'cr'                  => 0,
            'type_of_transaction' => 'invoice',
        ]);

        // 3. Update AR Main balance
        $arMain->increment('balance', $invoice->amount);
        $arMain->increment('current', $invoice->amount);

        // 4. Mark Invoice as Posted
        $invoice->update(['status' => 'posted']);

        return redirect()->back()->with('success', "Invoice {$invoice->invoice_no} posted successfully.");
    }

    public function client_statement($id)
    {
        $invoice = Invoice::with(['client', 'shipment_collection'])->findOrFail($id);

        // Get all AR transactions for this client
        $transactions = AccountsReceivableTransaction::where('client_id', $invoice->client_id)
            ->orderBy('date')
            ->get();

        return $this->renderPdfWithPageNumbers(
            'accounts.debtors.invoices.statement',
            [
                'client'       => $invoice->client,
                'invoice'      => $invoice,
                'transactions' => $transactions,
                'printedOn'    => now()->format('d-M-Y'),
            ],
            "ClientStatement-{$invoice->client->accountNo}.pdf",
            'a4',
            'portrait'
        );
    }

    public function postPayment(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'reference' => 'required|string|max:255',
            'payment_date' => 'required|date',
        ]);

        $invoice = Invoice::with('client', 'shipment_collection')->findOrFail($id);

        // 1. Create a credit transaction
        AccountsReceivableTransaction::create([
            'client_id'           => $invoice->client_id,
            'request_id'          => $invoice->shipment_collection->requestId ?? null,
            'batch_no'            => optional($invoice->loading_sheet_waybills->loading_sheet ?? null)->batch_no_formatted,
            'waybill_no'          => $invoice->shipment_collection->waybill_no,
            'reference'           => $request->reference,
            'details'             => "Payment received for Invoice {$invoice->invoice_no}",
            'date'                => $request->payment_date,
            'datetime'            => now(),
            'posted_by'           => auth()->id(),
            'amount'              => 0,
            'vat'                 => 0,
            'total'               => $request->amount,
            'dr'                  => 0,
            'cr'                  => $request->amount,
            'type_of_transaction' => 'payment',
        ]);

        // 2. Reduce balance in AR Main
        $arMain = AccountsReceivableMain::where('client_id', $invoice->client_id)->first();
        if ($arMain) {
            $arMain->decrement('balance', $request->amount);
        }

        return redirect()->back()->with('success', "Payment of Ksh {$request->amount} recorded successfully for Invoice {$invoice->invoice_no}.");
    }

}
