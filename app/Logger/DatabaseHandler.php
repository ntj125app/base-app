<?php

namespace App\Logger;

use App\Logger\Models\ServerLog;
use Monolog\Handler\AbstractProcessingHandler;

class DatabaseHandler extends AbstractProcessingHandler
{
  /**
   * @inheritDoc
   */
  protected function write(array $record): void
  {
    ServerLog::create([
      'message' => $record['message'],
      'channel' => $record['channel'],
      'level' => $record['level'],
      'level_name' => $record['level_name'],
      'datetime' => $record['datetime'],
      'context' => $record['context'],
      'extra' => $record['extra'],
    ]);
  }
}