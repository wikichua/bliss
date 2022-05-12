@props(['active', 'can' => null])
@php
$classes = ($active ?? false)
            ? 'bg-gray-700 bg-opacity-25 text-gray-100'
            : 'text-gray-100 hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100';

$canSee = true;
if (!is_null($can)) {
    if (is_string($can)) {
        if (json_decode($can)) {
            $can = json_decode($can, 1);
        } else {
            $can = [$can];
        }
    }
    $canSee = auth()->user()->canany($can);
}
@endphp
@if ($canSee)
<a {{ $attributes->merge(['class' => 'py-2 px-12 block text-sm '. $classes]) }}>
    {{ $slot }}
</a>
@endif
