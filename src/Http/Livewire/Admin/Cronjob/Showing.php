<?php

namespace Wikichua\Bliss\Http\Livewire\Admin\Cronjob;

class Showing extends Component
{
    protected $reauthEnabled = true;

    public function mount($id)
    {
        $this->castModelToProperty(app(config('bliss.Models.Cronjob'))->query()->findOrFail($id));
    }

    public function render()
    {
        $this->authorize('read-cronjobs');

        return view('bliss::admin.cronjob.show')->layout('bliss::layouts.app');
    }
}
