<?php

namespace Wikichua\Bliss\Http\Livewire\Admin\Audit;

class Showing extends Component
{
    protected $reauthEnabled = true;

    public function mount($id)
    {
        $this->castModelToProperty(app(config('bliss.Models.Audit'))->query()->with('user')->findOrFail($id));
    }

    public function render()
    {
        $this->authorize('read-audits');
        $this->data = json_encode($this->data, JSON_PRETTY_PRINT);
        $this->agents = json_encode($this->agents, JSON_PRETTY_PRINT);
        $this->iplocation = json_encode($this->iplocation, JSON_PRETTY_PRINT);

        return view('bliss::admin.audit.show')->layout('bliss::layouts.app');
    }
}
