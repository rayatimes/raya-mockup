@extends('default::layouts.app', ['title' => 'Berita Terbaru '.$tag['name'], 'description' => 'Kumpulan berita dan artikel terbaru mengenai '.$tag['name'].'.'])

@php $items = $rt->withTag($tag['slug']); @endphp

@section('content')
    <div class="mx-auto max-w-7xl px-4 pt-6">
        <header class="relative isolate overflow-hidden bg-ink p-6 text-white lg:p-10">
            @if ($items)
                <x-default::img :src="$items[0]['image']['lg']" alt="" class="absolute inset-0 -z-10 opacity-35" />
            @endif
            <div class="absolute inset-0 -z-10 bg-gradient-to-r from-ink via-ink/85 to-ink/30"></div>
            <p class="flex items-center gap-1.5 text-xs font-bold text-sun"><i class="ri-hashtag"></i>Halaman topik</p>
            <h1 class="mt-2 font-serif text-4xl font-semibold lg:text-5xl">{{ $tag['name'] }}</h1>
            <p class="mt-2 max-w-xl text-sm text-white/75">Kumpulan berita dan artikel terbaru mengenai {{ $tag['name'] }}.</p>
            <p class="mt-5 inline-flex bg-white/10 px-3 py-1.5 text-xs font-semibold">{{ count($items) }} berita ditemukan</p>
        </header>
    </div>

    <div class="mx-auto mt-8 grid max-w-7xl gap-10 px-4 lg:grid-cols-[minmax(0,1fr)_20rem]">
        <div class="min-w-0">
            <div id="tag-list" class="grid gap-5 sm:grid-cols-2">
                @foreach (array_slice($items, 0, 10) as $i => $item)
                    <x-default::card.tile :article="$item" :rt="$rt" />
                @endforeach
            </div>
            @if (count($items) > 10)
                <x-default::loadmore target="#tag-list" :scope="'tag:'.$tag['slug']" :offset="10" :per="6" template="tpl-tile" />
            @endif
        </div>
        @include('default::partials.sidebar')
    </div>
@endsection
