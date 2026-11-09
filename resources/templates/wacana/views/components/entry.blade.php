{{-- Entri teks-dulu dengan foto kecil di samping. --}}
@props(['article', 'rt', 'excerpt' => true, 'photo' => true])
<article data-card data-kanal="{{ $article['category']['slug'] }}" {{ $attributes->merge(['class' => 'group grid gap-5 py-6 '.($photo ? ($excerpt ? 'grid-cols-[1fr_7rem] sm:grid-cols-[1fr_11rem]' : 'grid-cols-[1fr_6rem]') : '')]) }}>
    <div>
        <x-wacana::kanal :category="$article['category']" />
        <h3 class="wc-head mt-2 text-[1.375rem] sm:text-[1.625rem]"><a href="{{ $article['url'] }}" class="wc-link">{{ $article['title'] }}</a></h3>
        @if ($excerpt)<p class="mt-2 hidden text-[15px] leading-relaxed text-graphite line-clamp-2 sm:block">{{ $article['excerpt'] }}</p>@endif
        <p class="wc-meta mt-3">{{ $rt->authorLine($article, compact: true) }}, {{ $rt->ago($article['published']) }}</p>
    </div>
    @if ($photo)
        <a href="{{ $article['url'] }}" tabindex="-1" aria-hidden="true"><x-wacana::photo :src="$article['image']['sm']" :alt="$article['title']" class="aspect-[4/3]" /></a>
    @endif
</article>
