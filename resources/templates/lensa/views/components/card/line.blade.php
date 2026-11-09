{{-- Baris ringkas dengan thumbnail bulat-sudut kecil. --}}
@props(['article', 'rt', 'time' => false])
<article data-card {{ $attributes->merge(['class' => 'group']) }}>
    <a href="{{ $article['url'] }}" class="flex gap-4">
        <x-lensa::frame :src="$article['image']['sm']" :alt="$article['title']" class="aspect-square w-20 shrink-0 rounded-xl" />
        <span class="min-w-0">
            <span class="text-xs text-dim">@if ($time)<span class="font-semibold text-ink">{{ $article['published']->format('H.i') }}</span> · @endif{{ $article['category']['name'] }}</span>
            <span class="ls-headline mt-1 block text-lg transition-colors group-hover:text-sun">{{ $article['title'] }}</span>
        </span>
    </a>
</article>
