@props(['id' => uniqid(), 'label', 'multiple' => false,'options' => [], 'allowEmptyOption' => true, 'allowCreateOption' => false])
<div class="mb-3 grid grid-cols-4 items-center">
    <div class="justify-self-end">
        <label for="{{ $id }}" class="text-sm font-medium text-gray-900 dark:text-gray-300 mr-3">{{ $label }}</label>
    </div>
    <div class="col-span-3">
        <select x-data="{
            init() {
                    return new TomSelect(this.$refs.tomselect{{ $id }}, {
                        plugins: ['caret_position', 'input_autogrow', 'remove_button', 'clear_button', 'checkbox_options'],
                        allowEmptyOption: {{ $allowEmptyOption ? 'true':'false' }},
                        create: {{ $allowCreateOption ? 'true':'false' }}
                    });
                }
            }"
            x-ref="tomselect{{ $id }}"
            {{ $attributes->merge(['class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-0 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500'])->whereDoesntStartWith('wire') }}
            id="{{ $id }}"
            {{ $attributes->whereStartsWith('wire') }}
            {{ $multiple ? 'multiple':'' }}
        >
            <option value="" {{ $multiple ? 'disabled':'' }}>{{ __('Please Select') }}</option>
            @foreach($options as $key => $val)
            <option value="{{ $key }}">{{ $val }}</option>
            @endforeach
        </select>
        @error($attributes->whereStartsWith('wire:model')->first())
        <div class="mt-2">
            <small class="block mt-1 font-bold text-xs text-red-600">{{ $message ?? '' }}</small>
        </div>
        @enderror
    </div>
</div>
