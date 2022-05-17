<?php

namespace Wikichua\Bliss\Models;

use Illuminate\Database\Eloquent\Model;
use Wikichua\Bliss\Casts\UserTimezone;

class Alert extends Model
{
    use \Wikichua\Bliss\Traits\AllModelTraits;
    public $searchableFields = [];

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
        'icon',
        'link',
        'status',
    ];

    protected $appends = [];

    protected $casts = [
        'created_at' => UserTimezone::class,
        'updated_at' => UserTimezone::class,
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        // $this->connection = 'mysql';
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
