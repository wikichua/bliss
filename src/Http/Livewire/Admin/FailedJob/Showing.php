<?php

namespace Wikichua\Bliss\Http\Livewire\Admin\FailedJob;

class Showing extends Component
{
    protected $reauthEnabled = true;
    public function mount($id)
    {
        $this->castModelToProperty(app(config('bliss.Models.FailedJob'))->query()->findOrFail($id));
    }
    public function render()
    {
        $this->authorize('read-failedjobs');
        $this->payload = json_encode($this->payload, JSON_PRETTY_PRINT);
        return view('bliss::admin.failedjob.show')->layout('bliss::layouts.app');
    }
}
