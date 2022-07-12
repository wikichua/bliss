<?php

namespace Wikichua\Bliss\Http\Livewire\Admin\QueueJob;

use Livewire\Component as LivewireComponent;

abstract class Component extends LivewireComponent
{
    use \Wikichua\Bliss\Concerns\ComponentTraits;

    public $headerTitle = 'Queue Jobs';

    protected $queryString = [];

    protected $listeners = [];

    public function booted()
    {
    }

    public function onBatchDelete(array $ids = [])
    {
        $this->authorize('delete-queuejobs');
        $models = app(config('bliss.Models.QueueJob'))->query()->whereIn('id', $ids)->get();
        foreach ($models as $model) {
            $this->alertNotify(
                message: __('Queue Job (:queue) deleted.', [
                    'queue' => $model->queue,
                ]),
                permissionString: 'read-queuejobs',
                link: route('queuejob.list'),
            );
            $model->delete();
        }
        $this->flashStatusSession('Data Deleted.');
    }

    public function onDelete($id, $redirect = '')
    {
        $this->authorize('delete-queuejobs');
        $model = app(config('bliss.Models.QueueJob'))->query()->findOrFail($id);
        $model->delete();
        $this->alertNotify(
            message: __('Queue Job (:queue) deleted.', [
                'queue' => $model->queue,
            ]),
            permissionString: 'read-queuejobs',
            link: route('queuejob.list'),
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
                'Started At' => $this->model->started_at,
                'Ended At' => $this->model->ended_at,
            ];
        }
    }
}
