@php
    $site = $rt->site();
    $pageTitle = isset($title) && $title ? $title.' — '.$site['name'] : $site['name'].' — '.$site['title'];
@endphp
<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $description ?? $site['description'] }}">
    <meta name="theme-color" content="#c62828">
    <link rel="icon" href="{{ $site['brand']['favicon'] }}">
    <script>
        // Pasang tema sebelum render agar tidak berkedip.
        try {
            const saved = localStorage.getItem('rt-default-theme');
            if (saved === 'dark' || (!saved && matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.dataset.theme = 'dark';
            }
        } catch (e) {}
    </script>
    @vite(['resources/templates/default/css/app.css', 'resources/templates/default/js/app.js'])
</head>
<body class="min-h-dvh">
    <div id="rt-progress" class="fixed inset-x-0 top-0 z-[70] h-0.5 bg-sun" aria-hidden="true"></div>
    <a href="#konten" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[80] focus:bg-raya focus:px-4 focus:py-2 focus:text-white">Langsung ke konten</a>

    @include('default::partials.header')

    <main id="konten">
        @yield('content')
    </main>

    @include('default::partials.footer')
    @include('default::partials.switcher')
    @include('default::partials.templates')
    @stack('templates')
</body>
</html>
