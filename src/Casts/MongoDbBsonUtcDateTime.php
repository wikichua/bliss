<?php

namespace Wikichua\Bliss\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Carbon\Carbon;

class MongoDbBsonUtcDateTime implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function get($model, string $key, $value, array $attributes)
    {
        if ($value instanceof \MongoDB\BSON\UTCDateTime) {
            $value = $value->toDateTime();
        }
        $carbon = Carbon::parse($value);
        return $carbon->toDateTimeString();
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function set($model, string $key, $value, array $attributes)
    {
        return $value;
    }
}
