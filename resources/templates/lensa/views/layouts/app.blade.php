@php
    $site = $rt->site();
    $overlay = $overlay ?? false;
    $pageTitle = isset($title) && $title ? $title.' — '.$site['name'] : $site['name'].' — '.$site['title'];
@endphp
<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $description ?? $site['description'] }}">
    <meta name="theme-color" content="#111111">
    <script>
        // Tema bawaan Lensa gelap; pilihan terang disimpan per pembaca.
        try {
            if (localStorage.getItem('rt-lensa-theme') === 'light') {
                document.documentElement.dataset.theme = 'light';
            }
        } catch (e) {}
    </script>
    <link rel="icon" href="{{ $site['brand']['favicon'] }}">
    @vite(['resources/templates/lensa/css/app.css', 'resources/templates/lensa/js/app.js'])
</head>
<body class="min-h-dvh pb-20 lg:pb-0">
    <div id="ls-progress" class="fixed inset-x-0 top-0 z-[80] h-[3px] origin-left scale-x-0 bg-sun" aria-hidden="true"></div>
    <a href="#konten" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[90] focus:rounded-full focus:bg-sun focus:px-4 focus:py-2 focus:text-ground">Langsung ke konten</a>

    @include('lensa::partials.header', ['overlay' => $overlay])

    <main id="konten" @class(['pt-16 lg:pt-20' => ! $overlay])>
        @yield('content')
    </main>

    @include('lensa::partials.footer')
    @include('lensa::partials.search')
    @include('lensa::partials.tabbar')
    @include('lensa::partials.switcher')
    @include('lensa::partials.templates')
</body>
</html>
