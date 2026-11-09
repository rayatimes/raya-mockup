@props(['rt', 'limit' => 6])
<section {{ $attributes->merge(['class' => 'bg-stone p-6']) }}>
    <h2 class="wc-display text-2xl">Paling banyak dibaca</h2>
    <ol class="mt-4 divide-y divide-ink/10">
        @foreach ($rt->popular($limit) as $i => $item)
            <li data-card data-kanal="{{ $item['category']['slug'] }}" class="group flex gap-4 py-4">
                <span class="wc-display w-6 shrink-0 text-3xl text-(--hue)">{{ $i + 1 }}</span>
                <div>
                    <a href="{{ $item['url'] }}" class="wc-head wc-link text-lg">{{ $item['title'] }}</a>
                    <p class="wc-meta mt-1">{{ $rt->compact($item['views']) }} pembaca</p>
                </div>
            </li>
        @endforeach
    </ol>
</section>
