<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title inertia>{{ config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link
        href="https://fonts.bunny.net/css?family=anonymous+pro:400,700|sen:400,500,600,700,800|Nunito+Sans:wght@100..800&display=swap"
        crossorigin="anonymous"
        rel="stylesheet" />
    @routes
    @vite(['resources/js/app.js'])
    @inertiaHead
    @stack('og')
    @stack('styles')
    @stack('scripts')

    <script>
        var _paq = window._paq = window._paq || [];
        _paq.push(['trackPageView']);
        _paq.push(['enableLinkTracking']);
        (function() {
            var u="//analytics.sketchni.uk/";
            _paq.push(['setTrackerUrl', u+'matomo.php']);
            _paq.push(['setSiteId', '1']);
            var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
            g.async=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
        })();
    </script>
</head>
<body class="font-sans antialiased h-full">
@inertia
</body>
</html>
