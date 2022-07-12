<?php

namespace Wikichua\Bliss\Http\Livewire\Admin\Setting;

use Livewire\Component as LivewireComponent;

abstract class Component extends LivewireComponent
{
    use \Wikichua\Bliss\Concerns\ComponentTraits;

    public $headerTitle = 'Setting Management';

    protected $queryString = [];

    protected $listeners = [];

    public $useKeyvalue = false;

    public $keyvalueTemplate = ['key' => null, 'val' => null];

    public $keyvalue = [];

    public function booted()
    {
    }

    public function onBatchDelete(array $ids = [])
    {
        $this->authorize('delete-settings');
        $models = app(config('bliss.Models.Setting'))->query()->whereIn('id', $ids)->get();
        foreach ($models as $model) {
            $this->alertNotify(
                message: __('Setting (:key) deleted.', [
                    'key' => $model->key,
                ]),
                permissionString: 'read-roles',
                link: route('setting.list'),
            );
            $model->delete();
        }
        $this->flashStatusSession('Data Deleted.');
    }

    public function onDelete($id, $redirect = '')
    {
        $this->authorize('delete-settings');
        $model = app(config('bliss.Models.Setting'))->query()->findOrFail($id);
        $model->delete();
        $this->alertNotify(
            message: __('Setting (:key) deleted.', [
                'key' => $model->key,
            ]),
            permissionString: 'read-roles',
            link: route('setting.list'),
        );
        if ($redirect) {
            return $this->flashStatusSession('Data Deleted.', $redirect);
        } else {
            $this->flashStatusSession('Data Deleted.');
        }
    }

    public function rules()
    {
        return [
            'key' => 'required',
        ];
    }
}
