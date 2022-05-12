@props(['class' => ''])
<div
    x-data="{
        @if ($attributes->whereStartsWith('x-data')->first())
        ...{{ $attributes->whereStartsWith('x-data')->first() }}
        @endif
    }"
    {{ $attributes->whereDoesntStartWith('x-data')->whereStartsWith('x-') }}
    {{ $attributes->whereStartsWith('wire') }}
    >
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-slate-900">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 shadow-md overflow-hidden sm:rounded-lg">
            <div class="p-6 bg-dark-default">
                <x-bliss::auth-session-status :status="session('status')" />
                <x-bliss::auth-validation-errors class="mb-4" :errors="$errors" />
                <div class="flex items-center">
                    <div>
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
