@extends('lensa::layouts.app', ['title' => 'Berita Terbaru '.$tag['name']])

@php $items = $rt->withTag($tag['slug']); @endphp

@section('content')
    <div class="mx-auto max-w-[90rem] px-4 pt-10 lg:px-8">
        <header class="flex flex-col justify-between gap-6 border-b border-edge pb-10 lg:flex-row lg:items-end">
            <div>
                <p class="ls-kanal text-sm"><i class="ri-hashtag"></i>Topik</p>
                <h1 class="ls-headline mt-2 text-6xl lg:text-8xl">{{ $tag['name'] }}</h1>
                <p class="mt-3 text-dim">Kumpulan berita dan artikel terbaru mengenai {{ $tag['name'] }}.</p>
            </div>
            <div class="flex -space-x-3">
                @foreach (array_slice($items, 0, 5) as $item)
                    <x-lensa::frame :src="$item['image']['sm']" alt="" class="size-14 rounded-full ring-4 ring-ground" />
                @endforeach
                <span class="grid size-14 place-items-center rounded-full bg-sun text-sm font-bold text-ground ring-4 ring-ground">{{ count($items) }}</span>
            </div>
        </header>
        <div class="mt-10 grid gap-12 lg:grid-cols-[minmax(0,1fr)_22rem]">
            <div>
                <div id="tag-grid" class="grid gap-x-5 gap-y-10 sm:grid-cols-2 xl:grid-cols-3">
                    @foreach (array_slice($items, 0, 9) as $item)
                        <x-lensa::card.photo :article="$item" :rt="$rt" excerpt />
                    @endforeach
                </div>
                @if (count($items) > 9)
                    <x-lensa::more target="#tag-grid" :scope="'tag:'.$tag['slug']" :offset="9" :per="6" />
                @endif
            </div>
            @include('lensa::partials.aside')
        </div>
    </div>
@endsection
