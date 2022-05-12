<?php

namespace Wikichua\Bliss\Http\Livewire\Admin\PersonalAccessToken;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Creating extends Component
{
    public $userModel;
    public $abilities;
    protected $reauthEnabled = true;

    public function mount(Builder|Model|int $user)
    {
        if (is_int($user)) {
            $this->userModel = app(config('bliss.Models.User'))->query()->findOrFail($user);
        } else {
            $this->userModel = $user;
        }
        $this->castModelToProperty($this->userModel);
        $this->name = randomWords();
    }

    public function render()
    {
        $this->authorize('create-personal-access-token');
        $groupAbilities = $this->getGroupAbilities($this->userModel);
        return view('bliss::admin.personal_access_tokens.create', compact('groupAbilities'))->layout('bliss::layouts.app');
    }

    public function onSubmit()
    {
        $this->authorize('create-personal-access-token');
        $this->validate();

        $user = $this->userModel;
        $abilities = $user->roles->contains('admin') ? ['*'] : app(config('bliss.Models.Permission'))->query()->select('name')->whereIn('id', \Arr::flatten($this->abilities))->pluck('name')->toArray();
        $tokenResult = $user->createToken($this->name, $abilities)->plainTextToken;
        $tokenResult = explode('|', $tokenResult);
        $model = app(config('bliss.Models.PersonalAccessToken'))->query()
            ->find($tokenResult[0])
        ;
        $model->plain_text_token = $tokenResult[1];
        $model->save();

        $this->alertNotify(
            message: __('Personal Access Token (:name) created.', [
                'name' => $model->name,
            ]),
            permissionString: 'read-personal-access-token',
            link: route('user.personal-access-token.list', $user->id),
        );

        return $this->flashStatusSession('Data created.', route('user.personal-access-token.list', [$user->id]));
    }
}
