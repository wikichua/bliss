<?php

namespace Wikichua\Bliss\Observers;

use Illuminate\Database\Eloquent\Model;
use Wikichua\Bliss\Events\SnapshotEvent;

class SnapshotObserver
{
    public function saved(Model $model)
    {
        $mode = $model->wasRecentlyCreated ? 'created' : 'updated';
        SnapshotEvent::dispatch($model, $mode);
    }

    public function deleted(Model $model)
    {
        SnapshotEvent::dispatch($model, 'deleted');
    }

    public function restored(Model $model)
    {
        SnapshotEvent::dispatch($model, 'restored');
    }

    public function forceDeleted(Model $model)
    {
        SnapshotEvent::dispatch($model, 'forceDeleted');
    }
}
