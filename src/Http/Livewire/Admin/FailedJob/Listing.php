<?php

namespace Wikichua\Bliss\Http\Livewire\Admin\FailedJob;

class Listing extends Component
{
    use \Livewire\WithPagination;

    protected $listeners = [];
    protected $bulkActionEnabled = true;
    protected $reauthEnabled = true;

    public function mount()
    {
        $this->cols = [
            ['title' => 'Connection', 'data' => 'connection', 'sortable' => true],
            ['title' => 'Queue Name', 'data' => 'queue', 'sortable' => true],
            ['title' => 'Failed At', 'data' => 'failed_at', 'sortable' => true],
            ['title' => '', 'data' => 'actionsView'],
        ];
    }
    public function render()
    {
        $this->authorize('read-failedjobs');
        $rows = app(config('bliss.Models.FailedJob'))->query()
            ->filter($this->filters)
            ->sorting($this->sorts)
            ->paginate($this->take)
        ;
        foreach ($rows as $model) {
            $model->actionsView = view('bliss::admin.failedjob.actions', compact('model'))->render();
        }
        return view('bliss::admin.failedjob.list', compact('rows'))->layout('bliss::layouts.app');
    }
}
