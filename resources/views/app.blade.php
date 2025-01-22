<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title inertia>{{ config('app.name', 'Laravel') }}</title>
    <!-- Fonts -->
    {{--<link rel="preconnect" href="https://fonts.bunny.net">
    <link
        href="https://fonts.bunny.net/css?family=nunito:300,400,500,600,700|red-hat-mono:300,400,500,600,700&display=swap"
        crossorigin="anonymous"
        rel="stylesheet" />--}}
    <!-- Scripts -->
    @routes
    @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
    @inertiaHead
    @stack('og')
    @stack('styles')
    @stack('scripts')
</head>
<body class="font-sans antialiased h-full">
@inertia
</body>
</html>
