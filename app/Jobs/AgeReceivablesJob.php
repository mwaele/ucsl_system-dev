<?php

namespace App\Jobs;

use App\Models\AccountsReceivableMain;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AgeReceivablesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Get all receivables with a balance > 0
        $receivables = AccountsReceivableMain::where('balance', '>', 0)->get();

        foreach ($receivables as $receivable) {
            $invoiceDate = Carbon::parse($receivable->date); // invoice date
            $days = $invoiceDate->diffInDays(Carbon::today());

            // Reset aging buckets
            $receivable->current    = 0;
            $receivable->days_30    = 0;
            $receivable->days_60    = 0;
            $receivable->days_90    = 0;
            $receivable->over_90    = 0;

            // Place balance in correct bucket
            if ($days <= 30) {
                $receivable->current = $receivable->balance;
            } elseif ($days <= 60) {
                $receivable->days_30 = $receivable->balance;
            } elseif ($days <= 90) {
                $receivable->days_60 = $receivable->balance;
            } elseif ($days <= 120) {
                $receivable->days_90 = $receivable->balance;
            } else {
                $receivable->over_90 = $receivable->balance;
            }

            $receivable->save();
        }
    }
}
