<?php

namespace App\Console;

use App\Console\Commands\CheckOffers;
use App\Console\Commands\SendReminders;
use App\Console\Commands\SendScheduledNotifications;
use Modules\Core\Console\CreateCrudes;
use Modules\Apps\Console\AppSetupCommand;
use App\Console\Commands\CreatePermission;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Spatie\WebhookClient\Models\WebhookCall;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        AppSetupCommand::class ,
        CreatePermission::class,
        SendReminders::class,
        SendScheduledNotifications::class

    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
//        $schedule->command('queue:work')->everyMinute()->withoutOverlapping();
        $schedule->command('send:notifications')->everyMinute()->withoutOverlapping();
        $schedule->command('send:reminders')->dailyAt('18:00')->withoutOverlapping();
//        $schedule->command('check:offers')->daily();
        // $schedule->command('queue:work')->hourly();
        $schedule->command('model:prune', [
            '--model' => [WebhookCall::class],
        ])->daily();
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

    protected function bootstrappers()
    {
        return array_merge(
            [\Bugsnag\BugsnagLaravel\OomBootstrapper::class],
            parent::bootstrappers(),
        );
    }
}
