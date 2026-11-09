@php
    $site = $rt->site();
    $headlines = $rt->headlines(5);
    $exclude = array_column($headlines, 'slug');
    $latest = $rt->latest(10, 0, $exclude);
    $featured = ['politik', 'ekonomi', 'hankam', 'hukum', 'analisis'];
    $lastBand = $rt->dateBand(end($latest)['published']);
@endphp
<div class="scr" data-sb="light">
    {{-- Header: kiri panel kanal, tengah logo, kanan cari dan mode gelap. --}}
    <header class="bar">
        <button type="button" class="bar-btn" data-drawer-open aria-label="Buka kanal, halaman, dan Insight"><i class="ri-menu-line"></i></button>
        <img src="{{ $site['brand']['logo_dark'] }}" alt="RayaTimes portal" class="bar-logo">
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
            <div class="headline" data-carousel-root>
                <div class="headline-track" data-carousel data-drag="page" data-duration="5000">
                    @foreach ($headlines as $article)
                        <a href="{{ $article['url'] }}" class="headline-slide">
                            <span class="headline-image"><img src="{{ $article['image']['lg'] }}" alt="" @if ($loop->first) fetchpriority="high" @else loading="lazy" @endif></span>
                            <span class="headline-eyebrow"><span class="badge">Headline</span><span class="row-label">{{ $article['category']['name'] }}</span></span>
                            @if ($article['upper'])<span class="row-upper">{{ $article['upper'] }}</span>@endif
                            <span class="headline-title">{{ $article['title'] }}</span>
                            <span class="headline-excerpt">{{ $article['excerpt'] }}</span>
                            <span class="row-meta">{{ $rt->ago($article['published']) }} • {{ $article['reading_time'] }} mnt baca</span>
                        </a>
                    @endforeach
                </div>
                <div class="dots">
                    @foreach ($headlines as $article)
                        <button type="button" data-dot aria-label="Headline {{ $loop->iteration }} dari {{ $loop->count }}"></button>
                    @endforeach
                </div>
            </div>

            <div data-feed data-scope="latest" data-offset="10" data-per="10" data-bands="1" data-last-band="{{ $lastBand }}" data-exclude="{{ implode(',', $exclude) }}">
                @include('default-mobile::partials.rows', ['items' => $latest, 'bands' => true])
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
                <section class="featured" data-kanal="{{ $slug }}">
                    <h2 class="featured-title"><a href="{{ $channel['url'] }}">{{ $channel['name'] }}</a></h2>
                    <a href="{{ $lead['url'] }}" class="lead">
                        <img src="{{ $lead['image']['lg'] }}" alt="" loading="lazy">
                        <span class="lead-text">
                            @if ($lead['upper'])<span class="lead-upper">{{ $lead['upper'] }}</span>@endif
                            <span class="lead-title">{{ $lead['title'] }}</span>
                            <span class="lead-meta">{{ $rt->ago($lead['published']) }} • {{ $lead['reading_time'] }} mnt baca</span>
                        </span>
                    </a>
                    @include('default-mobile::partials.rows', ['items' => $items, 'showCategory' => false])
                    <a href="{{ $channel['url'] }}" class="featured-more">Selengkapnya <i class="ri-arrow-right-s-line"></i></a>
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
