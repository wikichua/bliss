<?php

namespace Wikichua\Bliss\Http\Livewire\Admin\Log;

class Listing extends Component
{
    protected $listeners = [];
    protected $bulkActionEnabled = true;
    protected $reauthEnabled = true;

    public function mount()
    {
        $this->cols = [
            ['title' => 'Created At', 'data' => 'created_at', 'sortable' => true],
            ['title' => 'Message', 'data' => 'message', 'sortable' => true],
            ['title' => 'Level', 'data' => 'level_name', 'sortable' => true],
            ['title' => '', 'data' => 'actionsView'],
        ];
    }
    public function render()
    {
        $this->authorize('read-logs');
        $rows = app(config('bliss.Models.Log'))->query()
            ->filter($this->filters)
            ->sorting($this->sorts)
            ->paginate($this->take)
        ;
        foreach ($rows as $model) {
            $model->actionsView = view('bliss::admin.log.actions', compact('model'))->render();
        }
        return view('bliss::admin.log.list', compact('rows'))->layout('bliss::layouts.app');
    }
}
