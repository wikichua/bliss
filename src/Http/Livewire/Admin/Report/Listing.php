<?php

namespace Wikichua\Bliss\Http\Livewire\Admin\Report;

class Listing extends Component
{
    protected $listeners = [];

    protected $bulkActionEnabled = true;

    protected $reauthEnabled = true;

    public function mount()
    {
        $this->cols = [
            ['title' => 'Name', 'data' => 'name', 'sortable' => true],
            ['title' => 'Status', 'data' => 'status_name', 'sortable' => false],
            ['title' => 'Report Status', 'data' => 'cache_status', 'sortable' => false],
            ['title' => 'Last Run', 'data' => 'generated_at', 'sortable' => false],
            ['title' => 'Next Run', 'data' => 'next_generate_at', 'sortable' => false],
            ['title' => '', 'data' => 'actionsView'],
        ];
    }

    public function render()
    {
        $this->authorize('read-reports');
        $rows = app(config('bliss.Models.Report'))->query()
            ->filter($this->filters)
            ->sorting($this->sorts)
            ->fastPaginate($this->take);
        foreach ($rows as $model) {
            $model->cache_status = 'Ready';
            if (config('cache.default') != 'array') {
                $model->cache_status = false == cache()->has('report-'.str_slug($model->name)) ? 'Processing' : 'Ready';
            }
            $model->actionsView = view('bliss::admin.report.actions', compact('model'))->render();
        }

        return view('bliss::admin.report.list', compact('rows'))->layout('bliss::layouts.app');
    }

    public function onRunNow($id)
    {
        $model = app(config('bliss.Models.Report'))->findOrFail($id);
        \Artisan::call('bliss:report', [
            'name' => $model->name, '--clear' => true, '--queue' => true,
        ]);
        session()->flash('status', $model->name.' report is progressing.');
    }
}
