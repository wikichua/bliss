<?php

namespace Wikichua\Bliss\Concerns;

use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

trait ModelScopes
{
    public function scopeFilter($query, $searches)
    {
        if (count($searches)) {
            foreach ($searches as $field => $search) {
                if ((is_array($search) && count($search)) || '' != $search) {
                    $query->where(function ($query) use ($search, $field) {
                        $method = Str::camel('scopeFilter_'.$field);
                        $scope = Str::camel('filter_'.$field);
                        if (method_exists($this, $method)) {
                            $query->{$scope}($search);
                        } else {
                            if (!is_array($search)) {
                                try {
                                    $query->filterGenericDate($field, $search);
                                } catch (\Carbon\Exceptions\InvalidFormatException $e) {
                                    $query->filterGenericLike($field, $search);
                                }
                            } else {
                                $query->filterGenericIn($field, $search);
                            }
                        }
                    });
                }
            }
        }

        return $query;
    }

    public function scopeSorting($query, $sorts)
    {
        if (!empty($sorts)) {
            foreach ($sorts as $sortBy => $sortDirection) {
                $query->when($sortBy, function ($query) use ($sortDirection, $sortBy) {
                    $method = Str::camel('scopeSortBy_'.$sortBy);
                    $scope = Str::camel('sortBy_'.$sortBy);
                    if (isset($this->sortByCustomAttributeMaps)){
                        $sortBy = $this->sortByCustomAttributeMaps[$sortBy] ?? $sortBy;
                    } elseif (method_exists($this, $method)) {
                        return $query->{$scope}($sortDirection);
                    }
                    return $query->orderBy($sortBy, $sortDirection);
                });
            }
        }

        return $query;
    }

    public function scopeSortByStatusName($query, $sortDirection)
    {
        return $query->orderBy('status', $sortDirection);
    }

    public function scopeWhereDateInBetween($query, $startDate, $value, $endDate)
    {
        return $query->where($startDate, '<=', $value)->where($endDate, '>=', $value);
    }

    public function getDateFilter($search)
    {
        if (Str::contains($search, [' to ', ' - '])) { // date range
            $search = Str::contains($search, [' to ']) ? explode(' to ', $search) : explode(' - ', $search);
            $start_at = Carbon::parse($search[0])->format('Y-m-d 00:00:00');
            $stop_at = Carbon::parse($search[1])->addDay()->format('Y-m-d 00:00:00');
        } else { // single date
            $start_at = Carbon::parse($search)->format('Y-m-d 00:00:00');
            $stop_at = Carbon::parse($search)->addDay()->format('Y-m-d 00:00:00');
        }

        return compact('start_at', 'stop_at');
    }

    public function scopeFilterCreatedAt($query, $search)
    {
        $date = $this->getDateFilter($search);

        return $query->whereBetween('created_at', [
            $this->inUserTimezone($date['start_at']),
            $this->inUserTimezone($date['stop_at']),
        ]);
    }

    public function scopeFilterUpdatedAt($query, $search)
    {
        $date = $this->getDateFilter($search);

        return $query->whereBetween('updated_at', [
            $this->inUserTimezone($date['start_at']),
            $this->inUserTimezone($date['stop_at']),
        ]);
    }

    public function scopeFilterDeletedAt($query, $search)
    {
        $date = $this->getDateFilter($search);

        return $query->whereBetween('deleted_at', [
            $this->inUserTimezone($date['start_at']),
            $this->inUserTimezone($date['stop_at']),
        ]);
    }

    public function scopeFilterGenericLike($query, $field , $search)
    {
        return $query->where($field, 'like', "%{$search}%");
    }

    public function scopeFilterGenericIn($query, $field , $search)
    {
        return $query->whereIn($field, $search);
    }

    public function scopeFilterGenericDate($query, $field,  $search)
    {
        $date = $this->getDateFilter($search);

        return $query->whereBetween($field, [
            $this->inUserTimezone($date['start_at']),
            $this->inUserTimezone($date['stop_at']),
        ]);
    }
}
