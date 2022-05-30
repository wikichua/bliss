@props(['disabled' => false, 'id', 'multiple' => false, 'label', 'options' => [], 'allowEmptyOption' => true, 'allowCreateOption' => false])
<div class="form-group mb-2">
    <label for="{{ $id }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ $label ?? '' }}</label>
    <select
        x-data="{
            init() {
                return new TomSelect(this.$refs.tomselect{{ $id }}, {
                    plugins: ['caret_position', 'input_autogrow', 'remove_button', 'clear_button', 'checkbox_options'],
                    persist: false,
                    allowEmptyOption: {{ $allowEmptyOption ? 'true':'false' }},
                    create: {{ $allowCreateOption ? 'true':'false' }}
                });
            }
        }"
        x-ref="tomselect{{ $id }}"
        id="{{ $id }}"
        {{ $disabled ? 'disabled' : '' }}
        {!! $attributes->merge(['class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-0.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500']) !!}
        {{ $multiple ? 'multiple':'' }}
        >
            <option value="" {{ $multiple ? 'disabled':'' }}>{{ __('Please Select') }}</option>
            @foreach($options as $key => $val)
            <option value="{{ $key }}">{{ $val }}</option>
            @endforeach
    </select>
</div>
