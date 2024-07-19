<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('app:fetch-order-command')->weekly()->onFailure(function () {
            Log::error('FetchData command failed.');
            $this->retryJob();
        });
    }

    protected function retryJob()
    {
        // Komutu doğrudan yeniden çalıştır
        try {
            Artisan::call('app:fetch-order-command');
        } catch (\Exception $e) {
            Log::error('Retrying FetchData command failed: ' . $e->getMessage());
        }
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
