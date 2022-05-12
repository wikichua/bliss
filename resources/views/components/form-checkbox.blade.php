@props(['id' => uniqid(), 'label', 'checkedLabel' => 'Checked', 'uncheckedLabel' => 'Unchecked'])
<div class="mb-3 grid grid-cols-4 items-center" x-data="{
    label: '',
    checkedLabel: '{{ $checkedLabel }}',
    uncheckedLabel: '{{ $uncheckedLabel }}',
    mount(el) {
        this.checkThis(el);
    },
    checkThis(el) {
        this.label = this.uncheckedLabel;
        if (el.checked) {
            this.label = this.checkedLabel;
        }
    },
    @if ($attributes->whereStartsWith('x-data')->first())
    ...{{ $attributes->whereStartsWith('x-data')->first() }}
    @endif
}">
    <div class="justify-self-end">
        <label for="{{ $id }}" class="text-sm font-medium text-gray-900 dark:text-gray-300 mr-3">{{ $label }}</label>
    </div>
    <div class="col-span-3">
        <div class="form-check">
            <input {{ $attributes->merge(['class' => 'w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600'])->whereDoesntStartWith('wire') }}
                type="checkbox"
                id="{{ $id }}"
                {{ $attributes->whereStartsWith('wire') }}
                x-init="mount($el)"
                x-on:click="checkThis($el)">
            <label for="{{ $id }}" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-400">
                <span x-text="label"></span>
            </label>
        </div>
        @error($attributes->whereStartsWith('wire:model')->first())
        <div class="mt-2">
            <small class="block mt-1 font-bold text-xs text-red-600">{{ $message ?? '' }}</small>
        </div>
        @enderror
    </div>
</div>
