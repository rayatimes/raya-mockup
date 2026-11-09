@php
    // Urutan kanal: yang paling sering dibaca, lalu minat, lalu pilihan redaksi bagi pembaca baru.
    $fallback = ['berita', 'ekonomi', 'olahraga', 'politik', 'gaya-hidup'];
    $picked = array_slice(array_values(array_unique(array_filter(
        [...$activity, ...$interests, ...$fallback],
        fn ($slug) => $rt->category($slug) !== null,
    ))), 0, 5);
@endphp
@foreach ($picked as $slug)
    @php
        $channel = $rt->category($slug);
        $items = array_slice($rt->inCategory($slug), 0, 5);
    @endphp
    <section class="foryou-section">
        <div class="section-head">
            <div>
                @if (in_array($slug, $activity, true))<p class="section-badge">Sering Anda baca</p>@elseif (in_array($slug, $interests, true))<p class="section-badge">Minat Anda</p>@endif
                <h2 class="section-title">{{ $channel['name'] }}</h2>
            </div>
            <a href="{{ $channel['url'] }}" class="section-more">Lainnya <i class="ri-arrow-right-up-line"></i></a>
        </div>
        @foreach ($items as $i => $article)
            <x-lensa-mobile::row :rt="$rt" :article="$article" :category="false" :big="$i === 0" />
        @endforeach
    </section>
@endforeach
