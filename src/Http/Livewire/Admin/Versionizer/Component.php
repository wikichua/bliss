<?php

namespace Wikichua\Bliss\Http\Livewire\Admin\Versionizer;

use Livewire\Component as LivewireComponent;

abstract class Component extends LivewireComponent
{
    use \Wikichua\Bliss\Concerns\ComponentTraits;

    public $headerTitle = 'Versionizer';
    protected $queryString = [];
    protected $listeners = [];

    public function booted()
    {
    }

    public function onBatchDelete(array $ids = [])
    {
        $this->authorize('delete-versionizers');
        $models = app(config('bliss.Models.Versionizer'))->query()->whereIn('id', $ids)->get();
        foreach ($models as $model) {
            $this->alertNotify(
                message: __('Versionizer (:id) deleted.', [
                    'id' => $model->id,
                ]),
                permissionString: 'read-versionizers',
                link: route('versionizer.list'),
            );
            $model->delete();
        }
        $this->flashStatusSession('Data Deleted.');
    }

    public function onDelete($id, $redirect = '')
    {
        $this->authorize('delete-versionizers');
        $model = app(config('bliss.Models.Versionizer'))->query()->findOrFail($id);
        $model->delete();
        $this->alertNotify(
            message: __('Versionizer (:id) deleted.', [
                'id' => $model->id,
            ]),
            permissionString: 'read-versionizers',
            link: route('versionizer.list'),
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
                'ID' => $this->model->id,
                'Mode' => $this->model->mode,
                'Model ID' => $this->model->model_id,
                'Model Class' => $this->model->model_class,
                'Created At' => $this->model->created_at,
                'Updated At' => $this->model->updated_at,
                'Reverted At' => $this->model->reverted_at,
                'Reverted By' => $this->model->revertor->name ?? '',
            ];
        }
    }

    public function onRevert($id)
    {
        $model = app(config('bliss.Models.Versionizer'))->query()->findOrFail($id);
        $revertModel = app($model->model_class)->where('id',$model->model_id);
        $checkModel = (clone $revertModel)->first();
        if ($checkModel && 'Updated' == $model->mode) {
            $revertModel->update($model->data);
        } else {
            if (isset($model->data['deleted_at'])) {
                $revertModel->restore();
            } else {
                $revertModel = $revertModel->create($model->data);
                if (isset($model->data['id'])) {
                    $revertModel->id = $model->data['id'];
                    $revertModel->save();
                }
            }
        }
        $model->reverted_at = \Carbon\Carbon::now();
        $model->reverted_by = auth()->user()->id;
        $model->save();
        $this->alertNotify(
            message: __('Versionizer (:id) reverted.', [
                'id' => $model->id,
            ]),
            permissionString: 'read-versionizers',
            link: route('versionizer.list'),
        );
        $this->flashStatusSession('Data Reverted.');
    }
}
