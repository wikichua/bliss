<?php

namespace Wikichua\Bliss\Http\Livewire\Admin\PersonalAccessToken;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Listing extends Component
{
    protected $listeners = [];

    protected $bulkActionEnabled = false;

    protected $reauthEnabled = true;

    protected $userModel;

    protected $query;

    public function mount(Builder|Model|int $user)
    {
        if (is_int($user)) {
            $this->userModel = app(config('bliss.Models.User'))->query()->findOrFail($user);
        } else {
            $this->userModel = $user;
        }

        $this->query = app(config('bliss.Models.PersonalAccessToken'))->query()->where('tokenable_id', $this->userModel->id);
        $this->cols = [
            ['title' => 'Name', 'data' => 'name'],
            ['title' => 'Token', 'data' => 'token'],
            ['title' => 'Abilities', 'data' => 'abilities'],
            ['title' => 'Last Used', 'data' => 'last_used_at'],
            ['title' => '', 'data' => 'actionsView'],
        ];
    }

    public function render()
    {
        $this->authorize('read-personal-access-token');
        $rows = $this->query
            // ->filter($this->filters)
            ->paginate($this->take);
        foreach ($rows as $model) {
            $model->actionsView = view('bliss::admin.personal_access_tokens.actions', compact('model'))->render();
            $model->abilities = '<pre class="text-sm text-gray-500 dark:text-gray-400">'.(is_array($model->abilities) ? json_encode($model->abilities, JSON_PRETTY_PRINT) : $model->abilities).'</pre>';
        }
        $user = $this->userModel;

        return view('bliss::admin.personal_access_tokens.list', compact('rows', 'user'))->layout('bliss::layouts.app');
    }
}
