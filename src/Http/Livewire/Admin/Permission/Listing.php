<?php

namespace Wikichua\Bliss\Http\Livewire\Admin\Permission;

class Listing extends Component
{
    protected $listeners = [];
    protected $bulkActionEnabled = true;
    protected $reauthEnabled = true;

    public function mount()
    {
        $this->cols = [
            ['title' => 'Group', 'data' => 'group', 'sortable' => true, 'td-class' => 'whitespace-nowrap'],
            ['title' => 'Permissions', 'data' => 'permissions', 'td-class' => 'whitespace-normal'],
            ['title' => '', 'data' => 'actionsView'],
        ];
    }
    public function render()
    {
        $this->authorize('read-permissions');
        $rows = app(config('bliss.Models.Permission'))->query()
            ->select([
                'group',
                \DB::raw('min(`id`) as id'),
            ])->groupBy('group')
            ->filter($this->filters)
            ->sorting($this->sorts)
            ->paginate($this->take)
        ;
        foreach ($rows as $model) {
            $model->actionsView = view('bliss::admin.permission.actions', compact('model'))->render();
            $permissions = app(config('bliss.Models.Permission'))->query()->where('group', $model->group)->pluck('name')->toArray();
            $model->permissions = view('bliss::admin.role.permissionList', compact('permissions'))->render();
        }
        $filterPermissionOptions = app(config('bliss.Models.Permission'))->query()->groupby('group')->pluck('group','group');
        return view('bliss::admin.permission.list', compact('rows', 'filterPermissionOptions'))->layout('bliss::layouts.app');
    }
}
