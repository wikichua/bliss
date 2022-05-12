<div>
    <!-- Modal -->
    <div x-show="openSearchableModal" style="display: none" x-on:keydown.escape.prevent.stop="openSearchableModal = false" role="dialog" aria-modal="true" x-id="['modal-title']" :aria-labelledby="$id('modal-title')" class="fixed inset-0 overflow-y-auto z-50">
        <!-- Overlay -->
        <div x-show="openSearchableModal" x-transition.opacity class="fixed inset-0 bg-black bg-opacity-50 dark:bg-opacity-70"></div>

        <!-- Panel -->
        <div x-show="openSearchableModal" x-transition x-on:click="openSearchableModal = false" class="relative min-h-screen flex items-center justify-center p-4">
            <div x-on:click.stop x-trap.noscroll.inert="openSearchableModal" class="relative max-w-2xl w-full bg-white dark:bg-slate-800 border border-black rounded-lg shadow-lg p-12">
                <!-- Title -->
                <div class="flex justify-between">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-gray-50" :id="$id('modal-title')">{{ $modalTitle ?? 'Modal' }}</h2>
                    <a class="cursor-pointer box-content p-1 text-gray-900 dark:text-white border-none rounded-none opacity-50 focus:shadow-none focus:outline-none focus:opacity-100 hover:text-black dark:hover:text-gray-50 hover:opacity-75 hover:no-underline" x-on:click="openSearchableModal = false">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </a>
                </div>
                <!-- Content -->
                <form x-on:submit.prevent autocomplete="off">
                    <p class="mt-2 text-gray-600 p-4 dark:text-gray-50">
                        {{ $modalBody ?? 'Lorem Ipsum' }}
                    </p>
                    <!-- Buttons -->
                    <div class="mt-8 flex space-x-2 justify-end">
                        {!! $modalButtons ?? '' !!}
                        <button type="button" class="modal-button" x-on:click="openSearchableModal = false">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
