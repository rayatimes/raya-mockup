@props(['rt', 'limit' => 5, 'title' => 'Paling banyak dibaca'])
<section {{ $attributes->merge(['class' => 'rounded-frame bg-panel p-6']) }}>
    <h2 class="ls-headline text-2xl">{{ $title }}</h2>
    <ol class="mt-5 space-y-5">
        @foreach ($rt->popular($limit) as $i => $item)
            <li data-card class="group flex gap-4">
                <span class="ls-headline w-8 shrink-0 text-4xl text-sun/90">{{ $i + 1 }}</span>
                <a href="{{ $item['url'] }}" class="min-w-0">
                    <span class="ls-headline block text-lg transition-colors group-hover:text-sun">{{ $item['title'] }}</span>
                    <span class="mt-1 block text-xs text-dim">{{ $rt->compact($item['views']) }} pembaca · {{ $rt->ago($item['published']) }}</span>
                </a>
            </li>
        @endforeach
    </ol>
</section>
