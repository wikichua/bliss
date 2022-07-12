<?php

namespace Wikichua\Bliss\Concerns;

trait AllModelTraits
{
    use \Wikichua\Bliss\Concerns\ModelScopes;
    use \Wikichua\Bliss\Concerns\Searchable;
    use \Wikichua\Bliss\Concerns\DynamicFillable;
    use \Wikichua\Bliss\Concerns\UserTimezone;
    use \Wikichua\Bliss\Concerns\HybridRelations;

    protected static $opendns;

    protected static function bootAllModelTraits()
    {
        static::$opendns = blank(static::$opendns) ?? opendns();
        static::saved(
            function ($model) {
                $onWhichEvent = $model->wasRecentlyCreated ? 'onCreatedEvent' : 'onUpdatedEvent';
                $model->fireEvents([$onWhichEvent, 'onCachedEvent']);
            }
        );
        static::deleted(
            function ($model) {
                $model->fireEvents(['onDeletedEvent', 'onCachedEvent']);
            }
        );
    }

    public function setReadUrlAttribute($value)
    {
    }

    protected function fireEvents(array $methods): void
    {
        foreach ($methods as $method) {
            if (method_exists($this, $method)) {
                call_user_func_array([$this, $method], [$this]);
            }
        }
    }

    public function creator()
    {
        return $this->belongsTo(config('bliss.Models.User'), 'created_by', 'id');
    }

    public function modifier()
    {
        return $this->belongsTo(config('bliss.Models.User'), 'updated_by', 'id');
    }

    public function getSnapshot()
    {
        return $this->snapshot ?? false;
    }

    public function getAuditable()
    {
        return $this->auditable ?? false;
    }

    public function getAuditName()
    {
        return $this->auditName ?? basename(str_replace('\\', '/', $this::class));
    }
}
