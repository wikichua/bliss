@props(['active' => false, 'can' => null])

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
<div x-data="{ open: {{ $active ? 'true' : 'false' }} }">
    <button x-on:click="open = !open" {{ $attributes->merge(['class' => $classes.' w-full flex justify-between items-center py-2 px-6 cursor-pointer ']) }} class="">
        <span class="flex items-center">
            {{ $trigger }}
        </span>

        <span>
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path x-show="!open" d="M9 5L16 12L9 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: none;"></path>
                <path x-show="open" d="M19 9L12 16L5 9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
        </span>
    </button>

    <div x-show="open">
        {{ $content ?? '' }}
    </div>
</div>
@endif
