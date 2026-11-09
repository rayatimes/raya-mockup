@extends('wacana::layouts.app')

@php
    $site = $rt->site();
    $headlines = $rt->headlines(5, diverse: true);
    $lead = $headlines[0];
    $side = array_slice($headlines, 1, 4);
    $used = array_column($headlines, 'slug');
    $tajuk = $rt->inCategory('tajuk');
    // Satu tulisan dari tiap kanal perspektif agar bidang warnanya beragam.
    $opinion = array_values(array_filter(array_map(fn ($slug) => $rt->inCategory($slug)[0] ?? null, ['analisis', 'khazanah', 'ekonomi', 'hankam'])));
@endphp

@section('content')
    {{-- Headline: satu berita utama besar di kiri, empat headline lain di kanan --}}
    <section class="mx-auto mt-10 grid max-w-[84rem] gap-10 px-4 lg:grid-cols-12 lg:px-8" aria-label="Headline">
        <article data-card data-kanal="{{ $lead['category']['slug'] }}" class="group lg:col-span-7">
            <a href="{{ $lead['url'] }}" tabindex="-1" aria-hidden="true" class="block"><x-wacana::photo :src="$lead['image']['lg']" :alt="$lead['title']" eager class="aspect-[16/10]" /></a>
            <x-wacana::kanal :category="$lead['category']" class="mt-5" />
            <h1 class="wc-display mt-2 text-[2.4rem] sm:text-5xl lg:text-[3.6rem]"><a href="{{ $lead['url'] }}" class="wc-link">{{ $lead['title'] }}</a></h1>
            <p class="mt-4 max-w-3xl text-lg leading-relaxed text-graphite">{{ $lead['excerpt'] }}</p>
            <p class="wc-meta mt-4">Oleh <x-wacana::author-names :article="$lead" /> · {{ $rt->ago($lead['published']) }} · {{ $lead['reading_time'] }} menit baca</p>
        </article>

        <div class="lg:col-span-5">
            <ol class="divide-y divide-ink/10 [&>li:first-child]:pt-0">
                @foreach ($side as $item)
                    <li data-card data-kanal="{{ $item['category']['slug'] }}" class="group grid grid-cols-[8rem_1fr] gap-4 py-5 sm:grid-cols-[10rem_1fr]">
                        <a href="{{ $item['url'] }}" tabindex="-1" aria-hidden="true"><x-wacana::photo :src="$item['image']['sm']" :alt="$item['title']" class="aspect-[4/3]" /></a>
                        <div class="min-w-0">
                            <x-wacana::kanal :category="$item['category']" />
                            <h3 class="wc-head mt-1.5 text-xl sm:text-[1.375rem]"><a href="{{ $item['url'] }}" class="wc-link">{{ $item['title'] }}</a></h3>
                            <p class="wc-meta mt-2">{{ $rt->authorLine($item, compact: true) }}, {{ $rt->ago($item['published']) }}</p>
                        </div>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    {{-- Banner 2 kolom --}}
    <x-wacana::banners :banners="$rt->featureBanners()" class="mt-16" />

    {{-- Kolom utama berisi semua berita dulu (Terkini, lalu kanal); isi kolom samping menyusul.
         Di ponsel urutannya: Terkini → kanal → kolom samping. --}}
    <div class="mx-auto mt-20 grid max-w-[84rem] gap-12 px-4 lg:grid-cols-12 lg:px-8">
        <div class="min-w-0 space-y-20 lg:col-span-8">
            <section>
                <x-wacana::section title="Terkini" :href="$rt->url('/index')" />
                <div id="wc-latest" class="-mt-6 divide-y divide-ink/10">
                    @foreach ($rt->latest(6, 0, $used) as $item)
                        <x-wacana::entry :article="$item" :rt="$rt" />
                    @endforeach
                </div>
                <x-wacana::more target="#wc-latest" scope="latest" :offset="6" :per="6" :exclude="$used" />
            </section>

            {{-- Kanal berurutan: Ekonomi → Politik → Hukum → Hankam, masing-masing menuju halaman kanal --}}
            @foreach ($site['home_sections'] as $slug)
                @php
                    $kanal = $rt->category($slug);
                    $items = array_slice($rt->inCategory($slug), 0, 5);
                @endphp
                <section>
                    <x-wacana::section :title="$kanal['name']" :href="$kanal['url']" />
                    <article data-card data-kanal="{{ $slug }}" class="group grid gap-6 sm:grid-cols-[minmax(0,7fr)_minmax(0,5fr)]">
                        <a href="{{ $items[0]['url'] }}" tabindex="-1" aria-hidden="true"><x-wacana::photo :src="$items[0]['image']['lg']" :alt="$items[0]['title']" class="aspect-[16/10]" /></a>
                        <div>
                            <h3 class="wc-display text-3xl"><a href="{{ $items[0]['url'] }}" class="wc-link">{{ $items[0]['title'] }}</a></h3>
                            <p class="mt-3 text-base leading-relaxed text-graphite line-clamp-4">{{ $items[0]['excerpt'] }}</p>
                            <p class="wc-meta mt-3">{{ $rt->authorLine($items[0], compact: true) }}, {{ $rt->ago($items[0]['published']) }}</p>
                        </div>
                    </article>
                    <div class="mt-4 grid gap-x-8 sm:grid-cols-2 lg:grid-cols-1 xl:grid-cols-2">
                        @foreach (array_slice($items, 1) as $item)
                            <x-wacana::entry :article="$item" :rt="$rt" :excerpt="false" class="border-t border-ink/10" />
                        @endforeach
                    </div>
                </section>
            @endforeach
        </div>

        <aside class="lg:col-span-4">
            <div class="space-y-10 lg:sticky lg:top-20">
                <x-wacana::popular :rt="$rt" :limit="5" />
                <x-wacana::trending :rt="$rt" :limit="5" />
            </div>
        </aside>
    </div>

    {{-- Tajuk: latar utama RayaTimes Insight; satu tajuk utama berfoto, sisanya daftar --}}
    @if ($tajuk)
        <section class="wc-tajuk mt-20 text-paper" style="--tajuk-photo: url('{{ asset('dummy/banner/insight-cover.webp') }}')">
            <div class="mx-auto grid max-w-[84rem] gap-12 px-4 py-16 lg:grid-cols-12 lg:px-8 lg:py-24">
                <article data-card class="group lg:col-span-7">
                    <p class="text-sm font-semibold text-sun">Tajuk Raya</p>
                    <a href="{{ $tajuk[0]['url'] }}" tabindex="-1" aria-hidden="true" class="mt-4 block"><x-wacana::photo :src="$tajuk[0]['image']['lg']" :alt="$tajuk[0]['title']" class="aspect-[16/9]" /></a>
                    <h2 class="wc-display mt-6 text-4xl lg:text-[3.4rem]"><a href="{{ $tajuk[0]['url'] }}" class="wc-link">{{ $tajuk[0]['title'] }}</a></h2>
                    <p class="mt-4 max-w-2xl text-lg leading-relaxed text-paper/90">{{ $tajuk[0]['excerpt'] }}</p>
                    <a href="{{ $tajuk[0]['url'] }}" class="wc-btn mt-8 !bg-sun !text-ink hover:!bg-paper">Baca sikap redaksi<i class="ri-arrow-right-line"></i></a>
                </article>
                <div class="lg:col-span-5">
                    <p class="text-sm font-semibold text-sun">Tajuk sebelumnya</p>
                    <ul class="mt-2 divide-y divide-paper/25">
                        @foreach (array_slice($tajuk, 1, 5) as $item)
                            <li data-card class="group py-5">
                                <a href="{{ $item['url'] }}" class="wc-head wc-link text-[1.4rem]">{{ $item['title'] }}</a>
                                <p class="wc-meta mt-2 !text-paper/90">{{ $rt->ago($item['published']) }}</p>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </section>
    @endif

    {{-- Analisis & perspektif --}}
    <section class="mx-auto mt-20 max-w-[84rem] px-4 lg:px-8">
        <x-wacana::section title="Analisis & perspektif" lede="Membaca di balik peristiwa: sebab, keterkaitan, dan dampaknya." :href="$rt->url('/@analisis')" />
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($opinion as $item)
                <x-wacana::story-card :article="$item" :rt="$rt" />
            @endforeach
        </div>
    </section>


@endsection
