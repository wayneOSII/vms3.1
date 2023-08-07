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

        // $schedule->command('academic:update')->weeklyOn(1, '01:00');
        $schedule->command('academic:update')->dailyAt('01:00');
        // 定義執行自動生成簽到記錄的任務
        // $schedule->command('app:generate-attendance')->weeklyOn(6, '01:00');
        $schedule->command('app:generate-attendance')->dailyAt('22:00');
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
