<?php

namespace Wikichua\Bliss\Logging;

use Monolog\Formatter\NormalizerFormatter;

class DbLoggerFormatter extends NormalizerFormatter
{
    public function __construct()
    {
        parent::__construct();
    }

    public function format(array $record)
    {
        $record = parent::format($record);

        return $this->convertToDataBase($record);
    }

    protected function convertToDataBase(array $record)
    {
        $el = $record['extra'];
        $el['level'] = strtolower($record['level_name']);
        $el['message'] = $record['message'];
        if (! blank($record['context']['exception'] ?? [])) {
            $el['iteration'] = $record['context']['exception'];
        } else {
            $el['iteration'] = null;
        }

        return $el;
    }
}
