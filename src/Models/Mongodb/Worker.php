<?php

namespace Wikichua\Bliss\Models\Mongodb;

use Jenssegers\Mongodb\Eloquent\Model;
use Wikichua\Bliss\Casts\MongoDbBsonUtcDateTime as UserTimezone;

class Worker extends Model
{
    use \Wikichua\Bliss\Concerns\AllModelTraits;
    public $searchableFields = [];
    protected $auditable = false;
    protected $snapshot = false;
    const UPDATED_AT = null;

    protected $fillable = [
        'queue',
        'batch',
    ];

    protected $appends = [];

    protected $casts = [
        'created_at' => UserTimezone::class,
    ];

    protected $primaryKey = '_id';
    protected $connection = 'mongodb';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->collection = $this->getTable();
    }
}
