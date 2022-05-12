@props(['id' => uniqid(), 'label' => '', 'type' => 'javascript'])
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
            content: '',
            init() {
                let wire = $wire;

                codeMirrorEditor{{ $id }} = CodeMirror.fromTextArea(this.$refs.codemirror{{ $id }}, {
                    keyMap: 'sublime',
                    lineWrapping: true,
                    mode: '{{ $type }}',
                    theme: 'elegant',
                    lineNumbers: true,
                    matchBrackets: true,
                    indentUnit: 4,
                    indentWithTabs: true,
                    tabSize: 4,
                    foldGutter: true,
                    gutters: ['CodeMirror-linenumbers', 'CodeMirror-foldgutter']
                });

                this.content = this.$refs.codemirror{{ $id }}.value;
                codeMirrorEditor{{ $id }}.setValue(this.content);
                {{-- codeMirrorEditor{{ $id }}.setSize(this.$refs.codemirror{{ $id }}.value.split('\n').length + 5, 'height'); --}}
                setTimeout(function() {
                    codeMirrorEditor{{ $id }}.refresh();
                }, 1);

                codeMirrorEditor{{ $id }}.on('change', () => this.content = codeMirrorEditor{{ $id }}.getValue());
                $watch('content', () => {
                    {{-- codeMirrorEditor{{ $id }}.setValue(this.content); --}}
                    this.$wire.set('{{ $wireModel['value'] }}',this.content, true);
                    codeMirrorEditor{{ $id }}.refresh();
                });
            },
        }"
    >
        <textarea x-ref="codemirror{{ $id }}" {{ $attributes->merge(['class' => 'form-control w-full p-2 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-gray-600 focus:outline-none'])->whereDoesntStartWith('wire') }} id="{{ $id }}" {{ $attributes->whereStartsWith('wire') }} x-model="content">
        </textarea>
        @error($attributes->whereStartsWith('wire:model')->first())
        <div class="mt-2">
            <small class="block mt-1 font-bold text-xs text-red-600">{{ $message ?? '' }}</small>
        </div>
        @enderror
    </div>
</div>
