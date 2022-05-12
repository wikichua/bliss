<?php

namespace Wikichua\Bliss\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Carbon;
use Wikichua\Bliss\Casts\UserTimezone;
use \Lab404\Impersonate\Models\Impersonate;
use \Laravel\Sanctum\HasApiTokens;
use \Wikichua\Bliss\Traits\AdminUser;
use \Wikichua\Bliss\Traits\AllModelTraits;

abstract class User extends Authenticatable
{
    use AdminUser, AllModelTraits, HasApiTokens, Impersonate;

    public $searchableFields = ['name', 'email'];

    protected $auditable = true;
    protected $snapshot = true;

    protected $appends = ['roles_string'];
    protected $casts = [
        'created_at' => UserTimezone::class,
        'updated_at' => UserTimezone::class,
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->connection = 'mysql';
    }

    public function getAvatarAttribute($value)
    {
        if ($value && is_string($value) && !$value instanceof \Illuminate\Http\UploadedFile) {
            return \Storage::url($value);
        }
        return $value;
    }

    public function getRolesStringAttribute()
    {
        return $this->roles->sortBy('name')->implode('name', ', ');
    }

    public function scopeFilterType($query, $search)
    {
        return $query->where('type', $search);
    }

    public function scopeFilterName($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%");
    }

    public function scopeFilterEmail($query, $search)
    {
        return $query->where('email', 'like', "%{$search}%");
    }

    public function scopeFilterRoles($query, $search)
    {
        if ($search == '') {
            return $query;
        }
        return $query->whereHas('roles', function ($query) use ($search) {
            $table = app(config('bliss.Models.Role'))->getTable();
            return $query->whereIn($table.'.id', $search);
        });
    }

    public function getReadUrlAttribute($value)
    {
        return $this->readUrl = isset($this->id) ? route('user.show', $this->id):null;
    }

    public function activitylogs()
    {
        return $this->hasMany(config('bliss.Models.Audit'), 'user_id', 'id')->orderBy('created_at', 'desc');
    }

    public function onCachedEvent()
    {
        cache()->forget('permissions:'.$this->id);
        // cache()->tags('permissions')->flush();
    }
}
