<?php

namespace Wikichua\Bliss\Http\Livewire\Auth;

use Livewire\Component;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Spatie\Honeypot\Http\Livewire\Concerns\HoneypotData;
use Spatie\Honeypot\Http\Livewire\Concerns\UsesSpamProtection;

class Register extends Component
{
    use UsesSpamProtection;

    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public HoneypotData $honeypotFields;

    public function mount()
    {
        $this->honeypotFields = new HoneypotData();
    }

    public function render()
    {
        return view('bliss::auth.register')->layout('bliss::layouts.guest');
    }

    public function onSubmit()
    {
        $this->protectAgainstSpam();
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        event(new Registered($user));

        $auth = Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
