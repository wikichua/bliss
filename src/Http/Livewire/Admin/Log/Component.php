<?php

namespace Wikichua\Bliss\Http\Livewire\Admin\Log;

use Illuminate\Support\Facades\Gate;
use Livewire\Component as LivewireComponent;

abstract class Component extends LivewireComponent
{
    use \Wikichua\Bliss\Concerns\ComponentTraits;

    public $headerTitle = 'Logs';

    protected $queryString = [];

    protected $listeners = [];

    public function booted()
    {
        Gate::allowIf(fn () => config('logging.default', 'db') == 'db' || auth()->user()->isAdmin);
    }

    public function onBatchDelete(array $ids = [])
    {
        $this->authorize('delete-logs');
        $models = app(config('bliss.Models.Log'))->query()->whereIn('id', $ids)->get();
        foreach ($models as $model) {
            $this->alertNotify(
                message: __('Log (:message) deleted.', [
                    'message' => str($model->message)->words(5),
                ]),
                permissionString: 'read-logs',
                link: route('log.list'),
            );
            $model->delete();
        }
        $this->flashStatusSession('Data Deleted.');
    }

    public function onDelete($id, $redirect = '')
    {
        $this->authorize('delete-queuejobs');
        $model = app(config('bliss.Models.Log'))->query()->findOrFail($id);
        $model->delete();
        $this->alertNotify(
            message: __('Log (:message) deleted.', [
                'message' => str($model->message)->words(5),
            ]),
            permissionString: 'read-logs',
            link: route('log.list'),
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
                'Created At' => $this->model->created_at,
                'User' => $this->model->user->name,
            ];
        }
    }
}
