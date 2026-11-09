@php
    $site = $rt->site();
    $nav = $rt->nav();
    $current = '/'.ltrim(request()->path(), '/');
    $activeSlug = match (true) {
        isset($category) => $category['parent'] ?? $category['slug'],
        isset($article) => $article['category']['parent'] ?? $article['category']['slug'],
        default => null,
    };
@endphp
<header data-header data-solid="false" @if ($overlay) data-overlay data-theme="dark" @endif @class([
    'group/h fixed inset-x-0 top-0 z-50 transition-colors duration-500',
    'bg-gradient-to-b from-black/70 to-transparent data-[solid=true]:bg-ground data-[solid=true]:from-ground' => $overlay,
    'bg-ground' => ! $overlay,
])>
    <div class="mx-auto flex h-16 max-w-[90rem] items-center gap-3 px-4 lg:h-20 lg:gap-6 lg:px-8">
        <button type="button" data-sidenav-open aria-controls="ls-sidenav" aria-expanded="false" class="-ml-1 grid size-10 place-items-center rounded-full text-2xl transition hover:bg-ink/10 lg:hidden" aria-label="Buka menu navigasi">
            <i class="ri-menu-2-line"></i>
        </button>
        <a href="{{ $rt->url('/') }}" class="shrink-0" aria-label="RayaTimes — beranda">
            <img src="{{ $site['brand']['logo_dark'] }}" alt="RayaTimes portal" class="ls-logo-on-dark h-8 w-auto lg:h-10" width="130" height="40">
            <img src="{{ $site['brand']['logo'] }}" alt="RayaTimes portal" class="ls-logo-on-light h-8 w-auto lg:h-10" width="130" height="40">
        </a>
        <nav class="hidden flex-1 lg:block" aria-label="Kanal">
            <ul class="flex items-center gap-1 text-[15px] font-medium">
                @foreach (array_slice($nav, 1, 10) as $item)
                    {{-- Kanal bersub-kanal: dropdown (hover, fokus, atau ketuk panah). --}}
                    <li @if ($item['children']) data-dropdown data-open="false" @endif class="group/nav relative flex items-center">
                        <a href="{{ $item['url'] }}" @if ($activeSlug === $item['slug'] || $current === $item['url']) aria-current="page" @endif
                            @class(['relative block whitespace-nowrap rounded-full py-2 text-ink/80 transition hover:text-ink aria-[current=page]:text-sun', 'pl-2.5 pr-0.5' => $item['children'], 'px-2.5' => ! $item['children']])>
                            {{ $item['name'] }}
                        </a>
                        @if ($item['children'])
                            <button type="button" data-dropdown-toggle aria-expanded="false" aria-controls="ls-dd-{{ $item['slug'] }}" aria-label="Buka sub-kanal {{ $item['name'] }}" class="grid size-7 place-items-center rounded-full text-ink/70 transition hover:bg-ink/10 hover:text-ink">
                                <i class="ri-arrow-down-s-line transition-transform duration-300 group-hover/nav:rotate-180 group-data-[open=true]/nav:rotate-180"></i>
                            </button>
                            {{-- pt-3 menjembatani celah agar hover tidak putus saat kursor turun ke panel --}}
                            <div id="ls-dd-{{ $item['slug'] }}" class="invisible absolute left-0 top-full z-10 -translate-y-1 pt-3 opacity-0 transition duration-200 group-hover/nav:visible group-hover/nav:translate-y-0 group-hover/nav:opacity-100 group-focus-within/nav:visible group-focus-within/nav:translate-y-0 group-focus-within/nav:opacity-100 group-data-[open=true]/nav:visible group-data-[open=true]/nav:translate-y-0 group-data-[open=true]/nav:opacity-100">
                                <div class="min-w-56 rounded-2xl bg-panel p-2 shadow-2xl ring-1 ring-edge">
                                    <a href="{{ $item['url'] }}" class="block rounded-xl px-3 py-2 text-sm font-semibold text-ink hover:bg-raise hover:text-sun">Semua {{ $item['name'] }}</a>
                                    @foreach ($item['children'] as $child)
                                        <a href="{{ $child['url'] }}" class="block rounded-xl px-3 py-2 text-sm text-ink/80 hover:bg-raise hover:text-sun">{{ $child['name'] }}</a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </li>
                @endforeach
            </ul>
        </nav>
        <div class="ml-auto flex items-center gap-2">
            <a href="{{ $rt->url('/search') }}" data-search-open class="hidden h-10 items-center gap-3 whitespace-nowrap rounded-full bg-ink/10 pl-4 pr-2 text-sm text-ink/70 transition hover:bg-ink/15 2xl:flex">
                <i class="ri-search-line"></i>Cari berita <kbd class="rounded-md bg-ink/10 px-2 py-0.5 text-xs">/</kbd>
            </a>
            <a href="{{ $rt->url('/search') }}" data-search-open class="grid size-10 place-items-center rounded-full bg-ink/10 text-xl transition hover:bg-sun hover:text-ground 2xl:hidden" aria-label="Cari berita"><i class="ri-search-line"></i></a>
            <button type="button" data-theme-toggle class="grid size-10 place-items-center rounded-full bg-ink/10 text-xl transition hover:bg-sun hover:text-ground" aria-label="Ganti tema terang/gelap">
                <i class="ri-sun-line ls-logo-on-dark"></i><i class="ri-moon-line ls-logo-on-light"></i>
            </button>
            <button type="button" data-panel="ls-channels" aria-controls="ls-channels" aria-expanded="false" class="hidden size-10 place-items-center rounded-full bg-ink/10 text-xl lg:grid transition hover:bg-sun hover:text-ground" aria-label="Semua kanal">
                <i class="ri-apps-2-line"></i>
            </button>
        </div>
    </div>
</header>

{{-- Panel semua kanal: tiap kanal tampil dengan foto berita terbarunya --}}
<div id="ls-channels" data-panel-body data-open="false"
    class="fixed inset-x-0 top-16 bottom-0 z-40 overflow-y-auto bg-ground/[0.98] opacity-0 transition duration-300 invisible data-[open=true]:visible data-[open=true]:opacity-100 lg:top-20">
    <div class="mx-auto max-w-[90rem] px-4 py-8 lg:px-8">
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-5">
            @foreach (array_slice($nav, 1) as $item)
                @php $cover = isset($item['parent']) ? ($rt->inCategory($item['slug'])[0] ?? null) : null; @endphp
                <a href="{{ $item['url'] }}" data-card data-theme="dark" class="group relative isolate block aspect-[4/3] overflow-hidden rounded-frame bg-raise">
                    @if ($cover)
                        <x-lensa::frame :src="$cover['image']['sm']" alt="" class="absolute inset-0 -z-10 opacity-60" />
                    @endif
                    <span class="ls-shade absolute inset-0 -z-10"></span>
                    <span class="absolute bottom-3 left-4 right-4">
                        <span class="ls-headline block text-2xl">{{ $item['name'] }}</span>
                        @if ($item['children'])<span class="mt-1 block truncate text-xs text-ink/60">{{ implode(' · ', array_column($item['children'], 'name')) }}</span>@endif
                    </span>
                </a>
            @endforeach
        </div>
    </div>
</div>

{{-- Sidenav (ponsel & tablet): semua kanal, sub-kanal sebagai dropdown akordeon --}}
<div id="ls-sidenav" data-open="false" class="group/side invisible fixed inset-0 z-[70] data-[open=true]:visible lg:hidden">
    <button type="button" data-sidenav-close class="absolute inset-0 bg-black/60 opacity-0 transition duration-300 group-data-[open=true]/side:opacity-100" aria-label="Tutup menu" tabindex="-1"></button>
    <aside class="absolute inset-y-0 left-0 flex w-[86%] max-w-sm -translate-x-full flex-col rounded-r-3xl bg-ground transition duration-500 ease-[var(--ease-out)] group-data-[open=true]/side:translate-x-0" aria-label="Navigasi">
        <div class="flex items-center justify-between px-5 py-4">
            <img src="{{ $site['brand']['logo_dark'] }}" alt="RayaTimes" class="ls-logo-on-dark h-8 w-auto"><img src="{{ $site['brand']['logo'] }}" alt="RayaTimes" class="ls-logo-on-light h-8 w-auto">
            <button type="button" data-sidenav-close class="grid size-10 place-items-center rounded-full bg-raise text-xl" aria-label="Tutup menu"><i class="ri-close-line"></i></button>
        </div>
        <nav class="flex-1 overflow-y-auto px-3 pb-4" aria-label="Semua kanal">
            <ul class="space-y-1">
                @foreach ($nav as $item)
                    @php $isActive = $activeSlug === $item['slug'] || $current === $item['url']; @endphp
                    <li @if ($item['children']) data-accordion data-open="{{ $isActive ? 'true' : 'false' }}" @endif class="group/acc">
                        <div class="flex items-center gap-1">
                            <a href="{{ $item['url'] }}" @if ($isActive) aria-current="page" @endif class="ls-headline flex-1 rounded-2xl px-4 py-3 text-2xl transition hover:bg-panel aria-[current=page]:text-sun">{{ $item['name'] }}</a>
                            @if ($item['children'])
                                <button type="button" data-accordion-toggle aria-expanded="{{ $isActive ? 'true' : 'false' }}" aria-controls="ls-acc-{{ $item['slug'] }}" aria-label="Sub-kanal {{ $item['name'] }}" class="grid size-11 place-items-center rounded-full bg-panel text-xl transition hover:bg-raise">
                                    <i class="ri-arrow-down-s-line transition-transform duration-300 group-data-[open=true]/acc:rotate-180"></i>
                                </button>
                            @endif
                        </div>
                        @if ($item['children'])
                            <div id="ls-acc-{{ $item['slug'] }}" class="grid grid-rows-[0fr] transition-[grid-template-rows] duration-300 group-data-[open=true]/acc:grid-rows-[1fr]">
                                <div class="overflow-hidden">
                                    <div class="flex flex-wrap gap-2 px-4 pb-3 pt-1">
                                        @foreach ($item['children'] as $child)
                                            <a href="{{ $child['url'] }}" class="ls-pill">{{ $child['name'] }}</a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    </li>
                @endforeach
            </ul>
        </nav>
        <div class="flex gap-2 p-4">
            <a href="{{ $rt->url('/search') }}" data-search-open class="ls-btn-ghost flex-1"><i class="ri-search-line"></i>Cari</a>
            <button type="button" data-theme-toggle class="ls-btn-ghost flex-1"><i class="ri-sun-line ls-logo-on-dark"></i><i class="ri-moon-line ls-logo-on-light"></i>Tema</button>
        </div>
    </aside>
</div>
