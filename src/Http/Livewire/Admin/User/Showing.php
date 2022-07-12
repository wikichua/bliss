<?php

namespace Wikichua\Bliss\Http\Livewire\Admin\User;

class Showing extends Component
{
    protected $reauthEnabled = true;

    public function mount($id)
    {
        $this->model = app(config('bliss.Models.User'))->query()->findOrFail($id);
        $this->castModelToProperty();
        $this->roles = $this->model->roles->pluck('id', 'id');
    }

    public function render()
    {
        $this->authorize('read-users');
        $rolesOptions = $this->getRolesOptions();

        return view('bliss::admin.user.show', compact('rolesOptions'))->layout('bliss::layouts.app');
    }
}
