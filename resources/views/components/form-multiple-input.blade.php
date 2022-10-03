@props(['id' => fake()->word(), 'label', 'options' => [''], 'type' => 'text'])
@php
    if (is_object($options) && $options instanceOf \Illuminate\Support\Collection) {
        $options = $options->toArray();
    }
    $inputTemplate = [];
    if (count($options) != count($options, COUNT_RECURSIVE)) {
        $tmp = array_keys(array_first($options));
        foreach ($tmp as $key) {
            $inputTemplate[$key] = '';
        }
    }
    $wireModel = [];
    foreach ($attributes->whereStartsWith('wire:model') as $key => $value) {
        $wireModel = compact('key', 'value');
    }
@endphp
<div class="mb-3 grid grid-cols-4 items-center" x-data="initMultipleInput{{ $wireModel['value'] }}">
    <div class="justify-self-end">
        <label for="{{ $id }}" class="text-sm font-medium text-gray-900 dark:text-gray-300 mr-3">{{ $label }}</label>
    </div>
    <div class="col-span-3">
        <template x-for="(field, index, i) in fields" :key="index">
            <div class="flex mb-1">
            @forelse ($inputTemplate as $templateKey => $templateVal)
                <{{ $type != 'textarea' ? 'input type="text"' : 'textarea' }} {{ $attributes->merge(['class' => 'block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 '. (!$loop->first ? 'ml-1':'')])->whereDoesntStartWith('wire:model') }} x-model="field['{{ $templateKey }}']" rows="1">{!! $type == 'textarea' ? '</textarea>' : '' !!}
            @empty
                <{{ $type != 'textarea' ? 'input type="text"' : 'textarea' }} {{ $attributes->merge(['class' => 'block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500'])->whereDoesntStartWith('wire:model') }} x-model="fields[index]" rows="1">{!! $type == 'textarea' ? '</textarea>' : '' !!}
            @endforelse

            @if (! $attributes['disabled'])
                <button x-show="index <= firstKey" type="button" class="ml-1 p-2 rounded text-gray-100 border-1 shadow bg-blue-500 hover:bg-blue-700 m-0" x-on:click="addOption">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </button>
                <button x-show="index > firstKey" type="button" class="ml-1 p-2 rounded text-gray-100 border-1 shadow bg-red-500 hover:bg-red-700 m-0" x-on:click="delOption(index)">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </button>
            @endif
            </div>
        </template>
        @error($attributes->whereStartsWith('wire:model')->first())
        <div class="mt-2">
            <small class="block mt-1 font-bold text-xs text-red-600">{{ $message ?? '' }}</small>
        </div>
        @enderror
    </div>
</div>
@push('scripts')
<script type="text/javascript">
    function initMultipleInput{{ $wireModel['value'] }}() {
        return {
            fields: @js($options),
            firstKey: 0,
            init() {
                let wire = this.$wire;
                this.$watch('fields', (fields) => {
                    wire.set('{{ $wireModel['value'] }}', fields, true);
                });
            },
            addOption() {
                let templateOption = '';
                @if (count($inputTemplate) > 0)
                templateOption = @js($inputTemplate);
                @endif
                this.fields.push(templateOption);
            },
            delOption(index) {
                this.fields.splice(index, 1);
            }
        }
    }
</script>
@endpush
