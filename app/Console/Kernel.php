<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
     $schedule->command('queue:work --rest=1')->name('queue_worker')->withoutOverlapping()->everyMinute();
     $schedule->command('discord:listen')->name('discord_listener_v4')->withoutOverlapping()->everyMinute();
     $schedule->command('fetch:royalties')->name('fetch_royalties')->withoutOverlapping(5)->everyMinute();//->sendOutputTo(storage_path('logs/job_scheduler.log'), true); 
     $schedule->command('fetch:sales')->name('fetch_sales')->withoutOverlapping(5)->everyTwoMinutes();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
