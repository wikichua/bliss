<?php

namespace Wikichua\Bliss\Http\Livewire\Admin\Report;

class Creating extends Component
{
    public function mount()
    {
        $this->castModelToProperty(app(config('bliss.Models.Report'))->query());
    }

    public function render()
    {
        $this->authorize('create-reports');

        return view('bliss::admin.report.create')->layout('bliss::layouts.app');
    }

    public function onSubmit()
    {
        $this->authorize('create-reports');
        $this->validate();

        $data = [
            'name' => str_slug($this->name),
            'queries' => $this->queries,
            'status' => $this->status,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ];

        $model = app(config('bliss.Models.Report'))->create($data);

        $this->alertNotify(
            message: __('Report (:name) created.', [
                'name' => $model->name,
            ]),
            permissionString: 'read-reports',
            link: $model->readUrl,
        );

        return $this->flashStatusSession('Data created.', route('report.list'));
    }
}
