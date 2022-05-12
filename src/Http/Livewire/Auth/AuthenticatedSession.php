<?php

namespace Wikichua\Bliss\Http\Livewire\Auth;

use Livewire\Component;
use Wikichua\Bliss\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSession extends Component
{
    public $email;
    public $password;
    public $remember = false;

    public function render()
    {
        return view('bliss::auth.login')->layout('bliss::layouts.guest');
    }

    public function onSubmit()
    {
        $this->password = \Crypt::decryptString($this->password);
        $request = new LoginRequest;

        $request->merge([
            'email' => $this->email,
            'password' => $this->password,
            'remember' => $this->remember,
        ]);

        $request->authenticate();

        session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }
}
