<?php

namespace Wikichua\Bliss\Http\Livewire\Admin\Versionizer;

class Showing extends Component
{
    protected $reauthEnabled = true;

    public function mount($id)
    {
        $this->model = app(config('bliss.Models.Versionizer'))->query()->findOrFail($id);
        $this->castModelToProperty();
    }

    public function render()
    {
        $this->authorize('read-versionizers');
        $this->data = json_encode($this->data, JSON_PRETTY_PRINT);
        $this->changes = json_encode($this->changes, JSON_PRETTY_PRINT);

        return view('bliss::admin.versionizer.show')->layout('bliss::layouts.app');
    }
}
