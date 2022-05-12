<?php

namespace Wikichua\Bliss\Observers;

use Illuminate\Database\Eloquent\Model;
use Wikichua\Bliss\Events\AuditEvent;

class AuditObserver
{
    public function saved(Model $model)
    {
        $mode = $model->wasRecentlyCreated ? 'created' : 'updated';
        AuditEvent::dispatch($model, $mode);
    }

    public function deleted(Model $model)
    {
        AuditEvent::dispatch($model, 'deleted');
    }

    public function restored(Model $model)
    {
        AuditEvent::dispatch($model, 'restored');
    }

    public function forceDeleted(Model $model)
    {
        AuditEvent::dispatch($model, 'forceDeleted');
    }
}
