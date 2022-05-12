<div id="wire">
    <x-bliss::content-card x-data="{}">
        <x-slot name="full">
            <x-bliss::info-form>
                <x-bliss::form-input type="text" label="User" wire:model.defer="user.name" disabled />
                <x-bliss::form-input type="text" label="Model ID" wire:model.defer="model_id" disabled />
                <x-bliss::form-input type="text" label="Model" wire:model.defer="model_class" disabled />
                <x-bliss::form-input type="text" label="Created At" wire:model.defer="created_at" disabled />
                <x-bliss::form-input type="text" label="Message" wire:model.defer="message" disabled />
                <x-bliss::form-codemirror type="javascript" label="Data" wire:model.defer="data" disabled />
                <x-bliss::form-codemirror type="javascript" label="Agents" wire:model.defer="agents" disabled />
                <x-bliss::form-codemirror type="javascript" label="Ip Location" wire:model.defer="iplocation" disabled />

                <x-slot name="buttons">
                    @can('delete-audits')
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
    </x-bliss::content-card>
</div>
