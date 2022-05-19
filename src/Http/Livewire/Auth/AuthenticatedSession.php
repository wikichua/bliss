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
        $relogin = request()->cookie('relogined') ?? null;
        if (!blank($relogin)) {
            [$email, $name] = json_decode($relogin, 1);
            $this->email = $email;
            $avatar = app(config('bliss.Models.User'))->query()->where('email', $email)->first()?->avatar ?? null;
            return view('bliss::auth.relogin', [
                'email' => $email,
                'name' => $name,
                'avatar' => $avatar,
            ])->layout('bliss::layouts.guest');
        }
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

        cookie()->queue('relogined', json_encode([auth()->user()->email, auth()->user()->name]), 60 * 24);

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    public function itsNotMe()
    {
        cookie()->expire('relogined');
        return redirect()->intended(RouteServiceProvider::HOME);
    }
}
