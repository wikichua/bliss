<?php

namespace Wikichua\Bliss\Http\Livewire\Admin\Versionizer;

class Listing extends Component
{
    use \Livewire\WithPagination;

    protected $listeners = [];
    protected $bulkActionEnabled = true;
    protected $reauthEnabled = true;

    public function mount()
    {
        $this->cols = [
            ['title' => 'Created At', 'data' => 'created_at', 'sortable' => true],
            ['title' => 'Mode', 'data' => 'mode', 'sortable' => true],
            ['title' => 'Model', 'data' => 'model_class', 'sortable' => true],
            ['title' => 'Model ID', 'data' => 'model_id', 'sortable' => true],
            ['title' => 'Changes', 'data' => 'changes_view', 'sortable' => true],
            ['title' => '', 'data' => 'actionsView'],
        ];
    }
    public function render()
    {
        $this->authorize('read-versionizers');
        $rows = app(config('bliss.Models.Versionizer'))->query()
            ->filter($this->filters)
            ->sorting($this->sorts)
            ->paginate($this->take)
        ;
        foreach ($rows as $model) {
            $model->changes_view = view('bliss::admin.versionizer.changes', compact('model'))->render();
            $model->actionsView = view('bliss::admin.versionizer.actions', compact('model'))->render();
        }
        return view('bliss::admin.versionizer.list', compact('rows'))->layout('bliss::layouts.app');
    }
}
