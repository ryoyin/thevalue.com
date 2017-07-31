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
        //$schedule->call('App\Http\Controllers\ImageResizeSyncController@index')->hourlyAt(55);
        //$schedule->call('App\Http\Controllers\ImageResizeSyncController@relinkArticleDescPhoto')->hourlyAt(57);

//        $schedule->call('App\Http\Controllers\ImageResizeSyncController@index')->everyMinute();
//        $schedule->call('App\Http\Controllers\ImageResizeSyncController@relinkArticleDescPhoto')->everyMinute();

        $schedule->call('App\Http\Controllers\Backend\Crawler\ChristieController@autoGetList')->hourlyAt(config('app.christie_spider_time'));

//        $schedule->call('App\Http\Controllers\Backend\Crawler\ChristieController@dbDownloadImages')->everyMinute();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
