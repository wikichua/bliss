<?php

namespace Wikichua\Bliss\Http\Livewire\Admin\Report;

class Editing extends Component
{
    public function mount($id)
    {
        $this->castModelToProperty(app(config('bliss.Models.Report'))->query()->findOrFail($id));
    }

    public function render()
    {
        $this->authorize('update-reports');

        return view('bliss::admin.report.edit')->layout('bliss::layouts.app');
    }

    public function onSubmit()
    {
        $this->authorize('update-reports');
        $this->validate();

        $model = $this->model;

        $data = [
            'name' => str_slug($this->name),
            'queries' => $this->queries,
            'status' => $this->status,
            'updated_by' => auth()->id(),
        ];

        $model->update($data);

        $this->alertNotify(
            message: __('Report (:name) updated.', [
                'name' => $model->name,
            ]),
            permissionString: 'read-reports',
            link: $model->readUrl,
        );

        $this->flashStatusSession('Data Updated.');
    }
}
