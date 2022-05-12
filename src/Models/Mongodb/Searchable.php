<?php

namespace Wikichua\Bliss\Models\Mongodb;

use Jenssegers\Mongodb\Eloquent\Model;
use Wikichua\Bliss\Casts\MongoDbBsonUtcDateTime as UserTimezone;

class Searchable extends Model
{
    use \Wikichua\Bliss\Traits\AllModelTraits;

    protected $dates = [];
    protected $fillable = [
        'model',
        'model_id',
        'tags',
    ];

    protected $appends = [];

    protected $searchableFields = [];

    protected $casts = [
        'tags' => 'array',
        'created_at' => UserTimezone::class,
        'updated_at' => UserTimezone::class,
    ];

    protected $primaryKey = '_id';
    protected $connection = 'mongodb';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->collection = $this->getTable();
    }

    public function scopeFilterTags($query, $search)
    {
        if ($search == '') {
            return $query;
        }
        $searches = searchVariants($search);
        return $query->where('tags', 'regexp', '/'.implode('|', $searches).'/i');
    }
}
