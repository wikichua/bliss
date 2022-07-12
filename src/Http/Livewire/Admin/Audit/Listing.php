<?php

namespace Wikichua\Bliss\Http\Livewire\Admin\Audit;

class Listing extends Component
{
    protected $listeners = [];

    protected $bulkActionEnabled = true;

    protected $reauthEnabled = true;

    public function mount()
    {
        $this->cols = [
            ['title' => 'Created At', 'data' => 'created_at', 'sortable' => true],
            ['title' => 'User', 'data' => 'user.name', 'sortable' => true],
            ['title' => 'Model ID', 'data' => 'model_id', 'sortable' => true],
            ['title' => 'Model', 'data' => 'model_class', 'sortable' => true],
            ['title' => 'Message', 'data' => 'message'],
            ['title' => '', 'data' => 'actionsView'],
        ];
    }

    public function render()
    {
        $this->authorize('read-audits');
        $rows = app(config('bliss.Models.Audit'))->query()
            ->with(['user'])
            ->filter($this->filters)
            ->sorting($this->sorts)
            ->paginate($this->take);
        foreach ($rows as $model) {
            $model->actionsView = view('bliss::admin.audit.actions', compact('model'))->render();
        }

        return view('bliss::admin.audit.list', compact('rows'))->layout('bliss::layouts.app');
    }
}
