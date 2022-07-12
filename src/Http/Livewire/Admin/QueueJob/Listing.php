<?php

namespace Wikichua\Bliss\Http\Livewire\Admin\QueueJob;

class Listing extends Component
{
    protected $listeners = [];

    protected $bulkActionEnabled = true;

    protected $reauthEnabled = true;

    public function mount()
    {
        $this->cols = [
            ['title' => 'Started At', 'data' => 'started_at', 'sortable' => true],
            ['title' => 'Connection', 'data' => 'connection', 'sortable' => true],
            ['title' => 'Queue Name', 'data' => 'queue', 'sortable' => true],
            ['title' => 'Status', 'data' => 'status_name', 'sortable' => true],
            ['title' => 'Ended At', 'data' => 'ended_at', 'sortable' => true],
            ['title' => '', 'data' => 'actionsView'],
        ];
    }

    public function render()
    {
        $this->authorize('read-queuejobs');
        $rows = app(config('bliss.Models.QueueJob'))->query()
            ->filter($this->filters)
            ->sorting($this->sorts)
            ->paginate($this->take);
        foreach ($rows as $model) {
            $model->actionsView = view('bliss::admin.queuejob.actions', compact('model'))->render();
        }

        return view('bliss::admin.queuejob.list', compact('rows'))->layout('bliss::layouts.app');
    }
}
