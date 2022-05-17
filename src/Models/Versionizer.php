<?php

namespace Wikichua\Bliss\Models;

use Illuminate\Database\Eloquent\Model;
use Wikichua\Bliss\Casts\UserTimezone;

class Versionizer extends Model
{
    use \Wikichua\Bliss\Traits\AllModelTraits;
    protected $auditable = false;

    protected $dates = ['reverted_at'];
    protected $fillable = [
        'mode',
        'model_class',
        'model_id',
        'data',
        'changes',
        'reverted_at',
        'reverted_by',
    ];

    protected $casts = [
        'data' => 'array',
        'changes' => 'array',
        'created_at' => UserTimezone::class,
        'updated_at' => UserTimezone::class,
        'reverted_at' => UserTimezone::class,
    ];

    protected $appends = [];

    protected $searchableFields = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        // $this->connection = 'mysql';
    }

    public function getRevertedAtAttribute($value)
    {
        return $this->inUserTimezone($value);
    }

    public function model()
    {
        return $this->model_class ? app($this->model_class)->find($this->model_id) : null;
    }

    public function revertor()
    {
        return $this->belongsTo(config('bliss.Models.User'), 'reverted_by', 'id');
    }

    public function scopeFilterData($query, $search)
    {
        $searches = [
            $search,
            strtolower($search),
            strtoupper($search),
            ucfirst($search),
            ucwords($search),
        ];

        return $query->whereRaw('`data` RLIKE ":\.*?('.implode('|', $searches).')\.*?"');
    }

    public function scopeFilterChanges($query, $search)
    {
        $searches = [
            $search,
            strtolower($search),
            strtoupper($search),
            ucfirst($search),
            ucwords($search),
        ];

        return $query->whereRaw('`changes` RLIKE ":\.*?('.implode('|', $searches).')\.*?"');
    }
}
