<?php

namespace Wikichua\Bliss\Http\Livewire\Admin\QueueJob;

class Showing extends Component
{
    protected $reauthEnabled = true;

    public function mount($id)
    {
        $this->castModelToProperty(app(config('bliss.Models.QueueJob'))->query()->findOrFail($id));
    }

    public function render()
    {
        $this->authorize('read-queuejobs');
        $this->payload = json_encode($this->payload, JSON_PRETTY_PRINT);

        return view('bliss::admin.queuejob.show')->layout('bliss::layouts.app');
    }
}
