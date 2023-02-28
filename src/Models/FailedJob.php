<?php

namespace Wikichua\Bliss\Models;

use Illuminate\Database\Eloquent\Model;
use Wikichua\Bliss\Casts\UserTimezone;

class FailedJob extends Model
{
    use \Illuminate\Database\Eloquent\Concerns\HasUlids;
    use \Wikichua\Bliss\Concerns\AllModelTraits;

    public $timestamps = false;

    protected $fillable = [
        'uuid',
        'connection',
        'batch',
        'queue',
        'payload',
        'exception',
        'failed_at',
    ];

    protected $casts = [
        'failed_at' => UserTimezone::class,
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        // $this->connection = 'mysql';
    }

    public function scopeFilterQueue($query, $search)
    {
        return $query->where('queue', 'like', "%{$search}%");
    }

    public function scopeFilterException($query, $search)
    {
        return $query->where('exception', 'like', "%{$search}%");
    }

    public function scopeFilterFailedAt($query, $search)
    {
        $date = $this->getDateFilter($search);

        return $query->whereBetween('failed_at', [
            $this->inUserTimezone($date['start_at']),
            $this->inUserTimezone($date['stop_at']),
        ]);
    }

    public function getReadUrlAttribute($value)
    {
        return $this->readUrl = isset($this->id) ? route('failedjob.show', $this->id) : null;
    }
}
