<div class="block p-6 rounded-lg shadow-lg bg-white dark:bg-slate-900 max-w-full">
  <form {!! $attributes->whereStartsWith('x-') !!} {{ $attributes->whereStartsWith('wire') }} >
    <x-honeypot wire:model.defer="honeypotFields" />
    <div class="border-b-2 mb-1">
      {{ $slot }}
    </div>
    <div class="mt-2 flex justify-end">
      {{ $buttons }}
    </div>
  </form>
</div>
