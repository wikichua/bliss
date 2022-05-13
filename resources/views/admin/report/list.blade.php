<div id="wire">
    <x-slot name="header">
    </x-slot>
    <x-bliss::content-card>
        <x-slot name="full">
            <x-bliss::table :rows="$rows" :cols="$cols" :sorts="$sorts" wire:poll.750ms>
                <x-slot name="actionsRow">
                    <x-bliss::search-modal :pageOptions="$pageOptions">
                        <x-slot name="actionButtons">
                            <a class="action-button" href="{{ route('report.create') }}">Create <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></a>
                        </x-slot>
                        <x-slot name="modalTitle">
                            Advanced Filter
                        </x-slot>
                        <x-slot name="modalBody">
                            <x-bliss::search-input type="text" id="name" label="Name" wire:model.defer="filters.name" />
                            <x-bliss::search-select id="status" label="Status" multiple wire:model.defer="filters.status" :options="settings('report_status')" />
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
