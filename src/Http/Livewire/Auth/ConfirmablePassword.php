<?php

namespace Wikichua\Bliss\Http\Livewire\Auth;

use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Spatie\Honeypot\Http\Livewire\Concerns\HoneypotData;
use Spatie\Honeypot\Http\Livewire\Concerns\UsesSpamProtection;

class ConfirmablePassword extends Component
{
    use UsesSpamProtection;

    public $password;
    public HoneypotData $honeypotFields;

    public function mount()
    {
        $this->honeypotFields = new HoneypotData();
    }

    public function render()
    {
        return view('bliss::auth.confirm-password')->layout('bliss::layouts.app');
    }

    public function onSubmit(Request $request)
    {
        $this->protectAgainstSpam();
        $user = $request->user();
        if (! Auth::guard('web')->validate([
            'email' => $user->email,
            'password' => $this->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        session()->put('auth.password_confirmed_at', time());

        return redirect()->intended(RouteServiceProvider::HOME);
    }
}
