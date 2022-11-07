<?php

namespace Wikichua\Bliss\Models;

use Illuminate\Database\Eloquent\Model;
use Wikichua\Bliss\Casts\UserTimezone;

class Role extends Model
{
    use \Illuminate\Database\Eloquent\Concerns\HasUlids;
    use \Wikichua\Bliss\Concerns\AllModelTraits;

    public $searchableFields = ['name'];

    protected $appends = ['isAdmin'];

    protected $fillable = [
        'created_by',
        'updated_by',
        'name',
        'admin',
    ];

    protected $auditable = true;

    protected $snapshot = true;

    protected $casts = [
        'created_at' => UserTimezone::class,
        'updated_at' => UserTimezone::class,
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        // $this->connection = 'mysql';
    }

    public function permissions()
    {
        return $this->belongsToMany(config('bliss.Models.Permission'));
    }

    public function users()
    {
        return $this->belongsToMany(config('bliss.Models.User'));
    }

    public function getIsAdminAttribute($value)
    {
        return $this->admin ? 'Yes' : 'No';
    }

    public function scopeFilterName($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%");
    }

    public function scopeFilterAdmin($query, $search)
    {
        if ($search == '') {
            return $query;
        }

        return $query->where('admin', $search);
    }

    public function scopeFilterPermissions($query, $search)
    {
        if ($search == '') {
            return $query;
        }

        return $query->whereHas('permissions', function ($query) use ($search) {
            $table = app(config('bliss.Models.Permission'))->getTable();

            return $query->whereIn($table.'.id', $search);
        });
    }

    public function getReadUrlAttribute($value)
    {
        if (auth()->user()->can('read-cronjobs')) {
            return $this->readUrl = isset($this->id) ? route('role.show', $this->id) : null;
        }

        return null;
    }

    public function onCachedEvent()
    {
        $user_ids = \DB::table('role_user')->distinct('user_id')->where('role_id', $this->id)->pluck('user_id');
        foreach ($user_ids as $user_id) {
            cache()->forget('permissions:'.$user_id);
        }
    }
}
