<?php

namespace App\Jobs;

use App\Models\AccountsReceivableMain;
use App\Models\AccountsReceivableTransaction;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AgeReceivablesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
        //
    }

    public function handle(): void
    {
        $receivables = AccountsReceivableMain::where('balance', '>', 0)->get();

        foreach ($receivables as $receivable) {
            // 👇 Find the oldest transaction for this client
            $oldestTransaction = AccountsReceivableTransaction::where('client_id', $receivable->client_id)
                ->orderBy('date', 'asc')
                ->first();

            if (!$oldestTransaction) {
                continue;
            }

            $invoiceDate = Carbon::parse($oldestTransaction->date);
            $days = $invoiceDate->diffInDays(Carbon::today());

            // Reset buckets
            $receivable->current  = 0;
            $receivable->{'30_days'} = 0;
            $receivable->{'60_days'} = 0;
            $receivable->{'90_days'} = 0;

            // Place balance into correct bucket
            if ($days <= 30) {
                $receivable->current = $receivable->balance;
            } elseif ($days <= 60) {
                $receivable->{'30_days'} = $receivable->balance;
            } elseif ($days <= 90) {
                $receivable->{'60_days'} = $receivable->balance;
            } else {
                $receivable->{'90_days'} = $receivable->balance;
            }

            $receivable->save();
        }
    }
}
