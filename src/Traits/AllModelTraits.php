<?php

namespace Wikichua\Bliss\Traits;

trait AllModelTraits
{
    use \Wikichua\Bliss\Traits\ModelScopes;
    use \Wikichua\Bliss\Traits\Searchable;
    use \Wikichua\Bliss\Traits\DynamicFillable;
    use \Wikichua\Bliss\Traits\UserTimezone;
    use \Jenssegers\Mongodb\Eloquent\HybridRelations;

    protected static $opendns;

    protected static function bootAllModelTraits()
    {
        static::$opendns = '' == trim(static::$opendns) ?? opendns();
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

    public function getSnapshot() {
        return $this->snapshot ?? false;
    }

    public function getAuditable() {
        return $this->auditable ?? false;
    }

    public function getAuditName() {
        return $this->auditName ?? basename(str_replace('\\', '/', $this::class));
    }
}
