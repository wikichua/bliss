@props(['reauthEnabled' => $reauthEnabled ?? false, 'scripts' => '', 'styles' => ''])
<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <livewire:styles />
        {{ $styles ?? '' }}
        @stack('styles')

        <livewire:scripts />
        <script src="https://cdn.jsdelivr.net/gh/livewire/turbolinks@v0.1.x/dist/livewire-turbolinks.js" data-turbolinks-eval="false" data-turbo-eval="false"></script>
    </head>
    <body class="font-sans antialiased"
        x-data="xHtml(@js($reauthEnabled))"
        x-bind:class="{dark: darkMode == true}"
        x-cloak
    >
        <div>
            {{ $slot }}
        </div>

        {{ $scripts ?? '' }}
        @stack('scripts')
    </body>
</html>
