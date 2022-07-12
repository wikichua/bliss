<?php

namespace Wikichua\Bliss\Http\Livewire\Admin\Role;

class Creating extends Component
{
    public function mount()
    {
        $this->castModelToProperty(app(config('bliss.Models.Role'))->query());
    }

    public function render()
    {
        $this->authorize('create-roles');
        $groupPermissions = $this->getGroupPermissions();

        return view('bliss::admin.role.create', compact('groupPermissions'))->layout('bliss::layouts.app');
    }

    public function onSubmit()
    {
        $this->authorize('create-roles');
        $this->validate();
        $permissions = \Arr::flatten($this->permissions);
        $data = [
            'name' => $this->name,
            // 'admin' => $this->admin,
            'admin' => 0,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ];

        $model = app(config('bliss.Models.Role'))->create($data);
        $model->permissions()->sync($permissions);
        $this->alertNotify(
            message: __('Role (:name) created.', [
                'name' => $model->name,
            ]),
            permissionString: 'read-roles',
            link: $model->readUrl,
        );

        return $this->flashStatusSession('Data created.', route('role.list'));
    }
}
