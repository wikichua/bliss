<?php

namespace Wikichua\Bliss\Exp\Livewire;

use Livewire\Component;
use Illuminate\Foundation\Inspiring;

class ExperimentPoll extends Component
{
    public $pollCount = 0;
    public $inspireQuote = '';

    public function render()
    {
        $this->inspireQuote = Inspiring::quote();
        $this->pollCount++;
        return view('bliss::livewire.experiment.poll')->layout('bliss::layouts.guest');
    }
}