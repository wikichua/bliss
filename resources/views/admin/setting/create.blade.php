<div id="wire">
    <x-bliss::content-card x-data="{
        useKeyvalue: {{ $useKeyvalue ? 'true' : 'false' }},
    }">
        <x-slot name="left">
            <x-bliss::form x-on:submit.prevent="emitWithoutConfirm('onSubmit')">
                <x-bliss::form-input type="text" wire:model.defer="key" label="Key" />
                <x-bliss::form-checkbox wire:model.defer="protected" label="Apply Encryption" />
                <x-bliss::form-checkbox wire:model.defer="useKeyvalue" x-model="useKeyvalue" label="Use Key Value Type" checkedLabel="Yes" uncheckedLabel="No" />
                <span x-show="!useKeyvalue">
                    <x-bliss::form-input type="text" wire:model.defer="value" label="Value" />
                </span>
                <span x-show="useKeyvalue">
                    <x-bliss::form-multiple-input type="textarea" wire:model.defer="keyvalue" label="Keys & Values" :options="$keyvalue" />
                </span>

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
