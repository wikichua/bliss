<?php

namespace Wikichua\Bliss\Http\Livewire\Admin\Permission;

class Creating extends Component
{
    public function mount()
    {
        $this->castModelToProperty(app(config('bliss.Models.Permission'))->query());
        $this->name = collect(['']);
    }
    public function render()
    {
        $this->authorize('create-permissions');
        return view('bliss::admin.permission.create')->layout('bliss::layouts.app');
    }
    public function onSubmit()
    {
        $this->authorize('create-permissions');
        $this->validate();

        $data = [];

        foreach ($this->name as $value) {
            $data = [
                'group' => $this->group,
                'name' => str_slug($value),
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ];
            $model = app(config('bliss.Models.Permission'))->create($data);
        }

        $model = app(config('bliss.Models.Permission'))
            ->query()
            ->select([
                \DB::raw('min(`id`) as id'),
            ])
            ->where('group', $this->group)
            ->groupBy('group')
            ->first();

        $this->alertNotify(
            message: __('Permission (:group) created.', ['group' => $model->group]),
            permissionString: 'read-permissions',
            link: $model->readUrl,
        );

        return $this->flashStatusSession('Data created.', route('permission.list'));
    }
}
