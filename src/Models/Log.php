<?php

namespace Wikichua\Bliss\Models;

use Illuminate\Database\Eloquent\Model;
use Wikichua\Bliss\Casts\UserTimezone;

class Log extends Model
{
    use \Wikichua\Bliss\Traits\AllModelTraits;
    public $searchableFields = [
        'level',
        'iteration',
        'message',
    ];

    protected $fillable = [
        'level',
        'iteration',
        'message',
        'user_id',
        'job_id',
    ];

    protected $appends = [];

    protected $casts = [
        'iteration' => 'array',
        'created_at' => UserTimezone::class,
        'updated_at' => UserTimezone::class,
    ];

    protected $sortByCustomAttributeMaps = [
        'status_name' => 'status',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        // $this->connection = 'mysql';
    }

    public function user()
    {
        return $this->belongsTo(app(config('bliss.Models.User')), 'user_id', 'id');
    }

    public function getLevelNameAttribute($value)
    {
        return settings('log_level')[$this->attributes['level']];
    }
}
