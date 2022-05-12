@props(['pageOptions'])
<select
      {{ $attributes->whereStartsWith('wire:model') }}
      {{ $attributes->merge(['class' => 'action-button']) }}
      >
      <option disabled># Per Page</option>
      @foreach ($pageOptions as $pageOption)
      <option value="{{ $pageOption }}">{{ $pageOption }}</option>
      @endforeach
</select>
