<?php

namespace Wikichua\Bliss\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

trait DynamicFillable
{
    // set fillable using db table columns
    public function getFillable()
    {
        if (
            $this::class != config('bliss.Models.User')
            && isset($this->fillable)
            && is_array($this->fillable)
            && count($this->fillable)
        ) {
            return $this->fillable;
        }

        return Cache::remember('fillable:'.$this->getTable(), (60 * 60 * 24), function () {
            return Schema::connection($this->connection)?->getColumnListing($this->getTable()) ?? [];
        });
    }
}
