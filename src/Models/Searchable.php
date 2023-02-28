<?php

namespace Wikichua\Bliss\Models;

use Illuminate\Database\Eloquent\Model;
use Wikichua\Bliss\Casts\UserTimezone;

class Searchable extends Model
{
    use \Illuminate\Database\Eloquent\Concerns\HasUlids;
    use \Wikichua\Bliss\Concerns\AllModelTraits;

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

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        // $this->connection = 'mysql';
    }

    public function scopeFilterTags($query, $search)
    {
        if ($search == '') {
            return $query;
        }
        $search = '.*'.$search;
        $searches = searchVariants($search);

        return $query->whereRaw('`tags` RLIKE ":\.*?('.implode('|', $searches).')\.*?"');
    }
}
