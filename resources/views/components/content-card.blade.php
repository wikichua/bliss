@props(['class' => '', 'left' => '', 'right' => '', 'center' => '', 'full' => ''])
<div
    x-data="{
        @if ($attributes->whereStartsWith('x-data')->first())
        ...{{ $attributes->whereStartsWith('x-data')->first() }}
        @endif
    }"
    {{ $attributes->whereDoesntStartWith('x-data')->whereStartsWith('x-') }}
    {{ $attributes->whereStartsWith('wire') }}
    >
    <div class="align-middle inline-block min-w-full overflow-hidden sm:rounded-lg mx-auto {{ $class }}">
        <div class="body-content">
            <div class="p-6 bg-dark-default">
                <x-bliss::auth-session-status :status="session('status')" />
                <x-bliss::auth-validation-errors class="mb-4" :errors="$errors" />
                <div class="grid grid-cols-7 gap-3">
                    @if (!empty($left))
                    <div class="col-span-full {{ !empty($center) ? 'xl:col-span-3': 'xl:col-span-5' }}">
                        {{ $left }}
                    </div>
                    @endif
                    @if (!empty($center))
                    <div class="col-span-full xl:col-span-4">
                        {{ $center }}
                    </div>
                    @endif
                    @if (!empty($right))
                    <div class="col-span-full xl:col-span-2">
                        {{ $right }}
                    </div>
                    @endif
                    @if (!empty($full))
                    <div class="col-span-full">
                        {{ $full }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
