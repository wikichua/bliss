<div id="wire">
    <x-slot name="header">
        {{ __('Dashboard') }}
    </x-slot>

    <x-bliss::content-card x-data="{}">
        <x-slot name="left">
            Left Content
        </x-slot>
        <x-slot name="right">
            Right Content
        </x-slot>
    </x-bliss::content-card>
</div>
