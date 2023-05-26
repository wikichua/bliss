<?php

namespace Wikichua\Bliss\Logging;

// use App\Events\LogJobEvent;
use Monolog\Formatter\FormatterInterface;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Monolog\LogRecord;

class DbLoggerHandler extends AbstractProcessingHandler
{
    public function __construct($level = Logger::DEBUG, bool $bubble = true)
    {
        parent::__construct($level, $bubble);
    }

    protected function write(LogRecord $record): void
    {
        $log = app(config('bliss.Models.Log'))->query();
        $log->create($record['formatted']);
    }

    protected function getDefaultFormatter(): FormatterInterface
    {
        return new DbLoggerFormatter();
    }
}
