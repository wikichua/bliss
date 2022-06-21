<?php

namespace Wikichua\Bliss\Http\Livewire\Auth;

use Livewire\Component;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Spatie\Honeypot\Http\Livewire\Concerns\HoneypotData;
use Spatie\Honeypot\Http\Livewire\Concerns\UsesSpamProtection;

class NewPassword extends Component
{
    use UsesSpamProtection;

    public $email;
    public $token;
    public $password;
    public $password_confirmation;
    public HoneypotData $honeypotFields;

    public function mount(Request $request, $token)
    {
        $this->honeypotFields = new HoneypotData();
        $this->email = $request->get('email');
        $this->token = $token;
        $exist = \DB::table('password_resets')->where('token', $this->token)->count();
        if (!$exist) {
            $this->addError('token', 'Token is invalid or has been expired.');
        }
    }

    public function render()
    {
        return view('auth.reset-password')->layout('layouts.guest');
    }

    public function onSubmit()
    {
        $this->protectAgainstSpam();
        $validatedInputs = $this->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'password_confirmation' => ['required'],
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $validatedInputs,
            function ($user) {
                $user->forceFill([
                    'password' => Hash::make($this->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $status == Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('status', __($status))
                    : $this->addError('email', __($status));
    }
}
