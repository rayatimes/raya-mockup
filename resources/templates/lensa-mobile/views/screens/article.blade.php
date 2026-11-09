@php
    $site = $rt->site();
    $related = $rt->related($article, 8);
    $inline = array_slice($related, 0, 2);
    $more = array_slice($related, 2, 6);
    $blocks = $rt->contentBlocks($article);
    $insertAt = [2 => $inline[0] ?? null, 6 => $inline[1] ?? null];
    $channelRoot = $article['category']['parent'] ?? $article['category']['slug'];
    $shareUrl = 'https://rayatimes.id/'.$article['category']['slug'].'/'.$article['slug'];
    $authors = $article['authors'];
@endphp
<div class="scr" data-sb="light" data-article data-slug="{{ $article['slug'] }}" data-cat="{{ $channelRoot }}" data-title="{{ $article['title'] }}" data-url="{{ $shareUrl }}">
    {{-- Header: kiri kembali dan logo, kanan simpan, ukuran teks, bagikan. --}}
    <header class="bar bar-article">
        <div class="bar-start">
            <button type="button" class="bar-btn" data-back aria-label="Kembali"><i class="ri-arrow-left-s-line"></i></button>
            <span class="bar-brand">
                <img src="{{ $site['brand']['logo_main_dark'] }}" alt="RayaTimes" class="bar-logo">
            </span>
        </div>
        <div class="bar-end">
            <button type="button" class="bar-btn" data-save aria-pressed="false" aria-label="Simpan berita"><i class="ri-bookmark-line save-off"></i><i class="ri-bookmark-fill save-on"></i></button>
            <button type="button" class="bar-btn" data-sheet-open="text" aria-label="Ukuran teks"><i class="ri-font-size"></i></button>
            <button type="button" class="bar-btn" data-sheet-open="share" aria-label="Bagikan"><i class="ri-share-forward-line"></i></button>
        </div>
    </header>

    <div class="scroll" data-scroll>
        <figure class="article-cover">
            <img src="{{ $article['image']['lg'] }}" alt="{{ $article['caption'] ?: $article['title'] }}" width="{{ $article['image']['w'] }}" height="{{ $article['image']['h'] }}" fetchpriority="high">
            @if ($article['caption'])<figcaption><i class="ri-camera-line"></i> {{ $article['caption'] }}</figcaption>@endif
        </figure>

        <div class="article-pad">
            @if ($article['upper'])<p class="article-upper">{{ $article['upper'] }}</p>@endif
            <h1 class="article-title">{{ $article['title'] }}</h1>

            <div class="attr">
                <div class="attr-author">
                    <span class="attr-avatars">
                        @foreach (array_slice($authors, 0, 3) as $person)
                            <x-lensa-mobile::avatar :person="$person" :size="44" />
                        @endforeach
                    </span>
                    <span class="attr-lines">
                        @if ($article['author'])
                            <a href="{{ $article['author']['url'] }}" class="attr-name">{{ $rt->authorLine($article, compact: count($authors) > 2) }}</a>
                            <span class="attr-date">{{ $article['author']['label'] ?: 'Penulis' }}</span>
                        @else
                            <span class="attr-name">Redaksi</span>
                        @endif
                    </span>
                </div>
                <div class="attr-facts">
                    <a href="{{ $article['category']['url'] }}" class="attr-chip attr-chip-accent">{{ $article['category']['name'] }}</a>
                    <span class="attr-chip"><i class="ri-time-line"></i>{{ $article['reading_time'] }} menit baca</span>
                    <span class="attr-chip"><i class="ri-calendar-line"></i>{{ $rt->attributeDate($article['published']) }}</span>
                </div>
            </div>

            <div class="prose" data-body>
                @foreach ($blocks as $i => $block)
                    {!! $block !!}
                    @if (! empty($insertAt[$i]) && $i < count($blocks) - 1)
                        @php $inlineArticle = $insertAt[$i]; @endphp
                        <aside class="also" data-speech-skip>
                            <a href="{{ $inlineArticle['url'] }}">
                                <img src="{{ $inlineArticle['image']['lg'] }}" alt="" loading="lazy">
                                <span class="also-text">
                                    <span class="also-label">Baca Juga</span>
                                    <span class="also-title">{{ $inlineArticle['title'] }}</span>
                                </span>
                            </a>
                        </aside>
                    @endif
                @endforeach
            </div>

            @if ($article['tag_list'])
                <div class="tags">
                    <div class="tags-list">
                        @foreach ($article['tag_list'] as $tag)
                            <a href="{{ $tag['url'] }}" class="m-chip">#{{ $tag['name'] }}</a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        @if ($more)
            <section class="related">
                <h2 class="section-title">Berita Terkait</h2>
                <div class="rail" data-drag>
                    @foreach ($more as $item)
                        <a href="{{ $item['url'] }}" class="rail-card">
                            <img src="{{ $item['image']['sm'] }}" alt="" loading="lazy">
                            <span class="rail-text">
                                <span class="rail-label">{{ $item['category']['name'] }}</span>
                                <span class="rail-title">{{ $item['title'] }}</span>
                            </span>
                        </a>
                    @endforeach
                </div>
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
