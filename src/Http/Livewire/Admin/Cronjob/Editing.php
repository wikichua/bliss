<?php

namespace Wikichua\Bliss\Http\Livewire\Admin\Cronjob;

class Editing extends Component
{
    public function mount($id)
    {
        $this->castModelToProperty(app(config('bliss.Models.Cronjob'))->query()->findOrFail($id));
    }

    public function render()
    {
        $this->authorize('update-cronjobs');

        return view('bliss::admin.cronjob.edit')->layout('bliss::layouts.app');
    }

    public function onSubmit()
    {
        $this->authorize('update-cronjobs');
        $this->validate();

        $model = $this->model;

        $data = [
            'name' => $this->name,
            'command' => $this->command,
            'timezone' => $this->timezone,
            'frequency' => $this->frequency,
            'status' => $this->status,
            'mode' => $this->mode,
            'updated_by' => auth()->id(),
        ];

        $model->update($data);

        $this->alertNotify(
            message: __('CronJob (:name) created.', [
                'name' => $model->name,
            ]),
            permissionString: 'read-cronjobs',
            link: $model->readUrl,
        );

        $this->flashStatusSession('Data Updated.');
        $this->mount($model->id);
    }
}
