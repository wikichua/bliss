<div>
    <x-bliss::generic-card>
        <x-bliss::info-form>
            <x-bliss::form-input label="Search" wire:model.debounce.500ms="search" />
        </x-bliss::info-form>
        <x-bliss::info-form class="dark:bg-white rounded-t-none">
            @if ($searches)
                <span class="text-black font-black text-3xl">searching</span>
                <ul class="bg-white rounded-lg border border-gray-200 w-98 text-gray-900">
                    @foreach ($searches as $model)
                    <li class="px-6 py-2 w-full {{ $loop->first ? 'border-b border-gray-200 rounded-t-lg' : ($loop->last ? 'rounded-b-lg' : 'border-b border-gray-200') }}">{{ $model->name }}, {{ $model->group }}</li>
                    @endforeach
                </ul>
            @endif
            <br />
            @if ($searchesInModel)
                <span class="text-black font-black text-3xl">searchInModel</span>
                <ul class="bg-white rounded-lg border border-gray-200 w-98 text-gray-900">
                    @foreach ($searchesInModel as $model)
                    <li class="px-6 py-2 w-full {{ $loop->first ? 'border-b border-gray-200 rounded-t-lg' : ($loop->last ? 'rounded-b-lg' : 'border-b border-gray-200') }}">{{ $model->name }}, {{ $model->group }}</li>
                    @endforeach
                </ul>
            @endif
        </x-bliss::info-form>
    </x-bliss::generic-card>
</div>
