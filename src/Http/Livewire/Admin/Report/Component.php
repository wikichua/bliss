<?php

namespace Wikichua\Bliss\Http\Livewire\Admin\Report;

use Livewire\Component as LivewireComponent;
use Rap2hpoutre\FastExcel\SheetCollection;
use Wikichua\Bliss\Rules\AtLeastNotEmpty;
use Wikichua\Bliss\Rules\AllFilledNoEmpty;

abstract class Component extends LivewireComponent
{
    use \Wikichua\Bliss\Traits\ComponentTraits;

    public $headerTitle = 'Report Management';
    protected $queryString = [];
    protected $listeners = [];

    public $cache_ttl = 300;

    public function booted()
    {
    }

    public function onBatchDelete(array $ids = [])
    {
        $this->authorize('delete-reports');
        $models = app(config('bliss.Models.Report'))->query()->whereIn('id', $ids)->get();
        foreach ($models as $model) {
            $this->alertNotify(
                message: __('Report (:name) deleted.', [
                    'name' => $model->name,
                ]),
                permissionString: 'read-reports',
                link: route('report.list'),
            );
            $model->delete();
        }
        $this->flashStatusSession('Data Deleted.');
    }

    public function onDelete($id, $redirect = '')
    {
        $this->authorize('delete-reports');
        $model = app(config('bliss.Models.Report'))->query()->findOrFail($id);
        $model->delete();
        $this->alertNotify(
            message: __('Report (:name) deleted.', [
                'name' => $model->name,
            ]),
            permissionString: 'read-reports',
            link: route('report.list'),
        );
        if ($redirect) {
            return $this->flashStatusSession('Data Deleted.', $redirect);
        } else {
            $this->flashStatusSession('Data Deleted.');
        }
    }

    public function rules()
    {
        return [
            'name' => 'required',
            'status' => 'required',
            'queries' => [
                new AtLeastNotEmpty(1),
                new AllFilledNoEmpty(),
            ],
        ];
    }
    protected function getInfoData()
    {
        if ($this->model ?? null) {
            $this->infoData = [
                'Created At' => $this->model->created_at,
                'Created By' => $this->model->creator->name,
                'Updated At' => $this->model->updated_at,
                'Updated By' => $this->model->modifier->name,
            ];
        }
    }

    public function onExport($id)
    {
        $model = app(config('bliss.Models.Report'))->findOrFail($id);

        $reports = cache()->get('report-'.str_slug($model->name), function () use ($model) {
            $results = [];
            foreach ($model->queries as $sql) {
                $results[] = array_map(function ($value) {
                    return (array) $value;
                }, \DB::select($sql));
            }
            return $results;
        });

        $sheets = new SheetCollection($reports);

        $this->alertNotify(
            message: __('Report (:name) exported.', [
                'name' => $model->name,
            ]),
            permissionString: 'read-reports',
            link: $model->readUrl,
        );

        return response()->streamDownload(function () use ($sheets) {
            return fastexcel()->data($sheets)->export('php://output');
        }, sprintf('%s.xlsx', \Str::studly($model->name)));
    }
}
