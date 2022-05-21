<x-bliss::html>
    <x-slot name="styles">
    </x-slot>
    <x-slot name="scripts">
    </x-slot>
    <div class="flex h-screen bg-gray-200 dark:bg-slate-900">
        <div class="absolute w-full flex justify-end mt-2">
            <x-bliss::dark-mode-toggle />
        </div>
        <div class="flex-1 flex flex-col overflow-hidden">
            <main class="flex-1 overflow-x-hidden overflow-y-auto">
                <div class="container mx-auto px-6 py-8 mt-2">
                    <h3 class="header-title">
                        @if (isset($header) && $header != '')
                        {{ $header }}
                        @else
                        {{ __($headerTitle ?? $this->headerTitle) }}
                        @endif
                    </h3>
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>
</x-bliss::html>
