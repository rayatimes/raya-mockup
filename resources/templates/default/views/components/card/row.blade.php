@props(['article', 'rt', 'excerpt' => false])
<article data-card {{ $attributes->merge(['class' => 'group flex gap-4 py-5']) }}>
    <a href="{{ $article['url'] }}" class="block w-28 shrink-0 sm:w-40" tabindex="-1" aria-hidden="true">
        <x-default::img :src="$article['image']['sm']" :alt="$article['title']" class="aspect-[4/3]" />
    </a>
    <div class="min-w-0">
        <a href="{{ $article['category']['url'] }}" class="rt-kanal">{{ $article['category']['name'] }}</a>
        <h3 class="mt-1 text-base leading-snug sm:text-lg"><a href="{{ $article['url'] }}" class="rt-title">{{ $article['title'] }}</a></h3>
        @if ($excerpt)<p class="mt-1.5 hidden text-sm leading-relaxed text-muted line-clamp-2 sm:block">{{ $article['excerpt'] }}</p>@endif
        <x-default::byline :article="$article" :rt="$rt" class="mt-2" />
    </div>
</article>
