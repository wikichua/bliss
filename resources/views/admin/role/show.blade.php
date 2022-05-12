<div id="wire">
    <x-bliss::content-card x-data="{}">
        <x-slot name="left">
            <x-bliss::info-form>
                <x-bliss::form-input type="text" wire:model.defer="name" label="Name" disabled />
                <x-bliss::form-select wire:model.defer="admin" label="Is Admin" :options="['No','Yes']" disabled />
                <x-bliss::form-row label="Permissions" class="items-start">
                    @foreach ($groupPermissions as $group => $groupPermission)
                    <x-bliss::form-checkboxes wire:model.defer="permissions" :wire:key="$loop->index" :label="$group" :options="$groupPermission" disabled />
                    @endforeach
                </x-bliss::form-row>

                <x-slot name="buttons">
                    @can('update-roles')
                    <x-bliss::form-link href="{{ route('role.edit', $model->id) }}" class="mr-2">
                        Edit
                    </x-bliss::form-link>
                    @endcan
                    @can('delete-roles')
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
