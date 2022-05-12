<div id="wire">
    <x-bliss::content-card x-data="{}">
        <x-slot name="left">
            <x-bliss::info-form>
                <x-bliss::form-input type="text" wire:model.defer="key" label="Key" disabled />
                <x-bliss::form-checkbox wire:model.defer="protected" label="Apply Encryption" disabled />
                <x-bliss::form-checkbox wire:model="useKeyvalue" label="Use Key Value Type" checkedLabel="Yes" uncheckedLabel="No" disabled />
                @if (!$useKeyvalue)
                <x-bliss::form-input type="text" wire:model.defer="value" label="Value" disabled />
                @else
                <x-bliss::form-multiple-keyvalue-input wire:model.defer="keyvalue" label="Keys & Values" :options="$keyvalue" rows="1" class="h-10" disabled />
                @endif

                <x-slot name="buttons">
                    @can('update-settings')
                    <x-bliss::form-link href="{{ route('setting.edit', $model->id) }}" class="mr-2">
                        Edit
                    </x-bliss::form-link>
                    @endcan
                    @can('delete-settings')
                    <x-bliss::form-link href="#" class="mr-2" x-on:click="emitWithConfirm('onDelete','{{ $model->id }}', '{{ url()->previous() }}')">
                        Delete
                    </x-bliss::form-link>
                    @endcan
                    <x-bliss::form-link href="{{ url()->previous() }}">
                        Back
                    </x-bliss::form-link>
                </x-slot>
            </x-bliss:info-form>
        </x-slot>

        <x-slot name="right">
            <x-bliss::info-card :data="$infoData"/>
        </x-slot>
    </x-bliss::content-card>
</div>
