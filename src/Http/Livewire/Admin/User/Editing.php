<?php

namespace Wikichua\Bliss\Http\Livewire\Admin\User;

class Editing extends Component
{
    public function mount($id)
    {
        $this->castModelToProperty(app(config('bliss.Models.User'))->query()->findOrFail($id));
        $this->roles = $this->model->roles->pluck('id', 'id');
    }

    public function render()
    {
        $this->authorize('update-users');
        $rolesOptions = $this->getRolesOptions();

        return view('bliss::admin.user.edit', compact('rolesOptions'))->layout('bliss::layouts.app');
    }

    public function onSubmit()
    {
        $this->authorize('update-users');
        $this->validate();

        $model = $this->model;
        $roles = \Arr::flatten($this->roles);
        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'timezone' => $this->timezone,
            'updated_by' => auth()->id(),
        ];

        $model->update($data);
        $model->roles()->sync($roles);
        $this->alertNotify(
            message: __('User (:name) updated.', [
                'name' => $model->name,
            ]),
            permissionString: 'read-users',
            link: $model->readUrl,
        );
        $this->flashStatusSession('Data Updated.');
        $this->mount($model->id);
    }
}
