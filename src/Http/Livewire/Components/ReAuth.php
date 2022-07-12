<?php

namespace Wikichua\Bliss\Http\Livewire\Components;

use Livewire\Component;
use Wikichua\Bliss\Rules\PasswordConfirmed;

class ReAuth extends Component
{
    public $shouldConfirmPassword = false;

    public $confirmPassword;

    protected $listeners = ['checkShouldConfirmPassword'];

    public function mount()
    {
    }

    public function render()
    {
        return view('bliss::livewire.reauth');
    }

    public function onSubmit()
    {
        $this->validate();
        request()->session()->passwordConfirmed();
        $this->emit('passwordConfirmed', true);
    }

    public function checkShouldConfirmPassword()
    {
        $this->filters = [];
        $confirmedAt = time() - request()->session()->get('auth.password_confirmed_at', 0);
        $this->shouldConfirmPassword = $confirmedAt > config('auth.password_timeout', 10800);
        $this->emit('shouldConfirmPassword', $this->shouldConfirmPassword);
    }

    public function rules()
    {
        return [
            'confirmPassword' => [
                'required',
                new PasswordConfirmed,
            ],
        ];
    }
}
