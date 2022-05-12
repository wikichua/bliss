<div class="flex flex-wrap">
@foreach ($permissions as $permission)
    <div class="text-xs inline-block p-1 m-1 leading-none text-center whitespace-nowrap align-center font-bold bg-gray-300 dark:bg-slate-500 dark:text-gray-50 rounded-full">Can {{ \Str::headline($permission) }}</div>
@endforeach
</div>
