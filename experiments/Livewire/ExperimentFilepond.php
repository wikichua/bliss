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
        if (cache()->tags(['experiment'])->has('experiment.file')) {
            $this->expFile = \Storage::url(cache()->tags(['experiment'])->get('experiment.file'));
        }
        if (cache()->tags(['experiment'])->has('experiment.files')) {
            foreach (cache()->tags(['experiment'])->get('experiment.files') as $file) {
                $this->expFiles[] = \Storage::url($file);
            }
        }
        return view('bliss::livewire.experiment.filepond')->layout('bliss::layouts.guest');
    }

    public function onSubmit()
    {
        if ($this->expFile && $this->expFile instanceOf \Livewire\TemporaryUploadedFile) {
            $expFile = $this->expFile->store('public/exp');
            cache()->tags(['experiment'])->forever('experiment.file', $expFile);
        }
        if ($this->expFiles) {
            $expFiles = [];
            if (cache()->tags(['experiment'])->has('experiment.files')) {
                $expFiles = cache()->tags(['experiment'])->get('experiment.files');
            }
            foreach ($this->expFiles as $expFile) {
                if ($expFile instanceOf \Livewire\TemporaryUploadedFile) {
                    $expFiles[] = $expFile->store('public/exp');
                }
            }
            cache()->tags(['experiment'])->forever('experiment.files', $expFiles);
        }
        $this->dispatchBrowserEvent('flash-status', ['status' => 'Done']);
    }

    public function onFlush()
    {
        cache()->tags(['experiment'])->flush();
        $this->dispatchBrowserEvent('flash-status', ['icon' => 'warning', 'status' => 'Done']);
    }
}
