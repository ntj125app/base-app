<?php

namespace App\Jobs;

use App\Logger\Models\ServerLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PruneLogDebugLevelJob implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct()
  {
    //
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    Log::debug('Job Executed', ['jobName' => 'PruneLogDebugLevelJob']);
    $serverLog = ServerLog::where('level', 100)->where('created_at', '<=', now()->subWeek())->get()->pluck('id');
    ServerLog::destroy($serverLog);
    Log::debug('Job Finished', ['jobName' => 'PruneLogDebugLevelJob']);
  }
}
