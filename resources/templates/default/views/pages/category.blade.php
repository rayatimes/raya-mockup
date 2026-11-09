@extends('default::layouts.app', ['title' => 'Berita '.$category['name'].' Terkini Hari Ini', 'description' => $category['description']])

@php
    $items = $rt->inCategory($category['slug']);
    $lead = $items[0] ?? null;
    $list = array_slice($items, 1, 10);
    $children = $rt->childCategories($category['slug']);
    $parent = $category['parent'] ? $rt->category($category['parent']) : null;
@endphp

@section('content')
    <div class="mx-auto max-w-7xl px-4 pt-6">
        <header class="flex flex-col justify-between gap-5 border border-line bg-surface p-6 sm:flex-row sm:items-center lg:p-8">
            <div>
                <p class="rt-kanal">{{ $parent ? 'Kanal '.$parent['name'] : 'Kanal' }}</p>
                <h1 class="mt-1 font-serif text-4xl font-semibold">{{ $category['name'] }}</h1>
                <p class="rt-meta mt-1">@<span>{{ $category['slug'] }}</span> · {{ count($items) }} berita</p>
                <p class="mt-3 max-w-2xl text-sm text-muted">{{ $category['description'] }}</p>
                @if ($children)
                    <div class="mt-4 flex flex-wrap gap-2">
                        @foreach ($children as $child)<a href="{{ $child['url'] }}" class="rt-chip">{{ $child['name'] }}</a>@endforeach
                    </div>
                @endif
            </div>
            <button type="button" data-react data-on="ri-check-line" data-off="ri-add-line" aria-pressed="false" class="rt-btn rt-btn-solid shrink-0 aria-[pressed=true]:!border-fg aria-[pressed=true]:!bg-fg aria-[pressed=true]:!text-paper">
                <i class="ri-add-line"></i>Ikuti kanal
            </button>
        </header>
    </div>

    <div class="mx-auto mt-8 grid max-w-7xl gap-10 px-4 lg:grid-cols-[minmax(0,1fr)_20rem]">
        <div class="min-w-0">
            @if ($lead)
                <article data-card class="group">
                    <a href="{{ $lead['url'] }}" tabindex="-1" aria-hidden="true"><x-default::img :src="$lead['image']['lg']" :alt="$lead['title']" eager class="aspect-video" /></a>
                    <a href="{{ $lead['category']['url'] }}" class="mt-4 inline-block bg-raya px-2 py-0.5 text-[10.5px] font-bold uppercase text-white">{{ $lead['category']['name'] }}</a>
                    <h2 class="mt-2 text-2xl leading-tight sm:text-3xl"><a href="{{ $lead['url'] }}" class="rt-title">{{ $lead['title'] }}</a></h2>
                    <p class="mt-2 text-muted">{{ $lead['excerpt'] }}</p>
                    <x-default::byline :article="$lead" :rt="$rt" class="mt-3" />
                </article>
            @endif

            <section class="mt-10">
                <x-default::section-head title="Terkini" />
                <div id="cat-list" class="-mt-5 divide-y divide-line">
                    @forelse ($list as $item)
                        <x-default::card.row :article="$item" :rt="$rt" excerpt />
                    @empty
                        <p class="py-10 text-center text-sm text-muted">Belum ada berita lain di kanal ini.</p>
                    @endforelse
                </div>
                @if (count($items) > 11)
                    <x-default::loadmore target="#cat-list" :scope="'category:'.$category['slug']" :offset="11" />
                @endif
            </section>
        </div>
        @include('default::partials.sidebar', ['exclude' => array_column(array_slice($items, 0, 11), 'slug')])
    </div>
@endsection
