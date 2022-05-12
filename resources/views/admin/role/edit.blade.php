<div id="wire">
    <x-bliss::content-card>
        <x-slot name="left">
            <x-bliss::form x-on:submit.prevent="emitWithoutConfirm('onSubmit')">
                <x-bliss::form-input type="text" wire:model.defer="name" label="Name" />
                {{-- <x-bliss::form-select wire:model.defer="admin" label="Is Admin" :options="['No','Yes']" /> --}}
                <x-bliss::form-row label="Permissions" class="items-start">
                    @foreach ($groupPermissions as $group => $groupPermission)
                    <x-bliss::form-checkboxes wire:model.defer="permissions" :wire:key="$loop->index" :label="$group" :options="$groupPermission" />
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
            <x-bliss::info-card :data="$infoData"/>
        </x-slot>
    </x-bliss::content-card>
</div>
