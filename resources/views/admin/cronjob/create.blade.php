<div id="wire">
    <x-bliss::content-card>
        <x-slot name="left">
            <x-bliss::form x-on:submit.prevent="emitWithoutConfirm('onSubmit')">
                <x-bliss::form-input type="text" wire:model.defer="name" label="Name" />
                <x-bliss::form-textarea type="text" wire:model.defer="command" label="Command" />
                <x-bliss::form-select wire:model.defer="mode" label="Mode" :options="$modeOptions" />
                <x-bliss::form-select wire:model.defer="timezone" label="Timezone" :options="timezones()" />
                <x-bliss::form-select wire:model.defer="frequency" label="Frequency" :options="cronjob_frequencies()" />
                <x-bliss::form-select wire:model.defer="status" label="Status" :options="settings('cronjob_status')" />

                <x-slot name="buttons">
                    <x-bliss::form-link href="#" class="mr-2" x-on:click="history.back()">
                        Back
                    </x-bliss::form-link>
                    <x-bliss::form-button type="submit">
                        Submit
                    </x-bliss::form-button>
                </x-slot>
            </x-bliss::form>
        </x-slot>
        <x-slot name="right">

        </x-slot>
    </x-bliss::content-card>
</div>
