<?php

namespace Wikichua\Bliss\Models;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\Model;
use Str;
use Wikichua\Bliss\Casts\UserTimezone;

class Setting extends Model
{
    use \Illuminate\Database\Eloquent\Concerns\HasUlids;
    use \Wikichua\Bliss\Concerns\AllModelTraits;

    public $searchableFields = ['key'];

    protected $auditable = true;

    protected $snapshot = true;

    protected $appends = ['useKeyvalue', 'keyvalue'];

    protected $fillable = [
        'key',
        'value',
        'protected',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'created_at' => UserTimezone::class,
        'updated_at' => UserTimezone::class,
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        // $this->connection = 'mysql';
    }

    public function scopeFilterKey($query, $search)
    {
        return $query->where('key', 'like', "%{$search}%");
    }

    public function getValueAttribute($value)
    {
        if (($this->attributes['protected'] ?? 0) == 1) {
            try {
                $value = decrypt(trim($value));
            } catch (DecryptException $e) {
            } finally {
                $value = trim($value);
            }
        }
        if (json_decode($value)) {
            return json_decode($value, 1);
        }

        return $value;
    }

    public function setValueAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['value'] = json_encode($value);
        } else {
            $this->attributes['value'] = $value;
        }
        if (($this->attributes['protected'] ?? 0) == 1) {
            $this->attributes['value'] = encrypt($this->attributes['value']);
        }
    }

    public function getUseKeyvalueAttribute()
    {
        $value = $this->attributes['value'];
        if (($this->attributes['protected'] ?? 0) == 1) {
            try {
                $value = decrypt(trim($value));
            } catch (DecryptException $e) {
            } finally {
                $value = trim($value);
            }
        }
        if ($decoded = json_decode($value, 1)) {
            return is_array($decoded);
        }

        return false;
    }

    public function getKeyvalueAttribute()
    {
        $value = $this->attributes['value'];
        $fieldValue = [];
        if (($this->attributes['protected'] ?? 0) == 1) {
            try {
                $value = decrypt(trim($value));
            } catch (DecryptException $e) {
            } finally {
                $value = trim($value);
            }
        }
        if ($keyvalue = json_decode($value, 1)) {
            if (is_array($keyvalue)) {
                foreach ($keyvalue as $key => $val) {
                    $fieldValue[] = compact('key', 'val');
                }

                return $fieldValue;
            }
        }

        return $fieldValue[] = ['key' => null, 'val' => null];
    }

    public function getAllSettings()
    {
        $sets = [];
        $settings = app(config('bliss.Models.Setting'))->query()->all();
        foreach ($settings as $setting) {
            if (1 == $setting->protected) {
                $setting->value = decrypt($setting->value);
            }
            if (is_array($setting->value)) {
                foreach ($setting->value as $k => $v) {
                    $sets[$setting->key][] = [
                        'value' => $k,
                        'text' => $v,
                    ];
                }
            } else {
                $sets[$setting->key] = $setting->value;
            }
        }

        return $sets;
    }

    public function getReadUrlAttribute($value)
    {
        return $this->readUrl = isset($this->id) ? route('setting.show', $this->id) : null;
    }

    public function onCachedEvent()
    {
        cache()->forget('config-settings');
    }

    public function getKeyWords()
    {
        return Str::headline($this->key);
    }
}
