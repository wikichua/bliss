<?php

namespace Wikichua\Bliss\Http\Livewire\Admin;

use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        return view('bliss::admin.dashboard')->layout('bliss::layouts.app');
    }
}
