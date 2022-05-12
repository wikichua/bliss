<div>
    @if (auth()->user()->avatar != '')
    <img class="h-full w-full object-cover" src="{{ url(auth()->user()->avatar) }}">
    @else
    <img class="h-full w-full object-cover" src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}">
    @endif
</div>
