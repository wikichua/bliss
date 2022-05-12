<div class="text-nowrap">
    <div class="flex justify-center">
      <ul class="w-full">
        @foreach ($model->changes as $key => $change)
        <li>{{ $key }} : {{ (string)$change }}</li>
        @endforeach
      </ul>
    </div>
</div>
