<div id="wire">
    <x-slot name="header">
    </x-slot>
    <x-bliss::content-card>
        <x-slot name="full">
            <x-bliss::table :rows="$rows" :cols="$cols" :sorts="$sorts" wire:poll.750ms>
                <x-slot name="actionsRow">
                    <x-bliss::search-modal :pageOptions="$pageOptions">
                        <x-slot name="actionButtons">
                        </x-slot>
                        <x-slot name="modalTitle">
                            Advanced Filter
                        </x-slot>
                        <x-slot name="modalBody">
                            <x-bliss::search-input type="text" id="queue" label="Queue Name" wire:model.defer="filters.queue" />
                            <x-bliss::search-datepicker id="started_at" label="Started At" wire:model.defer="filters.started_at" datepicker="{
                                maxDate: 'today',
                            }" />
                            <x-bliss::search-datepicker id="ended_at" label="Ended At" wire:model.defer="filters.ended_at" datepicker="{
                                maxDate: 'today',
                            }" />
                            <x-bliss::search-select id="status" label="Status" wire:model.defer="filters.status" :options="settings('queuejob_status')" multiple />
                        </x-slot>
                        <x-slot name="modalButtons">
                            <button type="button" class="modal-button" wire:click="$emit('filterDatatable')" x-on:click="open = false">Filter</button>
                            <button type="button" class="modal-button" wire:click="resetFilters" x-on:click="open = false">
                                Reset
                            </button>
                        </x-slot>
                    </x-bliss::search-modal>
                </x-slot>
            </x-bliss::table>
        </x-slot>
    </x-bliss::content-card>
</div>
