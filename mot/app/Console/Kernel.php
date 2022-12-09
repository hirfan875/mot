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
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
//        $schedule->command('telescope:prune')->hourly();
        $schedule->command('expire:daily-deals')->everyMinute();
        $schedule->command('expire:flash-deals')->everyMinute();
//        $schedule->command('dhl:status-tracking')->hourly();
        $schedule->command('rates:get')->dailyAt('22:00');
        $schedule->command('google:feeds')->dailyAt('23:00');
        $schedule->command('trendyol:get-products')->twiceDaily(10, 22);
        $schedule->command('trendyol:translate-products')->daily();
        $schedule->command('trendyol:update-products')->daily();
        
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
