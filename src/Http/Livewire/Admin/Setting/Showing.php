<?php

namespace Wikichua\Bliss\Http\Livewire\Admin\Setting;

class Showing extends Component
{
    protected $reauthEnabled = true;

    public function mount($id)
    {
        $this->model = app(config('bliss.Models.Setting'))->query()->findOrFail($id);
        $this->castModelToProperty();
        if ($this->useKeyvalue) {
            $this->value = '';
        } else {
            $this->keyvalue = [$this->keyvalueTemplate];
        }
    }

    public function render()
    {
        $this->authorize('read-settings');

        return view('bliss::admin.setting.show')->layout('bliss::layouts.app');
    }
}
