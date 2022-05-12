<?php

namespace Wikichua\Bliss\Http\Livewire\Admin\Role;

class Editing extends Component
{
    public function mount($id)
    {
        $this->castModelToProperty(app(config('bliss.Models.Role'))->query()->findOrFail($id));
        $this->permissions = $this->model->permissions->pluck('id','id')->toArray();
    }

    public function render()
    {
        $this->authorize('update-roles');
        $groupPermissions = $this->getGroupPermissions();
        return view('bliss::admin.role.edit', compact('groupPermissions'))->layout('bliss::layouts.app');
    }

    public function onSubmit()
    {
        $this->authorize('update-roles');
        $this->validate();
        $permissions = \Arr::flatten($this->permissions);
        $model = $this->model;

        $data = [
            'name' => $this->name,
            // 'admin' => $this->admin,
            'updated_by' => auth()->id(),
        ];

        $model->update($data);
        $model->permissions()->sync($permissions);

        $this->alertNotify(
            message: __('Role (:name) updated.', [
                'name' => $model->name,
            ]),
            permissionString: 'read-roles',
            link: $model->readUrl,
        );

        $this->flashStatusSession('Data Updated.');
        $this->mount($model->id);
    }
}
