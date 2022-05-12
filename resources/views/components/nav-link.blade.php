@props(['active'])

@php
$classes = ($active ?? false)
            ? 'bg-gray-700 bg-opacity-25 text-gray-100'
            : 'text-gray-100 hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100';
@endphp

<a {{ $attributes->merge(['class' => 'flex items-center py-2 px-6 '. $classes]) }}>
    {{ $slot }}
</a>
