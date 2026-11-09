@php
    $site = $rt->site();
    $nav = $rt->nav();
    // Kanal aktif: dari halaman kanal, artikel (kanal induknya), atau path statis.
    $activeSlug = match (true) {
        isset($category) => $category['parent'] ?? $category['slug'],
        isset($article) => $article['category']['parent'] ?? $article['category']['slug'],
        default => trim($rt->localPath(), '/'),
    };
@endphp
<header class="sticky top-0 z-50 border-b border-line bg-paper lg:relative lg:border-0">
    <div class="mx-auto flex max-w-7xl items-center gap-4 px-4 py-3 lg:gap-8 lg:py-4">
        <button type="button" class="-ml-1 grid size-10 place-items-center text-2xl lg:hidden" data-toggle="rt-drawer" aria-controls="rt-drawer" aria-expanded="false" aria-label="Buka menu navigasi">
            <i class="ri-menu-line"></i>
        </button>

        <a href="{{ $rt->url('/') }}" class="shrink-0" aria-label="RayaTimes — beranda">
            <img src="{{ $site['brand']['logo'] }}" alt="RayaTimes portal" class="h-9 w-auto lg:h-11 dark:hidden" width="146" height="45">
            <img src="{{ $site['brand']['logo_dark'] }}" alt="RayaTimes portal" class="hidden h-9 w-auto lg:h-11 dark:block" width="146" height="45">
        </a>

        <form action="{{ $rt->url('/search') }}" method="get" role="search" data-search-root class="group relative mx-auto hidden w-full max-w-md md:block">
            <label for="rt-q" class="sr-only">Cari berita</label>
            <i class="ri-search-line pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-muted transition group-focus-within:text-raya"></i>
            <input id="rt-q" name="q" type="search" autocomplete="off" value="{{ $query ?? '' }}" placeholder="Cari berita, topik, penulis…"
                data-instant-search="rt-suggest"
                class="w-full py-2.5 pl-10 pr-4 text-sm text-fg placeholder:text-muted focus:outline-none">
            <div id="rt-suggest" data-suggest data-open="false"
                class="invisible absolute inset-x-0 top-full mt-1 translate-y-1 border border-line bg-surface opacity-0 shadow-[0_18px_40px_-20px_rgb(0_0_0/0.35)] transition duration-200 data-[open=true]:visible data-[open=true]:translate-y-0 data-[open=true]:opacity-100">
                <div data-results class="divide-y divide-line"></div>
                <a data-all href="{{ $rt->url('/search') }}" class="flex items-center justify-between border-t border-line px-4 py-3 text-sm font-semibold text-accent hover:bg-sunk">
                    <span>Lihat semua hasil (<span data-count>0</span>)</span><i class="ri-arrow-right-line"></i>
                </a>
            </div>
        </form>

        <div class="ml-auto flex items-center gap-1 md:ml-0">
            <a href="{{ $rt->url('/search') }}" class="grid size-10 place-items-center text-xl md:hidden" aria-label="Cari"><i class="ri-search-line"></i></a>
            <button type="button" data-theme-toggle class="grid size-10 place-items-center text-xl text-fg transition hover:text-accent" aria-label="Ganti tema terang/gelap">
                <i class="ri-moon-line dark:hidden"></i><i class="ri-sun-line hidden dark:inline"></i>
            </button>
            <a href="#" class="hidden px-3 py-2 text-sm font-semibold sm:inline-flex">Masuk</a>
            <a href="#" class="rt-btn rt-btn-solid hidden !px-4 !py-2 sm:inline-flex">Daftar</a>
        </div>
    </div>
</header>

{{-- Bilah kanal merah (desktop, sticky). Kanal bersub-kanal punya dropdown: hover, fokus, atau ketuk panah. --}}
<div data-header class="sticky top-0 z-40 hidden transition-shadow duration-300 data-[scrolled=true]:shadow-[0_10px_30px_-18px_rgb(0_0_0/0.6)] lg:block">
    <nav aria-label="Kanal" class="bg-raya text-white">
        <div class="mx-auto flex max-w-7xl items-stretch px-4">
            <ul class="flex flex-1 items-stretch">
                @foreach ($nav as $item)
                    @php $isActive = $activeSlug === $item['slug']; @endphp
                    <li @if ($item['children']) data-dropdown data-open="false" @endif class="group/nav relative flex">
                        <a href="{{ $item['url'] }}" @if ($isActive) aria-current="page" @endif
                            @class(['relative flex h-11 items-center text-[13.5px] font-semibold transition-colors hover:bg-raya-deep aria-[current=page]:bg-raya-deep', 'pl-3 pr-1' => $item['children'], 'px-3' => ! $item['children']])>
                            {{ $item['name'] }}
                            <span @class(['absolute bottom-0 h-[3px] origin-left scale-x-0 bg-sun transition-transform duration-300 group-hover/nav:scale-x-100 group-data-[open=true]/nav:scale-x-100', 'left-3 -right-5' => $item['children'], 'inset-x-3' => ! $item['children'], '!scale-x-100' => $isActive])></span>
                        </a>
                        @if ($item['children'])
                            <button type="button" data-dropdown-toggle aria-expanded="false" aria-controls="dd-{{ $item['slug'] }}" aria-label="Buka sub-kanal {{ $item['name'] }}"
                                class="flex h-11 items-center pr-2 text-base opacity-80 transition hover:bg-raya-deep hover:opacity-100">
                                <i class="ri-arrow-down-s-line transition-transform duration-300 group-hover/nav:rotate-180 group-data-[open=true]/nav:rotate-180"></i>
                            </button>
                            <div id="dd-{{ $item['slug'] }}" class="invisible absolute left-0 top-full z-10 min-w-56 -translate-y-1 border-t-[3px] border-sun bg-surface py-2 text-fg opacity-0 shadow-[0_20px_40px_-24px_rgb(0_0_0/0.5)] transition duration-200 group-hover/nav:visible group-hover/nav:translate-y-0 group-hover/nav:opacity-100 group-focus-within/nav:visible group-focus-within/nav:translate-y-0 group-focus-within/nav:opacity-100 group-data-[open=true]/nav:visible group-data-[open=true]/nav:translate-y-0 group-data-[open=true]/nav:opacity-100">
                                <a href="{{ $item['url'] }}" class="block px-4 py-2 text-sm font-semibold hover:bg-sunk hover:text-accent">Semua {{ $item['name'] }}</a>
                                @foreach ($item['children'] as $child)
                                    <a href="{{ $child['url'] }}" class="block px-4 py-2 text-sm hover:bg-sunk hover:text-accent">{{ $child['name'] }}</a>
                                @endforeach
                            </div>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </nav>
</div>

{{-- Sidenav (ponsel & tablet): semua kanal, sub-kanal sebagai dropdown akordeon --}}
<div id="rt-drawer" data-open="false" data-lock class="group/drawer invisible fixed inset-0 z-[66] data-[open=true]:visible lg:hidden">
    <button type="button" class="absolute inset-0 bg-black/50 opacity-0 transition duration-300 group-data-[open=true]/drawer:opacity-100" data-toggle="rt-drawer" aria-label="Tutup menu" tabindex="-1"></button>
    <aside class="absolute inset-y-0 left-0 flex w-[86%] max-w-sm -translate-x-full flex-col bg-surface transition duration-300 ease-[var(--ease-smooth)] group-data-[open=true]/drawer:translate-x-0" aria-label="Navigasi">
        <div class="flex items-center justify-between border-b-[3px] border-raya px-4 py-3">
            <img src="{{ $site['brand']['logo'] }}" alt="RayaTimes" class="h-8 dark:hidden"><img src="{{ $site['brand']['logo_dark'] }}" alt="RayaTimes" class="hidden h-8 dark:block">
            <button type="button" class="grid size-10 place-items-center text-2xl" data-toggle="rt-drawer" aria-label="Tutup menu"><i class="ri-close-line"></i></button>
        </div>
        <form action="{{ $rt->url('/search') }}" class="border-b border-line p-4" role="search">
            <label for="rt-drawer-q" class="sr-only">Cari berita</label>
            <div class="group relative"><i class="ri-search-line pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-muted transition group-focus-within:text-raya"></i>
                <input id="rt-drawer-q" name="q" type="search" placeholder="Cari berita…" class="w-full py-2.5 pl-9 pr-3 text-sm focus:outline-none"></div>
        </form>
        <nav class="flex-1 overflow-y-auto py-2" aria-label="Semua kanal">
            <ul>
                @foreach ($nav as $item)
                    @php $isActive = $activeSlug === $item['slug']; @endphp
                    <li @if ($item['children']) data-accordion data-open="{{ $isActive ? 'true' : 'false' }}" @endif class="group/acc">
                        <div class="flex items-stretch">
                            <a href="{{ $item['url'] }}" @if ($isActive) aria-current="page" @endif class="flex-1 border-l-4 border-transparent px-4 py-3 font-serif text-lg font-semibold hover:bg-sunk aria-[current=page]:border-raya aria-[current=page]:text-accent">{{ $item['name'] }}</a>
                            @if ($item['children'])
                                <button type="button" data-accordion-toggle aria-expanded="{{ $isActive ? 'true' : 'false' }}" aria-controls="acc-{{ $item['slug'] }}" aria-label="Sub-kanal {{ $item['name'] }}" class="grid w-12 place-items-center text-xl text-muted transition hover:bg-sunk hover:text-fg">
                                    <i class="ri-arrow-down-s-line transition-transform duration-300 group-data-[open=true]/acc:rotate-180"></i>
                                </button>
                            @endif
                        </div>
                        @if ($item['children'])
                            <div id="acc-{{ $item['slug'] }}" class="grid grid-rows-[0fr] transition-[grid-template-rows] duration-300 ease-[var(--ease-smooth)] group-data-[open=true]/acc:grid-rows-[1fr]">
                                <ul class="overflow-hidden bg-sunk">
                                    @foreach ($item['children'] as $child)
                                        <li><a href="{{ $child['url'] }}" class="block py-2.5 pl-9 pr-4 text-sm hover:text-accent">{{ $child['name'] }}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </li>
                @endforeach
            </ul>
        </nav>
        <div class="space-y-3 border-t border-line p-4">
            <div class="grid grid-cols-2 gap-2"><a href="#" class="rt-btn">Masuk</a><a href="#" class="rt-btn rt-btn-solid">Daftar</a></div>
            <button type="button" data-theme-toggle class="flex w-full items-center justify-center gap-2 py-2 text-sm font-semibold text-muted hover:text-fg"><i class="ri-moon-line dark:hidden"></i><i class="ri-sun-line hidden dark:inline"></i>Ganti tema</button>
        </div>
    </aside>
</div>
