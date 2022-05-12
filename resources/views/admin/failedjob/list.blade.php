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
                        <x-slot name="bulkActionButtons">
                            <button class="action-button" x-show="checkedKeys.length > 0" x-on:click="emitWithConfirmNoReauth('onBatchRetry', checkedKeys)">Retry
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </button>
                        </x-slot>
                        <x-slot name="modalTitle">
                            Advanced Filter
                        </x-slot>
                        <x-slot name="modalBody">
                            <x-bliss::search-input type="text" id="exception" label="Exception" wire:model.defer="filters.exception" />
                            <x-bliss::search-datepicker id="failed_at" label="Failed At" wire:model.defer="filters.failed_at" datepicker="{
                                maxDate: 'today',
                            }" />
                        </x-slot>
                        <x-slot name="modalButtons">
                            <button type="button" class="modal-button" wire:click="$emit('filterDatatable')" x-on:click="open = false">     Filter
                            </button>
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
