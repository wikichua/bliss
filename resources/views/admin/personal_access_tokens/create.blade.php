<div id="wire">
    <x-bliss::content-card>
        <x-slot name="left">
            <x-bliss::form x-on:submit.prevent="emitWithoutConfirm('onSubmit')">
                <x-bliss::form-input type="text" wire:model.defer="name" label="Name" />
                <x-bliss::form-row label="Abilities" class="items-start">
                    @foreach ($groupAbilities as $group => $groupAbility)
                    <x-bliss::form-checkboxes wire:model.defer="abilities" :wire:key="$loop->index" :label="$group" :options="$groupAbility" />
                    @endforeach
                </x-bliss::form-row>

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
