<div class="text-center text-nowrap">
    @can('read-personal-access-token')
    <a href="{{ route('user.personal-access-token.list', $model->id) }}" class="action-link" title="Edit">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
    </a>
    @endcan
    <a href="{{ route('user.show', $model->id) }}" class="action-link" title="Read">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
    </a>
    @can('update-users')
    <a href="{{ route('user.edit', $model->id) }}" class="action-link" title="Edit">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
    </a>
    @endcan
    @can('update-users-password')
    <a href="{{ route('user.edit.password', $model->id) }}" class="action-link" title="Edit Password">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.618 5.984A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016zM12 9v2m0 4h.01"></path></svg>
    </a>
    @endcan
    @can('delete-users')
    <a href="#" class="action-link" title="Delete" x-on:click="emitWithConfirm('onDelete','{{ $model->id }}')">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
    </a>
    @endcan
    @can('impersonate-users')
    <a href="#" class="action-link" title="Delete" x-on:click="emitToWithConfirm('header-profile','onImpersonate','{{ $model->id }}')">
        <svg viewBox="0 0 576 512" class="w-4 h-4" fill="currentColor" stroke="currentColor" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M288 64C39.52 64 0 182.1 0 273.5C0 379.5 78.8 448 176 448c27.33 0 51.21-6.516 66.11-36.79l19.93-40.5C268.3 358.6 278.1 352.4 288 352.1c9.9 .3711 19.7 6.501 25.97 18.63l19.93 40.5C348.8 441.5 372.7 448 400 448c97.2 0 176-68.51 176-174.5C576 182.1 536.5 64 288 64zM160 320c-35.35 0-64-28.65-64-64s28.65-64 64-64c35.35 0 64 28.65 64 64S195.3 320 160 320zM416 320c-35.35 0-64-28.65-64-64s28.65-64 64-64c35.35 0 64 28.65 64 64S451.3 320 416 320z"/></svg>
    </a>
    @endcan
</div>
