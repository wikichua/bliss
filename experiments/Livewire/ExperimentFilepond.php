<?php

namespace Wikichua\Bliss\Exp\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;

class ExperimentFilepond extends Component
{
    use WithFileUploads;

    public $expFile;

    public $expFiles;

    public $uploadedFiles;

    public function render()
    {
        if (cache()->has('experiment.file')) {
            $this->expFile = \Storage::url(cache()->get('experiment.file'));
        }
        if (cache()->has('experiment.files')) {
            foreach (cache()->get('experiment.files') as $file) {
                $this->expFiles[] = \Storage::url($file);
            }
        }

        return view('bliss::livewire.experiment.filepond')->layout('bliss::layouts.guest');
    }

    public function onSubmit()
    {
        if ($this->expFile && $this->expFile instanceof \Livewire\TemporaryUploadedFile) {
            $expFile = $this->expFile->store('public/exp');
            cache()->forever('experiment.file', $expFile);
        }
        if ($this->expFiles) {
            $expFiles = [];
            if (cache()->has('experiment.files')) {
                $expFiles = cache()->get('experiment.files');
            }
            foreach ($this->expFiles as $expFile) {
                if ($expFile instanceof \Livewire\TemporaryUploadedFile) {
                    $expFiles[] = $expFile->store('public/exp');
                }
            }
            cache()->forever('experiment.files', $expFiles);
        }
        $this->dispatchBrowserEvent('flash-status', ['status' => 'Done']);
    }

    public function onFlush()
    {
        cache()->forget('experiment.files');
        $this->dispatchBrowserEvent('flash-status', ['icon' => 'warning', 'status' => 'Done']);
    }
}
