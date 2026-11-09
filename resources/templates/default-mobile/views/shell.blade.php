@php
    $site = $rt->site();
    $roots = ['home' => 'Home', 'saved' => 'Tersimpan', 'notifications' => 'Notifikasi', 'profile' => 'Profil'];
    $activeTab = array_key_exists($screen, $roots) ? $screen : 'home';
    $isRoot = array_key_exists($screen, $roots);
    $sampleArticle = '/nusantara/114-korban-virgo-transport-8-ditemukan-129-masih-dicari';
    $jumps = [
        ['Terbaru', '', 'terbaru'],
        ['Unggulan', '', 'unggulan'],
        ['Untuk Anda', '', 'untuk-anda'],
        ['Detail artikel', $sampleArticle, null],
        ['Halaman kanal', '/@politik', null],
        ['Pencarian', '/search', null],
        ['Tersimpan', '/tersimpan', null],
        ['Notifikasi', '/notifikasi', null],
        ['Profil', '/profil', null],
    ];
@endphp
<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>Mockup Aplikasi Default — {{ $site['name'] }}</title>
    <meta name="description" content="Mockup aplikasi mobile RayaTimes, template Default, dalam bingkai iPhone ukuran asli.">
    <meta name="robots" content="noindex">
    <link rel="icon" href="{{ $site['brand']['favicon'] }}">
    <script>
        try {
            if (JSON.parse(localStorage.getItem('rt-dm-theme')) === 'dark') {
                document.documentElement.dataset.theme = 'dark';
            }
        } catch (e) {}
    </script>
    @vite(['resources/templates/default-mobile/css/app.css', 'resources/templates/default-mobile/js/app.js'])
</head>
<body>
<div class="stage">
    <aside class="stage-side">
        <img src="{{ $site['brand']['logo'] }}" alt="RayaTimes portal" class="h-11 w-auto">
        <h1 class="stage-title">Mockup aplikasi</h1>
        <p class="stage-note">iPhone 16, 393 × 852 pt, ukuran asli.</p>

        <nav class="stage-switch" aria-label="Template aplikasi">
            @foreach ($templates as $key => $info)
                <a href="/{{ $key }}" @if ($key === $template) aria-current="true" @endif>
                    <b>{{ $info['name'] }}</b><span>{{ $info['note'] }}</span>
                </a>
            @endforeach
        </nav>
    </aside>

    <div class="device" data-device>
        <span class="device-btn device-btn-action"></span>
        <span class="device-btn device-btn-up"></span>
        <span class="device-btn device-btn-down"></span>
        <span class="device-btn device-btn-power"></span>

        <div id="vp" data-sb="light">
            @include('default-mobile::partials.statusbar')

            <div class="tabroot" data-tabroot>
                <div class="tabpanels">
                    @foreach ($roots as $key => $label)
                        <section class="tabscreen" data-tab-screen="{{ $key }}" data-active="{{ $key === $activeTab ? 'true' : 'false' }}">
                            @if ($key === 'home' || $key === $activeTab)
                                @include("default-mobile::screens.{$key}")
                            @endif
                        </section>
                    @endforeach
                </div>
                @include('default-mobile::partials.tabbar', ['roots' => $roots, 'activeTab' => $activeTab])
            </div>

            <div class="stack" data-stack>
                @unless ($isRoot)
                    <section class="screen" data-entering="false" data-url="{{ request()->getRequestUri() }}">
                        @include("default-mobile::screens.{$screen}")
                    </section>
                @endunless
            </div>

            <div class="scrim" data-scrim data-open="false"></div>
            @include('default-mobile::partials.drawer')
            @include('default-mobile::partials.sheets')
            <div class="toast" data-toast data-open="false" role="status" aria-live="polite"></div>
            <span class="home-indicator" aria-hidden="true"></span>
        </div>
    </div>

    <aside class="stage-side stage-side-right">
        <p class="stage-label">Coba layar</p>
        <nav class="stage-jumps" aria-label="Pintasan layar">
            @foreach ($jumps as [$label, $path, $page])
                <a href="{{ $rt->url($path ?: '/') }}" @if ($page) data-jump-page="{{ $page }}" @endif>{{ $label }}<i class="ri-arrow-right-s-line"></i></a>
            @endforeach
        </nav>
        <button type="button" class="stage-fit" data-fit aria-pressed="false"><i class="ri-fullscreen-line"></i><span>Muat di layar</span></button>
    </aside>
</div>
</body>
</html>
