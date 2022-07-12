<?php

namespace Wikichua\Bliss\Concerns;

trait Searchable
{
    public function searchableModelAs()
    {
        return $this::class;
    }

    public function getSearchableFields()
    {
        return $this->searchableFields ?? null;
    }

    public function toSearchableFieldsArray()
    {
        $array = [];
        if (! \Str::contains($this::class, config('bliss.searchable.exceptions'))) {
            if (isset($this->searchableFields)) {
                foreach ($this->searchableFields as $field) {
                    $array[$field] = $this->attributes[$field] ?? $this->{$field};
                }
            } else {
                $array = $this->toArray();
            }
        }

        return $array;
    }

    public function scopeSearching($query, $search)
    {
        $modelIds = app(config('bliss.Models.Searchable'))
            ->where('model', $this->searchableModelAs())
            ->filterTags($search)
            ->pluck('model_id');

        return $query->whereIn('id', $modelIds);
    }

    public function scopeSearchInModel($query, $search)
    {
        $searches = searchVariants($search);

        return $query->where(function ($query) use ($searches) {
            foreach (array_keys($this->toSearchableFieldsArray()) as $field) {
                foreach ($searches as $search) {
                    $query->orWhere($field, 'like', "%$search%");
                }
            }

            return $query;
        });
    }
}
