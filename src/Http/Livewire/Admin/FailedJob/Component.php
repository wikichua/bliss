<?php

namespace Wikichua\Bliss\Http\Livewire\Admin\FailedJob;

use Livewire\Component as LivewireComponent;

abstract class Component extends LivewireComponent
{
    use \Wikichua\Bliss\Concerns\ComponentTraits;

    public $headerTitle = 'Failed Jobs';

    protected $queryString = [];

    protected $listeners = [];

    public function booted()
    {
    }

    public function onBatchDelete(array $ids = [])
    {
        $this->authorize('delete-failedjobs');
        $models = app(config('bliss.Models.FailedJob'))->query()->whereIn('id', $ids)->get();
        foreach ($models as $model) {
            $this->alertNotify(
                message: __('FailedJob (:queue) deleted.', [
                    'queue' => $model->queue,
                ]),
                permissionString: 'read-failedjobs',
                link: route('failedjob.list'),
            );
            $model->delete();
        }
        $this->flashStatusSession('Data Deleted.');
    }

    public function onDelete($id, $redirect = '')
    {
        $this->authorize('delete-failedjobs');
        $model = app(config('bliss.Models.FailedJob'))->query()->findOrFail($id);
        $model->delete();
        $this->alertNotify(
            message: __('FailedJob (:queue) deleted.', [
                'queue' => $model->queue,
            ]),
            permissionString: 'read-failedjobs',
            link: route('failedjob.list'),
        );
        if ($redirect) {
            return $this->flashStatusSession('Data Deleted.', $redirect);
        } else {
            $this->flashStatusSession('Data Deleted.');
        }
    }

    protected function getInfoData()
    {
        if ($this->model ?? null) {
            $this->infoData = [
                'Failed At' => $this->model->failed_at,
            ];
        }
    }

    public function onRetry($id)
    {
        $model = app(config('bliss.Models.FailedJob'))->query()->findOrFail($id);
        app(config('bliss.Models.Worker'))->query()->create([
            'batch' => $model->batch,
            'queue' => $model->queue,
        ]);
        \Artisan::call('queue:retry', [
            'id' => $model->uuid,
        ]);
        $this->alertNotify(
            message: __('FailedJob (:queue) set to retry.', [
                'queue' => $model->queue,
            ]),
            permissionString: 'read-failedjobs',
            link: route('failedjob.list'),
        );
    }

    public function onBatchRetry($ids)
    {
        $models = app(config('bliss.Models.FailedJob'))->query()->whereIn('id', $ids)->get();
        foreach ($models as $model) {
            app(config('bliss.Models.Worker'))->query()->create([
                'queue' => $model->queue,
            ]);
            \Artisan::call('queue:retry', [
                'id' => $model->uuid,
            ]);
            $this->alertNotify(
                message: __('FailedJob (:queue) set to retry.', [
                    'queue' => $model->queue,
                ]),
                permissionString: 'read-failedjobs',
                link: route('failedjob.list'),
            );
        }
    }
}
