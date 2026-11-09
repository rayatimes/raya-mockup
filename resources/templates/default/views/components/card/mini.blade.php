@props(['article', 'rt'])
<article data-card {{ $attributes->merge(['class' => 'group flex gap-3 py-3']) }}>
    <a href="{{ $article['url'] }}" class="block w-[4.5rem] shrink-0" tabindex="-1" aria-hidden="true">
        <x-default::img :src="$article['image']['sm']" :alt="$article['title']" class="aspect-square" />
    </a>
    <div class="min-w-0">
        <a href="{{ $article['category']['url'] }}" class="rt-kanal">{{ $article['category']['name'] }}</a>
        <h3 class="mt-0.5 text-sm leading-snug line-clamp-3"><a href="{{ $article['url'] }}" class="rt-title">{{ $article['title'] }}</a></h3>
    </div>
</article>
