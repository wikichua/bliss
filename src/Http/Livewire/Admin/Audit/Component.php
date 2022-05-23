<?php

namespace Wikichua\Bliss\Http\Livewire\Admin\Audit;

use Livewire\Component as LivewireComponent;

abstract class Component extends LivewireComponent
{
    use \Wikichua\Bliss\Concerns\ComponentTraits;

    public $headerTitle = 'Audit Logs';
    protected $queryString = [];
    protected $listeners = [];

    public function booted()
    {
    }

    public function onBatchDelete(array $ids = [])
    {
        $this->authorize('delete-audits');
        $models = app(config('bliss.Models.Audit'))->query()->whereIn('id', $ids)->get();
        foreach ($models as $model) {
            $this->alertNotify(
                message: __('Audit (:modelClass, :modelId) deleted.', [
                    'modelClass' => $model->model_class,
                    'modelId' => $model->model_id
                ]),
                permissionString: 'read-audits',
                link: route('audit.list'),
            );
            $model->delete();
        }
        $this->flashStatusSession('Data Deleted.');
    }

    public function onDelete($id, $redirect = '')
    {
        $this->authorize('delete-audits');
        $model = app(config('bliss.Models.Audit'))->query()->findOrFail($id);
        $model->delete();

        $this->alertNotify(
            message: __('Audit (:modelClass, :modelId) deleted.', [
                'modelClass' => $model->model_class,
                'modelId' => $model->model_id
            ]),
            permissionString: 'read-audits',
            link: route('audit.list'),
        );

        if ($redirect) {
            return $this->flashStatusSession('Data Deleted.', $redirect);
        } else {
            $this->flashStatusSession('Data Deleted.');
        }
    }
    protected function getInfoData()
    {
        $this->infoData = [];
    }
}
