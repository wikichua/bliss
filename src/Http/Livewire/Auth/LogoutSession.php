<?php

namespace Wikichua\Bliss\Http\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class LogoutSession extends Component
{
    public function mount()
    {
        Auth::guard('web')->logout();

        session()->invalidate();

        session()->regenerateToken();

        return redirect('/');
    }
}
