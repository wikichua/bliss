<?php

namespace Wikichua\Bliss\Http\Livewire\Admin\Setting;

class Creating extends Component
{
    public function mount()
    {
        $this->castModelToProperty(app(config('bliss.Models.Setting'))->query());
        $this->keyvalue[] = $this->keyvalueTemplate;
    }

    public function render()
    {
        $this->authorize('create-settings');

        return view('bliss::admin.setting.create')->layout('bliss::layouts.app');
    }

    public function onSubmit()
    {
        $this->authorize('create-settings');
        $this->validate();

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
            'protected' => $this->protected ?? 0, // this must set before value
            'value' => $this->useKeyvalue ? $keyvalue : $this->value,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ];

        $model = app(config('bliss.Models.Setting'))->create($data);

        $this->alertNotify(
            message: __('Setting (:key) created.', [
                'key' => $model->key,
            ]),
            permissionString: 'read-roles',
            link: $model->readUrl,
        );

        return $this->flashStatusSession('Data created.', route('setting.list'));
    }
}
