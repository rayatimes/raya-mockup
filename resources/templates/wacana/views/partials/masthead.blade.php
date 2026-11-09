@php
    $site = $rt->site();
    $nav = $rt->nav();
    $activeSlug = match (true) {
        isset($category) => $category['parent'] ?? $category['slug'],
        isset($article) => $article['category']['parent'] ?? $article['category']['slug'],
        default => trim($rt->localPath(), '/'),
    };
    $today = $rt->now()->locale('id')->translatedFormat('l, j F Y');
@endphp
<header data-masthead class="bg-paper">
    <div class="wc-nav-text mx-auto flex max-w-[84rem] items-center justify-between gap-4 px-4 py-3 text-graphite lg:px-8">
        <p class="hidden font-medium sm:block">{{ $today }}</p>
        <div class="ml-auto flex items-center gap-1">
            <button type="button" data-open-panel="wc-search" aria-expanded="false" class="flex items-center gap-2 px-3 py-2 font-medium transition hover:bg-stone"><i class="ri-search-2-line text-base"></i>Cari</button>
            <a href="#" class="px-3 py-2 font-medium transition hover:bg-stone">Masuk</a>
            <a href="#" class="bg-java px-3 py-2 font-semibold text-paper transition hover:bg-ink">Berlangganan</a>
        </div>
    </div>
    <div class="mx-auto max-w-[84rem] px-4 pb-6 pt-2 text-center lg:px-8 lg:pb-8">
        <a href="{{ $rt->url('/') }}" class="inline-block" aria-label="RayaTimes — beranda">
            <img src="{{ $site['brand']['logo_main'] }}" alt="RayaTimes" class="mx-auto h-12 w-auto sm:h-16 lg:h-20" width="300 " height="71">
        </a>
        <p class="mt-3 text-lg font-bold italic text-ink lg:text-xl">Fakta, Makna, Daya Bangsa</p>
    </div>
</header>

{{-- Navigasi kanal --}}
<nav class="sticky top-0 z-40 border-y-[3px] border-ink bg-paper" aria-label="Kanal">
    <div class="mx-auto flex max-w-[84rem] items-center px-4 lg:px-8">
        <a href="{{ $rt->url('/') }}" data-compact data-show="false" class="mr-2 hidden w-0 shrink-0 overflow-hidden opacity-0 transition-all duration-500 data-[show=true]:w-24 data-[show=true]:opacity-100 xl:block" aria-label="Beranda">
            <img src="{{ $site['brand']['logo'] }}" alt="RayaTimes" class="h-7 w-auto max-w-none">
        </a>
        {{-- Ponsel & tablet: tombol sidenav --}}
        <button type="button" data-sidenav-open aria-controls="wc-sidenav" aria-expanded="false" class="wc-nav-text flex items-center gap-2 py-3 pr-3 font-semibold lg:hidden">
            <i class="ri-menu-line text-lg"></i>Kanal
        </button>
        <a href="{{ $rt->url('/search') }}" class="ml-auto grid size-9 place-items-center transition hover:bg-stone lg:hidden" aria-label="Cari"><i class="ri-search-2-line"></i></a>

        {{-- Desktop: tombol menu menuju halaman kanal; tombol panah terpisah membuka sub-kanal --}}
        <ul class="hidden flex-1 justify-center lg:flex">
            @foreach ($nav as $item)
                <li data-kanal="{{ $item['slug'] }}" @if ($item['children']) data-dropdown data-open="false" @endif class="group/nav relative flex items-center">
                    <a href="{{ $item['url'] }}" @if ($activeSlug === $item['slug']) aria-current="page" @endif
                        class="wc-nav-text relative block whitespace-nowrap px-1.5 py-3 font-semibold text-graphite transition hover:text-ink aria-[current=page]:text-ink xl:px-2 2xl:px-3">
                        {{ $item['name'] }}
                        <span class="absolute inset-x-1.5 -bottom-[3px] h-[3px] origin-left scale-x-0 bg-(--hue) transition-transform duration-300 xl:inset-x-2 2xl:inset-x-3 [a:hover>&]:scale-x-100 [a[aria-current=page]>&]:scale-x-100"></span>
                    </a>
                    @if ($item['children'])
                        <button type="button" data-dropdown-toggle aria-expanded="false" aria-controls="wc-dd-{{ $item['slug'] }}" aria-label="Buka sub-kanal {{ $item['name'] }}" class="-ml-1 mr-0.5 grid h-full w-5 place-items-center text-graphite transition hover:bg-stone hover:text-ink group-data-[open=true]/nav:bg-stone group-data-[open=true]/nav:text-ink">
                            <i class="ri-arrow-down-s-line transition-transform duration-300 group-data-[open=true]/nav:rotate-180"></i>
                        </button>
                        <div id="wc-dd-{{ $item['slug'] }}" class="invisible absolute left-0 top-full z-10 mt-[3px] min-w-48 -translate-y-1 border-t-[3px] border-(--hue) bg-paper py-2 opacity-0 shadow-[0_30px_60px_-30px_rgb(0_0_0/0.45)] ring-1 ring-ink/10 transition duration-200 group-data-[open=true]/nav:visible group-data-[open=true]/nav:translate-y-0 group-data-[open=true]/nav:opacity-100">
                            @foreach ($item['children'] as $child)
                                <a href="{{ $child['url'] }}" class="wc-nav-text block px-4 py-2 font-medium text-graphite hover:bg-stone hover:text-ink">{{ $child['name'] }}</a>
                            @endforeach
                        </div>
                    @endif
                </li>
            @endforeach
        </ul>
        <button type="button" data-open-panel="wc-search" class="ml-2 hidden size-9 shrink-0 place-items-center transition hover:bg-stone xl:data-[show=true]:grid" data-compact data-show="false" aria-label="Cari"><i class="ri-search-2-line"></i></button>
    </div>
    <div data-read-bar class="absolute inset-x-0 -bottom-[3px] h-[3px] origin-left scale-x-0 bg-java"></div>

    {{-- Panel pencarian --}}
    <div id="wc-search" data-panel data-dismiss-outside data-open="false"
        class="invisible absolute inset-x-0 top-full -translate-y-2 border-b border-ink/10 bg-paper opacity-0 shadow-[0_30px_60px_-40px_rgb(0_0_0/0.45)] transition duration-300 data-[open=true]:visible data-[open=true]:translate-y-0 data-[open=true]:opacity-100">
        <div class="mx-auto grid max-w-[84rem] gap-8 px-4 py-8 lg:grid-cols-[1fr_20rem] lg:px-8">
            <div>
                <form action="{{ $rt->url('/search') }}" class="flex items-end gap-3 border-b-2 border-ink pb-2">
                    <label for="wc-q" class="sr-only">Cari</label>
                    <input id="wc-q" name="q" type="search" autocomplete="off" data-live-search="#wc-hits" placeholder="Cari isu, tokoh, atau peristiwa…" class="wc-ui-display w-full bg-transparent text-2xl placeholder:text-ash/60 focus:outline-none lg:text-3xl">
                    <button class="wc-btn shrink-0">Cari</button>
                </form>
                <div id="wc-hits" class="mt-4 divide-y divide-ink/10"></div>
            </div>
            <div>
                <p class="text-[13px] font-semibold text-ash">Isu yang sedang dibicarakan</p>
                <ul class="mt-3 space-y-2">
                    @foreach ($rt->hitTopics(6) as $topic)
                        <li><a href="{{ $topic['url'] }}" class="wc-ui-head text-[15px] hover:text-java">{{ $topic['name'] }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</nav>

{{-- Sidenav (ponsel & tablet): semua kanal dengan warna kanalnya, sub-kanal sebagai dropdown akordeon --}}
<div id="wc-sidenav" data-open="false" class="group/side invisible fixed inset-0 z-[70] data-[open=true]:visible lg:hidden">
    <button type="button" data-sidenav-close class="absolute inset-0 bg-ink/50 opacity-0 transition duration-300 group-data-[open=true]/side:opacity-100" aria-label="Tutup menu" tabindex="-1"></button>
    <aside class="absolute inset-y-0 left-0 flex w-[86%] max-w-sm -translate-x-full flex-col bg-paper transition duration-300 ease-[var(--ease-paper)] group-data-[open=true]/side:translate-x-0" aria-label="Navigasi">
        <div class="flex items-center justify-between border-b-[3px] border-ink px-5 py-4">
            <img src="{{ $site['brand']['logo'] }}" alt="RayaTimes" class="h-8 w-auto">
            <button type="button" data-sidenav-close class="grid size-10 place-items-center bg-stone text-xl transition hover:bg-ink hover:text-paper" aria-label="Tutup menu"><i class="ri-close-line"></i></button>
        </div>
        <nav class="flex-1 overflow-y-auto" aria-label="Semua kanal">
            <ul class="divide-y divide-ink/10">
                @foreach ($nav as $item)
                    @php $isActive = $activeSlug === $item['slug']; @endphp
                    <li data-kanal="{{ $item['slug'] }}" @if ($item['children']) data-accordion data-open="{{ $isActive ? 'true' : 'false' }}" @endif class="group/acc">
                        <div class="flex items-stretch">
                            <a href="{{ $item['url'] }}" @if ($isActive) aria-current="page" @endif class="flex flex-1 items-center gap-3 px-5 py-3.5 text-[15px] font-semibold transition hover:bg-stone aria-[current=page]:text-java">
                                <span class="size-2.5 shrink-0 bg-(--hue)"></span>{{ $item['name'] }}
                            </a>
                            @if ($item['children'])
                                <button type="button" data-accordion-toggle aria-expanded="{{ $isActive ? 'true' : 'false' }}" aria-controls="wc-acc-{{ $item['slug'] }}" aria-label="Sub-kanal {{ $item['name'] }}" class="grid w-14 place-items-center text-xl text-graphite transition hover:bg-stone hover:text-ink">
                                    <i class="ri-arrow-down-s-line transition-transform duration-300 group-data-[open=true]/acc:rotate-180"></i>
                                </button>
                            @endif
                        </div>
                        @if ($item['children'])
                            <div id="wc-acc-{{ $item['slug'] }}" class="grid grid-rows-[0fr] transition-[grid-template-rows] duration-300 group-data-[open=true]/acc:grid-rows-[1fr]">
                                <ul class="overflow-hidden border-l-[6px] border-(--hue) bg-stone">
                                    @foreach ($item['children'] as $child)
                                        <li><a href="{{ $child['url'] }}" class="block px-6 py-2.5 text-sm font-medium hover:text-java">{{ $child['name'] }}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </li>
                @endforeach
            </ul>
        </nav>
        <div class="grid grid-cols-2 gap-2 border-t border-ink/10 p-4">
            <a href="#" class="wc-btn-line">Masuk</a>
            <a href="#" class="wc-btn !bg-java">Berlangganan</a>
        </div>
    </aside>
</div>
