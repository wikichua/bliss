<?php

namespace Wikichua\Bliss\Concerns;

use Carbon\Carbon;

trait UserTimezone
{
    // convert date to user timezone
    public function inUserTimezone($value)
    {
        if (auth()->check() && $value) {
            if ($value instanceof \MongoDB\BSON\UTCDateTime) {
                $value = $value->toDateTime();
            }
            $carbon = Carbon::parse($value);
            $carbon->tz(auth()->user()->timezone);

            return $carbon->toDateTimeString();
        }

        return $value;
    }
}
