<div id="wire">
    <x-bliss::content-card x-data="{}">
        <x-slot name="left">
            <x-bliss::info-form>
                <x-bliss::form-input type="text" label="UUID" wire:model.defer="uuid" disabled />
                <x-bliss::form-input type="text" label="Connection" wire:model.defer="connection" disabled />
                <x-bliss::form-input type="text" label="Queue Name" wire:model.defer="queue" disabled />
                <x-bliss::form-codemirror type="javascript" label="Payload" wire:model.defer="payload" disabled />
                <x-bliss::form-codemirror type="javascript" label="Exception" wire:model.defer="exception" disabled />

                <x-slot name="buttons">
                    @can('retry-failedjobs')
                    <x-bliss::form-link href="#" class="mr-2" x-on:click="emitWithConfirmNoReauth('onRetry','{{ $model->id }}', '{{ url()->previous() }}')">
                        Retry
                    </x-bliss::form-link>
                    @endcan
                    @can('delete-failedjobs')
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
