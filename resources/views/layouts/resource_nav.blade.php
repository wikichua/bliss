<x-bliss::nav-dropdown
    :active="request()->routeIs([
        {{--KeepMeHerePlease activeStr--}}
    ])"
    :can="[
        {{--KeepMeHerePlease canStr--}}
    ]"
    >
    <x-slot name="trigger">
        {{ __('Resource') }}
    </x-slot>
    <x-slot name="content">
        {{--KeepMeHerePlease linkStr--}}
    </x-slot>
</x-bliss::nav-dropdown>
