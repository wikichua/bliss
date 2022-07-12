<?php

namespace Wikichua\Bliss\Http\Livewire\Admin\User;

class Creating extends Component
{
    public function mount()
    {
        $this->castModelToProperty(app(config('bliss.Models.User'))->query());
    }

    public function render()
    {
        $this->authorize('create-users');
        $rolesOptions = $this->getRolesOptions();

        return view('bliss::admin.user.create', compact('rolesOptions'))->layout('bliss::layouts.app');
    }

    public function onSubmit()
    {
        $this->authorize('create-users');
        $rules = $this->rules() + [
            'password' => ['required', 'confirmed'],
            'password_confirmation' => 'required',
        ];
        $this->validate($rules);
        $roles = \Arr::flatten($this->roles);
        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'timezone' => $this->timezone,
            'password' => bcrypt($this->password),
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ];

        $model = app(config('bliss.Models.User'))->create($data);
        $model->roles()->sync($roles);
        $this->alertNotify(
            message: __('User (:name) created.', [
                'name' => $model->name,
            ]),
            permissionString: 'read-users',
            link: $model->readUrl,
        );

        return $this->flashStatusSession('Data created.', route('user.list'));
    }
}
