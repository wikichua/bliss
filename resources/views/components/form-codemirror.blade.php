@props(['id' => uniqid(), 'label' => '', 'ref' => fake()->word()])
@php
    $wireModel = [];
    foreach ($attributes->whereStartsWith('wire:model') as $key => $value) {
        $wireModel = compact('key', 'value');
    }
@endphp
<div class="mb-3 grid grid-cols-4 items-center">
    @if (!empty($label))
    <div class="justify-self-end">
        <label for="{{ $id }}" class="text-sm font-medium text-gray-900 dark:text-gray-300 mr-3">{{ $label }}</label>
    </div>
    @endif
    <div class="{{ empty($label) ? 'col-span-full' : 'col-span-3' }}"
        x-cloak
        x-data="{
            oneDark: $data.darkMode,
            content: '',
            init() {
                this.content = this.$refs.{{ $ref }}.value;

                let editor = Editor.create(this.$refs.{{ $ref }}, {
                    doc: this.content,
                    oneDark: this.oneDark,
                });

                $watch('content', () => {
                    this.$wire.set('{{ $wireModel['value'] }}', this.content, true);
                });
            },
        }"
    >
        <div x-ref="{{ $ref }}" {{ $attributes->merge(['class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500'])->whereDoesntStartWith('wire') }} id="{{ $id }}" {{ $attributes->whereStartsWith('wire') }} x-model="content">
        </div>
        @error($attributes->whereStartsWith('wire:model')->first())
        <div class="mt-2">
            <small class="block mt-1 font-bold text-xs text-red-600">{{ $message ?? '' }}</small>
        </div>
        @enderror
    </div>
</div>
