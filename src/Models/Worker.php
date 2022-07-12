<?php

namespace Wikichua\Bliss\Models;

use Illuminate\Database\Eloquent\Model;
use Wikichua\Bliss\Casts\UserTimezone;

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
        'attempted',
    ];

    protected $appends = [];

    protected $casts = [
        'created_at' => UserTimezone::class,
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        // $this->connection = 'mysql';
    }
}
