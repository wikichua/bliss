<?php

namespace Wikichua\Bliss\Http\Livewire\Components;

use Livewire\Component;

class AlertNotification extends Component
{
    protected $listeners = [
        'refresh' => '$refresh',
    ];

    public function render()
    {
        $alerts = app(config('bliss.Models.Alert'))->query()
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'desc')
            ->where('receiver_id', auth()->id())
            ->take(25)
            ->get();
        $ids = $alerts->pluck('id');
        $unread_count = $alerts->where('status', 'U')->count();

        return view('bliss::livewire.alert-notification', compact('ids', 'alerts', 'unread_count'));
    }

    public function onRead(array $ids = [])
    {
        $modelKeyName = app(config('bliss.Models.Alert'))->getModel()->getKeyName();
        $alerts = app(config('bliss.Models.Alert'))->query()
            ->where('receiver_id', auth()->id())
            ->whereIn($modelKeyName, $ids)
            ->where('status', 'U')
            ->update(['status' => 'R']);
    }
}
