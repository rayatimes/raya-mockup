@php
    $site = $rt->site();
    $headlines = $rt->headlines(5, diverse: true);
    $mainHeadline = $headlines[0];
    $sideHeadlines = array_slice($headlines, 1);
    $exclude = array_column($headlines, 'slug');
    $latest = $rt->latest(10, 0, $exclude);
    $featured = ['politik', 'ekonomi', 'hankam', 'hukum', 'analisis'];
    $lastBand = $rt->dateBand(end($latest)['published']);
    $today = $rt->now()->locale('id')->translatedFormat('l, j F Y');
@endphp
<div class="scr" data-sb="auto">
    {{-- Header: kiri panel kanal, tengah logo, kanan cari dan mode gelap. --}}
    <header class="bar">
        <button type="button" class="bar-btn" data-drawer-open aria-label="Buka kanal, halaman, dan Insight"><i class="ri-menu-line"></i></button>
        <span class="bar-brand">
            <img src="{{ $site['brand']['logo_main'] }}" alt="RayaTimes" class="bar-logo logo-on-light">
            <img src="{{ $site['brand']['logo_main_dark'] }}" alt="RayaTimes" class="bar-logo logo-on-dark">
        </span>
        <div class="bar-end">
            <a href="{{ $rt->url('/search') }}" class="bar-btn" aria-label="Cari berita"><i class="ri-search-2-line"></i></a>
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
            <p class="dateline">{{ $today }}</p>

            {{-- Blok headline: satu berita utama, empat lainnya dari kanal berbeda. --}}
            <a href="{{ $mainHeadline['url'] }}" class="headline" data-kanal="{{ $mainHeadline['category']['slug'] }}">
                <span class="headline-image"><img src="{{ $mainHeadline['image']['lg'] }}" alt="" fetchpriority="high"></span>
                <span class="row-label">{{ $mainHeadline['category']['name'] }}</span>
                @if ($mainHeadline['upper'])<span class="row-upper">{{ $mainHeadline['upper'] }}</span>@endif
                <span class="headline-title">{{ $mainHeadline['title'] }}</span>
                <span class="headline-excerpt">{{ $mainHeadline['excerpt'] }}</span>
                <span class="row-meta">Oleh {{ $mainHeadline['author'] ? $rt->authorLine($mainHeadline, compact: true) : 'Redaksi' }} · {{ $rt->ago($mainHeadline['published']) }}</span>
            </a>
            <div class="side-headlines">
                @foreach ($sideHeadlines as $article)
                    <a href="{{ $article['url'] }}" class="side-headline" data-kanal="{{ $article['category']['slug'] }}">
                        <span class="side-image"><img src="{{ $article['image']['sm'] }}" alt="" loading="lazy"></span>
                        <span class="row-text">
                            <span class="row-label">{{ $article['category']['name'] }}</span>
                            <span class="side-title">{{ $article['title'] }}</span>
                            <span class="row-meta">{{ $rt->ago($article['published']) }}</span>
                        </span>
                    </a>
                @endforeach
            </div>

            <div data-feed data-scope="latest" data-offset="10" data-per="10" data-bands="1" data-last-band="{{ $lastBand }}" data-exclude="{{ implode(',', $exclude) }}">
                @include('wacana-mobile::partials.rows', ['items' => $latest, 'bands' => true])
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
                    <div class="featured-head">
                        <h2 class="featured-title"><a href="{{ $channel['url'] }}">{{ $channel['name'] }}</a></h2>
                    </div>
                    <a href="{{ $lead['url'] }}" class="lead">
                        <span class="lead-image"><img src="{{ $lead['image']['lg'] }}" alt="" loading="lazy"></span>
                        <span class="lead-text">
                            @if ($lead['upper'])<span class="lead-upper">{{ $lead['upper'] }}</span>@endif
                            <span class="lead-title">{{ $lead['title'] }}</span>
                            <span class="lead-excerpt">{{ $lead['excerpt'] }}</span>
                            <span class="row-meta">Oleh {{ $lead['author'] ? $rt->authorLine($lead, compact: true) : 'Redaksi' }} · {{ $rt->ago($lead['published']) }}</span>
                        </span>
                    </a>
                    @include('wacana-mobile::partials.rows', ['items' => $items, 'showCategory' => false])
                    <a href="{{ $channel['url'] }}" class="featured-more">Selengkapnya di {{ $channel['name'] }} <i class="ri-arrow-right-line"></i></a>
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
