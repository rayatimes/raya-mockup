@php $topics = $rt->hitTopics(8); @endphp
<div class="scr" data-sb="auto">
    <form class="searchbar" data-search-form role="search">
        <button type="button" class="icon-btn" data-back aria-label="Kembali"><i class="ri-arrow-left-s-line"></i></button>
        <label class="searchbar-field">
            <i class="ri-search-line"></i>
            <span class="sr-only">Cari</span>
            <input type="search" data-search-input placeholder="Cari berita, penulis, atau kanal" autocomplete="off" enterkeyhint="search">
            <button type="button" data-search-clear aria-label="Hapus" hidden><i class="ri-close-circle-fill"></i></button>
        </label>
    </form>
    <div class="scroll" data-scroll>
        <div data-search-empty class="search-empty">
            <div data-recents-wrap hidden>
                <div class="section-head section-head-plain">
                    <h2 class="section-title">Pencarian Terakhir</h2>
                    <button type="button" class="section-more" data-recents-clear>Hapus</button>
                </div>
                <div class="chip-wrap" data-recents></div>
            </div>
            <div class="section-head section-head-plain"><h2 class="section-title">Sedang Tren</h2></div>
            <ol class="trend">
                @foreach ($topics as $i => $topic)
                    <li><button type="button" data-search-term="{{ $topic['name'] }}"><span>{{ $i + 1 }} · Tren di {{ $topic['kanal'] }}</span><b>{{ $topic['label'] }}</b><small>{{ $topic['recent'] }} berita</small></button></li>
                @endforeach
            </ol>
        </div>
        <div data-search-results hidden></div>
        <div class="article-end"></div>
    </div>
</div>
