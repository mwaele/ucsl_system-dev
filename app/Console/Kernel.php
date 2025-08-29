<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // If you make a custom Artisan command instead of a Job, register it here.
        // \App\Console\Commands\AgeAccountsReceivablesCommand::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Run the receivables aging logic daily at midnight
        $schedule->job(new \App\Jobs\AgeAccountsReceivables)->dailyAt('00:00');

        // Example: clear logs weekly
        // $schedule->command('logs:clear')->weekly();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
