@props(['id' => uniqid(), 'label', 'options' => ['']])
@php
    list($modelKey, $modelVal) = array_keys(collect($options)->first());
    $wireModel = [];
    foreach ($attributes->whereStartsWith('wire:model') as $key => $value) {
        $wireModel = compact('key', 'value');
    }
@endphp
<div class="mb-3 grid grid-cols-4 items-center">
    <div class="justify-self-end">
        <label for="{{ $id }}" class="text-sm font-medium text-gray-900 dark:text-gray-300 mr-3">{{ $label }}</label>
    </div>
    <div class="col-span-3">
        @foreach ($options as $index => $option)
        <div class="flex mb-1">
            <textarea {{ $attributes->merge(['class' => 'w-1/3 block p-2.5 w-1/3 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500'])->whereDoesntStartWith('wire:model') }}
                {{ $wireModel['key'] }}="{{ $wireModel['value'].'.'.$index.'.'.$modelKey }}"
            ></textarea>
            <textarea {{ $attributes->merge(['class' => 'ml-1 block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500'])->whereDoesntStartWith('wire:model') }}
                {{ $wireModel['key'] }}="{{ $wireModel['value'].'.'.$index.'.'.$modelVal }}"
            ></textarea>
            @if (! $attributes['disabled'])
                @if ($loop->first)
                <button type="button" class="ml-1 p-2 rounded text-gray-100 border-1 shadow bg-blue-500 hover:bg-blue-700 m-0" wire:click="$emit('addOption')">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </button>
                @else
                <button type="button" class="ml-1 p-2 rounded text-gray-100 border-1 shadow bg-red-500 hover:bg-red-700 m-0" wire:click="$emit('delOption','{{ $index }}')">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </button>
                @endif
            @endif
        </div>
        @endforeach

        @error($attributes->whereStartsWith('wire:model')->first())
        <div class="mt-2">
            <small class="block mt-1 font-bold text-xs text-red-600">{{ $message ?? '' }}</small>
        </div>
        @enderror
    </div>
</div>
