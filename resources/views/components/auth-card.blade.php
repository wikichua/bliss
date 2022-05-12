<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-slate-900">
    <div>
        <a href="/">
            <x-bliss::application-logo class="w-20 h-20" />
        </a>
    </div>

    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-gray-50 dark:bg-slate-800 shadow-md overflow-hidden sm:rounded-lg">
        {{ $slot }}
    </div>
</div>
