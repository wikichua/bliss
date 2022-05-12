<?php

namespace Wikichua\Bliss\Http\Livewire\Auth;

use Livewire\Component;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ConfirmablePassword extends Component
{
    public $password;

    public function render()
    {
        return view('bliss::auth.confirm-password')->layout('bliss::layouts.app');
    }

    public function onSubmit(Request $request)
    {
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
