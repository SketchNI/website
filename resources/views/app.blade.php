<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
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
    <body class="font-sans antialiased {{ session('theme') ?? 'mocha' }} bg-base h-full">
        @inertia
    </body>
    <footer class="max-w-6xl my-6 text-center md:flex text-subtext0 items-center justify-between mx-auto">
        <div class="inline-flex space-x-3 items-center">
            <p class="text-sm">&copy; SketchNI {{ date("Y") }}</p>

            <div>
                <form action="{{ route('set-theme') }}" method="post">
                    @csrf
                    <label for="theme" class="text-subtext0">Theme</label>
                    <select name="theme" id="theme" class="bg-crust text-text px-1 py-0.5 text-sm w-28">
                        <option {{ session('theme') === 'mocha' ? 'selected' : '' }} value="mocha">Mocha</option>
                        <option {{ session('theme') === 'macchiato' ? 'selected' : '' }} value="macchiato">Macchiato</option>
                        <option {{ session('theme') === 'frappe' ? 'selected' : '' }} value="frappe">Frappe</option>
                        <option {{ session('theme') === 'latte' ? 'selected' : '' }} value="latte">Latte</option>
                    </select>
                    <button type="submit" class="border border-blue/60 px-2 py-0.5 text-sm hover:bg-crust transition duration-150 ease-in">Save</button>
                </form>
            </div>
        </div>
        <p class="text-sm">Built with
            <a href="https://laravel.com" target="_blank" class="link">Laravel</a>,
            <a href="https://inertiajs.com/" target="_blank" class="link">InertiaJS</a>,
            <a href="https://vuejs.org" target="_blank" class="link">VueJS</a> and
            <a href="https://tailwindcss.com" target="_blank" class="link">TailwindCSS</a>
        </p>
    </footer>
</html>
