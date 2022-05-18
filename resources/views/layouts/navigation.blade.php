<div :class="sidebarOpen ? 'block' : 'hidden'" x-on:click="sidebarOpen = false" class="fixed z-20 inset-0 bg-black opacity-50 transition-opacity lg:hidden"></div>

<div :class="sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'" class="fixed z-30 inset-y-0 left-0 w-64 transition duration-300 transform dark:bg-slate-800 bg-slate-500 overflow-y-auto lg:translate-x-0 lg:static lg:inset-0">
    <div class="flex items-center justify-center mt-8">
        <div class="flex items-center">
            <a href="{{ route('dashboard') }}">
                <x-bliss::application-logo class="block h-12 w-12" />
            </a>
            <span class="text-gray-900 dark:text-gray-50 text-2xl mx-2 font-extrabold">{{ config('app.name', 'Bliss') }}</span>
        </div>
    </div>

    <nav class="mt-10">
        <x-bliss::nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
            {{ __('Dashboard') }}
        </x-bliss::nav-link>

        <x-bliss::nav-dropdown
            :active="request()->routeIs([
                'permission.*',
                'setting.*',
                'cronjob.*',
                'audit.*',
                'versionizer.*',
                'queuejob.*',
                'failedjob.*',
                'log.*',
            ])"
            :can="[
                'read-permissions',
                'read-settings',
                'read-cronjobs',
                'read-audits',
                'read-versionizers',
                'read-queuejobs',
                'read-failedjobs',
                'read-logs',
            ]">
            <x-slot name="trigger">
                {{ __('System') }}
            </x-slot>
            <x-slot name="content">
                <x-bliss::dropdown-link :href="route('permission.list')" :active="request()->routeIs('permission.*')" can="read-permissions">
                    {{ __('Permission') }}
                </x-bliss::dropdown-link>
                <x-bliss::dropdown-link :href="route('setting.list')" :active="request()->routeIs('setting.*')" can="read-settings">
                    {{ __('Setting') }}
                </x-bliss::dropdown-link>
                <x-bliss::dropdown-link :href="route('cronjob.list')" :active="request()->routeIs('cronjob.*')" can="read-cronjobs">
                    {{ __('Cronjob') }}
                </x-bliss::dropdown-link>
                <x-bliss::dropdown-link :href="route('audit.list')" :active="request()->routeIs('audit.*')" can="read-audits">
                    {{ __('Audit') }}
                </x-bliss::dropdown-link>
                <x-bliss::dropdown-link :href="route('versionizer.list')" :active="request()->routeIs('versionizer.*')" can="read-versionizers">
                    {{ __('Versionizer') }}
                </x-bliss::dropdown-link>
                <x-bliss::dropdown-link :href="route('queuejob.list')" :active="request()->routeIs('queuejob.*')" can="read-queuejobs">
                    {{ __('Queue Job') }}
                </x-bliss::dropdown-link>
                <x-bliss::dropdown-link :href="route('failedjob.list')" :active="request()->routeIs('failedjob.*')" can="read-failedjobs">
                    {{ __('Failed Job') }}
                </x-bliss::dropdown-link>
                @if (config('logging.default', 'db') == 'db' || auth()->user()->isAdmin)
                <x-bliss::dropdown-link :href="route('log.list')" :active="request()->routeIs('log.*')" can="read-logs">
                    {{ __('System Logs') }}
                </x-bliss::dropdown-link>
                @endif
            </x-slot>
        </x-bliss::nav-dropdown>

        <x-bliss::nav-dropdown
            :active="request()->routeIs([
            'report.*',
            'role.*',
            'user.*',
            'user.personal-access-token.*',
            ])"
            :can="[
                'read-roles',
                'read-reports',
                'read-users',
            ]"
            >
            <x-slot name="trigger">
                {{ __('Management') }}
            </x-slot>
            <x-slot name="content">
                <x-bliss::dropdown-link :href="route('role.list')" :active="request()->routeIs('role.*')" can="read-roles">
                    {{ __('Role') }}
                </x-bliss::dropdown-link>
                <x-bliss::dropdown-link :href="route('user.list')" :active="request()->routeIs([
                    'user.*',
                    'user.personal-access-token.*',
                    ])" can="read-users">
                    {{ __('User') }}
                </x-bliss::dropdown-link>
                <x-bliss::dropdown-link :href="route('report.list')" :active="request()->routeIs('report.*')" can="read-reports">
                    {{ __('Report') }}
                </x-bliss::dropdown-link>
            </x-slot>
        </x-bliss::nav-dropdown>
    </nav>
</div>
