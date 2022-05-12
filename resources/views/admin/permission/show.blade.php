<div id="wire">
    <x-bliss::content-card x-data="{}">
        <x-slot name="left">
            <x-bliss::info-form>
                <x-bliss::form-input type="text" wire:model.defer="group" label="Group" disabled/>
                <x-bliss::form-multiple-input type="text" wire:model.defer="name" label="Permissions" :options="$name" disabled/>

                <x-slot name="buttons">
                    @can('update-permissions')
                    <x-bliss::form-link href="{{ route('permission.edit', $model->id) }}" class="mr-2">
                        Edit
                    </x-bliss::form-link>
                    @endcan
                    @can('delete-permissions')
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
