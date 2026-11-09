@php
    $site = $rt->site();
    $pageTitle = isset($title) && $title ? $title.' — '.$site['name'] : $site['name'].' — '.$site['title'];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $description ?? $site['description'] }}">
    <meta name="theme-color" content="#ffffff">
    <link rel="icon" href="{{ $site['brand']['favicon'] }}">
    @vite(['resources/templates/wacana/css/app.css', 'resources/templates/wacana/js/app.js'])
</head>
<body class="min-h-dvh">
    <a href="#konten" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[90] focus:bg-ink focus:px-4 focus:py-2 focus:text-paper">Langsung ke konten</a>
    @include('wacana::partials.masthead')
    <main id="konten">
        @yield('content')
    </main>
    @include('wacana::partials.footer')
    @include('wacana::partials.switcher')
    @include('wacana::partials.templates')
</body>
</html>
