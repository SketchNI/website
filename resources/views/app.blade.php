<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title inertia>{{ config('app.name', 'Laravel') }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.bunny.net/css?family=kode-mono:400,700|sen:400,500,600,700,800&display=swap"
        crossorigin="anonymous"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Playpen+Sans:wght@100..800&display=swap" rel="stylesheet">
    <!-- Scripts -->
    @routes
    @vite(['resources/js/app.js'])
    @inertiaHead
    @stack('og')
    @stack('styles')
    @stack('scripts')
</head>
<body class="font-sans antialiased h-full">
@inertia
</body>
</html>
