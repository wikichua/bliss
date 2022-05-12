<div>
    <div wire:poll.15s
        x-data="
        {
            ids: @js($ids),
            dropdownOpen: false,
            init () {
                this.$watch('dropdownOpen', () => {
                    if (this.ids.length > 0) {
                        this.$wire.onRead(this.ids);
                    }
                });
            },
        }"
        class="items-center space-x-2 mr-3">
        <div x-on:click="dropdownOpen = ! dropdownOpen" class="cursor-pointer">
            @if ($unread_count)
            <div class="animate-pulse absolute inline-block top-0 right-0 bottom-auto left-auto -translate-x-1 -translate-y-1/2 rotate-0 skew-x-0 skew-y-0 scale-x-100 scale-y-100 py-1 px-1.5 text-xs leading-none text-center whitespace-nowrap align-middle font-bold bg-red-500 text-white rounded-full z-10">{{ $unread_count }}</div>
            @endif
            <div class="flex items-center justify-center text-center rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 dark:text-white" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
                </svg>
            </div>
        </div>
        <div x-show="dropdownOpen" x-on:click="dropdownOpen = false" class="fixed inset-0 h-full w-full z-10" style="display: none;"></div>
        <div x-show="dropdownOpen" class="absolute right-0 mt-2 w-48 bg-white dark:bg-slate-900 rounded-md overflow-hidden shadow-xl z-10" style="display: none;">
        @forelse ($alerts as $alert)
            <a href="{{ $alert->link ?? '#' }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-50 hover:bg-gray-600 hover:text-white">
                {!! $alert->message !!}
            </a>
        @empty
        <span class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-50 hover:bg-gray-600 hover:text-white">{{ __('No new notification') }}</span>
        @endforelse
        </div>
    </div>
</div>
