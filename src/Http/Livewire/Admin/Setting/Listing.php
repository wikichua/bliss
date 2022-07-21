<?php

namespace Wikichua\Bliss\Http\Livewire\Admin\Setting;

class Listing extends Component
{
    protected $listeners = [];

    protected $bulkActionEnabled = true;

    protected $reauthEnabled = true;

    public function mount()
    {
        $this->cols = [
            ['title' => 'Key', 'data' => 'key', 'sortable' => true, 'td-class' => 'align-top'],
            ['title' => 'Value', 'data' => 'valueString'],
            ['title' => '', 'data' => 'actionsView'],
        ];
    }

    public function render()
    {
        $this->authorize('read-settings');
        $rows = app(config('bliss.Models.Setting'))->query()
            ->filter($this->filters)
            ->sorting($this->sorts)
            ->fastPaginate($this->take);
        foreach ($rows as $model) {
            $model->actionsView = view('bliss::admin.setting.actions', compact('model'))->render();
            $model->valueString = '<pre class="text-sm text-gray-500 dark:text-gray-400">'.(is_array($model->value) ? json_encode($model->value, JSON_PRETTY_PRINT) : $model->value).'</pre>';
        }

        return view('bliss::admin.setting.list', compact('rows'))->layout('bliss::layouts.app');
    }
}
