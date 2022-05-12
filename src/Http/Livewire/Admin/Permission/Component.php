<?php

namespace Wikichua\Bliss\Http\Livewire\Admin\Permission;

use Livewire\Component as LivewireComponent;
use Wikichua\Bliss\Rules\AtLeastNotEmpty;
use Wikichua\Bliss\Rules\AllFilledNoEmpty;

abstract class Component extends LivewireComponent
{
    use \Wikichua\Bliss\Traits\ComponentTraits;

    public $headerTitle = 'Permission Management';
    protected $queryString = [];
    protected $listeners = [];

    public function booted()
    {
    }

    public function onBatchDelete(array $ids = [])
    {
        $this->authorize('delete-permission');
        $models = app(config('bliss.Models.Permission'))->query()->whereIn('id', $ids)->get();

        foreach ($models as $model) {
            $this->alertNotify(
                message: __('Permission (:group) deleted.', ['group' => $model->name]),
                permissionString: 'read-permissions',
                link: route('permission.list'),
            );
            $model->delete();
        }

        $this->flashStatusSession('Data Deleted.');
    }

    public function onDelete($id, $redirect = '')
    {
        $this->authorize('delete-permission');
        $model = app(config('bliss.Models.Permission'))->query()->findOrFail($id);
        app(config('bliss.Models.Permission'))->where('group', $model->group)->delete();

        $this->alertNotify(
            message: __('Permission (:group) deleted.', ['group' => $model->group]),
            permissionString: 'read-permissions',
            link: route('permission.list'),
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
            'name' => [
                'required',
                new AtLeastNotEmpty(1),
                new AllFilledNoEmpty(),
            ],
            'group' => 'required',
        ];
    }
}
