<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
  /**
   * Get the timezone that should be used by default for scheduled events.
   * Don't use timezone for DST time, use UTC instead
   *
   * @return \DateTimeZone|string|null
   */
  protected function scheduleTimezone()
  {
    return 'Asia/Jakarta';
  }

  /**
   * Define the application's command schedule.
   *
   * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
   * @return void
   */
  protected function schedule(Schedule $schedule)
  {
    /** Packages Cron */
    $schedule->command('horizon:snapshot')->everyFiveMinutes()->runInBackground()->withoutOverlapping();
    $schedule->command('storage:link')->everyFiveMinutes()->runInBackground()->withoutOverlapping();
    $schedule->command('model:prune')->hourly()->runInBackground()->withoutOverlapping();
    $schedule->command('sanctum:prune-expired --hours=0')->hourly()->runInBackground()->withoutOverlapping();
    $schedule->command('queue:prune-failed')->daily()->runInBackground()->withoutOverlapping();
    $schedule->command('queue:flush')->daily()->runInBackground()->withoutOverlapping();

    /** Custom Jobs Cron */
    $schedule->job(new \App\Jobs\PruneLogDebugLevelJob)->dailyAt('00:00');
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
