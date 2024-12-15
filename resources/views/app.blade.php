<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="bg-base h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title inertia>{{ config('app.name', 'Laravel') }}</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=nunito:300,400,500,600,700|red-hat-mono:300,400,500,600,700&display=swap" crossorigin="anonymous"
              rel="stylesheet" />
        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
        @stack('og')
        @stack('styles')
        @stack('scripts')
    </head>
    <body class="font-sans antialiased mocha h-full">
        @inertia
    </body>
    <footer class="max-w-6xl my-6 text-center md:flex text-subtext0 items-center justify-between mx-auto">
        <p class="text-sm">&copy; SketchNI {{ date("Y") }}</p>
        <p class="text-sm">Built with
            <a href="https://laravel.com" target="_blank" class="link">Laravel</a>,
            <a href="https://inertiajs.com/" target="_blank" class="link">InertiaJS</a>,
            <a href="https://vuejs.org" target="_blank" class="link">VueJS</a> and
            <a href="https://tailwindcss.com" target="_blank" class="link">TailwindCSS</a>
        </p>
    </footer>
</html>
