<div>
    <div wire:poll.5s
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
        <div x-show="dropdownOpen" x-on:click="dropdownOpen = false" class="fixed inset-0 h-full z-10 min-w-max" style="display: none;"></div>
        <div x-show="dropdownOpen" class="absolute right-0 mt-6 bg-white dark:bg-gray-800 shadow-xl z-10 w-96 max-h-72 overflow-scroll" style="display: none;">
            <div class="p-4 max-w-md rounded-lg border shadow-md sm:p-8 dark:border-gray-700">
                <div class="flow-root">
                    <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($alerts as $alert)
                        <li class="py-3 sm:py-4 text-gray-900 dark:text-gray-100">
                            <a href="{{ $alert->link ?? '#' }}" class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    @if ($alert->sender->avatar != '')
                                    <img class="w-8 h-8 rounded-full object-cover" src="{{ url($alert->sender->avatar) }}" />
                                    @else
                                    <img class="w-8 h-8 rounded-full object-cover" src="https://ui-avatars.com/api/?name={{ $alert->sender->name }}" />
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <span class="text-sm font-normal">
                                        {!! $alert->message !!}
                                    </span>
                                </div>
                                <time class="inline-flex items-center text-xs font-normal">
                                    {{ \Carbon\Carbon::parse($alert->created_at)->diffForHumans() }}
                                </time>
                            </a>
                        </li>
                    @empty
                    <li class="py-3 sm:py-4 text-gray-900 dark:text-gray-100">
                        {{ __('No new notification') }}
                    </li>
                    @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
