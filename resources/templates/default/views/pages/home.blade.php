@extends('default::layouts.app')

@php
    $site = $rt->site();
    $hero = $rt->headlines(6);
    $heroSlugs = array_column($hero, 'slug');
    $latest = $rt->latest(10, 0, $heroSlugs);
    $widgets = collect($site['home_widgets']);
    $single = $widgets->firstWhere('type', 'single');
    $singleArticle = $single ? $rt->article($single['config']['article']) : null;
    $opinion = array_slice([...$rt->inCategory('tajuk'), ...$rt->inCategory('analisis')], 0, 8);
    usort($opinion, fn ($a, $b) => strcmp($b['published_at'], $a['published_at']));
    $opinion = array_slice($opinion, 0, 4);
@endphp

@section('content')
    {{-- Sorotan utama: desktop grid, mobile geser --}}
    <section class="mx-auto max-w-7xl px-4 pt-5" aria-label="Berita utama">
        <div class="hidden gap-3 lg:grid lg:grid-cols-3 lg:grid-rows-[15rem_15rem_13rem]">
            <x-default::card.overlay :article="$hero[0]" :rt="$rt" size="lg" eager class="col-span-2 row-span-2" />
            <x-default::card.overlay :article="$hero[1]" :rt="$rt" />
            <x-default::card.overlay :article="$hero[2]" :rt="$rt" />
            @foreach (array_slice($hero, 3, 3) as $item)
                <x-default::card.overlay :article="$item" :rt="$rt" size="sm" />
            @endforeach
        </div>
        <div class="lg:hidden">
            <div data-hero-track class="no-scrollbar -mx-4 flex snap-x snap-mandatory overflow-x-auto">
                @foreach ($hero as $i => $item)
                    <x-default::card.overlay :article="$item" :rt="$rt" size="lg" :eager="$i === 0" class="aspect-[4/5] w-full shrink-0 snap-center sm:aspect-video" />
                @endforeach
            </div>
            <div class="mt-3 flex justify-center gap-1.5">
                @foreach ($hero as $i => $item)
                    <button type="button" data-dot="{{ $i }}" aria-current="{{ $i === 0 ? 'true' : 'false' }}" aria-label="Sorotan {{ $i + 1 }}" class="h-1 w-4 bg-line transition-all duration-300 aria-[current=true]:w-8 aria-[current=true]:bg-raya"></button>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Banner 2 kolom --}}
    <x-default::banners :banners="$site['home_banners']" class="mx-auto mt-8 max-w-7xl px-4" />

    {{-- Kolom utama berisi semua berita dulu (Terkini, lalu kanal); isi kolom samping menyusul.
         Di ponsel urutannya: Terkini → kanal → kolom samping. --}}
    <div class="mx-auto mt-10 grid max-w-7xl gap-10 px-4 lg:grid-cols-[minmax(0,1fr)_20rem]">
        <div class="min-w-0 space-y-14">
            <section>
                <x-default::section-head title="Terkini" :href="$rt->url('/index')" />
                <div id="home-latest" class="-mt-5 divide-y divide-line">
                    @foreach ($latest as $item)
                        <x-default::card.row :article="$item" :rt="$rt" excerpt />
                    @endforeach
                </div>
                <x-default::loadmore target="#home-latest" scope="latest" :offset="10" :per="6" :exclude="$heroSlugs" label="Muat lebih banyak" />
            </section>

            {{-- Kanal berurutan: Ekonomi → Politik → Hukum → Hankam, masing-masing menuju halaman kanal --}}
            @foreach ($site['home_sections'] as $slug)
                @php $railCategory = $rt->category($slug); @endphp
                <x-default::rail :title="$railCategory['name']" :href="$railCategory['url']" :items="array_slice($rt->inCategory($slug), 0, 8)" :rt="$rt" />
            @endforeach
        </div>

        <aside class="space-y-8 lg:sticky lg:top-16 lg:self-start">
            @if ($singleArticle)
                <article data-card class="group border border-line bg-surface">
                    <a href="{{ $singleArticle['url'] }}" tabindex="-1" aria-hidden="true"><x-default::img :src="$singleArticle['image']['sm']" :alt="$singleArticle['title']" class="aspect-video" /></a>
                    <div class="p-4">
                        <a href="{{ $singleArticle['category']['url'] }}" class="rt-kanal">{{ $singleArticle['category']['name'] }}</a>
                        <h3 class="mt-1 text-lg leading-snug"><a href="{{ $singleArticle['url'] }}" class="rt-title">{{ $singleArticle['title'] }}</a></h3>
                        <p class="mt-2 text-sm leading-relaxed text-muted line-clamp-3">{{ $singleArticle['excerpt'] }}</p>
                    </div>
                </article>
            @endif
            <x-default::widget.trending :rt="$rt" />
            <x-default::widget.popular :rt="$rt" />
            <x-default::widget.newsletter />
        </aside>
    </div>

    {{-- Tajuk & Analisis: pita gelap editorial --}}
    <section class="mt-14 bg-ink py-12 text-white dark:bg-surface">
        <div class="mx-auto max-w-7xl px-4">
            <div class="mb-8 flex items-end justify-between gap-4 border-b border-white/15 pb-3">
                <h2 class="flex items-center gap-2.5 font-serif text-xl font-bold"><span class="block h-5 w-1.5 bg-sun"></span>Tajuk &amp; Analisis</h2>
                <a href="{{ $rt->url('/@tajuk') }}" class="text-xs font-semibold text-sun hover:underline">Semua tajuk</a>
            </div>
            <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-4">
                @foreach ($opinion as $item)
                    <article data-card class="group flex flex-col">
                        <a href="{{ $item['category']['url'] }}" class="text-[11px] font-bold uppercase tracking-wide text-sun">{{ $item['category']['name'] }}</a>
                        <h3 class="mt-2 font-serif text-2xl font-semibold leading-tight text-balance">
                            <a href="{{ $item['url'] }}" class="bg-[linear-gradient(var(--color-sun),var(--color-sun))] bg-[length:0_2px] bg-left-bottom bg-no-repeat transition-[background-size] duration-500 group-hover:bg-[length:100%_2px]">{{ $item['title'] }}</a>
                        </h3>
                        <p class="mt-3 text-sm leading-relaxed text-white/70 line-clamp-3">{{ $item['excerpt'] }}</p>
                        <p class="mt-auto pt-4 text-xs text-white/60">{{ $rt->authorLine($item, compact: true) }} · {{ $rt->ago($item['published']) }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

@endsection
