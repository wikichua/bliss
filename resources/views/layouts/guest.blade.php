<x-bliss::html>
    <x-slot name="styles">
    </x-slot>
    <x-slot name="scripts">
    </x-slot>
    <div class="min-h-screen bg-gray-100 dark:bg-slate-900">
        <div class="absolute w-full flex justify-end mt-2">
            <x-bliss::dark-mode-toggle />
        </div>
        {{ $slot }}
    </div>
</x-bliss::html>
