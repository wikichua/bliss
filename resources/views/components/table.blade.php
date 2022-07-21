<div x-data="xTable">
    <div
    {{ $attributes->whereStartsWith('x-')->first() }}

    {{ is_bool($attributes->whereStartsWith('wire:poll')->first()) ? $attributes->wire('poll')->directive : $attributes->whereStartsWith('wire:poll') }}
    >
        {{ $actionsRow ?? '' }}
        <div class="flex flex-col">
            <div class="py-2 my-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                <div class="inline-block min-w-full overflow-hidden align-middle border-b border-gray-200 shadow sm:rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                @if ($bulkActionEnabled)
                                <th scope="col" class="p-4">
                                    <div class="flex items-center">
                                        <input id="checkbox-all" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" x-model="checkedAll"
                                        x-on:click="checkedAll = !checkedAll">
                                        <label for="checkbox-all" class="sr-only">checkbox</label>
                                    </div>
                                </th>
                                @endif
                                @foreach ($cols as $col)
                                <th scope="col" class="px-6 py-3">
                                    @if ($col['data'] == 'actionsView')
                                    <div class="inline-block">Actions</div>
                                    @else
                                    <div class="inline-block">
                                        <div class="flex">
                                            <div>{!! $col['title'] !!}</div>
                                            @if (isset($col['sortable']) && $col['sortable'])
                                            @php
                                            $showAsc = true;
                                            $showDesc = true;
                                            if (isset($sorts[$col['data']])) {
                                                if ($sorts[$col['data']] == 'asc') {
                                                    $showAsc = false;
                                                } else {
                                                    $showDesc = false;
                                                }
                                            }
                                            @endphp
                                            <div class="flex flex-col ml-1">
                                                @if ($showAsc)
                                                <svg class="w-2 h-2 cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" wire:click="$set('sorts.{{ $col['data'] }}','asc')"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 15l7-7 7 7"></path></svg>
                                                @endif
                                                @if ($showDesc)
                                                <svg class="w-2 h-2 cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" wire:click="$set('sorts.{{ $col['data'] }}','desc')"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M19 9l-7 7-7-7"></path></svg>
                                                @endif
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    @endif
                                </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="table-tbody">
                            @foreach ($rows as $row)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                @if ($bulkActionEnabled)
                                <td scope="row" class="p-4">
                                    <div class="flex items-center">
                                        <input id="checkbox-table-{{ $row->{$bulkActionKey} }}" type="checkbox" class="rowCheckboxes w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" x-model="checkedKeys" value="{{ $row->{$bulkActionKey} }}">
                                        <label for="checkbox-table-{{ $row->{$bulkActionKey} }}" class="sr-only">checkbox</label>
                                    </div>
                                </td>
                                @endif
                                @foreach ($cols as $col)
                                <td scope="row" class="px-6 py-3 {{ $col['td-class'] ?? '' }}">
                                    @if (Str::contains($col['data'], '.'))
                                    @php
                                    $cd = explode('.', $col['data']);
                                    @endphp
                                    {!! $row->{$cd[0]}->{$cd[1]} ?? null !!}
                                    @else
                                        @php
                                            $col['data'] = trim(str_replace(['(',')'],'',$col['data']));
                                        @endphp
                                        @if (method_exists($row, $col['data']) && is_callable([$row, $col['data']]))
                                        {!! $row->{$col['data']}() ?? null !!}
                                        @else
                                        {!! $row->{$col['data']} ?? null !!}
                                        @endif
                                    @endif
                                </td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="py-2 my-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                    @if (!is_array($rows) && method_exists($rows, 'links'))
                    {{ $rows->links() }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
