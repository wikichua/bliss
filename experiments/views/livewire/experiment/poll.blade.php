<div>
    <x-bliss::generic-card>
        <div x-data="{
                init() {
                    $nextTick(() => {
                    });
                },
                testing() {
                    alert('here');
                }
            }"
            wire:poll
        >
            <div class="p-4 text-sm text-gray-700 bg-gray-100 rounded-lg dark:bg-gray-700 dark:text-gray-300" role="alert">
                <span class="font-medium">Poll Count</span> {{ $pollCount }}
            </div>
            <div class="mt-3 p-4 text-sm text-gray-700 bg-gray-100 rounded-lg dark:bg-gray-700 dark:text-gray-300" role="alert">
                <span class="font-medium">{{ $inspireQuote }}</span>
            </div>
            <div class="mt-3 p-4 text-sm text-gray-700 bg-gray-100 rounded-lg dark:bg-gray-700 dark:text-gray-300" role="alert">
                <button x-on:click="testing">Testing</button>
            </div>
        </div>
    </x-bliss::generic-card>
</div>
