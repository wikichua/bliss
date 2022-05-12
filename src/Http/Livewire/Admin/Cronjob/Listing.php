<?php

namespace Wikichua\Bliss\Http\Livewire\Admin\Cronjob;

class Listing extends Component
{
    use \Livewire\WithPagination;

    protected $listeners = [];
    protected $bulkActionEnabled = true;
    protected $reauthEnabled = true;

    public function mount()
    {
        $this->cols = [
            ['title' => 'Name', 'data' => 'name', 'sortable' => true],
            ['title' => 'Timezone', 'data' => 'timezone', 'sortable' => true],
            ['title' => 'Frequency', 'data' => 'frequency', 'sortable' => true],
            ['title' => 'Status', 'data' => 'status_name', 'sortable' => true],
            ['title' => 'Created Date', 'data' => 'created_at', 'sortable' => true],
            ['title' => 'Last Run Date', 'data' => 'last_run_date', 'sortable' => true],
            ['title' => '', 'data' => 'actionsView'],
        ];
    }
    public function render()
    {
        $this->authorize('read-cronjobs');
        $rows = app(config('bliss.Models.Cronjob'))->query()
            ->where('mode', '!=', 'que')
            ->filter($this->filters)
            ->sorting($this->sorts)
            ->paginate($this->take)
        ;
        foreach ($rows as $model) {
            $model->actionsView = view('bliss::admin.cronjob.actions', compact('model'))->render();
        }
        return view('bliss::admin.cronjob.list', compact('rows'))->layout('bliss::layouts.app');
    }
}
