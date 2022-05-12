<?php

namespace Wikichua\Bliss\Http\Livewire\Admin\User;

class Listing extends Component
{
    use \Livewire\WithPagination;

    protected $listeners = [];
    protected $bulkActionEnabled = false;
    protected $reauthEnabled = true;

    public function viewShare()
    {
        $this->bulkActionEnabled = request()->user()->can('bulk-delete-users');
        parent::viewShare();
    }

    public function mount()
    {
        $this->authorize('read-users');
        $this->cols = [
            ['title' => 'Name', 'data' => 'name', 'sortable' => true],
            ['title' => 'Email', 'data' => 'email', 'sortable' => true],
            ['title' => 'Timezone', 'data' => 'timezone', 'sortable' => true],
            ['title' => 'Roles', 'data' => 'roles_string'],
            ['title' => '', 'data' => 'actionsView'],
        ];
    }
    public function render()
    {
        $rows = app(config('bliss.Models.User'))->query()
            ->where('id', '!=', 1)
            ->filter($this->filters)
            ->sorting($this->sorts)
            ->paginate($this->take)
        ;
        foreach ($rows as $model) {
            $model->actionsView = view('bliss::admin.user.actions', compact('model'))->render();
        }
        $rolesOptions = $this->getRolesOptions();
        return view('bliss::admin.user.list', compact('rows', 'rolesOptions'))->layout('bliss::layouts.app');
    }
}
