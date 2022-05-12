<?php

namespace Wikichua\Bliss\Http\Livewire\Admin\Report;

class Showing extends Component
{
    protected $reauthEnabled = true;
    public function mount($id)
    {
        $this->castModelToProperty(app(config('bliss.Models.Report'))->query()->findOrFail($id));
    }
    public function render()
    {
        $this->authorize('read-reports');
        $reports = cache()->get('report-'.str_slug($this->model->name), function () {
            $results = [];
            foreach ($this->model->queries as $sql) {
                $results[] = array_map(function ($value) {
                    return (array) $value;
                }, \DB::select($sql));
            }
            return $results;
        });
        return view('bliss::admin.report.show', compact('reports'))->layout('bliss::layouts.app');
    }
}
