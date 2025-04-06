<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\CheckStockLevels::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('stock:check')
                 ->dailyAt('08:00')
                 ->timezone('Africa/Tunisia'); // Adjust to your timezone
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}