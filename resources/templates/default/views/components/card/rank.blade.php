@props(['article', 'rt', 'rank'])
<li data-card {{ $attributes->merge(['class' => 'group flex gap-3 py-3']) }}>
    <span class="grid size-7 shrink-0 place-items-center bg-raya text-xs font-bold text-white transition-colors group-hover:bg-ink dark:group-hover:bg-sun dark:group-hover:text-ink">{{ $rank }}</span>
    <div class="min-w-0">
        <h3 class="text-sm leading-snug"><a href="{{ $article['url'] }}" class="rt-title">{{ $article['title'] }}</a></h3>
        <p class="rt-meta mt-1">{{ $rt->ago($article['published']) }} · {{ $rt->compact($article['views']) }} pembaca</p>
    </div>
</li>
