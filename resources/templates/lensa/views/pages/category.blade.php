@extends('lensa::layouts.app', ['overlay' => true, 'title' => 'Berita '.$category['name'], 'description' => $category['description']])

@php
    $items = $rt->inCategory($category['slug']);
    $lead = $items[0] ?? null;
    $children = $rt->childCategories($category['slug']);
    $parent = $category['parent'] ? $rt->category($category['parent']) : null;
@endphp

@section('content')
    <header data-theme="dark" class="relative isolate flex min-h-[70svh] items-end overflow-hidden bg-ground">
        @if ($lead)<x-lensa::frame :src="$lead['image']['lg']" alt="" eager class="absolute inset-0 -z-10 opacity-60" />@endif
        <div class="absolute inset-0 -z-10 bg-gradient-to-t from-ground via-ground/60 to-black/40"></div>
        <div class="mx-auto w-full max-w-[90rem] px-4 pb-10 pt-32 lg:px-8">
            <p class="ls-kanal text-sm">{{ $parent ? $parent['name'] : 'Kanal' }}</p>
            <h1 class="ls-headline mt-2 text-7xl lg:text-[9rem] lg:leading-[0.9]">{{ $category['name'] }}</h1>
            <p class="mt-4 max-w-2xl text-ink/80">{{ $category['description'] }}</p>
            <div class="mt-6 flex flex-wrap items-center gap-2">
                <button type="button" data-toggle-press aria-pressed="false" class="ls-btn aria-[pressed=true]:bg-ink"><i class="ri-add-line"></i>Ikuti kanal</button>
                @foreach ($children as $child)<a href="{{ $child['url'] }}" class="ls-pill">{{ $child['name'] }}</a>@endforeach
            </div>
            @if ($lead)
                <a href="{{ $lead['url'] }}" data-card class="group mt-10 flex max-w-2xl items-center gap-4 rounded-frame bg-ground/70 p-3 transition hover:bg-ground">
                    <x-lensa::frame :src="$lead['image']['sm']" :alt="$lead['title']" class="aspect-square w-20 shrink-0 rounded-xl" />
                    <span><span class="text-xs text-sun">Teratas sekarang</span><span class="ls-headline block text-xl group-hover:text-sun">{{ $lead['title'] }}</span></span>
                </a>
            @endif
        </div>
    </header>

    <div class="mx-auto mt-14 grid max-w-[90rem] gap-12 px-4 lg:grid-cols-[minmax(0,1fr)_22rem] lg:px-8">
        <div>
            <div id="cat-grid" class="grid gap-x-5 gap-y-10 sm:grid-cols-2 xl:grid-cols-3">
                @foreach (array_slice($items, 1, 12) as $i => $item)
                    @if ($i === 0)
                        <x-lensa::card.cover :article="$item" :rt="$rt" size="text-3xl lg:text-4xl" large class="aspect-[4/5] sm:col-span-2 sm:aspect-[16/9] xl:col-span-3 xl:aspect-[21/9]" />
                    @else
                        <x-lensa::card.photo :article="$item" :rt="$rt" />
                    @endif
                @endforeach
            </div>
            @if (count($items) > 13)
                <x-lensa::more target="#cat-grid" :scope="'category:'.$category['slug']" :offset="13" :per="6" />
            @endif
        </div>
        @include('lensa::partials.aside', ['exclude' => array_column(array_slice($items, 0, 13), 'slug')])
    </div>
@endsection
