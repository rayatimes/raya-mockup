@extends('lensa::layouts.app', ['overlay' => true])

@php
    $site = $rt->site();
    $slides = $rt->headlines(5);
    $used = array_column($slides, 'slug');
    $latest = $rt->latest(5, 0, $used);
    $used = [...$used, ...array_column($latest, 'slug')];
    $pulse = $rt->latest(7, 0, $used);
    $used = [...$used, ...array_column($pulse, 'slug')];
    $tajuk = $rt->inCategory('tajuk')[0] ?? null;
    $media = $rt->media();
@endphp

@section('content')
    {{-- Hero bergaya story --}}
    <section data-stories data-duration="6500" data-theme="dark" class="relative h-[88svh] min-h-[34rem] max-h-[58rem] overflow-hidden bg-ground" aria-roledescription="carousel" aria-label="Sorotan utama">
        @foreach ($slides as $i => $item)
            <article data-slide data-active="{{ $i === 0 ? 'true' : 'false' }}" class="absolute inset-0" aria-roledescription="slide" aria-label="{{ $i + 1 }} dari {{ count($slides) }}">
                <x-lensa::frame :src="$item['image']['lg']" :alt="$item['title']" :eager="$i === 0" class="absolute inset-0" />
                <div class="absolute inset-0 bg-gradient-to-t from-ground via-ground/40 to-black/30"></div>
                <div class="absolute inset-x-0 bottom-0">
                    <div data-slide-copy class="mx-auto max-w-[90rem] px-4 pb-24 lg:px-8 lg:pb-28">
                        <a href="{{ $item['category']['url'] }}" class="ls-kanal text-sm"><i class="ri-focus-3-line"></i>{{ $item['category']['name'] }}</a>
                        <h2 class="ls-headline mt-3 max-w-5xl text-[2.6rem] sm:text-6xl lg:text-[5.2rem]">
                            <a href="{{ $item['url'] }}" class="transition hover:text-sun">{{ $item['title'] }}</a>
                        </h2>
                        <p class="mt-4 max-w-2xl text-base text-ink/80 lg:text-lg">{{ $item['excerpt'] }}</p>
                    </div>
                </div>
            </article>
        @endforeach

        <div class="absolute inset-x-0 bottom-0 z-10">
            <div class="mx-auto flex max-w-[90rem] items-center gap-4 px-4 pb-8 lg:px-8">
                <div class="flex flex-1 gap-1.5">
                    @foreach ($slides as $i => $item)
                        <button type="button" data-bar data-state="idle" class="relative h-1 flex-1 overflow-hidden rounded-full bg-white/25" aria-label="Sorotan {{ $i + 1 }}: {{ $item['title'] }}">
                            <span class="absolute inset-0 rounded-full bg-sun"></span>
                        </button>
                    @endforeach
                </div>
                <div class="flex gap-2">
                    <button type="button" data-go="-1" class="grid size-11 place-items-center rounded-full bg-white/10 text-xl transition hover:bg-sun hover:text-ground" aria-label="Sorotan sebelumnya"><i class="ri-arrow-left-line"></i></button>
                    <button type="button" data-go="1" class="grid size-11 place-items-center rounded-full bg-white/10 text-xl transition hover:bg-sun hover:text-ground" aria-label="Sorotan berikutnya"><i class="ri-arrow-right-line"></i></button>
                </div>
            </div>
        </div>
    </section>

    {{-- Banner 2 kolom --}}
    <x-lensa::banners :banners="$site['home_banners']" class="mt-10" />

    {{-- Kolom utama berisi semua berita dulu (Terkini, lalu kanal); isi kolom samping menyusul.
         Di ponsel urutannya: Terkini → kanal → kolom samping. --}}
    <div class="mx-auto mt-16 grid max-w-[90rem] gap-10 px-4 lg:grid-cols-[minmax(0,1fr)_22rem] lg:px-8">
        <div class="min-w-0 space-y-16">
            <section>
                <x-lensa::head title="Terkini" :href="$rt->url('/index')" />
                <div class="grid gap-6 sm:grid-cols-2">
                    <x-lensa::card.cover :article="$latest[0]" :rt="$rt" size="text-3xl lg:text-4xl" large class="aspect-[4/5] sm:col-span-2 sm:aspect-[16/8]" />
                    @foreach (array_slice($latest, 1, 4) as $item)
                        <x-lensa::card.photo :article="$item" :rt="$rt" ratio="aspect-video" />
                    @endforeach
                </div>
                <div id="ls-more" class="mt-10 grid gap-x-5 gap-y-10 sm:grid-cols-2 xl:grid-cols-3">
                    @foreach ($rt->latest(9, 0, $used) as $item)
                        <x-lensa::card.photo :article="$item" :rt="$rt" />
                    @endforeach
                </div>
                <x-lensa::more target="#ls-more" scope="latest" :offset="9" :per="9" :exclude="$used" />
            </section>

            {{-- Kanal berurutan: Ekonomi → Politik → Hukum → Hankam, masing-masing menuju halaman kanal --}}
            @foreach ($site['home_sections'] as $slug)
                @php $railCategory = $rt->category($slug); @endphp
                <x-lensa::rail :title="$railCategory['name']" :href="$railCategory['url']" :items="array_slice($rt->inCategory($slug), 0, 10)" :rt="$rt" :inset="false" />
            @endforeach
        </div>

        <aside class="space-y-6 lg:sticky lg:top-24 lg:self-start">
            <section class="rounded-frame bg-panel p-6">
                <h2 class="flex items-center gap-2 text-sm font-semibold"><span class="relative flex size-2.5"><span class="absolute inset-0 animate-ping rounded-full bg-raya opacity-75"></span><span class="relative size-2.5 rounded-full bg-raya"></span></span>Detik ini</h2>
                <ol class="mt-5 space-y-5 border-l border-edge pl-5">
                    @foreach ($pulse as $item)
                        <li class="relative before:absolute before:-left-[25px] before:top-1.5 before:size-2 before:rounded-full before:bg-sun">
                            <x-lensa::card.line :article="$item" :rt="$rt" time />
                        </li>
                    @endforeach
                </ol>
            </section>
            <x-lensa::trending :rt="$rt" />
            <x-lensa::rank :rt="$rt" />
        </aside>
    </div>

    {{-- Tajuk: satu bingkai penuh, foto sebagai suasana --}}
    @if ($tajuk)
        <section class="mt-20">
            <a href="{{ $tajuk['url'] }}" data-card data-theme="dark" class="group relative isolate block overflow-hidden bg-ground">
                <x-lensa::frame :src="$tajuk['image']['lg']" alt="" class="absolute inset-0 -z-10 opacity-45" />
                <span class="absolute inset-0 -z-10 bg-gradient-to-r from-ground via-ground/80 to-ground/20"></span>
                <span class="mx-auto block max-w-[90rem] px-4 py-24 lg:px-8 lg:py-32">
                    <span class="ls-kanal text-sm"><i class="ri-quill-pen-line"></i>Tajuk RayaTimes</span>
                    <span class="ls-headline mt-4 block max-w-4xl text-5xl transition-colors group-hover:text-sun lg:text-7xl">{{ $tajuk['title'] }}</span>
                    <span class="mt-5 block max-w-2xl font-read text-xl leading-relaxed text-ink/80">{{ $tajuk['excerpt'] }}</span>
                    <span class="ls-btn mt-8">Baca tajuk<i class="ri-arrow-right-line"></i></span>
                </span>
            </a>
        </section>
    @endif

    {{-- Infografis --}}
    @if ($media)
        <section class="mx-auto mt-20 max-w-[90rem] px-4 lg:px-8">
            <x-lensa::head title="Infografis" :href="$rt->url('/media')" note="Data dan isu dalam satu tampilan" />
            <div class="grid gap-4 sm:grid-cols-2">
                @foreach ($media as $item)
                    <a href="{{ $item['url'] }}" data-card data-theme="dark" class="group relative isolate block aspect-[16/10] overflow-hidden rounded-frame bg-raise">
                        <x-lensa::frame :src="$item['images'][0]['lg']" :alt="$item['title']" class="absolute inset-0 -z-10 [&>img]:object-top" />
                        <span class="ls-shade absolute inset-0 -z-10"></span>
                        <span class="absolute inset-x-5 bottom-5"><span class="ls-kanal"><i class="ri-image-line"></i>Infografis</span><span class="ls-headline mt-1 block text-3xl">{{ $item['title'] }}</span></span>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

@endsection
