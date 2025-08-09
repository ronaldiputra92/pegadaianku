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
        // Jalankan pengingat jatuh tempo setiap hari jam 9 pagi
        $schedule->command('reminder:due-date')->dailyAt('09:00');
        
        // Jalankan pengecekan denda keterlambatan setiap hari jam 10 pagi
        $schedule->command('penalty:calculate')->dailyAt('10:00');
        
        // Jalankan pengingat overdue setiap hari jam 11 pagi
        $schedule->command('reminder:overdue')->dailyAt('11:00');
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