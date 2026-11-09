{{-- Kartu foto standar: gambar di atas, konteks ringkas di bawah. --}}
@props(['article', 'rt', 'ratio' => 'aspect-[4/3]', 'size' => 'text-xl', 'excerpt' => false])
<article data-card {{ $attributes->merge(['class' => 'group']) }}>
    <a href="{{ $article['url'] }}" class="block">
        <x-lensa::frame :src="$article['image']['sm']" :alt="$article['title']" class="{{ $ratio }} rounded-frame" />
        <span class="ls-kanal mt-3">{{ $article['category']['name'] }}</span>
        <h3 class="ls-headline mt-1 {{ $size }} transition-colors duration-300 group-hover:text-sun">{{ $article['title'] }}</h3>
        @if ($excerpt)<p class="mt-2 text-sm leading-relaxed text-dim line-clamp-2">{{ $article['excerpt'] }}</p>@endif
        <p class="mt-2 text-xs text-dim">{{ $rt->ago($article['published']) }} · {{ $rt->authorLine($article, compact: true) }}</p>
    </a>
</article>
