<?php

namespace Wikichua\Bliss\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Wikichua\Bliss\Casts\UserTimezone;

class Cronjob extends Eloquent
{
    use \Illuminate\Database\Eloquent\Concerns\HasUlids;
    use \Illuminate\Database\Eloquent\SoftDeletes;
    use \Wikichua\Bliss\Concerns\AllModelTraits;

    protected $auditable = true;

    protected $snapshot = false;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name',
        'mode',
        'timezone',
        'command',
        'frequency',
        'status',
        'created_by',
        'updated_by',
        'output',
    ];

    protected $appends = [
        'status_name',
    ];

    protected $searchableFields = [
        'name',
    ];

    protected $casts = [
        'output' => 'array',
        'created_at' => UserTimezone::class,
        'updated_at' => UserTimezone::class,
        'deleted_at' => UserTimezone::class,
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        // $this->connection = 'mysql';
    }

    public function scopeFilterName($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%");
    }

    public function scopeFilterStatus($query, $search)
    {
        return $query->whereIn('status', $search);
    }

    public function statusName(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => settings('cronjob_status')[$this->attributes['status']] ?? 'P'
        );
    }

    public function output(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => is_array($value) ? $value : []
        );
    }

    public function readUrl(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->readUrl = isset($this->id) ? route('cronjob.show', $this->id) : null
        );
    }

    public function onCachedEvent()
    {
        cache()->forget('cronjob');
    }
}
