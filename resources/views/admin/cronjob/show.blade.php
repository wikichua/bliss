<div id="wire">
    <x-bliss::content-card x-data="{}">
        <x-slot name="left">
            <x-bliss::info-form>
                <x-bliss::form-input type="text" wire:model.defer="name" label="Name" disabled />
                <x-bliss::form-textarea type="text" wire:model.defer="command" label="Command" disabled />
                <x-bliss::form-select wire:model.defer="mode" label="Mode" :options="$modeOptions" disabled />
                <x-bliss::form-select wire:model.defer="timezone" label="Timezone" :options="timezones()" disabled />
                <x-bliss::form-select wire:model.defer="frequency" label="Frequency" :options="cronjob_frequencies()" disabled />
                <x-bliss::form-select wire:model.defer="status" label="Status" :options="settings('cronjob_status')" disabled />

                <x-slot name="buttons">
                    @can('update-cronjobs')
                    <x-bliss::form-link href="{{ route('cronjob.edit', $model->id) }}" class="mr-2">
                        Edit
                    </x-bliss::form-link>
                    @endcan
                    @can('delete-cronjobs')
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
