<?php

namespace Wikichua\Bliss\Http\Livewire\Components;

use Livewire\Component;

class ReAuth extends Component
{
    public $reauthExpired = false;
    public $reauthPassword;

    protected $listeners = ['lastAuth'];

    public function mount()
    {

    }
    public function render()
    {
        return view('bliss::livewire.reauth');
    }

    public function onSubmit()
    {
        $this->validate([
            'reauthPassword' => function ($attribute, $value, $fail) {
                if (!\Hash::check(\Crypt::decryptString($this->reauthPassword), auth()->user()->password)) {
                    $fail('The '.$attribute.' is invalid.');
                }
            },
        ]);
        session()->put(str_slug(strtolower(config('app.name'))).'.reauth.last_auth', strtotime('now'));
        $this->emit('canProceedProcess', true);
    }

    public function lastAuth()
    {
        $this->filters = [];
        $this->reauthExpired = false;
        $lastAuth = time() - session()->get(str_slug(strtolower(config('app.name'))).'.reauth.last_auth', 0);
        if ($lastAuth > config('bliss.reauth.timeout', 3600)) {
            $this->reauthExpired = true;
        }
        if (!$this->reauthExpired && config('bliss.reauth.reset', true)) {
            session()->put(str_slug(strtolower(config('app.name'))).'.reauth.last_auth', strtotime('now'));
        }
        $this->emit('checkLastAuth', $this->reauthExpired);
    }
}
