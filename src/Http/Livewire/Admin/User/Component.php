<?php

namespace Wikichua\Bliss\Http\Livewire\Admin\User;

use Livewire\Component as LivewireComponent;
use Wikichua\Bliss\Http\Requests\Admin\UserRequest;

abstract class Component extends LivewireComponent
{
    use \Wikichua\Bliss\Traits\ComponentTraits;

    public $headerTitle = 'User Management';
    protected $queryString = [];
    protected $listeners = [];

    public $roles;
    public $password_confirmation;

    public function booted()
    {
    }

    public function onBatchDelete(array $ids = [])
    {
        $this->authorize('delete-users');
        $models = app(config('bliss.Models.User'))->query()->where('id', '!=', 1)->whereIn('id', $ids)->get();
        foreach ($models as $model) {
            $this->alertNotify(
                message: __('User (:name) deleted.', [
                    'name' => $model->name,
                ]),
                permissionString: 'read-users',
                link: route('user.list'),
            );
            $model->delete();
        }
        $this->flashStatusSession('Data Deleted.');
    }

    public function onDelete($id, $redirect = '')
    {
        $this->authorize('delete-users');
        if ($id != 1) {
            $model = app(config('bliss.Models.User'))->query()->findOrFail($id);
            $model->delete();
            $this->alertNotify(
                message: __('User (:name) deleted.', [
                    'name' => $model->name,
                ]),
                permissionString: 'read-users',
                link: route('user.list'),
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
        return (new UserRequest)->rules();
    }

    public function getRolesOptions()
    {
        return app(config('bliss.Models.Role'))->pluck('name', 'id')->sortBy('name')->toArray();
    }
}
