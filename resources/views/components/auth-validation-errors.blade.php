@props(['errors'])

@if ($errors->any())
@php
    $title = '<div class="text-red-600">'.__('Whoops! Something went wrong.').'</div>';
    $html = '<ul class="mt-3 list-inside text-md font-bold text-red-600">';
    if (count($errors->all()) > 1)
    {
        foreach ($errors->all() as $error) {
            $html .= '<li>'.$error.'</li>';
        }
    } else {
        $html .= $errors->first();
    }
    $html .= '</ul>';
@endphp
<span x-data="{
    init() {
        Swal.fire({
          title: '{{ $title }}',
          icon: 'error',
          html: '{{ $html }}',
          showCloseButton: true,
          focusConfirm: false
        });
    }
}"></span>
@endif
