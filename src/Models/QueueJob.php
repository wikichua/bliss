<?php

namespace Wikichua\Bliss\Models;

use Illuminate\Database\Eloquent\Model;
use Wikichua\Bliss\Casts\UserTimezone;

class QueueJob extends Model
{
    use \Illuminate\Database\Eloquent\Concerns\HasUlids;
    use \Wikichua\Bliss\Concerns\AllModelTraits;

    // public $timestamps = false;
    const UPDATED_AT = null;

    protected $dates = ['started_at', 'ended_at'];

    protected $fillable = [
        'uuid',
        'connection',
        'batch',
        'queue',
        'payload',
        'status',
        'created_at',
        'started_at',
        'ended_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'created_at' => UserTimezone::class,
        'started_at' => UserTimezone::class,
        'ended_at' => UserTimezone::class,
    ];

    protected $sortByCustomAttributeMaps = [
        'status_name' => 'status',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        // $this->connection = 'mysql';
    }

    public function getStatusNameAttribute($value)
    {
        return settings('queuejob_status')[$this->attributes['status']];
    }

    public function scopeFilterQueue($query, $search)
    {
        return $query->where('queue', 'like', "%{$search}%");
    }

    public function scopeFilterStatus($query, $search)
    {
        return $query->whereIn('status', $search);
    }

    public function scopeFilterStartedAt($query, $search)
    {
        $date = $this->getDateFilter($search);

        return $query->whereBetween('started_at', [
            $this->inUserTimezone($date['start_at']),
            $this->inUserTimezone($date['stop_at']),
        ]);
    }

    public function scopeFilterEndedAt($query, $search)
    {
        $date = $this->getDateFilter($search);

        return $query->whereBetween('ended_at', [
            $this->inUserTimezone($date['start_at']),
            $this->inUserTimezone($date['stop_at']),
        ]);
    }
}
