@props(['id' => uniqid(), 'label', 'options' => [], 'disabled' => false])
@php
    if (is_object($options) && $options instanceOf \Illuminate\Support\Collection) {
        $options = $options->toArray();
    }
    $wireModel = [];
    foreach ($attributes->whereStartsWith('wire:model') as $key => $value) {
        $wireModel = compact('key', 'value');
    }
    $isGrouped = true;
    if (count($options) == count($options, COUNT_RECURSIVE)) {
        $isGrouped = false;
        $options = [$wireModel['value'] => $options];
    }
    $allSelect = [];
    foreach ($options as $optionLabel => $optionArray) {
        $optionLabel = camel_case(strtolower($optionLabel));
        $allSelect[$optionLabel] = false;
        $selectAll[$optionLabel] = false;
    }
@endphp
<div class="mb-3 grid grid-cols-4 items-start">
    <div class="justify-self-end">
        <label class="text-sm font-medium text-gray-900 dark:text-gray-300 mr-3">{{ $label }}</label>
    </div>
    <div class="col-span-3" x-data="formGroupedCheckboxes">
    @foreach ($options as $optionLabel => $optionArray)
        @php
            $group = camel_case(strtolower($optionLabel));
        @endphp
        <div class="mb-3 grid grid-cols-3 items-start pb-2 {{ !$loop->last ? 'border-b':'' }}">
            @if ($isGrouped)
            <div class="justify-self-end">
                <label class="form-label mr-6">{{ $optionLabel }}</label>
            </div>
            @endif
            <div class="col-span-2">
                @if (!$disabled)
                <div class="form-check">
                    <input class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" type="checkbox" id="selectAllCheckbox{{ $group }}" x-on:change="checkedAll('{{ $group }}')" x-model="allSelect.{{ $group }}">
                    <label class="form-check-label inline-block text-gray-800 dark:text-gray-50" for="selectAllCheckbox{{ $group }}">
                      Select All
                    </label>
                </div>
                @endif

                @foreach ($optionArray as $optionKey => $optionValue)
                <div class="form-check">
                    <input
                        {{ $attributes->merge(['class' => $group .' '.$wireModel['value'].'-class w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600'])->whereDoesntStartWith('wire') }}
                        type="checkbox"
                        value="{{ $optionKey }}"
                        id="{{ $optionKey }}" {{ $wireModel['key'] }}="{{ $wireModel['value'].'.'.$optionKey }}"
                        x-model="itemsCard.{{ $group }}"
                        data-group="{{ $group }}"
                        @if (!$disabled)
                        x-on:change="checkThis('{{ $group }}', '#selectAllCheckbox{{ $group }}')"
                        @else
                        disabled
                        @endif
                    >
                    <label class="form-check-label inline-block text-gray-800 dark:text-gray-50" for="{{ $optionKey }}">
                    {{ \Str::headline($optionValue) }}
                    </label>
                </div>
                @endforeach

            </div>
        </div>
    @endforeach
    @error($wireModel['value'])
        <div class="mt-2">
            <small class="block mt-1 font-bold text-xs text-red-600">{{ $message ?? '' }}</small>
        </div>
    @enderror
    </div>
</div>
@push('scripts')
<script type="text/javascript">
    function formGroupedCheckboxes () {
        return {
            allSelect: @js($allSelect),
            selectAll: @js($selectAll),
            itemsCard: [],
            init() {
                // let wire = window.livewire.find(document.querySelector('#wire').getAttribute('wire:id'));
                let wire = this.$wire;
                let itemsCard = [];
                let isPrefetch = wire.get('{{ $wireModel['value'] }}');
                _.forEach(this.selectAll, function(value, index) {
                    itemsCard[index] = [];
                    if (_.isNull(isPrefetch) == false) {
                        var checked = wire.get('{{ $wireModel['value'] }}.'+index);
                        if (_.isNull(checked) == false && _.isUndefined(checked) == false) {
                            itemsCard[index] = _.toArray(checked);
                        }
                    }
                });
                this.itemsCard = itemsCard;
            },
            setWireModel () {
                // let wire = window.livewire.find(document.querySelector('#wire').getAttribute('wire:id'));
                let wire = this.$wire;
                wire.set('{{ $wireModel['value'] }}', []);
                allCheckboxes = document.querySelectorAll('.{{ $wireModel['value'] }}-class');
                _.forEach(allCheckboxes, function(checkbox) {
                    if (checkbox.checked) {
                        wire.set('{{ $wireModel['value'] }}.' + checkbox.dataset.group + '.' + checkbox.id, checkbox.value, true);
                    }
                });
            },
            checkedAll(selector) {
                this.selectAll[selector] = !this.selectAll[selector];
                let allValues = [];
                if (this.selectAll[selector]) {
                    let checkboxes = document.querySelectorAll('.'+selector);
                    [...checkboxes].map((el) => {
                        el.checked = true;
                        allValues.push(el.value);
                    });
                }
                this.itemsCard[selector] = allValues;
                this.setWireModel();
            },
            checkThis(selector, selectAllId) {
                let checkboxes = document.querySelectorAll('.'+selector);
                let el = document.querySelector(selectAllId);
                el.indeterminate = false;
                if (this.itemsCard[selector].length == 0) {
                    this.allSelect[selector] = this.selectAll[selector] = false;
                } else if (this.itemsCard[selector].length == checkboxes.length) {
                    this.allSelect[selector] = this.selectAll[selector] = true;
                } else {
                    this.allSelect[selector] = this.selectAll[selector] = false;
                    el.indeterminate = true;
                }
                this.setWireModel();
            },
        }
    }
</script>
@endpush
