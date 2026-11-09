@php
    $articles = array_slice($rt->search($query), 0, 20);
    $needle = mb_strtolower($query);
    $authors = array_slice(array_values(array_filter($rt->authors(), fn ($author) => str_contains(mb_strtolower($author['name']), $needle))), 0, 20);
    $channels = array_values(array_filter($rt->categories(), fn ($category) => str_contains(mb_strtolower($category['name']), $needle)));
@endphp
<div data-rtabs-root>
    <div class="menu" role="tablist">
        <button type="button" class="menu-item" role="tab" data-rt="artikel" aria-selected="true">Artikel</button>
        <button type="button" class="menu-item" role="tab" data-rt="penulis" aria-selected="false">Penulis</button>
        <button type="button" class="menu-item" role="tab" data-rt="kanal" aria-selected="false">Kanal</button>
    </div>
    <div data-rpanel="artikel">
        @if ($articles)
            @include('wacana-mobile::partials.rows', ['items' => $articles])
        @else
            <x-wacana-mobile::empty icon="ri-search-line" title="Tidak Ditemukan" message="Coba kata kunci lain untuk “{{ $query }}”." />
        @endif
    </div>
    <div data-rpanel="penulis" hidden>
        @forelse ($authors as $author)
            <a href="{{ $author['url'] }}" class="result-row">
                <x-wacana-mobile::avatar :person="$author" :size="44" />
                <span><b>{{ $author['name'] }}</b><small>{{ $author['label'] ?: 'Penulis' }}</small></span>
                <i class="ri-arrow-right-s-line"></i>
            </a>
        @empty
            <x-wacana-mobile::empty icon="ri-user-3-line" title="Tidak Ditemukan" message="Tidak ada penulis dengan nama itu." />
        @endforelse
    </div>
    <div data-rpanel="kanal" hidden>
        @forelse ($channels as $channel)
            <a href="{{ $channel['url'] }}" class="result-row">
                <span><b>{{ $channel['name'] }}</b><small>{{ count($rt->inCategory($channel['slug'])) }} berita</small></span>
                <i class="ri-arrow-right-s-line"></i>
            </a>
        @empty
            <x-wacana-mobile::empty icon="ri-price-tag-3-line" title="Tidak Ditemukan" message="Tidak ada kanal dengan nama itu." />
        @endforelse
    </div>
</div>
