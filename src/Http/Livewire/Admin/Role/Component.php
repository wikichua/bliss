<?php

namespace Wikichua\Bliss\Http\Livewire\Admin\Role;

use Livewire\Component as LivewireComponent;
use Wikichua\Bliss\Http\Requests\Admin\RoleRequest;

abstract class Component extends LivewireComponent
{
    use \Wikichua\Bliss\Concerns\ComponentTraits;

    public $headerTitle = 'Role Management';
    protected $queryString = [];
    protected $listeners = [];

    public $permissions;

    public function booted()
    {
    }

    public function onBatchDelete(array $ids = [])
    {
        $this->authorize('delete-roles');
        $models = app(config('bliss.Models.Role'))->query()->where('id', '!=', 1)->whereIn('id', $ids)->get();
        foreach ($models as $model) {
            $this->alertNotify(
                message: __('Role (:name) deleted.', [
                    'name' => $model->name,
                ]),
                permissionString: 'read-roles',
                link: route('role.list'),
            );
            $model->delete();
        }
        $this->flashStatusSession('Data Deleted.');
    }

    public function onDelete($id, $redirect = '')
    {
        $this->authorize('delete-roles');
        if ($id != 1) {
            $model = app(config('bliss.Models.Role'))->query()->findOrFail($id);
            $model->delete();
            $this->alertNotify(
                message: __('Role (:name) deleted.', [
                    'name' => $model->name,
                ]),
                permissionString: 'read-roles',
                link: route('role.list'),
            );
            if ($redirect) {
                return $this->flashStatusSession('Data Deleted.', $redirect);
            } else {
                $this->flashStatusSession('Data Deleted.');
            }
        }
    }

    public function rules()
    {
        return (new Role)->rules();
    }

    protected function getGroupPermissions()
    {
        $permissions = app(config('bliss.Models.Permission'))->select(['id', 'name', 'group'])->get()->groupBy('group');
        $groupPermissions = [];
        foreach ($permissions as $group => $perms) {
            foreach ($perms as $perm) {
                $groupPermissions[$group][$perm->id] = 'Can ' . $perm->name;
            }
        }
        return $groupPermissions;
    }
}
