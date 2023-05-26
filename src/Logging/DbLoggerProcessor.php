<?php

namespace Wikichua\Bliss\Logging;

use Monolog\LogRecord;

class DbLoggerProcessor
{
    public function __invoke(LogRecord $record): LogRecord
    {
        $record['extra'] = [
            'user_id' => auth()->user()?->id ?? null,
        ];

        return $record;
    }
}
