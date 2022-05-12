<?php

namespace Wikichua\Bliss\Http\Livewire\Admin\Cronjob;

class Creating extends Component
{
    public function mount()
    {
        $this->castModelToProperty(app(config('bliss.Models.Cronjob'))->query());
    }

    public function render()
    {
        $this->authorize('create-cronjobs');
        return view('bliss::admin.cronjob.create')->layout('bliss::layouts.app');
    }

    public function onSubmit()
    {
        $this->authorize('create-cronjobs');
        $this->validate();

        $data = [
            'name' => $this->name,
            'command' => $this->command,
            'timezone' => $this->timezone,
            'frequency' => $this->frequency,
            'status' => $this->status,
            'mode' => $this->mode,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ];

        $model = app(config('bliss.Models.Cronjob'))->create($data);

        $this->alertNotify(
            message: __('CronJob (:name) created.', [
                'name' => $model->name,
            ]),
            permissionString: 'read-cronjobs',
            link: $model->readUrl,
        );

        return $this->flashStatusSession('Data created.', route('cronjob.list'));
    }
}
