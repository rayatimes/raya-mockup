@php
    // Urutan kanal: yang paling sering dibaca, lalu minat, lalu pilihan redaksi bagi pembaca baru.
    $fallback = ['berita', 'ekonomi', 'olahraga', 'politik', 'gaya-hidup'];
    $picked = array_slice(array_values(array_unique(array_filter(
        [...$activity, ...$interests, ...$fallback],
        fn ($slug) => $rt->category($slug) !== null,
    ))), 0, 5);
@endphp
@foreach ($picked as $slug)
    @php $channel = $rt->category($slug); @endphp
    <section class="foryou-section" data-kanal="{{ $slug }}">
        <div class="section-head">
            <div>
                <h2 class="section-title">{{ $channel['name'] }}</h2>
                @if (in_array($slug, $activity, true))<p class="section-badge">Sering Anda baca</p>@elseif (in_array($slug, $interests, true))<p class="section-badge">Minat Anda</p>@endif
            </div>
            <a href="{{ $channel['url'] }}" class="section-more">Lihat lainnya <i class="ri-arrow-right-line"></i></a>
        </div>
        @include('wacana-mobile::partials.rows', ['items' => array_slice($rt->inCategory($slug), 0, 5), 'showCategory' => false])
    </section>
@endforeach
