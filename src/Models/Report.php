<?php

namespace Wikichua\Bliss\Models;

use Illuminate\Database\Eloquent\Model;
use Wikichua\Bliss\Casts\UserTimezone;

class Report extends Model
{
    use \Illuminate\Database\Eloquent\Concerns\HasUlids;
    use \Illuminate\Database\Eloquent\SoftDeletes;
    use \Wikichua\Bliss\Concerns\AllModelTraits;

    protected $auditable = true;

    protected $snapshot = true;

    protected $fillable = [
        'created_by',
        'updated_by',
        'name',
        'queries',
        'status',
        'cache_ttl',
        'generated_at',
        'next_generate_at',
    ];

    protected $appends = [
        'status_name',
    ];

    protected $searchableFields = [];

    protected $casts = [
        'queries' => 'array',
        'created_at' => UserTimezone::class,
        'updated_at' => UserTimezone::class,
        'deleted_at' => UserTimezone::class,
        'generated_at' => UserTimezone::class,
        'next_generate_at' => UserTimezone::class,
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        // $this->connection = 'mysql';
    }

    public function getStatusNameAttribute($value)
    {
        return settings('report_status')[$this->attributes['status']];
    }

    public function scopeFilterName($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%");
    }

    public function scopeFilterStatus($query, $search)
    {
        return $query->where('status', $search);
    }

    public function getGeneratedAtAttribute($value)
    {
        return $this->inUserTimezone($value);
    }

    public function getNextGenerateAtAttribute($value)
    {
        return $this->inUserTimezone($value);
    }

    public function getReadUrlAttribute($value)
    {
        return $this->readUrl = isset($this->id) ? route('report.show', $this->id) : null;
    }
}
