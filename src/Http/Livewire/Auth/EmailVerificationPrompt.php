<?php

namespace Wikichua\Bliss\Http\Livewire\Auth;

use Livewire\Component;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;

class EmailVerificationPrompt extends Component
{
    protected $user;

    public function mount(Request $request)
    {
        $this->user = $request->user();
    }

    public function render()
    {
        return $this->user->hasVerifiedEmail()
                    ? redirect()->intended(RouteServiceProvider::HOME)
                    : view('bliss::auth.verify-email')->layout('bliss::layouts.guest');
    }

    public function onSubmit(Request $request)
    {
        $this->user = $request->user();
        if ($this->user->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::HOME);
        }

        $this->user->sendEmailVerificationNotification();

        return session()->flash('status', 'verification-link-sent');
    }
}
