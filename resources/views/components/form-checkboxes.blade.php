@props(['id' => uniqid(), 'label', 'options' => [], 'disabled' => false])
@php
    if (is_object($options) && $options instanceOf \Illuminate\Support\Collection) {
        $options = $options->toArray();
    }
    $wireModel = [];
    foreach ($attributes->whereStartsWith('wire:model') as $key => $value) {
        $wireModel = compact('key', 'value');
    }
    $wireKey = $attributes->wire('key')->value() ? $attributes->wire('key')->value() : 0;
@endphp
<div class="mb-3 grid grid-cols-4 items-start">
    <div class="justify-self-end">
        <label class="text-sm font-medium text-gray-900 dark:text-gray-300 mr-3">{{ $label }}</label>
    </div>
    <div class="col-span-3" x-data="initCheckboxes{{ $wireKey }}">
        <div class="items-start">
            <div>
                @if (!$disabled)
                <div class="form-check">
                    <input class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" type="checkbox" id="checkAll-{{ $wireKey }}" x-model="checkedAll{{ $wireKey }}" x-on:click="checkedAll{{ $wireKey }} = !checkedAll{{ $wireKey }}">
                    <label class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300" for="checkAll-{{ $wireKey }}">
                      Select All
                    </label>
                </div>
                @endif

                @foreach ($options as $optionKey => $optionValue)
                <div class="form-check">
                    <input
                        {{ $attributes->merge(['class' => 'checkKey-'.$wireKey.' w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600'])->whereDoesntStartWith('wire') }}
                        type="checkbox"
                        value="{{ $optionKey }}"
                        id="checkKey-{{ $optionKey }}"
                        {{ $wireModel['key'] }}="{{ $wireModel['value'].'.'.$optionKey }}"
                        x-model="checkedKeys{{ $wireKey }}"
                        @if ($disabled)
                        disabled
                        @endif
                    >
                    <label class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300" for="checkKey-{{ $optionKey }}">
                    {{ \Str::headline($optionValue) }}
                    </label>
                </div>
                @endforeach
            </div>
        </div>
        @error($wireModel['value'])
        <div class="mt-2">
            <small class="block mt-1 font-bold text-xs text-red-600">{{ $message ?? '' }}</small>
        </div>
        @enderror
    </div>
</div>
@push('scripts')
<script type="text/javascript">
    function initCheckboxes{{ $wireKey }} () {
        return {
            availableKeys: @js(array_keys($options)),
            @if (!$disabled)
            checkedAll{{ $wireKey }}: document.querySelector('#checkAll-{{ $wireKey }}').checked,
            @endif
            checkedKeys{{ $wireKey }}: [],
            init () {
                const wire = this.$wire;
                let rowCheckboxes = document.querySelectorAll('.checkKey-{{ $wireKey }}');
                let checkAllCheckbox = document.querySelector('#checkAll-{{ $wireKey }}');

                @if (!$disabled)
                this.$watch('checkedAll{{ $wireKey }}', (checked) => {
                    this.checkedKeys{{ $wireKey }} = [];
                    let checkedItems = [];
                    _.forEach(rowCheckboxes, (checkbox) => {
                        checkbox.checked = false;
                        if (checked) {
                            checkedItems.push(checkbox.value);
                        }
                    });
                    this.checkedKeys{{ $wireKey }} = checkedItems;
                });
                this.$watch('checkedKeys{{ $wireKey }}', (keys) => {
                    checkAllCheckbox.indeterminate = false;
                    if (keys.length <= 0) {
                        checkAllCheckbox.checked = false;
                    } else if (keys.length != rowCheckboxes.length) {
                        checkAllCheckbox.indeterminate = true;
                    } else if (keys.length == rowCheckboxes.length) {
                        checkAllCheckbox.checked = true;
                    }

                    _.forEach(this.availableKeys, (key) => {
                        wire.set('{{ $wireModel['value'] }}.' + key, false, true);
                    });
                    _.forEach(keys, (key) => {
                        wire.set('{{ $wireModel['value'] }}.' + key, key, true);
                    });
                });
                @endif

                // check if prefilled
                let initCheckedKeys = _.toArray(wire.get('{{ $wireModel['value'] }}'));
                if (initCheckedKeys.length > 0) {
                    this.checkedKeys{{ $wireKey }} = [];
                    let checkedItems = [];
                    _.forEach(rowCheckboxes, (checkbox) => {
                        let checked = _.find(initCheckedKeys, function(key) {
                            return key == checkbox.value;
                        });
                        checkbox.checked = false;
                        if (checked) {
                            checkbox.checked = checked;
                            if (checkbox.checked) {
                                checkedItems.push(checkbox.value);
                            }
                        }
                    });
                    this.checkedKeys{{ $wireKey }} = checkedItems;
                }
            },
        };
    }
</script>
@endpush
