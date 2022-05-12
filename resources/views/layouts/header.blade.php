<header
    x-data="{
        confirmLogout: (href) => {
            Swal.fire({
                title: 'Are you sure?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, please!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href
                }
            });
        },
    }"
    class="flex justify-between items-center py-4 px-6 bg-gray-50 dark:bg-slate-900 border-b-4 border-gray-500 shadow-xl">
    <div class="flex items-center">
        <button x-on:click="sidebarOpen = true" class="text-gray-500 focus:outline-none lg:hidden">
            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M4 6H20M4 12H20M4 18H11" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round"></path>
            </svg>
        </button>

        <livewire:searchable />
    </div>

    <div class="flex items-center">
        <div class="relative">
            <x-bliss::dark-mode-toggle />
        </div>

        <div class="relative">
            <livewire:alert-notification />
        </div>

        <div x-data="{ dropdownOpen: false }" class="relative">
            <button x-on:click="dropdownOpen = ! dropdownOpen" class="relative block h-8 w-8 rounded-full overflow-hidden shadow focus:outline-none">
                <livewire:header-profile />
            </button>

            <div x-show="dropdownOpen" x-on:click="dropdownOpen = false" class="fixed inset-0 h-full w-full z-10" style="display: none;"></div>
            <div x-show="dropdownOpen" class="absolute right-0 mt-2 w-48 bg-white dark:bg-slate-900 rounded-md overflow-hidden shadow-xl z-10" style="display: none;">
                <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-50 hover:bg-gray-600 hover:text-white">{{ __('Profile') }}</a>
                <hr />
                @impersonating
                <a href="#" x-on:click.prevent="emitToWithoutConfirmAndReauth('header-profile', 'onLeaveImpersonate')" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-50 hover:bg-gray-600 hover:text-white">{{ __('Leave Impersonate') }}</a>
                @else
                <a href="#" x-on:click.prevent="confirmLogout('{{ route('logout') }}')" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-50 hover:bg-gray-600 hover:text-white">{{ __('Logout') }}</a>
                @endImpersonating
            </div>
        </div>
    </div>
</header>
