<?php

namespace Wikichua\Bliss\Http\Livewire\Admin\User;

use Wikichua\Bliss\Http\Requests\Admin\UserPasswordRequest;

class EditingPassword extends Component
{
    protected $reauthEnabled = true;

    public $password;

    public $password_confirmation;

    public function mount($id)
    {
        $this->castModelToProperty(app(config('bliss.Models.User'))->query()->findOrFail($id));
    }

    public function render()
    {
        $this->authorize('update-users-password');

        return view('bliss::admin.user.editPassword')->layout('bliss::layouts.app');
    }

    public function onSubmit()
    {
        $this->authorize('update-users-password');
        $this->password = \Crypt::decryptString($this->password);
        $this->password_confirmation = \Crypt::decryptString($this->password_confirmation);
        $this->validate();

        $model = $this->model;
        $data = [
            'password' => bcrypt($this->password),
            'updated_by' => auth()->id(),
        ];

        $model->update($data);
        $this->alertNotify(
            message: __('User\'s password (:name) updated.', [
                'name' => $model->name,
            ]),
            permissionString: 'read-users',
            link: $model->readUrl,
        );
        $this->flashStatusSession('Data Updated.');
        $this->mount($model->id);
    }

    public function rules()
    {
        return (new UserPasswordRequest)->rules();
    }
}
