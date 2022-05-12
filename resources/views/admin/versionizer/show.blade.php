<div id="wire">
    <x-bliss::content-card x-data="{}">
        <x-slot name="left">
            <x-bliss::info-form>
                <x-bliss::form-codemirror type="javascript" label="Data" wire:model.defer="data" disabled />
                <x-bliss::form-codemirror type="javascript" label="Changes" wire:model.defer="changes" disabled />

                <x-slot name="buttons">
                    @can('revert-versionizers')
                    <x-bliss::form-link href="#" class="mr-2" x-on:click="emitWithConfirm('onRevert','{{ $model->id }}')">
                        Revert
                    </x-bliss::form-link>
                    @endcan
                    @can('delete-versionizers')
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
