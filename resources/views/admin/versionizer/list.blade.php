<div id="wire">
    <x-slot name="header">
    </x-slot>
    <x-bliss::content-card>
        <x-slot name="full">
            <x-bliss::table :rows="$rows" :cols="$cols" :sorts="$sorts">
                <x-slot name="actionsRow">
                    <x-bliss::search-modal :pageOptions="$pageOptions">
                        <x-slot name="actionButtons">
                        </x-slot>
                        <x-slot name="modalTitle">
                            Advanced Filter
                        </x-slot>
                        <x-slot name="modalBody">
                            <x-bliss::search-input type="text" id="name" label="Changes" wire:model.defer="filters.changes" />
                            <x-bliss::search-datepicker id="created_at" label="Created At" wire:model.defer="filters.created_at" datepicker="{
                                maxDate: 'today',
                            }" />
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
