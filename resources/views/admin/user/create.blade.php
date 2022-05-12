<div id="wire">
    <x-bliss::content-card>
        <x-slot name="left">
            <x-bliss::form x-on:submit.prevent="emitWithoutConfirm('onSubmit')">
                <x-bliss::form-input type="text" wire:model.defer="name" label="Full Name" />
                <x-bliss::form-input type="email" wire:model.defer="email" label="Email" />
                <x-bliss::form-input type="password" wire:model.defer="password" label="Password" />
                <x-bliss::form-input type="password" wire:model.defer="password_confirmation" label="Password Confirmation" />
                <x-bliss::form-select wire:model.defer="timezone" label="Timezone" :options="timezones()" />
                {{-- <x-bliss::form-grouped-checkboxes wire:model.defer="roles" label="Roles" :options="$rolesOptions" /> --}}
                <x-bliss::form-checkboxes wire:model.defer="roles" label="Roles" :options="$rolesOptions" />

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
