<?php

namespace Wikichua\Bliss\Http\Livewire\Admin\PersonalAccessToken;

use Livewire\Component as LivewireComponent;

abstract class Component extends LivewireComponent
{
    use \Wikichua\Bliss\Traits\ComponentTraits;

    public $headerTitle = 'Personal Access Token Management';
    protected $queryString = [];
    protected $listeners = [];

    public function booted()
    {
    }

    public function onBatchDelete(array $ids = [])
    {
        $this->authorize('delete-personal-access-token');
        $models = app(config('bliss.Models.PersonalAccessToken'))->query()->whereIn('id', $ids)->get();
        foreach ($models as $model) {
            $this->alertNotify(
                message: __('Personal Access Token (:name) deleted.', [
                    'name' => $model->name,
                ]),
                permissionString: 'read-personal-access-token',
                link: route('user.personal-access-token.list', $model->tokenable_id),
            );
            $model->delete();
        }
        $this->flashStatusSession('Data Deleted.');
    }

    public function onDelete($id, $redirect = '')
    {
        $this->authorize('delete-personal-access-token');
        $model = app(config('bliss.Models.PersonalAccessToken'))->query()->findOrFail($id);
        $model->delete();
        $this->alertNotify(
            message: __('Personal Access Token (:name) deleted.', [
                'name' => $model->name,
            ]),
            permissionString: 'read-personal-access-token',
            link: route('user.personal-access-token.list', $model->tokenable_id),
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
            'name' => 'required',
        ];
    }

    protected function getInfoData()
    {
        if ($this->model ?? null) {
            $this->infoData = [
                'Created At' => $this->model->created_at,
                'Updated_At' => $this->model->updated_at,
            ];
        }
    }

    protected function getGroupAbilities($userModel)
    {
        $permissions = app(config('bliss.Models.User'))->flatPermissions($userModel->id)->groupBy('group')->all();
        if (count($permissions) <= 0) {
            $permissions = app(config('bliss.Models.Permission'))->select(['id', 'name', 'group'])->get()->groupBy('group');
        }
        $groupPermissions = [];
        foreach ($permissions as $group => $perms) {
            foreach ($perms as $perm) {
                $groupPermissions[$group][$perm->id] = 'Can ' . $perm->name;
            }
        }
        return $groupPermissions;
    }
}
