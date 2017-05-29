<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Carbon\Carbon;
use App\Mail\Abonnement;
use Illuminate\Support\Facades\DB;



class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\SendNewsletters::class,
        Commands\notify::class,
        Commands\redisub::class,
        Commands\notifylaravel::class,
        Commands\DailyNews::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        /*$schedule->command('email:newsletter')
                 ->everyMinute();
        $schedule->call(function() {
            $users = User::all();
            foreach ($users as $user) {
                if (Carbon::parse($user->abonnement->end_date)->diffInDays(Carbon::now()) < 10)
                {
                    Mail::to($user)->send(new Abonnement());
                }
            }
        })->everyMinute();*/
        //$schedule->command(DailyNews::class, ['--force'])->everyMinute();
        $schedule->command('command:SendMail')->everyMinute()->withoutOverlapping()->when(function(){
            $news = DB::table('news')->get();
            return($news->isNotEmpty());

        });
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
