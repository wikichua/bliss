<?php

namespace Wikichua\Bliss\Http\Livewire\Auth;

use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Livewire\Component;

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
            $this->user->forceFill([
                'status' => 'A',
            ]);
            event(new Verified($this->user));
        }

        return redirect()->intended(RouteServiceProvider::HOME.'?verified=1');
    }

    public function render()
    {
    }
}
