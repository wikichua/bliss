@props(['id' => fake()->word(), 'label', 'disabled' => false, 'datepicker' => null, 'range' => false, 'time' => false])
<div class="mb-3 grid grid-cols-4 items-center">
    <div class="justify-self-end">
        <label for="{{ $id }}" class="text-sm font-medium text-gray-900 dark:text-gray-300 mr-3">{{ $label }}</label>
    </div>
    <div class="col-span-3">
        <input x-data="{
            value: [],
            init () {
                let picker = flatpickr(this.$refs.{{ $id }}, {
                    {{ $range ? 'mode: \'range\',' : '' }}
                    enableTime: @js($time),
                    @if ($time)
                    dateFormat: 'Y/m/d H:i',
                    @else
                    dateFormat: 'Y/m/d',
                    @endif
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
        {{ $attributes->merge(['class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500'])->whereDoesntStartWith('wire') }} id="{{ $id }}" {{ $attributes->whereStartsWith('wire') }} x-ref="{{ $id }}">
        @error($attributes->whereStartsWith('wire:model')->first())
        <div class="mt-2">
            <small class="block mt-1 font-bold text-xs text-red-600">{{ $message ?? '' }}</small>
        </div>
        @enderror
    </div>
</div>
