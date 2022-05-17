<?php

namespace Wikichua\Bliss\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

trait DynamicFillable
{
    // set fillable using db table columns
    public function getFillable()
    {
        if (!blank($this->fillable ?? [])) {
            return $this->fillable;
        }

        return Cache::remember('fillable:'.$this->getTable(), (60 * 60 * 24), function () {
            return Schema::connection($this->connection)?->getColumnListing($this->getTable()) ?? [];
        });
    }
}
