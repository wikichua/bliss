<?php

namespace Wikichua\Bliss\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Wikichua\Bliss\Casts\UserTimezone;

class Audit extends Model
{
    use \Illuminate\Database\Eloquent\Concerns\HasUlids;
    use \Wikichua\Bliss\Concerns\AllModelTraits;

    const UPDATED_AT = null;

    public $searchableFields = [];

    protected $fillable = [
        'user_id',
        'model_id',
        'model_class',
        'message',
        'data',
        'agents',
        'opendns',
        'iplocation',
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

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        // $this->connection = 'mysql';
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

    protected function data(): Attribute
    {
        return Attribute::make(
            get: function ($data) {
                $data = json_decode($data, 1);
                $masks = config('bliss.audit.masks', $this->masks);
                foreach ($masks as $key) {
                    if (isset($data[$key])) {
                        $data[$key] = '***censored***';
                    }
                }

                return $data;
            },
        );
    }
}
