@props(['disabled' => false, 'id', 'label' , 'datepicker' => null])
<div class="form-group mb-2" wire:ignore>
    <label for="{{ $id }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ $label ?? '' }}</label>
    <input
        x-data="{
            value: [],
            init () {
                let picker = flatpickr(this.$refs.flatpicker, {
                    mode: 'range',
                    dateFormat: 'Y/m/d',
                    defaultDate: this.value,
                    onChange: (date, dateString) => {
                        this.value = dateString.split(' to ')
                    },
                    @if ($datepicker)
                    ...{{ $datepicker }}
                    @endif
                });
                this.$watch('value', () => picker.setDate(this.value))
            },
        }"
        id="{{ $id }}" {!! $attributes->merge(['class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500']) !!} {{ $disabled ? 'disabled' : '' }} type="text" x-ref="flatpicker">
</div>
