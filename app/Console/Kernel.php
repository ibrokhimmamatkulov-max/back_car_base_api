<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();

        // 30-дневный срок объявления (config listing.lifetime_days). Витрина
        // прячет просроченное сама, эта команда переводит статус в archived,
        // чтобы владелец видел это в кабинете. Требует работающий крон
        // с `php artisan schedule:run` на сервере.
        $schedule->command('listings:expire')->daily();
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
