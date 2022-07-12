<?php

namespace Wikichua\Bliss\Listeners;

use Wikichua\Bliss\Events\SnapshotEvent;

class SnapshotListener
{
    public function __construct()
    {
        //
    }

    public function handle(SnapshotEvent $event)
    {
        $model = $event->getModel();
        $snapshot = $model->getSnapshot();
        $mode = $event->getMode();
        if ('created' != strtolower($mode) && $snapshot) {
            $changes = $model->getChanges();
            if (blank($changes)) {
                $changes = $event->getChanges();
            }
            if (! blank($changes) || 'deleted' == strtolower($mode)) {
                $data = $model->getOriginal();
                app(config('bliss.Models.Versionizer'))->create([
                    'mode' => $mode,
                    'model_class' => $model::class,
                    'model_id' => $model->id,
                    'data' => $data,
                    'changes' => $changes,
                ]);
            }
        }
    }
}
