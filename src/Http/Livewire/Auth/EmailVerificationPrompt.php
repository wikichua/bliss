<?php

namespace Wikichua\Bliss\Http\Livewire\Auth;

use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Livewire\Component;
use Spatie\Honeypot\Http\Livewire\Concerns\HoneypotData;
use Spatie\Honeypot\Http\Livewire\Concerns\UsesSpamProtection;

class EmailVerificationPrompt extends Component
{
    use UsesSpamProtection;

    protected $user;

    public HoneypotData $honeypotFields;

    public function mount(Request $request)
    {
        $this->user = $request->user();
        $this->honeypotFields = new HoneypotData();
    }

    public function render()
    {
        return $this->user->hasVerifiedEmail()
                    ? redirect()->intended(RouteServiceProvider::HOME)
                    : view('bliss::auth.verify-email')->layout('bliss::layouts.guest');
    }

    public function onSubmit(Request $request)
    {
        $this->protectAgainstSpam();
        $this->user = $request->user();
        if ($this->user->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::HOME);
        }

        $this->user->sendEmailVerificationNotification();

        return session()->flash('status', 'verification-link-sent');
    }
}
