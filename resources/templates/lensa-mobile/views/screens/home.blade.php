@php
    $site = $rt->site();
    $headlines = $rt->headlines(5);
    $exclude = array_column($headlines, 'slug');
    $latest = $rt->latest(10, 0, $exclude);
    $featured = ['politik', 'ekonomi', 'hankam', 'hukum', 'analisis'];
    $lastBand = $rt->dateBand(end($latest)['published']);
@endphp
<div class="scr" data-sb="auto">
    {{-- Header: kiri panel kanal, tengah logo, kanan cari dan mode gelap. --}}
    <header class="bar">
        <button type="button" class="bar-btn" data-drawer-open aria-label="Buka kanal, halaman, dan Insight"><i class="ri-menu-2-line"></i></button>
        <span class="bar-brand">
            <img src="{{ $site['brand']['logo_main_dark'] }}" alt="RayaTimes" class="bar-logo">
        </span>
        <div class="bar-end">
            <a href="{{ $rt->url('/search') }}" class="bar-btn" aria-label="Cari berita"><i class="ri-search-line"></i></a>
            <button type="button" class="bar-btn" data-theme-toggle aria-label="Ganti mode gelap"><i class="ri-moon-line theme-light"></i><i class="ri-sun-line theme-dark"></i></button>
        </div>
    </header>

    {{-- Menu --}}
    <div class="menu" role="tablist">
        <button type="button" class="menu-item" role="tab" data-pg="terbaru" aria-selected="false">Terbaru</button>
        <button type="button" class="menu-item" role="tab" data-pg="unggulan" aria-selected="true">Unggulan</button>
        <button type="button" class="menu-item" role="tab" data-pg="untuk-anda" aria-selected="false">Untuk Anda</button>
    </div>

    {{-- Content --}}
    <div class="pager" data-pager data-start="unggulan" data-drag="page">
        <div class="page scroll" data-page="terbaru" data-scroll>
            <div class="hero" data-carousel-root data-theme="dark">
                <div class="hero-track" data-carousel data-drag="page" data-duration="6500">
                    @foreach ($headlines as $article)
                        <a href="{{ $article['url'] }}" class="hero-slide">
                            <img src="{{ $article['image']['lg'] }}" alt="" @if ($loop->first) fetchpriority="high" @else loading="lazy" @endif>
                            <span class="hero-text">
                                <span class="hero-label"><i class="ri-focus-3-line"></i>{{ $article['category']['name'] }}</span>
                                <span class="hero-title">{{ $article['title'] }}</span>
                                <span class="hero-excerpt">{{ $article['excerpt'] }}</span>
                            </span>
                        </a>
                    @endforeach
                </div>
                <div class="hero-bars">
                    @foreach ($headlines as $article)
                        <button type="button" data-dot aria-label="Sorotan {{ $loop->iteration }} dari {{ $loop->count }}"></button>
                    @endforeach
                </div>
            </div>

            {{-- Banner foto dengan teks di atasnya, lalu pembatas sebelum daftar Terbaru --}}
            <section class="home-banners" aria-label="Banner">
                @foreach ($rt->featureBanners() as $banner)
                    <a href="{{ $banner['url'] }}" @if ($banner['is_external']) target="_blank" rel="noopener" @endif class="home-banner">
                        <img src="{{ $banner['image'] }}" alt="" loading="lazy">
                        <span class="home-banner-text">
                            <span class="home-banner-copy">
                                <span class="home-banner-title">{{ $banner['title'] }}</span>
                                <span class="home-banner-note">{{ $banner['text'] }}</span>
                            </span>
                            <i class="ri-arrow-right-up-line home-banner-visit" aria-hidden="true"></i>
                        </span>
                    </a>
                @endforeach
            </section>

            <div data-feed data-scope="latest" data-offset="10" data-per="10" data-bands="1" data-last-band="{{ $lastBand }}" data-exclude="{{ implode(',', $exclude) }}">
                @include('lensa-mobile::partials.rows', ['items' => $latest, 'bands' => true])
            </div>
            <div class="sentinel" data-sentinel><span class="m-spinner"></span></div>
        </div>

        <div class="page scroll" data-page="unggulan" data-scroll>
            @foreach ($featured as $slug)
                @php
                    $channel = $rt->category($slug);
                    $items = array_slice($rt->inCategory($slug), 0, 6);
                    $lead = array_shift($items);
                @endphp
                <section class="featured">
                    <h2 class="featured-title"><a href="{{ $channel['url'] }}">{{ $channel['name'] }}</a></h2>
                    <a href="{{ $lead['url'] }}" class="lead">
                        <img src="{{ $lead['image']['lg'] }}" alt="" loading="lazy">
                        <span class="lead-text">
                            @if ($lead['upper'])<span class="lead-upper">{{ $lead['upper'] }}</span>@endif
                            <span class="lead-title">{{ $lead['title'] }}</span>
                            <span class="lead-meta">{{ $rt->ago($lead['published']) }} • {{ $lead['reading_time'] }} mnt baca</span>
                        </span>
                    </a>
                    @include('lensa-mobile::partials.rows', ['items' => $items, 'showCategory' => false])
                    <a href="{{ $channel['url'] }}" class="featured-more">Selengkapnya <i class="ri-arrow-right-line"></i></a>
                </section>
            @endforeach
            <div class="page-end"></div>
        </div>

        <div class="page scroll" data-page="untuk-anda" data-scroll>
            <div data-for-you>
                <div class="m-loading" aria-label="Memuat"><span></span><span></span><span></span></div>
            </div>
            <div class="page-end"></div>
        </div>
    </div>
</div>
