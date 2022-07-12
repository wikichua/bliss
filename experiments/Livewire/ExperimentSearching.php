<?php

namespace Wikichua\Bliss\Exp\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

class ExperimentSearching extends Component
{
    use WithPagination;

    public $search;

    public function render()
    {
        $searches = null;
        $searchesInModel = null;
        if (! blank($this->search ?? '')) {
            $searches = app(config('bliss.Models.Permission'))->query()->searching($this->search ?? '')->get();
            $searchesInModel = app(config('bliss.Models.Permission'))->query()->searchInModel($this->search ?? '')->get();
        }

        return view('bliss::livewire.experiment.searching', compact('searches', 'searchesInModel'))->layout('bliss::layouts.guest');
    }
}
