<?php

namespace Wikichua\Bliss\Exp\Models;

use Illuminate\Database\Eloquent\Model;
use Wikichua\Bliss\Casts\UserTimezone;

class ExperimentKeywordsModel extends Model
{
    use \Wikichua\Bliss\Concerns\AllModelTraits;
    public $table = 'experiment_keywords_queue';

    protected $fillable = [
        'keyword'
    ];

    protected $appends = [];

    protected $casts = [
        'created_at' => UserTimezone::class,
        'updated_at' => UserTimezone::class,
    ];

    public $searchableFields = ['keyword'];
    protected $auditable = ['keyword'];
    protected $auditName = 'Keyword';
    protected $snapshot = true;


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        // $this->connection = 'mysql';
    }

}
