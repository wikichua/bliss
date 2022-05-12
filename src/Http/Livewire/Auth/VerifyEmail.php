<?php

namespace Wikichua\Bliss\Http\Livewire\Auth;

use Livewire\Component;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class VerifyEmail extends Component
{
    protected $user;

    public function mount(EmailVerificationRequest $request, $id, $hash)
    {
        $this->user = $request->user();
        if ($this->user->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::HOME.'?verified=1');
        }

        if ($this->user->markEmailAsVerified()) {
            event(new Verified($this->user));
        }

        return redirect()->intended(RouteServiceProvider::HOME.'?verified=1');
    }

    public function render()
    {

    }
}
