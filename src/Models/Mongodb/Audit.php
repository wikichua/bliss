<?php

namespace Wikichua\Bliss\Models\Mongodb;

use Jenssegers\Mongodb\Eloquent\Model;
use Wikichua\Bliss\Casts\MongoDbBsonUtcDateTime as UserTimezone;

class Audit extends Model
{
    use \Wikichua\Bliss\Concerns\AllModelTraits;

    const UPDATED_AT = null;
    public $searchableFields = [];
    protected $fillable = [
        'id',
        'user_id',
        'model_id',
        'model_class',
        'message',
        'data',
        'agents',
        'opendns',
        'iplocation',
        'created_at',
    ];

    protected $casts = [
        'data' => 'array',
        'agents' => 'array',
        'iplocation' => 'array',
        'created_at' => UserTimezone::class,
    ];

    protected $masks = [
        'password',
        'password_confirmation',
        'token',
    ];

    protected $primaryKey = '_id';
    protected $connection = 'mongodb';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->collection = $this->getTable();
    }

    // user relationship
    public function user()
    {
        return $this->belongsTo(config('bliss.Models.User'))->withDefault(['name' => null]);
    }

    // dynamic model
    public function model()
    {
        return $this->model_class ? app($this->model_class)->find($this->model_id) : null;
    }

    public function getDataAttribute($data)
    {
        $data = json_decode($data, 1);
        $masks = config('bliss.audit.masks', $this->masks);
        foreach ($masks as $key) {
            if (isset($data[$key])) {
                $data[$key] = '***censored***';
            }
        }

        return $data;
    }
}
