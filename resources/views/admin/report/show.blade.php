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
                    <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
                        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="{{ uniqid() }}" data-tabs-toggle="#reportTab" role="tablist">
                            @foreach ($model->queries as $query)
                            <li class="mr-2" role="presentation">
                                <button class="inline-block p-4 rounded-t-lg border-b-2 text-blue-600 hover:text-blue-600 dark:text-blue-500 dark:hover:text-blue-500 border-blue-600 dark:border-blue-500" id="report-{{ $loop->iteration }}-tab" data-tabs-target="#report-{{ $loop->iteration }}" type="button" role="tab" aria-controls="report-{{ $loop->iteration }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                    Query {{ $loop->iteration }}
                                </button>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    <div id="reportTab">
                        @foreach ($reports as $report)
                        @php
                            $cols = array_keys(collect($report)->first());
                        @endphp
                        <div class="p-4 bg-gray-50 rounded-lg dark:bg-gray-800" id="report-{{ $loop->iteration }}" role="tabpanel" aria-labelledby="report-{{ $loop->iteration }}-tab">
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
                        @endforeach
                    </div>
                </div>
            </div>
        </x-slot>
    </x-bliss::content-card>
</div>
