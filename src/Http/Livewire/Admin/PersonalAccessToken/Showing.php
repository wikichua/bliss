<?php

namespace Wikichua\Bliss\Http\Livewire\Admin\PersonalAccessToken;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Showing extends Component
{
    public $userModel;
    protected $reauthEnabled = true;

    public function mount(Builder|Model|int $user, int $id)
    {
        if (is_int($user)) {
            $this->userModel = app(config('bliss.Models.User'))->query()->findOrFail($user);
        } else {
            $this->userModel = $user;
        }
        $this->castModelToProperty(app(config('bliss.Models.PersonalAccessToken'))->query()->findOrFail($id));
        $abilities = app(config('bliss.Models.Permission'))->query()->whereIn('name', $this->model->abilities)->select(['id', 'group'])->get();
        $this->abilities = [];
        foreach ($abilities as $ability) {
            $this->abilities[camel_case(strtolower($ability->group))][$ability->id] = $ability->id;
        }
    }
    public function render()
    {
        $this->authorize('read-personal-access-token');
        $groupAbilities = $this->getGroupAbilities($this->userModel);
        return view('bliss::admin.personal_access_tokens.show', compact('groupAbilities'))->layout('bliss::layouts.app');
    }
}
