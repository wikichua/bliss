<?php

namespace Wikichua\Bliss\Http\Livewire\Admin\Log;

class Showing extends Component
{
    protected $reauthEnabled = true;

    public function mount($id)
    {
        $this->castModelToProperty(app(config('bliss.Models.Log'))->query()->findOrFail($id));
    }

    public function render()
    {
        $this->authorize('read-logs');
        $this->iteration = json_encode($this->iteration, JSON_PRETTY_PRINT);

        return view('bliss::admin.log.show')->layout('bliss::layouts.app');
    }
}
