<?php

namespace Wikichua\Bliss\Http\Livewire\Admin\Permission;

class Editing extends Component
{
    public $afterActionRedirectTo = '';

    public function mount($id)
    {
        $this->castModelToProperty(app(config('bliss.Models.Permission'))->query()->findOrFail($id));
        $this->name = app(config('bliss.Models.Permission'))->query()->where('group', $this->model->group)->pluck('name');
    }
    public function render()
    {
        $this->authorize('update-permissions');
        return view('bliss::admin.permission.edit')->layout('bliss::layouts.app');
    }
    public function onSubmit()
    {
        $this->authorize('update-permissions');
        $this->validate();
        $model = $this->model;

        $permissions = app(config('bliss.Models.Permission'))->where('group', $model->group)->pluck('name')->toArray();

        $input_permissions = collect($this->name)->toArray();

        // delete
        $deleted_permissions = array_diff($permissions, $input_permissions);
        app(config('bliss.Models.Permission'))->where('group', $model->group)->whereIn('name', $deleted_permissions)->delete();

        // new
        $new_permissions = array_diff($input_permissions, $permissions);
        foreach ($new_permissions as $permission) {
            app(config('bliss.Models.Permission'))->create([
                'group' => $this->group,
                'name' => str_slug(strtolower($permission)),
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);
        }
        // update
        $update_permissions = array_diff($input_permissions, array_merge($deleted_permissions, $new_permissions));
        foreach ($update_permissions as $permission) {
            app(config('bliss.Models.Permission'))->where('name', $permission)->update([
                'group' => $this->group,
                'name' => str_slug(strtolower($permission)),
                'updated_by' => auth()->id(),
            ]);
        }

        $this->alertNotify(
            message: __('Permission (:group) updated.', ['group' => $model->group]),
            permissionString: 'read-permissions',
            link: $model->readUrl,
        );

        $this->flashStatusSession('Data Updated.', $this->afterActionRedirectTo);
    }
}
