<?php

namespace Wikichua\Bliss\Logging;

use Monolog\Logger;
use Wikichua\Bliss\Logging\DbLoggerHandler;
use Wikichua\Bliss\Logging\DbLoggerProcessor;

class DbLogger
{
    public function __invoke(array $config): Logger
    {
        $handlers = [new DbLoggerHandler()];
        $processors = [new DbLoggerProcessor()];
        $logger = new Logger('', $handlers, $processors);
        return $logger;
    }
}
