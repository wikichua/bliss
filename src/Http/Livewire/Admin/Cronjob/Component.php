<?php

namespace Wikichua\Bliss\Http\Livewire\Admin\Cronjob;

use Livewire\Component as LivewireComponent;
use Wikichua\Bliss\Http\Requests\Admin\CronjobRequest;

abstract class Component extends LivewireComponent
{
    use \Wikichua\Bliss\Concerns\ComponentTraits;

    public $headerTitle = 'Cronjob Management';
    protected $queryString = [];
    protected $listeners = [];

    public $modeOptions = ['art' => 'Run with php artisan', 'exec' => 'Run on shell exec'];

    public function booted()
    {
    }

    public function onBatchDelete(array $ids = [])
    {
        $this->authorize('delete-cronjobs');
        $models = app(config('bliss.Models.Cronjob'))->query()->whereIn('id', $ids)->get();
        foreach ($models as $model) {
            $this->alertNotify(
                message: __('CronJob (:name) deleted.', [
                    'name' => $model->name,
                ]),
                permissionString: 'read-cronjobs',
                link: route('cronjob.list'),
            );
            $model->delete();
        }
        $this->flashStatusSession('Data Deleted.');
    }

    public function onDelete($id, $redirect = '')
    {
        $this->authorize('delete-cronjobs');
        $model = app(config('bliss.Models.Cronjob'))->query()->findOrFail($id);
        $model->delete();
        $this->alertNotify(
            message: __('CronJob (:name) deleted.', [
                'name' => $model->name,
            ]),
            permissionString: 'read-cronjobs',
            link: route('cronjob.list'),
        );
        if ($redirect) {
            return $this->flashStatusSession('Data Deleted.', $redirect);
        } else {
            $this->flashStatusSession('Data Deleted.');
        }
    }

    public function rules()
    {
        return (new CronjobRequest)->rules();
    }
    protected function getInfoData()
    {
        if ($this->model ?? null) {
            $this->infoData = [
                'Created At' => $this->model->created_at,
                'Created By' => $this->model->creator->name,
                'Updated At' => $this->model->updated_at,
                'Updated By' => $this->model->modifier->name,
                'Output By' => json_encode($this->model->output, JSON_PRETTY_PRINT),
            ];
        }
    }
}
