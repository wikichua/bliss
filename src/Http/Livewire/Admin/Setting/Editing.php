<?php

namespace Wikichua\Bliss\Http\Livewire\Admin\Setting;

class Editing extends Component
{
    protected $reauthEnabled = true;

    public function mount($id)
    {
        $this->castModelToProperty(app(config('bliss.Models.Setting'))->query()->findOrFail($id));
        if ($this->useKeyvalue) {
            $this->value = '';
        } else {
            $this->keyvalue = [$this->keyvalueTemplate];
        }
    }

    public function render()
    {
        $this->authorize('update-settings');
        return view('bliss::admin.setting.edit')->layout('bliss::layouts.app');
    }

    public function onSubmit()
    {
        $this->authorize('update-settings');
        $this->validate();

        $model = $this->model;

        if ($this->useKeyvalue) {
            $keyvalue = [];
            collect($this->keyvalue)->map(function ($kv) use (&$keyvalue) {
                $kv = array_values($kv);
                $keyvalue[$kv[0]] = $kv[1];
            });
        }

        if (empty($this->protected) || is_null($this->protected)) {
            $this->protected = false;
        }

        $data = [
            'key' => $this->key,
            'protected' => $this->protected, // this must set before value
            'value' => $this->useKeyvalue ? $keyvalue : $this->value,
            'updated_by' => auth()->id(),
        ];

        $model->update($data);

        $this->alertNotify(
            message: __('Setting (:key) created.', [
                'key' => $model->key,
            ]),
            permissionString: 'read-roles',
            link: $model->readUrl,
        );

        $this->flashStatusSession('Data Updated.');
        $this->mount($model->id);
    }
}
