<?php

namespace Wikichua\Bliss\Http\Livewire\Admin\Permission;

class Showing extends Component
{
    protected $reauthEnabled = true;

    public function mount($id)
    {
        $this->castModelToProperty(app(config('bliss.Models.Permission'))->query()->findOrFail($id));
        $this->name = app(config('bliss.Models.Permission'))->query()->where('group', $this->model->group)->pluck('name', 'id');
    }

    public function render()
    {
        $this->authorize('read-permissions');

        return view('bliss::admin.permission.show')->layout('bliss::layouts.app');
    }
}
