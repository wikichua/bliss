<?php

namespace Wikichua\Bliss\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Wikichua\Bliss\Events\AuditEvent;

class AuditListener
{
    public function __construct()
    {
        //
    }

    public function handle(AuditEvent $event)
    {
        $model = $event->getModel();
        $auditable = $model->getAuditable();
        $mode = $event->getMode();
        if (!\Str::contains($model::class, config('bliss.audit.exceptions')) && $auditable !== false && !blank($auditable)) {
            $name = $model->getAuditName();
            $data = $model->getAttributes();
            if (is_array($auditable)) {
                $auditable = array_unique(
                    array_merge(
                        config('bliss.audit.must_include_fields'),
                        $auditable
                    )
                );
                foreach (array_keys($data) as $key) {
                    if (!in_array($key, $auditable)) {
                        unset($data[$key]);
                    }
                }
            }
            audit($mode.' '.$name.': '.$model->id, $data, $model, opendns());
        }
    }
}
