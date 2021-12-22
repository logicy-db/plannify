<?php

namespace App\Console;

use App\Models\UserInvitation;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Carbon\Carbon;

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
        // Expire user invitations if such exist
        $schedule->call(function () {
            $expiredInvitations = UserInvitation::where('expires_at', '>', Carbon::now());
            /** @var UserInvitation $expiredInvitation */
            foreach($expiredInvitations as $expiredInvitation) {
                $expiredInvitation->status = UserInvitation::EXPIRED;
            }
        })->everyMinute()->when(is_null(UserInvitation::where('expires_at', '>', Carbon::now())->first()));

        // Expire
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
