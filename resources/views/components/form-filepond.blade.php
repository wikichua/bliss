@props([
    'id' => fake()->word(),
    'label',
    'files' => [],
    'accept' => 'image/jpg,image/jpeg,image/png,application/pdf',
    'multiple' => false,
])
@php
    if (!is_array($files)) {
        $files = [$files];
    }
    $uploadedFiles = [];
    foreach ($files as $file) {
        if (!is_null($file)) {
            $uploadedFiles[] = [
                'source' => url($file),
                'options' => [
                    'type' => 'local',
                    'metadata' => [
                        'poster' => url($file),
                    ]
                ],
            ];
        }
    }
@endphp
<div class="mb-3 grid grid-cols-4 items-center"
    wire:ignore
    x-cloak
    x-data="{
        pond{{ $id }}: null,
        wireId: null,
        modelValue: @entangle($attributes->wire('model')),
        oldValue: undefined,

        init() {
            let wire = this.$wire;
            this.$watch('modelValue', value => {
                @if ($multiple)
                    const removeOldFiles = (newValue, oldValue) => {
                        if (newValue.length < oldValue.length) {
                            const difference = oldValue.filter(i => ! newValue.includes(i));
                            difference.forEach(serverId => {
                                const file = this.pondpond{{ $id }}.getFiles().find(f => f.serverId === serverId);
                                file && this.pondpond{{ $id }}.removeFile(file.id);
                            });
                        }
                    };
                    if (this.oldValue !== undefined) {
                        try {
                            const files = Array.isArray(value) ? value : JSON.parse(String(value).split('livewire-files:')[1]);
                            const oldFiles = Array.isArray(this.oldValue) ? this.oldValue : JSON.parse(String(this.oldValue).split('livewire-files:')[1]);
                            if (Array.isArray(files) && Array.isArray(oldFiles)) {
                                removeOldFiles(files, oldFiles);
                            }
                        } catch (e) {}
                    }
                    this.oldValue = value;
                @else
                    ! value && this.pondpond{{ $id }}.removeFile();
                @endif
            });

            @if ($multiple)
                let multipleCount = 0;
            @endif

            this.$nextTick(function () {
                this.pondpond{{ $id }} = FilePond.create($refs.filepond{{ $id }}, {
                    allowMultiple: Boolean('{{ $multiple }}') || false,
                    server: {
                        process: (fieldName, file, metadata, load, error, progress, abort, transfer, options) => {
                            @if ($multiple)
                            wire.upload('{{ $attributes->wire('model')->value() }}.' + multipleCount, file, load, error, progress);
                            multipleCount++;
                            @else
                            wire.upload('{{ $attributes->wire('model')->value() }}', file, load, error, progress);
                            @endif
                        },
                        revert: (filename, load) => {
                            wire.removeUpload('{{ $attributes->wire('model')->value() }}', filename, load);
                        },
                    },
                });
                @if (count($uploadedFiles))
                this.pondpond{{ $id }}.addFiles({{ json_encode($uploadedFiles) }});
                @endif
            });
        },
    }"
    x-on:filepond-clear.window="
        if (! this.wireId || $event.detail.id !== this.wireId) {
            return;
        }
        @if ($multiple)
        this.pondpond{{ $id }}.getFiles().forEach(file => this.pondpond{{ $id }}.removeFile(file.id));
        @else
        this.pondpond{{ $id }}.removeFile();
        @endif
    "
>
    <div class="justify-self-end">
        <label for="{{ $id }}" class="form-label mr-6 whitespace-normal">{{ $label }}</label>
    </div>
    <div class="col-span-3">
        <input id="{{ $id }}" x-ref="filepond{{ $id }}" type="file" style="display: none;" {{ $attributes->except(['type'])->whereDoesntStartWith('wire') }} {{ $attributes->whereStartsWith('wire') }} {{ $multiple ? 'multiple' : '' }} accept="{{ $accept ?? '' }}">
        @error($attributes->wire('model')->value())
        <div class="mt-2">
            <small class="block mt-1 font-bold text-xs text-red-600">{{ $message ?? '' }}</small>
        </div>
        @enderror
    </div>
</div>
