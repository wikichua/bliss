<?php

namespace Wikichua\Bliss\Http\Livewire\Admin\Role;

class Listing extends Component
{
    protected $listeners = [];

    protected $bulkActionEnabled = true;

    protected $reauthEnabled = true;

    public function mount()
    {
        $this->cols = [
            ['title' => 'Name', 'data' => 'name', 'sortable' => true, 'td-class' => 'text-center whitespace-nowrap'],
            ['title' => 'Is Admin', 'data' => 'isAdmin', 'td-class' => 'text-center whitespace-nowrap'],
            ['title' => 'Permissions', 'data' => 'permissionsView', 'td-class' => 'text-left whitespace-normal'],
            ['title' => '', 'data' => 'actionsView'],
        ];
    }

    public function render()
    {
        $this->authorize('read-roles');
        $rows = app(config('bliss.Models.Role'))->query()
            ->where('id', '!=', 1)
            ->filter($this->filters)
            ->sorting($this->sorts)
            ->paginate($this->take);
        foreach ($rows as $model) {
            $model->actionsView = view('bliss::admin.role.actions', compact('model'))->render();
            $permissions = $model->permissions()->pluck('name')->toArray();
            $model->permissionsView = view('bliss::admin.role.permissionList', compact('permissions'))->render();
        }
        $filterPermissions = app(config('bliss.Models.Permission'))->query()
            ->pluck('name', 'id')
            ->map(function ($value) {
                return 'Can '.\Str::headline($value);
            });

        return view('bliss::admin.role.list', compact('rows', 'filterPermissions'))->layout('bliss::layouts.app');
    }
}
