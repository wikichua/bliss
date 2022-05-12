<div x-data="{
    openSearchableModal: false,
}">
    <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-gray-300">Search</label>
    <div class="relative">
        <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>
        <input type="search" class="block p-2 pl-10 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search..." x-on:click="openSearchableModal = true; $nextTick(() => {
            $refs.searchInput.focus();
        })" placeholder="Search...">
    </div>

    <x-bliss::searchable-modal modalTitle="Global Search">
        <x-slot name="modalBody">
            <div class="relative mx-4 lg:mx-0">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center">
                    <svg class="h-5 w-5 text-gray-500" viewBox="0 0 24 24" fill="none">
                        <path
                            d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        </path>
                    </svg>
                </span>
                <input type="search" class="block p-2 pl-10 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search..."  wire:model.debounce.250ms="query" x-ref="searchInput">
            </div>
            @if ($searchables)
            <div class="relative mx-4 lg:mx-0 p-6">
                <ol class="border-l border-gray-300">
                @forelse ($searchables as $searchable)
                    <li>
                        <div class="flex flex-start items-center pt-3">
                            <div class="bg-gray-300 dark:bg-gray-100 w-2 h-2 rounded-full -ml-1 mr-3"></div>
                            <p class="text-gray-500 text-sm">{{ $searchable->data['timestamp'] }}</p>
                        </div>
                        <div class="mt-0.5 ml-4 mb-6">
                            <h4 class="text-gray-800 dark:text-gray-200 font-semibold text-xl mb-1.5">{{ $searchable->data['title'] }}</h4>
                            <a href="{{ $searchable->data['url'] }}">
                                <p class="mb-3">
                                    <ul class="text-gray-500 dark:text-gray-200 list-unstyled">
                                        @foreach ($searchable->tags as $key => $val)
                                        <li>{{ ucwords($key) }} : {{ $val }}</li>
                                        @endforeach
                                    </ul>
                                </p>
                            </a>
                        </div>
                    </li>
                @empty
                    <li>
                        <div class="mt-0.5 ml-4 mb-6">
                            <h4 class="text-gray-800 dark:text-gray-200 font-semibold text-xl mb-1.5">{{ __('No Searchable Result') }}</h4>
                        </div>
                    </li>
                @endforelse
                </ol>
            </div>
            <div class="relative mx-4 lg:mx-0">
                @if (method_exists($searchables, 'links'))
                {{ $searchables->links() }}
                @endif
            </div>
            @endif
        </x-slot>
    </x-bliss::searchable-modal>
</div>
