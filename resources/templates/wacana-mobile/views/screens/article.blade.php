@php
    $site = $rt->site();
    $related = $rt->related($article, 7);
    $inline = array_slice($related, 0, 2);
    $more = array_slice($related, 2, 5);
    $blocks = $rt->contentBlocks($article);
    $insertAt = [2 => $inline[0] ?? null, 6 => $inline[1] ?? null];
    $channelRoot = $article['category']['parent'] ?? $article['category']['slug'];
    $shareUrl = 'https://rayatimes.id/'.$article['category']['slug'].'/'.$article['slug'];
    $authors = $article['authors'];
@endphp
<div class="scr" data-sb="auto" data-article data-kanal="{{ $article['category']['slug'] }}" data-slug="{{ $article['slug'] }}" data-cat="{{ $channelRoot }}" data-title="{{ $article['title'] }}" data-url="{{ $shareUrl }}">
    {{-- Header: kiri kembali dan logo, kanan simpan, ukuran teks, bagikan. --}}
    <header class="bar bar-article">
        <div class="bar-start">
            <button type="button" class="bar-btn" data-back aria-label="Kembali"><i class="ri-arrow-left-s-line"></i></button>
            <span class="bar-brand">
                <img src="{{ $site['brand']['logo_main'] }}" alt="RayaTimes" class="bar-logo logo-on-light">
                <img src="{{ $site['brand']['logo_main_dark'] }}" alt="RayaTimes" class="bar-logo logo-on-dark">
            </span>
        </div>
        <div class="bar-end">
            <button type="button" class="bar-btn" data-save aria-pressed="false" aria-label="Simpan berita"><i class="ri-bookmark-line save-off"></i><i class="ri-bookmark-fill save-on"></i></button>
            <button type="button" class="bar-btn" data-sheet-open="text" aria-label="Ukuran teks"><i class="ri-font-size"></i></button>
            <button type="button" class="bar-btn" data-sheet-open="share" aria-label="Bagikan"><i class="ri-share-forward-line"></i></button>
        </div>
    </header>
    <span class="progress-line" data-progress-line></span>

    <div class="scroll" data-scroll>
        <figure class="article-cover">
            <img src="{{ $article['image']['lg'] }}" alt="{{ $article['caption'] ?: $article['title'] }}" width="{{ $article['image']['w'] }}" height="{{ $article['image']['h'] }}" fetchpriority="high">
            @if ($article['caption'])<figcaption>{{ $article['caption'] }}</figcaption>@endif
        </figure>

        <div class="article-pad">
            @if ($article['upper'])<p class="article-upper">{{ $article['upper'] }}</p>@endif
            <h1 class="article-title">{{ $article['title'] }}</h1>

            <div class="attr">
                <a href="{{ $article['category']['url'] }}" class="row-label">{{ $article['category']['name'] }}</a>
                <p class="attr-by">Oleh
                    @if ($article['author'])
                        <a href="{{ $article['author']['url'] }}">{{ $rt->authorLine($article, compact: count($authors) > 2) }}</a>
                    @else
                        <b>Redaksi</b>
                    @endif
                </p>
                <p class="attr-date">{{ $rt->attributeDate($article['published']) }} · {{ $article['reading_time'] }} menit baca</p>
            </div>

            <div class="prose" data-body>
                @foreach ($blocks as $i => $block)
                    {!! $block !!}
                    @if (! empty($insertAt[$i]) && $i < count($blocks) - 1)
                        @php $inlineArticle = $insertAt[$i]; @endphp
                        <aside class="also" data-speech-skip data-kanal="{{ $inlineArticle['category']['slug'] }}">
                            <span class="also-label">Baca juga</span>
                            <a href="{{ $inlineArticle['url'] }}" class="also-title">{{ $inlineArticle['title'] }}</a>
                        </aside>
                    @endif
                @endforeach
            </div>

            @if ($article['tag_list'])
                <div class="tags">
                    <p class="tags-label">Topik</p>
                    <div class="tags-list">
                        @foreach ($article['tag_list'] as $tag)
                            <a href="{{ $tag['url'] }}" class="tag">{{ $tag['name'] }}</a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        @if ($more)
            <section class="related">
                <h2 class="section-title">Berita Terkait</h2>
                @include('wacana-mobile::partials.rows', ['items' => $more])
            </section>
        @endif
        <div class="article-end"></div>
    </div>

    {{-- Pil pembaca mengikuti aplikasi: bisa digeser dan dikuncupkan. --}}
    <div class="pill" data-pill data-playing="false" data-collapsed="false">
        <i class="ri-draggable pill-drag" aria-hidden="true"></i>
        <button type="button" class="pill-play" data-pill-play aria-label="Bacakan berita ini"><i class="ri-play-fill pill-idle"></i><i class="ri-stop-fill pill-on"></i></button>
        <span class="pill-readout">
            <span data-pill-percent>0%</span>
            <span class="pill-track"><span class="pill-fill" data-pill-fill></span></span>
        </span>
        <button type="button" class="pill-voice" data-pill-voice aria-label="Pilih suara pembaca"><i class="ri-volume-up-line"></i></button>
        <button type="button" class="pill-toggle" data-pill-toggle aria-label="Perkecil pil" aria-expanded="true"><i class="ri-arrow-right-s-line pill-less"></i><i class="ri-arrow-left-s-line pill-more"></i></button>
    </div>
</div>
