@props(['rt', 'article', 'category' => true, 'big' => false, 'index' => 0])
@php $shareUrl = 'https://rayatimes.id/'.$article['category']['slug'].'/'.$article['slug']; @endphp
<article @class(['row', 'row-big' => $big])>
    <a href="{{ $article['url'] }}" class="row-link">
        <span class="row-thumb"><img src="{{ $big ? $article['image']['lg'] : $article['image']['sm'] }}" alt="" loading="lazy"></span>
        <span class="row-text">
            @if ($category)<span class="row-label">{{ $article['category']['name'] }}</span>@endif
            @if ($article['upper'])<span class="row-upper">{{ $article['upper'] }}</span>@endif
            <span class="row-title">{{ $article['title'] }}</span>
            <span class="row-meta">{{ $rt->ago($article['published']) }} • {{ $article['reading_time'] }} mnt baca</span>
        </span>
    </a>
    <button type="button" class="row-more" aria-label="Kelola artikel" data-more
        data-slug="{{ $article['slug'] }}" data-title="{{ $article['title'] }}" data-url="{{ $shareUrl }}"
        data-cat="{{ $article['category']['name'] }}" data-cat-url="{{ $article['category']['url'] }}"
        data-author="{{ $article['author']['name'] ?? '' }}" data-author-url="{{ $article['author']['url'] ?? '#' }}"><i class="ri-more-2-fill"></i></button>
</article>
