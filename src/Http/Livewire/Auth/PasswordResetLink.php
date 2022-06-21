<?php

namespace Wikichua\Bliss\Http\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Password;
use Spatie\Honeypot\Http\Livewire\Concerns\HoneypotData;
use Spatie\Honeypot\Http\Livewire\Concerns\UsesSpamProtection;

class PasswordResetLink extends Component
{
    use UsesSpamProtection;

    public $email;
    public HoneypotData $honeypotFields;

    public function mount()
    {
        $this->honeypotFields = new HoneypotData();
    }

    public function render()
    {
        return view('bliss::auth.forgot-password')->layout('bliss::layouts.guest');
    }

    public function onSubmit()
    {
        $this->protectAgainstSpam();
        $this->validate([
            'email' => ['required', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            ['email' => $this->email]
        );

        $this->reset();

        return $status == Password::RESET_LINK_SENT
                    ? session()->flash('status', __($status))
                    : $this->addError('email', __($status));
    }
}
