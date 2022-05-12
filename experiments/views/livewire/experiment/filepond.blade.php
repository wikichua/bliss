<div>
    <x-bliss::generic-card>
        <x-bliss::info-form>
            <x-bliss::form-filepond label="Experiment File" wire:model.defer="expFile" :files="[$expFile]" accept="image/jpg,image/jpeg,image/png" />
            <x-bliss::form-filepond label="Experiment Files" wire:model.defer="expFiles" :files="$expFiles" multiple accept="image/jpg,image/jpeg,image/png" />

            <button type="button" wire:click="onSubmit" class="action-button">Submit</button>
            <button type="button" wire:click="onFlush" class="action-button">Flush / Delete</button>
        </x-bliss::info-form>
    </x-bliss::generic-card>
</div>
