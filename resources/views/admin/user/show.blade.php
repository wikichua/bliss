<div id="wire">
    <x-bliss::content-card x-data="{}">
        <x-slot name="left">
            <x-bliss::info-form>
                <x-bliss::form-input type="text" wire:model.defer="name" label="Full Name" disabled />
                <x-bliss::form-input type="email" wire:model.defer="email" label="Email" disabled />
                <x-bliss::form-select wire:model.defer="timezone" label="Timezone" :options="timezones()" disabled />
                <x-bliss::form-select wire:model.defer="status" label="Status" :options="settings('user_status')" disabled />
                <x-bliss::form-checkboxes wire:model.defer="roles" label="Roles" :options="$rolesOptions" disabled />

                <x-slot name="buttons">
                    @can('read-personal-access-token')
                    <x-bliss::form-link href="{{ route('user.personal-access-token.list', $model->id) }}" class="mr-2">
                        Personal Access Token
                    </x-bliss::form-link>
                    @endcan
                    @can('update-users')
                    <x-bliss::form-link href="{{ route('user.edit', $model->id) }}" class="mr-2">
                        Edit
                    </x-bliss::form-link>
                    @endcan
                    @can('update-users-password')
                    <x-bliss::form-link href="{{ route('user.edit.password', $model->id) }}" class="mr-2">
                        Edit Password
                    </x-bliss::form-link>
                    @endcan
                    @can('delete-users')
                    <x-bliss::form-link href="#" class="mr-2" x-on:click="emitWithConfirm('onDelete','{{ $model->id }}', '{{ url()->previous() }}')">
                        Delete
                    </x-bliss::form-link>
                    @endcan
                    <x-bliss::form-link href="{{ url()->previous() }}">
                        Back
                    </x-bliss::form-link>
                </x-slot>
            </x-bliss:info-form>
        </x-slot>

        <x-slot name="right">
            <x-bliss::info-card :data="$infoData"/>
        </x-slot>
    </x-bliss::content-card>
</div>
