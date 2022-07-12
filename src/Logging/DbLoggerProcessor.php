<?php

namespace Wikichua\Bliss\Logging;

class DbLoggerProcessor
{
    public function __invoke(array $record): array
    {
        $record['extra'] = [
            'user_id' => auth()->user()?->id ?? null,
        ];

        return $record;
    }
}
