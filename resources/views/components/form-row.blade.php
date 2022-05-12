<div class="mb-3 grid grid-cols-4
    {{ !str_contains($attributes->get('class'), 'items-') ? 'items-center' : '' }}
    {{ $attributes->get('class') }}">
    <div class="justify-self-end">
        <label class="form-label mr-6 whitespace-nowrap">{{ $label }}</label>
    </div>
    <div class="col-span-3">
        {{ $slot }}
    </div>
</div>
