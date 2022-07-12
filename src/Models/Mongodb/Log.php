<?php

namespace Wikichua\Bliss\Models\Mongodb;

use Jenssegers\Mongodb\Eloquent\Model;
use Wikichua\Bliss\Casts\MongoDbBsonUtcDateTime as UserTimezone;

class Log extends Model
{
    use \Wikichua\Bliss\Concerns\AllModelTraits;

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

    protected $primaryKey = '_id';

    protected $connection = 'mongodb';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->collection = $this->getTable();
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
