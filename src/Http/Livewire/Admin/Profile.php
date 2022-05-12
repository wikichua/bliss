<?php

namespace Wikichua\Bliss\Http\Livewire\Admin;

use Livewire\Component;
use Wikichua\Bliss\Rules\MatchCurrentPassword;

class Profile extends Component
{
    use \Wikichua\Bliss\Traits\ComponentTraits;

    public $headerTitle = 'My Profile';
    protected $queryString = [];
    protected $listeners = [];

    public $current_password;
    public $password_confirmation;

    public function mount()
    {
        $this->model = auth()->user();
        $this->castModelToProperty();
    }

    public function render()
    {
        // if ($this->avatar && is_string($this->avatar) && !$this->avatar instanceof \Illuminate\Http\UploadedFile) {
        //     $this->avatar = \Storage::url($this->avatar);
        // }
        return view('bliss::admin.profile')->layout('bliss::layouts.app');
    }

    public function getQueryString()
    {
        unset($this->queryString['take'], $this->queryString['filters'], $this->queryString['sorts'], $this->queryString['page']);
        return $this->queryString;
    }

    public function rules()
    {
        return [
            'name' => 'required',
            'email' => 'required',
            'timezone' => 'required',
        ];
    }

    public function onSaveAvatar()
    {
        $this->validate([
            'avatar' => ['required', 'mimes:jpeg,jpg,png', 'max:1024', 'dimensions:min_width=250,min_height=250'],
        ]);
        $model = $this->model;
        $data = [
            'avatar' => $this->avatar->store('public/profile'),
            'updated_by' => auth()->id(),
        ];
        $model->update($data);
        $this->flashStatusSession('Data Updated.');
        $this->emitTo('header-profile','refresh');
    }

    public function onSaveProfile()
    {
        $this->validate();
        $model = $this->model;
        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'timezone' => $this->timezone,
            'updated_by' => auth()->id(),
        ];
        $model->update($data);
        $this->flashStatusSession('Data Updated.');
        $this->emitTo('header-profile','refresh');
    }

    public function onSavePassword()
    {
        $this->current_password = \Crypt::decryptString($this->current_password);
        $this->password = \Crypt::decryptString($this->password);
        $this->password_confirmation = \Crypt::decryptString($this->password_confirmation);
        $this->validate([
            'current_password' => ['required', MatchCurrentPassword::class],
            'password' => ['required', 'confirmed'],
            'password_confirmation' => 'required',
        ]);
        $model = $this->model;
        $data = [
            'password' => bcrypt($this->password),
            'updated_by' => auth()->id(),
        ];
        $model->update($data);
        $this->flashStatusSession('Data Updated.');
    }
}
