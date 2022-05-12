<?php

namespace Wikichua\Bliss\Http\Livewire\Auth;

use Livewire\Component;
use Wikichua\Bliss\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;

class ReAuthenticatedSession extends Component
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
        $request->validate([
            'password' => function ($attribute, $value, $fail) {
                if (!\Hash::check($this->password, request()->user()->password)) {
                    $fail('The '.$attribute.' is invalid.');
                }
            },
        ]);
        session()->put(str_slug(strtolower(config('app.name'))).'.reauth.last_auth', strtotime('now'));
        $url = session()->get(str_slug(strtolower(config('app.name'))).'.reauth.requested_url', '/');

        return redirect()->to($url);
    }
}
