<?php

namespace Wikichua\Bliss\Observers;

use Illuminate\Database\Eloquent\Model;
use Wikichua\Bliss\Events\SearchableEvent;

class SearchableObserver
{
    public function saved(Model $model)
    {
        if (!blank($model->getSearchableFields())) {
            $mode = $model->wasRecentlyCreated ? 'created' : 'updated';
            SearchableEvent::dispatch($model, $mode);
        }
    }

    public function deleted(Model $model)
    {
        if (!blank($model->getSearchableFields())) {
            SearchableEvent::dispatch($model, 'deleted');
        }
    }

    public function restored(Model $model)
    {
        if (!blank($model->getSearchableFields())) {
            SearchableEvent::dispatch($model, 'restored');
        }
    }

    public function forceDeleted(Model $model)
    {
        if (!blank($model->getSearchableFields())) {
            SearchableEvent::dispatch($model, 'forceDeleted');
        }
    }
}
