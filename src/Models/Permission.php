<?php

namespace Wikichua\Bliss\Models;

use Illuminate\Database\Eloquent\Model;
use Wikichua\Bliss\Casts\UserTimezone;

class Permission extends Model
{
    use \Wikichua\Bliss\Concerns\AllModelTraits;

    public $searchableFields = ['name', 'group'];

    protected $fillable = [
        'group',
        'name',
    ];

    protected $auditable = true;

    protected $snapshot = true;

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

    // roles relationship
    public function roles()
    {
        return $this->belongsToMany(config('bliss.Models.Role'));
    }

    // users relationship
    public function users()
    {
        return $this->belongsToMany(config('bliss.Models.User'));
    }

    // create permission group
    public function createGroup($group, $names = [], $user_id = '1')
    {
        foreach ($names as $name) {
            $this->create([
                'group' => $group,
                'name' => $name,
                'created_by' => auth()->check() ? auth()->id() : $user_id,
                'updated_by' => auth()->check() ? auth()->id() : $user_id,
            ]);
        }
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = str_slug($value);
    }

    public function scopeFilterName($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%");
    }

    public function scopeFilterGroup($query, $search)
    {
        return $query->whereIn('group', $search);
    }

    public function getReadUrlAttribute($value)
    {
        return $this->readUrl = isset($this->id) ? route('permission.show', $this->id) : null;
    }

    public function onCachedEvent()
    {
        $role_ids = $this->roles()->pluck('role_id');
        $user_ids = \DB::table('role_user')->distinct('user_id')->whereIn('role_id', $role_ids)->pluck('user_id');
        foreach ($user_ids as $user_id) {
            cache()->forget('permissions:'.$user_id);
        }
    }
}
