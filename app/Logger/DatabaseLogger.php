<?php

namespace App\Logger;

use Monolog\Logger;

class DatabaseLogger
{
    /**
     * Create a custom Monolog instance.
     *
     * @param  array  $config
     * @return \Monolog\Logger
     */
    public function __invoke(array $config)
    {
      return new Logger('database', [
        new DatabaseHandler()
      ]);
    }
}