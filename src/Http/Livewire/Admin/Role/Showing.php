<?php

namespace Wikichua\Bliss\Http\Livewire\Admin\Role;

class Showing extends Component
{
    protected $reauthEnabled = true;
    public function mount($id)
    {
        $this->castModelToProperty(app(config('bliss.Models.Role'))->query()->findOrFail($id));
        $this->permissions = $this->model->permissions->pluck('id','id')->toArray();
    }
    public function render()
    {
        $this->authorize('read-roles');
        $groupPermissions = $this->getGroupPermissions();
        return view('bliss::admin.role.show', compact('groupPermissions'))->layout('bliss::layouts.app');
    }
}
