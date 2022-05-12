<?php

namespace Wikichua\Bliss\Http\Livewire\Components;

use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class HeaderProfile extends Component
{
    use AuthorizesRequests;

    protected $listeners = [
        'refresh' => '$refresh',
        'onImpersonate',
        'onLeaveImpersonate',
    ];
    public function render()
    {
        return view('bliss::livewire.header-profile');
    }
    public function onImpersonate($id)
    {
        $this->authorize('impersonate-users');
        if ($id != 1) {
            $model = app(config('bliss.Models.User'))->query()->findOrFail($id);
            auth()->user()->impersonate($model);
            return redirect()->route('dashboard')->with('status', 'Impersonated as ' . $model->name . '.');
        }
    }
    public function onLeaveImpersonate()
    {
        auth()->user()->leaveImpersonation();
        return redirect()->route('dashboard')->with('status', 'Welcome back ' . auth()->user()->name . '.');
    }
}
