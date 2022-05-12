<div id="wire">
    <x-bliss::content-card x-data="{}">
        <x-slot name="left">
            <x-bliss::form x-on:submit.prevent="emitWithoutConfirm('onSubmit')">
                <x-bliss::form-input type="text" wire:model.defer="name" label="Name" />
                <x-bliss::form-input type="text" wire:model.defer="cache_ttl" label="TTL (Seconds)" placeholder="300" />
                <x-bliss::form-multiple-input type="textarea" wire:model.defer="queries" label="SQL queries" :options="$queries" />
                <x-bliss::form-select wire:model.defer="status" label="Status" :options="settings('report_status')" />

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
            <x-bliss::info-card :data="$infoData"/>
        </x-slot>
    </x-bliss::content-card>
</div>
