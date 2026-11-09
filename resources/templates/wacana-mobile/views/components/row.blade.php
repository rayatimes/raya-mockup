@props(['rt', 'article', 'category' => true, 'index' => 0])
@php
    $shareUrl = 'https://rayatimes.id/'.$article['category']['slug'].'/'.$article['slug'];
    $byline = $article['author'] ? $rt->authorLine($article, compact: true) : 'Redaksi';
@endphp
<article class="row" data-kanal="{{ $article['category']['slug'] }}">
    <a href="{{ $article['url'] }}" class="row-link">
        <span class="row-text">
            @if ($category)<span class="row-label">{{ $article['category']['name'] }}</span>@endif
            @if ($article['upper'])<span class="row-upper">{{ $article['upper'] }}</span>@endif
            <span class="row-title">{{ $article['title'] }}</span>
            <span class="row-meta">{{ $byline }} · {{ $rt->ago($article['published']) }}</span>
        </span>
        <span class="row-thumb"><img src="{{ $article['image']['sm'] }}" alt="" loading="lazy"></span>
    </a>
    <button type="button" class="row-more" aria-label="Kelola artikel" data-more
        data-slug="{{ $article['slug'] }}" data-title="{{ $article['title'] }}" data-url="{{ $shareUrl }}"
        data-cat="{{ $article['category']['name'] }}" data-cat-url="{{ $article['category']['url'] }}"
        data-author="{{ $article['author']['name'] ?? '' }}" data-author-url="{{ $article['author']['url'] ?? '#' }}"><i class="ri-more-fill"></i></button>
</article>
