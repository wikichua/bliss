<?php

namespace Wikichua\Bliss\Models\Mongodb;

use Jenssegers\Mongodb\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Wikichua\Bliss\Casts\MongoDbBsonUtcDateTime as UserTimezone;

class Alert extends Model
{
    use \Wikichua\Bliss\Concerns\AllModelTraits;
    public $searchableFields = [];

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
        'icon',
        'link',
        'status',
    ];

    protected $attributes = [
        'status' => 'U',
    ];

    protected $appends = [];

    protected $casts = [
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

    public function sender()
    {
        return $this->belongsTo(app(config('bliss.Models.User')), 'sender_id', 'id');
    }

    public function receiver()
    {
        return $this->belongsTo(app(config('bliss.Models.User')), 'receiver_id', 'id');
    }
}
