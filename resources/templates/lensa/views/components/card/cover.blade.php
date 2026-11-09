{{-- Kartu "story frame": judul ditumpuk di atas foto. --}}
@props(['article', 'rt', 'size' => 'text-2xl', 'eager' => false, 'large' => false])
<article data-card data-theme="dark" {{ $attributes->merge(['class' => 'group relative isolate overflow-hidden rounded-frame bg-raise']) }}>
    <x-lensa::frame :src="$large ? $article['image']['lg'] : $article['image']['sm']" :alt="$article['title']" :eager="$eager" class="absolute inset-0 -z-10" />
    <span class="ls-shade absolute inset-0 -z-10"></span>
    <a href="{{ $article['url'] }}" class="flex h-full flex-col justify-end p-5">
        <span class="ls-kanal">{{ $article['category']['name'] }}</span>
        <h3 class="ls-headline mt-1.5 {{ $size }}">{{ $article['title'] }}</h3>
        <span class="mt-2 text-xs text-ink/70">{{ $rt->ago($article['published']) }}</span>
    </a>
</article>
