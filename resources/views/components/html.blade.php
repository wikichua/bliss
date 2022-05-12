@props(['reauthEnabled' => $reauthEnabled ?? false, 'scripts' => '', 'styles' => ''])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <livewire:styles />
        {{ $styles ?? '' }}
        @stack('styles')

        <!-- Scripts -->
        <livewire:scripts />
        <script src="https://cdn.jsdelivr.net/gh/livewire/turbolinks@v0.1.x/dist/livewire-turbolinks.js" data-turbo-eval="false" data-turbolinks-eval="false"></script>
        <script src="{{ asset('js/app.js') }}"></script>
        <script src="{{ asset('js/alpine.js') }}" defer></script>
    </head>
    <body class="font-sans antialiased"
        x-bind:class="{dark: darkMode == true}"
        x-data="xHtml(@js($reauthEnabled))"
        x-cloak
    >
        {{ $slot }}

        {{ $scripts ?? '' }}
        @stack('scripts')
    </body>
</html>
