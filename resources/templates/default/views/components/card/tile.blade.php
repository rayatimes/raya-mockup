@props(['article', 'rt', 'excerpt' => true, 'boxed' => true])
<article data-card {{ $attributes->merge(['class' => 'group flex flex-col '.($boxed ? 'border border-line bg-surface' : '')]) }}>
    <a href="{{ $article['url'] }}" tabindex="-1" aria-hidden="true">
        <x-default::img :src="$article['image']['sm']" :alt="$article['title']" class="aspect-video" />
    </a>
    <div class="flex flex-1 flex-col {{ $boxed ? 'p-4' : 'pt-3' }}">
        <p class="rt-meta"><a href="{{ $article['category']['url'] }}" class="rt-kanal">{{ $article['category']['name'] }}</a> · {{ $rt->ago($article['published']) }}</p>
        <h3 class="mt-2 text-lg leading-snug line-clamp-3"><a href="{{ $article['url'] }}" class="rt-title">{{ $article['title'] }}</a></h3>
        @if ($excerpt)<p class="mt-2 text-sm leading-relaxed text-muted line-clamp-2">{{ $article['excerpt'] }}</p>@endif
    </div>
</article>
