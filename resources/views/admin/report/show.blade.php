<div id="wire">
    <x-bliss::content-card x-data="{}">
        <x-slot name="left">
            <x-bliss::info-form>
                <x-bliss::form-input type="text" wire:model.defer="name" label="Name" disabled />
                <x-bliss::form-input type="text" wire:model.defer="cache_ttl" label="TTL (Seconds)" placeholder="300" disabled />
                <x-bliss::form-multiple-input type="textarea" wire:model.defer="queries" label="SQL queries" :options="$queries" disabled />
                <x-bliss::form-select wire:model.defer="status" label="Status" :options="settings('report_status')" disabled />

                <x-slot name="buttons">
                    <x-bliss::form-link href="#" wire:click="onExport('{{ $model->id }}')" class="mr-2">
                        Export
                    </x-bliss::form-link>
                    @can('update-reports')
                    <x-bliss::form-link href="{{ route('report.edit', $model->id) }}" class="mr-2">
                        Edit
                    </x-bliss::form-link>
                    @endcan
                    @can('delete-reports')
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

    <x-bliss::content-card>
        <x-slot name="full">
                <div class="block p-6 rounded-lg shadow-lg bg-white dark:bg-slate-900 max-w-full">
                    <div class="mb-1">
                        <ul class="nav nav-tabs flex flex-col md:flex-row flex-wrap list-none border-b-0 pl-0 mb-4" role="tablist">
                            @foreach ($model->queries as $query)
                            <li class="nav-item" role="presentation">
                                <a href="#tabs-{{ $loop->iteration }}-content" class="nav-link w-full block font-medium text-xs leading-tight uppercase border-x-0 border-t-0 border-b-2 border-transparent px-6 py-3 my-2 hover:border-transparent hover:bg-gray-100 focus:border-transparent {{ $loop->first? 'active':'' }}" id="tabs-{{ $loop->iteration }}" data-bs-toggle="pill" data-bs-target="#tabs-{{ $loop->iteration }}-content" role="tab" aria-controls="tabs-{{ $loop->iteration }}" aria-selected="true">
                                    Query {{ $loop->iteration }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                        <div class="tab-content">
                            @foreach ($reports as $report)
                            @php
                                $cols = array_keys(collect($report)->first());
                            @endphp
                            <div class="tab-pane fade  {{ $loop->first? 'show active':'' }}" id="tabs-{{ $loop->iteration }}-content" role="tabpanel" aria-labelledby="tabs-{{ $loop->iteration }}">
                                <div class="flex flex-col">
                                    <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                                        <div class="py-2 inline-block min-w-full sm:px-6 lg:px-8">
                                            <div class="overflow-x-auto">
                                                <table class="min-w-full">
                                                    <thead class="border-b">
                                                        <tr>
                                                        @foreach ($cols as $col)
                                                            <th scope="col" class="text-sm font-medium p-4 text-left">
                                                                {{ $col }}
                                                            </th>
                                                        @endforeach
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($report as $row)
                                                        <tr class="border-b">
                                                            @foreach ($cols as $col)
                                                            <td class="text-sm font-light p-4 whitespace-normal">
                                                                {{ $row[$col] }}
                                                            </td>
                                                            @endforeach
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </x-slot>
    </x-bliss::content-card>
</div>
